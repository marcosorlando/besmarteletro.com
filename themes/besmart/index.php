<?php
	require REQUIRE_PATH . "/inc/slides.php" ?>

<?php

	$Read->FullRead("SELECT p.pdt_id, p.pdt_name, p.pdt_title, p.pdt_cover, p.pdt_destaque, p.pdt_voltage, p.pdt_line, l.line_title, l.line_image FROM " . DB_PDT . " p, " . DB_PDT_LINES . " l WHERE l.line_id = p.pdt_line AND p.pdt_destaque = :dstq AND p.pdt_status = :status ORDER BY RAND()",
		"dstq=1&status=1");

	if ($Read->getResult()):
		?>
		<section class="destaque">
			<div class="products container">
				<div class="content carousel-index">

					<header>
						<h1 class='title-section'>PRODUTOS EM DESTAQUE</h1>
					</header>

					<div class="products_wrap">
						<div class="owl-carousel">
							<?php
								$countdown = true;
								foreach ($Read->getResult() as $PDT):
									extract($PDT);

									require REQUIRE_PATH . '/inc/product.php';
								endforeach;
								unset($countdown);
							?>
						</div>
					</div>

					<div class="clear"></div>
				</div>
			</div>
		</section>


	<?php
	endif;
?>
<?php
	$Read->FullRead("SELECT banner_title, banner_size, banner_link, banner_image FROM " . DB_BANNERS . " WHERE banner_status = :status AND banner_line = :line AND banner_page = :page ORDER BY banner_date DESC",
		"status=1&line=1&page=index");
	if ($Read->getResult()):
		?>
		<div class="banners container" id="count_line_banner">
			<div class="content">
				<section>
					<header>
						<h1>Confira as Novidades</h1>
					</header>

					<div>
						<?php
							foreach ($Read->getResult() as $BANNER):
								?>
							<article class="banners_item box box<?= $BANNER['banner_size']; ?>">
								<h1><?= $BANNER['banner_title']; ?></h1>
								<a href="<?= BASE . '/' . $BANNER['banner_link']; ?>"
								   title="<?= $BANNER['banner_title']; ?>">
									<img src="<?= BASE; ?>/uploads/<?= $BANNER['banner_image']; ?>"
									     alt="<?= $BANNER['banner_title']; ?>" title="<?= $BANNER['banner_title']; ?>"/>
								</a>
								</article><?php
							endforeach;
						?>
					</div>
				</section>

				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>

<?php
	$Read->FullRead("SELECT pdt_id, pdt_code, pdt_name, pdt_title, pdt_cover, pdt_price, pdt_offer_price, pdt_dimension_weight, pdt_offer_start ,pdt_offer_end FROM " . DB_PDT . " WHERE pdt_status = :status AND (pdt_inventory >= 1 OR pdt_inventory IS NULL) ORDER BY pdt_created DESC, RAND() LIMIT :limit",
		"status=1&limit=12");
	if ($Read->getResult()):
		?>
		<div class="products container">
			<div class="content">
				<section>
					<header class="heading">
						<h1>
							Confira as
							<span>Novidades</span>
						</h1>
					</header>

					<div class="products_wrap">
						<div class="owl-carousel">
							<?php
								$launch = true;
								/*  foreach ($Read->getResult() as $PDT):
									  extract($PDT);
									  require REQUIRE_PATH . '/inc/product.php';
								  endforeach;*/
								foreach ($Read->getResult() as $PDT):
									extract($PDT);
									if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
										$PdtPrice = $pdt_offer_price;
										$discount = (int)((($pdt_price - $pdt_offer_price) * 100) / $pdt_price);
									else:
										$PdtPrice = $pdt_price;
										$discount = false;
									endif;
									require REQUIRE_PATH . '/inc/product.php';
								endforeach;

								unset($launch);
							?>

						</div>
					</div>
				</section>
				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>

