<?php

	$Read->FullRead("SELECT slide_image_mobile, slide_image_tablet, slide_image_desktop, slide_link_banner, slide_title, slide_desc, slide_complement,slide_category, slide_product, slide_link_pdt, slide_link_pdt_btn, slide_link_cat, slide_link_cat_btn, show_title, show_desc, show_complement FROM " . DB_SLIDES . " WHERE slide_status = :status AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL) ORDER BY slide_date DESC",
		"status=1");

	if ($Read->getResult()):

		?>
		<section class="carousel container">
			<div class="content">
				<div class="owl-carousel owl-theme">
					<?php
						foreach ($Read->getResult() as $SLIDE):
							$image_desktop = $SLIDE['slide_image_desktop'];
							$image_mobile = (!empty($SLIDE['slide_image_mobile']) ? $SLIDE['slide_image_mobile'] : $image_desktop);
							$image_tablet = (!empty($SLIDE['slide_image_tablet']) ? $SLIDE['slide_image_tablet'] : $image_desktop);
							?>
							<div>
								<a href="<?= $SLIDE['slide_link_banner'] ? $SLIDE['slide_link_banner'] : '#'; ?>"
								   title="<?= $SLIDE['slide_title']; ?>">
									<picture alt="<?= $SLIDE['slide_title']; ?>">
										<source media="(min-width: 992px)"
										        srcset="<?= BASE; ?>/uploads/<?= $image_desktop; ?>"/>
										<source media="(min-width: 468px)"
										        srcset="<?= BASE; ?>/uploads/<?= $image_tablet; ?>"/>
										<source media="(min-width: 1px)"
										        srcset="<?= BASE; ?>/uploads/<?= $image_mobile; ?>"/>
										<img src="<?= BASE; ?>/uploads/<?= $image_desktop; ?>"
										     alt="<?= $SLIDE['slide_title']; ?>" title="<?= $SLIDE['slide_title']; ?>"/>

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
						endforeach;
					?>
				</div>
				<div class="clear"></div>
			</div>
		</section>
	<?php
	endif;
?>
