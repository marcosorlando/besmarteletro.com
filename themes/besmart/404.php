<section class="container page_single not_found">
	<div class="content">
		<h2><span class="text-white">ERRO</span>
			<br><span class="_404 text-blue">404</span>
		</h2>
		<h3 class="text-blue">
			PÁGINA NÃO
			<br> ENCONTRADA!
			<br><span class="text-white">DESCULPE-NOS!</span>
		</h3>
	</div>
</section>

<?php
	$Read = new Read();
	$Read->FullRead("SELECT pdt_id, pdt_code, pdt_name, pdt_title, pdt_cover, pdt_price, pdt_offer_price, pdt_dimension_weight FROM " . DB_PDT . " WHERE pdt_status = :status ORDER BY pdt_created DESC, RAND() LIMIT :limit",
		"status=1&limit=12");

	$Read->FullRead("SELECT p.pdt_id, p.pdt_name, p.pdt_title, p.pdt_cover, p.pdt_destaque, p.pdt_voltage, p.pdt_line, l.line_title, l.line_image FROM " . DB_PDT . " p, " . DB_PDT_LINES . " l WHERE l.line_id = p.pdt_line AND p.pdt_status = :status ORDER BY p.pdt_created DESC, RAND() LIMIT :limit",
		"status=1&limit=12");


	if ($Read->getResult()):
		?>
		<section class="news-404">
			<div class="products container">
				<div class="content carousel-index">
					<header>
						<h1 class="title-section">Confira as Novidades</h1>
					</header>

					<div class="products_wrap">
						<div class="owl-carousel">
							<?php
								$launch = true;

								foreach ($Read->getResult() as $PDT):
									extract($PDT);

									require REQUIRE_PATH . '/inc/product.php';
								endforeach;

								unset($launch);
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
</section>