<?php

	$Read->FullRead("SELECT banner_title, banner_size, banner_link, banner_image FROM " . DB_BANNERS . " WHERE banner_status = :status AND banner_line = :line AND banner_page = :page ORDER BY banner_date DESC",
		"status=1&line=2&page=index");
	if ($Read->getResult()):
		?>
		<div class="banners container" id="count_line_banner">
			<div class="content">
				<section>
					<header>
						<h1>Confira as Novidades</h1>
					</header>

					<div>
						<?php

							foreach ($Read->getResult() as $BANNER):
								?>
							<article class="banners_item box box<?= $BANNER['banner_size']; ?>">
								<h1><?= $BANNER['banner_title']; ?></h1>
								<a href="<?= BASE . '/' . $BANNER['banner_link']; ?>"
								   title="<?= $BANNER['banner_title']; ?>">
									<img src="<?= BASE; ?>/uploads/<?= $BANNER['banner_image']; ?>"
									     alt="<?= $BANNER['banner_title']; ?>" title="<?= $BANNER['banner_title']; ?>"/>
								</a>
								</article><?php
							endforeach;
						?>
					</div>
				</section>

				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>

<?php

	$Read->FullRead("SELECT pdt_id, pdt_code, pdt_name, pdt_title, pdt_cover, pdt_price, pdt_offer_price, pdt_offer_start, pdt_offer_end, pdt_dimension_weight  FROM " . DB_PDT . " WHERE pdt_status = :status AND (pdt_inventory >= 1 OR pdt_inventory IS NULL) ORDER BY RAND() LIMIT :limit",
		"status=1&limit=8");
	if ($Read->getResult()):
		?>
		<div class="products container">
			<div class="content">
				<section>
					<header class="heading">
						<h1>
							Mais
							<span>Populares</span>
						</h1>
					</header>

					<div class="products_wrap">
						<div class="owl-carousel">
							<?php

								/*     foreach ($Read->getResult() as $PDT):
										 extract($PDT);
										 require REQUIRE_PATH . '/inc/product.php';
									 endforeach;*/
								foreach ($Read->getResult() as $PDT):
									extract($PDT);
									if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
										$PdtPrice = $pdt_offer_price;
										$discount = (int)((($pdt_price - $pdt_offer_price) * 100) / $pdt_price);
									else:
										$PdtPrice = $pdt_price;
										$discount = false;
									endif;
									require REQUIRE_PATH . '/inc/product.php';
								endforeach;
							?>
						</div>
					</div>
				</section>

				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>

<?php

	$Read->FullRead("SELECT banner_title, banner_size, banner_link, banner_image FROM " . DB_BANNERS . " WHERE banner_status = :status AND banner_line = :line AND banner_page = :page ORDER BY banner_date DESC",
		"status=1&line=3&page=index");
	if ($Read->getResult()):
		?>
		<div class="banners container" id="count_line_banner">
			<div class="content">
				<section>
					<header>
						<h1>Confira as Novidades</h1>
					</header>

					<div>
						<?php

							foreach ($Read->getResult() as $BANNER):
								?>
							<article class="banners_item box box<?= $BANNER['banner_size']; ?>">
								<h1><?= $BANNER['banner_title']; ?></h1>
								<a href="<?= BASE . '/' . $BANNER['banner_link']; ?>"
								   title="<?= $BANNER['banner_title']; ?>">
									<img src="<?= BASE; ?>/uploads/<?= $BANNER['banner_image']; ?>"
									     alt="<?= $BANNER['banner_title']; ?>" title="<?= $BANNER['banner_title']; ?>"/>
								</a>
								</article><?php
							endforeach;
						?>
					</div>
				</section>

				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>


<?php

	$Read->FullRead("SELECT banner_title, banner_size, banner_link, banner_image FROM " . DB_BANNERS . " WHERE banner_status = :status AND banner_line = :line AND banner_page = :page ORDER BY banner_date DESC",
		"status=1&line=4&page=index");
	if ($Read->getResult()):
		?>
		<div class="banners container" id="count_line_banner">
			<div class="content">
				<section>
					<header>
						<h1>Confira as Novidades</h1>
					</header>

					<div>
						<?php

							foreach ($Read->getResult() as $BANNER):
								?>
							<article class="banners_item box box<?= $BANNER['banner_size']; ?>">
								<h1><?= $BANNER['banner_title']; ?></h1>
								<a href="<?= BASE . '/' . $BANNER['banner_link']; ?>"
								   title="<?= $BANNER['banner_title']; ?>">
									<img src="<?= BASE; ?>/uploads/<?= $BANNER['banner_image']; ?>"
									     alt="<?= $BANNER['banner_title']; ?>" title="<?= $BANNER['banner_title']; ?>"/>
								</a>
								</article><?php
							endforeach;
						?>
					</div>
				</section>

				<div class="clear"></div>
			</div>
		</div>
	<?php
	endif;
