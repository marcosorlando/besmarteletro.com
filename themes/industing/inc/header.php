<div class="topbar">
	<div class="header-container">
		<div class="row">
			<div class="col-xl-12 col-lg-12 col-md-12">
				<div class="topbar_right text-right row align-items-center justify-content-between">

								<div class="topbar_element info_element">
						<i class="fa fa-envelope"></i>
						<p>
							<a class="color-white" href="mailto:<?php
                            echo SITE_ADDR_EMAIL; ?>"><?php
                                echo SITE_ADDR_EMAIL; ?></a>
						</p>
					</div>
					<div class="topbar_element info_element">
						<i class="fa fa-phone"></i>
						<p>
							<a class="color-white" href="tel:<?php
                            echo SITE_ADDR_PHONE_A; ?>"><?php
                                echo SITE_ADDR_PHONE_A; ?></a>
						</p>
					</div>
					<div class="topbar_element info_element">
						<i class="fa fa-headset"></i>
						<p>
							<a class="color-white" href="<?php
                            echo BASE; ?>/ouvidoria">Ouvidoria</a>
						</p>
					</div>
					<div class="topbar_element search_element">
						<form method="post" action="">
							<i class="fa fa-search"></i>
							<input type="search" name="p" placeholder="Pesquisar por Produto..."/>
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="nav_bar" id="fix_nav">
	<div class="header-container">
		<div class="row">
			<div class="col-xl-1 col-lg-1 col-md-1 noPadding">
				<div class="logo text-left">
					<a href="<?php
                    echo BASE; ?>" title="Voltar ao Início!">
						<img src="<?php
                        echo INCLUDE_PATH; ?>/images/logo-color.png"
						     alt="<?php
                             echo SITE_ADDR_NAME; ?> - Logotipo"/>
					</a>
				</div>
			</div>
			<div class="col-xl-8 col-lg-8">
				<div class="mobileMenuBar">
					<a href="javascript: void(0);"><span>Menu</span><i class="fa fa-bars"></i></a>
				</div>

				<nav class="mainmenu">
					<h2 class="title-hidden">Plásticos de Engenharia de Alto Desempenho e com a Industing</h2>
					<ul>
						<li class="current-menu-item menu-item-has-children">
							<a href="<?php
                            echo BASE; ?>"><i class="fa fa-home"></i></a>
						</li>
						<li class="menu-item-has-children">
							<a href="<?php
                            echo BASE; ?>/sobre">A Empresa</a>
							<ul class="sub_menu">
								<li><a href="<?php
                                    echo BASE; ?>/sobre">Sobre nós</a></li>
								<li><a href="<?php
                                    echo BASE; ?>/certificacoes">Certificações</a></li>
							</ul>
						</li>

						<li class="menu-item-has-children">
							<a href="<?php
                            echo BASE; ?>/produtos/produtos"></i>Produtos</a>
                            <?php
                            $Read ??= new \App\Conn\Read();
                            $Read->exeRead(
                                DB_PDT_CATS_TRAVI,
                                'WHERE cat_parent = :sector',
                                'sector=1'
                            );
                            if ($Read->getResult()) {
                                echo "<ul class='sub_menu'>";
                                foreach ($Read->getResult() as $cat) {
                                    extract($cat);
                                    echo "<li><a href='" . BASE . sprintf(
                                            "/produtos/%s'>",
                                            $cat_name
                                        ) . mb_convert_case(
                                            (string)$cat_title,
                                            MB_CASE_TITLE
                                        ) . '</a></li>';
                                }
                                echo '</ul>';
                            }
                            ?>

						</li>
                        <?php
                        $Read->fullRead(
                            'SELECT svc_title, svc_name FROM ' . DB_SVC . ' WHERE svc_status = :st',
                            'st=1'
                        );
                        if ($Read->getResult()) {
                            echo "<li class='menu-item-has-children'><a href='" . BASE . "/servicos'>Processos</a><ul class='sub_menu'>";
                            foreach ($Read->getResult() as $svc) {
                                extract($svc);
                                echo "<li><a href='" . BASE . sprintf(
                                        "/servico/%s'>%s</a></li>",
                                        $svc_name,
                                        $svc_title
                                    );
                            }
                            echo '</ul></li>';
                        }
                        ?>

                        <?php
                        $Read->fullRead(
                            'SELECT seg_title, seg_name FROM ' . DB_SEG . ' WHERE seg_status = :st',
                            'st=1'
                        );
                        if ($Read->getResult()) {
                            echo "<li class='menu-item-has-children'><a href='" . BASE . "/segmentos'>Segmentos</a>";
                            echo "<ul class='sub_menu'>";
                            foreach ($Read->getResult() as $seg) {
                                extract($seg);
                                echo "<li><a href='" . BASE . sprintf(
                                        "/segmento/%s'>%s</a></li>",
                                        $seg_name,
                                        $seg_title
                                    );
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                        ?>

                        <?php
                        echo "<li class='menu-item-has-children'>";
                        echo "<a href='javascript: void(0)'>Blog</a>";
                        echo "<ul class='sub_menu'>";

                        $Read->exeRead(
                            DB_CATEGORIES,
                            'WHERE category_parent IS NULL ORDER BY category_id ASC'
                        );

                        if ($Read->getResult()) {
                            foreach ($Read->getResult() as $Cat) {
                                echo "<li class='menu-item-has-children'><a title=' " . SITE_NAME . sprintf(
                                        " | %s' href='",
                                        $Cat['category_title']
                                    ) . BASE . sprintf(
                                        "/artigos/%s'>%s</a>",
                                        $Cat['category_name'],
                                        $Cat['category_title']
                                    );

                                $Read->exeRead(
                                    DB_CATEGORIES,
                                    'WHERE category_parent = :ct ORDER BY category_name ASC',
                                    'ct=' . $Cat['category_id']
                                );

                                if ($Read->getResult()) {
                                    echo "<ul class='sub_menu'>";
                                    foreach ($Read->getResult() as $SubCat) {
                                        echo sprintf(
                                                "<li><a title='%s | %s' href='",
                                                $Cat['category_title'],
                                                $SubCat['category_title']
                                            ) . BASE . sprintf(
                                                "/artigos/%s'>%s</a></li>",
                                                $SubCat['category_name'],
                                                $SubCat['category_title']
                                            );
                                    }
                                    echo '</ul>';
                                }
                                echo '</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</li>';

                        ?>
						<li>
							<a href='<?php
                            echo BASE; ?>/representantes'>Representantes</a>
						</li>
						<li>
							<a href='<?php
                            echo BASE; ?>/materiais'>Materiais</a>
						</li>
						<li class="menu-item-has-children">
							<a href="javascript:void(0);">Contatos</a>
							<ul class="sub_menu">
								<li>
									<a href="<?php
                                    echo BASE; ?>/contato">Entrar em Contato</a>
								</li>
								<li>
									<a href="<?php
                                    echo BASE; ?>/trabalhe-conosco">Trabalhe Conosco</a>
								</li>
							</ul>
						</li>


						<a class="cta_menu icon-newspaper" target="_blank"
						   title="Clique! Nosso time esta aguardando seu contato."
						   href='#'>Orçamento</a>
				</nav>

			</div>
			<div class="col-xl-3 col-lg-3">
				<div class="top_social text-right">
                    <?php
                    require_once __DIR__ . '/../../../_cdn/gtranslate/gtranslate.php';
                    ?>
				</div>
			</div>
		</div>
	</div>
	<span class="right_bgs"></span>
</div>

<!-- Overlay Menu -->
<div class="popup popup__menu">
	<div class="header-container mobileContainer">
		<div class="row">
			<div class="col-lg-8 text-left">
				<div class="popup_logos">
					<a href="<?php
                    echo BASE; ?>">
						<img src="<?php
                        echo INCLUDE_PATH; ?>/images/logo-white.png"
						     alt="<?php
                             echo SITE_ADDR_NAME; ?> - Logotipo"/>
					</a>
				</div>
			</div>
			<div class="col-lg-4 text-right">
				<a href="" id="close-popup" class="close-popup"></a>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="popup-inner">
					<div class="dl-menu__wrap dl-menuwrapper">
						<ul class="dl-menu dl-menuopen">
							<li class="current-menu-item menu-item-has-children">
								<a href="<?php
                                echo BASE; ?>"><i class="fa fa-home fa-2x"></i></a>
							</li>
							<li>
								<a href="<?php
                                echo BASE; ?>/sobre">A Travi</a>
							</li>
							<li class="menu-item-has-children">
								<a href="javascript:void(0);">Contatos</a>
								<ul class="dl-submenu">
									<li>
										<a href="<?php
                                        echo BASE; ?>/contato">Entre em Contato</a>
									</li>
									<li>
										<a href="<?php
                                        echo BASE; ?>/contato">Trabalhe Conosco</a>
									</li>
								</ul>
							</li>
							<li class="menu-item-has-children">
								<a href="javascript:void(0);">Páginas</a>
								<ul class="dl-submenu">
									<!--                                    <li>-->
									<!--                                        <a href="team.html">Team Page</a>-->
									<!--                                    </li>-->
									<li>
										<a href="<?php
                                        echo BASE; ?>404">404</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6 col-sm-6 col-xs-12 text-left">
				<ul class="footer__contacts">
					<li>
						<a href="tel:<?php
                        echo SITE_ADDR_PHONE_A; ?>" title="Ligar para Industing"><i
									class="fa fa-phone"></i> Telefone: <?php
                            echo SITE_ADDR_PHONE_A; ?>
						</a>
					</li>
					<li>
						<a href="mailto:<?php
                        echo SITE_ADDR_EMAIL; ?>" target="_blank" title="Envie-nos um E-mail..."><i
									class="fa fa-envelope"></i> Email: <?php
                            echo SITE_ADDR_EMAIL; ?>
						</a>
					</li>
					<li>
						<a href="https://goo.gl/maps/mxwo37AoV7m86Jbt7" target="_blank"
						   title="Visite à Industing"><i class="fa fa-home"></i> Endereço: <?php
                            echo SITE_ADDR_ADDR; ?>
							, <?php
                            echo SITE_ADDR_DISTRICT; ?>, <?php
                            echo SITE_ADDR_CITY; ?>/<?php
                            echo SITE_ADDR_UF; ?>
							- <?php
                            echo SITE_ADDR_COUNTRY; ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="col-lg-6 col-sm-6 col-xs-12 col-xs-12">
				<div class="foo_social popUp_social text-right">

                    <?php
                    echo SITE_SOCIAL_TWITTER !== '' ? "<a title='Siga-nos no Twitter' href='https://www.twitter.com/" . SITE_SOCIAL_TWITTER . "'  target='_blank'><i class='fab fa-twitter'></i></a>" : ''; ?>

                    <?php
                    echo SITE_SOCIAL_FB_PAGE !== '' ? "<a title='Siga-nos no Facebook' href='https://www.facebook.com/" . SITE_SOCIAL_FB_PAGE . "'  target='_blank'><i class='fab fa-facebook-f'></i></a>" : ''; ?>

                    <?php
                    echo SITE_SOCIAL_INSTAGRAM !== '' ? "<a title='Siga-nos no Instagram' href='https://www.instagram.com/" . SITE_SOCIAL_INSTAGRAM . "'  target='_blank'><i class='fab fa-instagram'></i></a>" : ''; ?>

                    <?php
                    echo SITE_SOCIAL_LINKEDIN !== '' ? "<a title='Siga-nos no Linkedin' href='https://www.linkedin.com/company/" . SITE_SOCIAL_LINKEDIN . "'  target='_blank'><i class='fab fa-linkedin'></i></a>" : ''; ?>

                    <?php
                    echo SITE_SOCIAL_YOUTUBE !== '' ? "<a title='Inscreva-se em nosso canal no Youtube' href='https://www.youtube.com/user/" . SITE_SOCIAL_YOUTUBE . "?sub_confirmation=1'  target='_blank'><i class='fab fa-youtube'></i></a>" : ''; ?>

				</div>
			</div>
		</div>
	</div>
</div><!-- /Overlay Menu -->

<div class='testimony'>
	<div class='testimony_content'>
		<span class='testimony_close'>X</span>
		<h1><b>Assistir Vídeo: </b></h1>
		<div class='embed-container'></div>
		<p><b>Descrição: </b></p>
		<div class='content_like'>
			<div class='box_like'></div>
		</div>
	</div>
</div>
<script>

    $(function () {
        const BASE = <?= BASE ?>
        //PLAY TAKE
        $('.manual_video').click(function () {
            let videoId = $(this).attr('id');
            let videoTitle = $(this).data('title');
            let videoDesc = $(this).data('desc');

            $('.testimony_content h1').append(' ' + videoTitle);
            $('.testimony_content p').append(' ' + videoDesc);

            $('.testimony_content .embed-container').html('<iframe width="640" height="360" src="https://www.youtube' +
                '.com/embed/' + videoId + '?rel=0&amp;showinfo=0&autoplay=1&origin="' + BASE +
                'frameborder="0" allowfullscreen></iframe>');
            $('.testimony').fadeIn(200);
        });

        $('.testimony_close').click(function () {
            $('.testimony').fadeOut(200, function () {
                $('.testimony_content .embed-container').html('');
            });
        });
        //END PLAY TAKE
    });

</script>
