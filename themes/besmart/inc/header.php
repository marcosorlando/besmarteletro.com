<?php
	use App\Helpers\Check;
	// require '_cdn/widgets/budget/cart.inc.php';
	//require '_cdn/widgets/contact/contact.wc.php';
?>
<header class="header">
	<!--MENU MOBILE-->
	<div class="header_mobile">
		<div class="header_mobile_wrap">

			<div class="header_mobile_logo container">
				<div class="content">
					<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>">
						<img src="<?= INCLUDE_PATH; ?>/images/logo.svg" alt="<?= SITE_NAME; ?>"
						     title="<?= SITE_NAME; ?>"/>
					</a>
					<div class="clear"></div>
				</div>
			</div>

			<div class="header_mobile_nav container">
				<div class="content">
					<ul>

						<li>
							<a class="<?= ($URL[0] == 'conta' ? 'active' : ''); ?>" href="<?= BASE; ?>/conta"
							   title="Minha Conta">
								<i class="fa fa-user-circle"></i>
							</a>
						</li>

						<li>
							<a class="j_open_search_mobile" href="#" title="Pesquisar Produtos">
								<i class="fa fa-search"></i>
							</a>
						</li>
						<li>
							<a class="j_open_categories_mobile" href="#" title="Categorias">
								<i class="fa fa-bars"></i>
							</a>
						</li>
					</ul>

					<div class="clear"></div>
				</div>
			</div>
		</div>

		<div class="header_mobile_search container">
			<div class="content">
				<form class="one_input j_search" name="search" method="post" action="" enctype="multipart/form-data">
					<input class="one_input_field" type="search" name="s" placeholder="O que você está procurando?"
					       autocomplete="off" required="required"/>
					<button class="one_input_button" type="submit">
						<i class="fa fa-search"></i>
					</button>
					<div class="realtime_search"></div>
				</form>

				<div class="clear"></div>
			</div>
		</div>

		<div class="header_mobile_categories container">
			<div class="content">

				<ul class='header_mobile_categories_ul'>
					<li>
						<div>
							<div class='j_cat_open_end_close'>
								<i class='fa fa-building'></i>
							</div>
							<a class="wc_goto" href="<?= isset($URL[1]) ? BASE . '/#quem-somos' :	'#quem-somos';
							?>" title="Sobre à
							BE.SMART"> Quem Somos</a>
						</div>
					</li>

					<?php
						$Read->FullRead("SELECT cat_id, cat_title, cat_name FROM " . DB_PDT_CATS . " WHERE cat_parent IS NULL ORDER BY cat_title ASC");
						if ($Read->getResult()):

							foreach ($Read->getResult() as $SES):
								$Read->FullRead("SELECT cat_id, cat_title, cat_name FROM " . DB_PDT_CATS . " WHERE cat_parent = :parent ORDER BY cat_title ASC",
									"parent={$SES['cat_id']}");

								echo "<li>";
								echo "<div>";
								echo "<div class='j_cat_open_end_close'>" . ($Read->getResult() ? "<i class='fa fa-angle-double-right'></i>" : "<i class='fa fa-angle-double-down'></i>") . "</div>";
								echo "<a href='" . BASE . "/produtos/{$SES['cat_name']}' title='{$SES['cat_title']}'>{$SES['cat_title']}</a>";
								echo "</div>";

								if ($Read->getResult()):
									echo "<ul>";
									foreach ($Read->getResult() as $CAT):
										$Read->FullRead("SELECT cat_id, cat_title, cat_name FROM " . DB_PDT_CATS . " WHERE cat_parent = :parent ORDER BY cat_title ASC",
											"parent={$CAT['cat_id']}");

										echo "<li>";
										echo "<div>";
										echo "<div class='j_cat_open_end_close'>" . ($Read->getResult() ? "<i class='fa fa-angle-double-right'></i>" : "<i class='fa fa-angle-double-down'></i>") . "</div>";
										echo "<a href='" . BASE . "/produtos/{$CAT['cat_name']}' title='{$CAT['cat_title']}'>{$CAT['cat_title']}</a>";
										echo "</div>";

										if ($Read->getResult()):
											echo "<ul>";
											foreach ($Read->getResult() as $SUBCAT):
												echo "<li>";
												echo "<div>";
												echo "<div class='j_cat_open_end_close'><i class='fa fa-angle-double-down'></i></div>";
												echo "<a href='" . BASE . "/produtos/{$SUBCAT['cat_name']}' title='{$SUBCAT['cat_title']}'>{$SUBCAT['cat_title']}</a>";
												echo "</div>";
												echo "</li>";
											endforeach;
											echo "</ul>";
										endif;
										echo "</li>";
									endforeach;
									echo "</ul>";
								endif;
								echo "</li>";
							endforeach;
						endif;
					?>

					<li>
						<div>
							<div class='j_cat_open_end_close'>
								<i class='fa fa-bullhorn'></i>
							</div>
							<a href="<?= isset($URL[1]) ? BASE . '/#contato' :	'#contato';
							?>" class="wc_goto" title="Fale com a Gente"> Fale com a Gente</a>
						</div>
					</li>

					<?php
						$Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL ORDER BY category_title ASC");

						if ($Read->getResult()):

							foreach ($Read->getResult() as $SES):

								$Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent = :cat ORDER BY category_title ASC",
									"cat={$SES['category_id']}");

								echo "<li>";
								echo "<div>";
								echo "<div class='j_cat_open_end_close'>" . ($Read->getResult() ? "<i class='fa fa-angle-double-right'></i>" : "<i class='fa fa-angle-double-down'></i>") . "</div>";
								echo "<a href='" . BASE . "/artigos/{$SES['category_name']}' title='{$SES['category_title']}'>{$SES['category_title']}</a>";
								echo "</div>";

								if ($Read->getResult()):
									echo "<ul>";
									foreach ($Read->getResult() as $CAT):
										$Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent = :parent ORDER BY category_title ASC",
											"parent={$CAT['category_id']}");

										echo "<li>";
										echo "<div>";
										echo "<div class='j_category_open_end_close'>" . ($Read->getResult() ? "<i class='fa fa-angle-double-right'></i>" : "<i class='fa fa-angle-double-down'></i>") . "</div>";
										echo "<a href='" . BASE . "/produtos/{$CAT['category_name']}' title='{$CAT['category_title']}'>{$CAT['category_title']}</a>";
										echo "</div>";

										if ($Read->getResult()):
											echo "<ul>";
											foreach ($Read->getResult() as $SUBCAT):
												echo "<li>";
												echo "<div>";
												echo "<div class='j_category_open_end_close'><i class='fa fa-angle-double-down'></i></div>";
												echo "<a href='" . BASE . "/produtos/{$SUBCAT['category_name']}' title='{$SUBCAT['category_title']}'>{$SUBCAT['category_title']}</a>";
												echo "</div>";
												echo "</li>";
											endforeach;
											echo "</ul>";
										endif;
										echo "</li>";
									endforeach;
									echo "</ul>";
								endif;
								echo "</li>";
							endforeach;
						endif;
					?>

				</ul>


				<div class="clear"></div>
			</div>
		</div>
	</div>
	<!--MENU MOBILE-->

	<!--MENU DESKTOP-->
	<div class="header_desktop">
		<div class="container">
			<div class="content">
				<div class="header_desktop_logo">
					<h1>
						<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>">
							<img src="<?= INCLUDE_PATH; ?>/images/logo.svg" alt="<?= SITE_NAME; ?>" title="<?=
								SITE_NAME; ?>"/>
						</a>
					</h1>
				</div>

				<nav class="menu">
					<ul>
						<li>
							<a href="<?= isset($URL[1]) ? BASE . '/#quem-somos' : '#quem-somos'; ?>"
							   title="Sobre BE.SMART" class="wc_goto"> Quem<br>Somos</a>
						</li>
						<?php
							$Read->FullRead("SELECT cat_id, cat_title, cat_name FROM " . DB_PDT_CATS . " WHERE cat_parent IS NULL ORDER BY cat_title ASC");
							if ($Read->getResult()):
								foreach ($Read->getResult() as $SES):
									?>
									<li>
									<a href='<?= BASE . '/produtos/' . $SES['cat_name']; ?>'
									   title='<?= $SES['cat_title']; ?>'> <?= $SES['cat_title']; ?>
									</a>
									<?php

									$Read->FullRead("SELECT cat_id, cat_title, cat_name FROM " . DB_PDT_CATS . " WHERE cat_parent = :cat ORDER BY cat_title ASC",
										"cat={$SES['cat_id']}");
									if ($Read->getResult()):
										echo "<ul  id=\"{$SES['cat_title']}\">";
										foreach ($Read->getResult() as $CAT):
											?>
											<li>
											<a href="<?= BASE . '/produtos/' . $CAT['cat_name']; ?>"
											   title="<?= $CAT['cat_title']; ?>">  <?= $CAT['cat_title']; ?></a>

											<?php
											$Read->setPlaces("cat={$CAT['cat_id']}");
											if ($Read->getResult()):
												echo "<ul id='{$CAT['cat_title']}'>";
												foreach ($Read->getResult() as $SUBCAT):
													?>
													<li>
														<a href="<?= BASE . '/produtos/' . $SUBCAT['cat_name']; ?>"
														   title="<?= $SUBCAT['cat_title']; ?>"> <?= $SUBCAT['cat_title']; ?></a>
													</li>
												<?php
												endforeach;
												echo " </ul>";
											endif;
											?>
											</li>
										<?php
										endforeach;
										echo "</ul>";
									endif;
									?>
									</li>
								<?php
								endforeach;
							endif;

							$Read->FullRead("SELECT page_id, page_title, page_name FROM " . DB_PAGES . " WHERE page_status <> 0 ORDER BY page_order, page_title ASC");
							if ($Read->getResult()) {
								foreach ($Read->getResult() as $pages) {
									extract($pages);
									echo "<li><a href='" . BASE . "/{$page_name}' title='{$page_title}'> {$page_title}</a></li>";
								}
							}
						?>
						<li>
							<a href="<?= isset($URL[1]) ? BASE . '/#contato' :	'#contato';
							?>" class="wc_goto" title="Fale com a Gente"> Fale com<br> a gente</a>
						</li>

						<?php
							$Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent IS NULL ORDER BY category_title ASC");
							if ($Read->getResult()):
								foreach ($Read->getResult() as $SES):
									?>
									<li>
									<a href='<?= BASE . '/artigos/' . $SES['category_name']; ?>'
									   title='<?= $SES['category_title']; ?>'> <?= $SES['category_title']; ?>
									</a>
									<?php

									$Read->FullRead("SELECT category_id, category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_parent = :cat ORDER BY category_title ASC",
										"cat={$SES['category_id']}");
									if ($Read->getResult()):
										echo "<ul id=\"{$SES['category_title']}\">";
										foreach ($Read->getResult() as $CAT):
											?>
											<li>
											<a href="<?= BASE . '/artigos/' . $CAT['category_name']; ?>"
											   title="<?= $CAT['category_title']; ?>"><?= $CAT['category_title']; ?></a>

											<?php
											$Read->setPlaces("cat={$CAT['category_id']}");
											if ($Read->getResult()):
												echo "<ul>";
												foreach ($Read->getResult() as $SUBCAT):
													?>
													<li>
														<a href="<?= BASE . '/artigos/' . $SUBCAT['category_name']; ?>"
														   title="<?= $SUBCAT['category_title']; ?>"><?= $SUBCAT['category_title']; ?></a>
													</li>
												<?php
												endforeach;
												echo " </ul>";
											endif;
											?>
											</li>
										<?php
										endforeach;
										echo "</ul>";
									endif;
									?>
									</li>
								<?php
								endforeach;
							endif;
						?>

					</ul>
				</nav>

				<div class='footer_about_social'>

					<?= SITE_SOCIAL_FB_PAGE ? "<a href='https://www.facebook.com/" . SITE_SOCIAL_FB_PAGE . "' target='_blank' title='" . SITE_NAME . " no Facebook'><i class='fa fa-facebook-square'></i></a>" : ''; ?>
					<?= SITE_SOCIAL_TWITTER ? "<a href='https://www.twitter.com/" . SITE_SOCIAL_TWITTER . "' target='_blank' title='" . SITE_NAME . " no Twitter'><i class='fa fa-twitter'></i></a>" : ''; ?>
					<?= SITE_SOCIAL_INSTAGRAM ? "<a href='https://www.instagram.com/" . SITE_SOCIAL_INSTAGRAM . "' target='_blank' title='" . SITE_NAME . " no Instagram'><i class='fa fa-instagram'></i></a>" : ''; ?>
					<?= SITE_SOCIAL_YOUTUBE ? "<a href='http://www.youtube.com/channel/" . SITE_SOCIAL_YOUTUBE . "?sub_confirmation=1' target='_blank' title='" . SITE_NAME . " no Youtube'><i class='fa fa-youtube'></i></a>" : ''; ?>
					<?= SITE_SOCIAL_LINKEDIN ? "<a href='https://www.linkedin.com/company/" . SITE_SOCIAL_LINKEDIN . "' target='_blank' title='" . SITE_NAME . " no Linkedin'><i class='fa fa-linkedin'></i></a>" : ''; ?>
				</div>

			</div>
		</div>
		<div class="container header_belt">
			<div class='content'>
				<div class='header_desktop_search'>
					<form class='one_input j_search' name='search' method='post' action=''
					      enctype='multipart/form-data'>
						<input class='one_input_field' type='search' name='s' placeholder='O que você procura?'
						       autocomplete='off' required='required'/>
						<button class='one_input_button' type='submit'>
							<i class='fa fa-search'></i>
						</button>

						<div class='realtime_search'></div>
					</form>
				</div>
				<div class='header_whatsapp'>
					<a target="_blank" href='<?= Check::WhatsMessage(SITE_ADDR_WHATS,"Olá! Obrigado pelo seu contato com a Be Smart! Nossa equipe está pronta para te atender.Responderemos o mais breve possível! Atenciosamente, Equipe Be Smart") ?>'><span class='fa fa-whatsapp'></span>VAMOS CONVERSAR</a>
				</div>
			</div>
		</div>

	</div>
	<!--MENU DESKTOP-->
</header>

<div class="force_login">
	<div class="force_login_content">
		<div class="force_login_content_close">
			<svg class="j_force_login_close" width="14" height="14" viewBox="0 0 14 14"
			     xmlns="http://www.w3.org/2000/svg" ratio="1">
				<line fill="none" stroke="#999999" stroke-width="1.1" x1="1" y1="1" x2="13" y2="13"></line>
				<line fill="none" stroke="#999999" stroke-width="1.1" x1="13" y1="1" x2="1" y2="13"></line>
			</svg>
		</div>
		<img src="<?= INCLUDE_PATH; ?>/images/cart-heart.png" alt="">
		<p class="force_login_content_message">Faça login para pode adicionar este e outros produtos aos seu
			orçamento.</p>
		<a class="force_login_content_button" title="Minha Conta" href="<?= BASE; ?>/conta"> Minha Conta </a>
	</div>
</div>