?>

<?php

	$browsing_history = (filter_input(INPUT_COOKIE, 'browsing_history', FILTER_DEFAULT) ? filter_input(INPUT_COOKIE,
		'browsing_history', FILTER_DEFAULT) : null);
	if ($browsing_history):
		$arrIds = explode(',', $browsing_history);
		$strIds = "'" . implode("','", $arrIds) . "'";

		$Read->FullRead("SELECT pdt_id, pdt_code, pdt_name, pdt_title, pdt_cover, pdt_price, pdt_offer_price, pdt_offer_start, pdt_offer_end, pdt_dimension_weight FROM " . DB_PDT . " WHERE pdt_status = :status AND (pdt_inventory >= 1 OR pdt_inventory IS NULL) AND pdt_id IN ({$strIds}) ORDER BY RAND() LIMIT :limit",
			"status=1&limit=40");
		if ($Read->getResult()):
			?>
			<div class="products browsing_history container">
				<div class="content">
					<section>
						<header class="heading">
							<h1>
								Meu
								<span>Histórico</span>
							</h1>
						</header>

						<div class="browsing_history_remove">
							<span class="j_browsing_history j_all" title="Limpar Histórico"
							      data-pdt-id="<?= $browsing_history; ?>">Limpar Histórico</span>
						</div>

						<div class="products_wrap">
							<div class="owl-carousel">
								<?php

									/*   foreach ($Read->getResult() as $PDT):
										   extract($PDT);
										   require REQUIRE_PATH . '/inc/product.php';
									   endforeach;*/
									foreach ($Read->getResult() as $PDT):
										extract($PDT);
										if ($pdt_offer_price && $pdt_offer_start <= date('Y-m-d H:i:s') && $pdt_offer_end >= date('Y-m-d H:i:s')):
											$PdtPrice = $pdt_offer_price;
											$discount = (int)((($pdt_price - $pdt_offer_price) * 100) / $pdt_price);
										else:
											$PdtPrice = $pdt_price;
											$discount = false;
										endif;
										require REQUIRE_PATH . '/inc/product.php';
									endforeach;

									unset($browsing_history);
								?>
							</div>
						</div>
					</section>

					<div class="clear"></div>
				</div>
			</div>
		<?php
		endif;
	endif;
?>

<?php
	/*
	$Read->FullRead('SELECT cat_title, cat_name, (SELECT pdt_cover FROM ' . DB_PDT . ' WHERE pdt_status = :status AND FIND_IN_SET(cat_id, pdt_category) AND pdt_cover IS NOT NULL ORDER BY pdt_delivered DESC LIMIT :limit) AS pdt_cover FROM ' . DB_PDT_CATS . ' WHERE cat_parent IS NULL', "status=1&limit=1");
	if ($Read->getResult()):
		?>
		<section class="options">
			<div class="container">
				<div class="content">
					<header class="heading">
						<h1>
							Aqui <span>Também Tem</span>
						</h1>
					</header>

					<div class="owl-carousel">
						<?php foreach ($Read->getResult() as $PDT): ?>
							<article>
								<a href="<?= BASE . '/produtos/' . $PDT['cat_name']; ?>" title="<?= $PDT['cat_title']; ?>">
									<img src="<?= BASE; ?>/uploads/<?= $PDT['pdt_cover']; ?>" alt="<?= $PDT['cat_title']; ?>" title="<?= $PDT['cat_title']; ?>"/>
									<header>
										<h2><?= $PDT['cat_title']; ?></h2>
									</header>
								</a>
							</article>
						<?php endforeach; ?>
					</div>

					<div class="clear"></div>
				</div>
			</div>
		</section>
		<?php
	endif;
	*/

?>
<?php
	require REQUIRE_PATH . "/inc/about.php" ?>
