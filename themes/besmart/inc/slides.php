<?php

	$Read->fullRead("SELECT slide_image_mobile, slide_image_tablet, slide_image_desktop, slide_link_banner, slide_title, slide_desc, slide_complement,slide_category, slide_product, slide_link_pdt, slide_link_pdt_btn, slide_link_cat, slide_link_cat_btn, show_title, show_desc, show_complement FROM " . DB_SLIDES . " WHERE slide_status = :status AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL) ORDER BY slide_date DESC",
		"status=1");

	if ($Read->getResult()) {
		// WebP redimensionado pelo tim.php + largura/altura reais do arquivo (reserva o espaço e evita CLS)
		$slideSize = static function (string $file): array {
			$size = @getimagesize(dirname(__DIR__, 3) . '/uploads/' . $file);

			return $size ? [$size[0], $size[1]] : [1920, 600];
		};
		$slideSrcset = static function (string $file, int $width, array $steps): string {
			$widths = array_unique(array_merge(array_filter($steps, static fn (int $w): bool => $w < $width), [$width]));

			return implode(', ', array_map(
				static fn (int $w): string => BASE . "/tim.php?src=uploads/{$file}&w={$w}&fit=max&fm=webp&q=80 {$w}w",
				$widths
			));
		};
		// Título do admin vem com <br>: vira espaço no alt/title
		$slideAlt = static fn (string $title): string => htmlspecialchars(trim(preg_replace('~\s+~', ' ', strip_tags(preg_replace('~<\/?br\s*\/?>~i', ' ', $title)))), ENT_QUOTES);
		$slideIndex = 0;

		?>
		<section class="carousel container">
			<div class="content">
				<div class="owl-carousel owl-theme">
					<?php
						foreach ($Read->getResult() as $SLIDE) {
							$image_desktop = $SLIDE['slide_image_desktop'];
							$image_mobile = (!empty($SLIDE['slide_image_mobile']) ? $SLIDE['slide_image_mobile'] : $image_desktop);
							$image_tablet = (!empty($SLIDE['slide_image_tablet']) ? $SLIDE['slide_image_tablet'] : $image_desktop);
							[$desktopW, $desktopH] = $slideSize($image_desktop);
							[$tabletW, $tabletH] = $slideSize($image_tablet);
							[$mobileW, $mobileH] = $slideSize($image_mobile);
							// Só o primeiro slide aparece na primeira pintura: é o candidato a LCP
							$slidePriority = (0 === $slideIndex++ ? ' fetchpriority="high"' : '');
							?>
							<div>
								<a href="<?= $SLIDE['slide_link_banner'] ? $SLIDE['slide_link_banner'] : '#'; ?>"
								   title="<?= $slideAlt($SLIDE['slide_title']); ?>">
									<picture>
										<source media="(min-width: 992px)" sizes="100vw"
										        width="<?= $desktopW; ?>" height="<?= $desktopH; ?>"
										        srcset="<?= $slideSrcset($image_desktop, $desktopW, [1366, 1600]); ?>"/>
										<source media="(min-width: 468px)" sizes="100vw"
										        width="<?= $tabletW; ?>" height="<?= $tabletH; ?>"
										        srcset="<?= $slideSrcset($image_tablet, $tabletW, [768]); ?>"/>
										<source media="(min-width: 1px)" sizes="100vw"
										        width="<?= $mobileW; ?>" height="<?= $mobileH; ?>"
										        srcset="<?= $slideSrcset($image_mobile, $mobileW, [480]); ?>"/>
										<img src="<?= BASE; ?>/uploads/<?= $image_desktop; ?>"
										     width="<?= $desktopW; ?>" height="<?= $desktopH; ?>"<?= $slidePriority; ?>
										     decoding="async" alt="<?= $slideAlt($SLIDE['slide_title']); ?>"/>
									</picture>
								</a>

								<div class="slide-text">
									<?php
										if ($SLIDE['show_title']) {
											echo "<h2>{$SLIDE['slide_title']}</h2>";
										}
										if ($SLIDE['show_desc']) {
											echo "<p>{$SLIDE['slide_desc']}</p>";
										}
										if ($SLIDE['show_complement']) {
											echo "<span>{$SLIDE['slide_complement']}</span>";
										}
									?>

									<div class="slide-buttons">
										<?php
											if ($SLIDE['slide_product']) {
												echo "<a href='{$SLIDE['slide_link_pdt']}' title='Acessar página do produto'>&nbsp;&nbsp;{$SLIDE['slide_link_pdt_btn']}&nbsp;&nbsp;</a>";
											}
											if ($SLIDE['slide_category']) {
												echo "<a href='{$SLIDE['slide_link_cat']}' title='Acessar página de produtos'>&nbsp;&nbsp;{$SLIDE['slide_link_cat_btn']}&nbsp;&nbsp;</a>";
											}
										?>
									</div>
								</div>
							</div>
						<?php
						}
					?>
				</div>
				<div class="clear"></div>
			</div>
		</section>
	<?php
	}
?>
