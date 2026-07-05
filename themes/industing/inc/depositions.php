<?php

$Read->exeRead(DB_DEPOSITIONS, 'ORDER BY depositions_order DESC');
if ($Read->getResult()) {
    ?>
	<section class="commonSection testimonialSection">
		<div class="container">
			<div class="row">
				<div class="col-xl-4 col-lg-4 noPaddingRight">
					<h6 class="sub_title ">Depoimentos</h6>
					<h2 class="sec_title">
						<span>O que dizem nossos clientes?</span>
					</h2>
					<p class="ind_lead">Soluções em Plásticos Industriais</p>
					<!-- <p>É nosso cliente? <a href="" title="Enviar meu depoimento!"> Deixe seu depoimento!</a></p>-->

				</div>
				<div class="col-xl-8 col-lg-8 pdl40">
					<div class="testimonialSliderHolder tw-stretch-element-inside-column">
						<div class="testimonialSlider">
                            <?php
                            foreach ($Read->getResult() as $Deposition) {
                                \extract($Deposition);
                                ?>
								<div class="ts_item">
									<div class="testimonial_item">
                                            <span class="ratings"><i class="fas fa-star"></i><i class="fas fa-star"></i><i
			                                            class="fas fa-star"></i><i class="fas fa-star"></i><i
			                                            class="fas fa-star"></i></span>

										<p><?php echo $depositions_text; ?></p>
										<div class="ti_author clearfix">
											<img src="<?php echo BASE.\sprintf(
											    '/tim.php?src=uploads/%s&w=70&h=70',
											    $depositions_image
											); ?>"
											     alt="Foto de <?php echo $deposition_name; ?>"
											     title="Foto de <?php echo $depositions_name; ?>"/>
											<h4><?php echo $depositions_name; ?></h4>
											<span><?php echo $depositions_profession; ?></span>
										</div>
									</div>
								</div>
                                <?php
                            }

    ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
    <?php
} ?>
