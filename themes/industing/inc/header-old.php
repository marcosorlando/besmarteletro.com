<section class="topbar">
    <div class="header-container">
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-3 noPadding">
                <div class="logo text-left">
                    <a href="<?php echo BASE; ?>" title="Voltar ao Início!">
                        <img src="<?php echo INCLUDE_PATH; ?>/images/logo-color.png"
                             alt="<?php echo SITE_ADDR_NAME; ?> - Logotipo"/>
                    </a>
                </div>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-9">
                <div class="topbar_right text-right">
                    <div class="topbar_element info_element">
                        <i class="fa fa-envelope"></i>
                        <h5>
                            <a class="color-white" href="mailto:<?php echo SITE_ADDR_EMAIL; ?>"><?php echo SITE_ADDR_EMAIL; ?></a>
                        </h5>
                        <!--                        <p>-->
                        <!--                            <a href="mailto:info@webmail.com">info@webmail.com</a>-->
                        <!--                        </p>-->
                    </div>
                    <div class="topbar_element info_element">
                        <i class="fa fa-phone"></i>
                        <h5>
                            <a class="color-white" href="tel:<?php echo SITE_ADDR_PHONE_A; ?>"><?php echo SITE_ADDR_PHONE_A; ?></a>
                        </h5>

                    </div>
                    <div class="topbar_element search_element">
                        <form method="post" action="">
                            <i class="fa fa-search"></i>
                            <input type="search" name="s" placeholder="Pesquisar no Site..."/>
                        </form>
                    </div>
                    <div class="topbar_element settings_bar">
                        <a href="#" class="hamburger" id="open-overlay-nav"><i class="fal fa-bars"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="nav_bar" id="fix_nav">
    <div class="header-container">
        <div class="row">
            <div class="col-xl-8 col-lg-9">
                <div class="mobileMenuBar">
                    <a href="javascript: void(0);"><span>Menu</span><i class="fa fa-bars"></i></a>
                </div>
                <nav class="mainmenu">
                    <ul>
                        <li class="current-menu-item menu-item-has-children">
                            <a href="<?php echo BASE; ?>"><i class="fa fa-home"></i>Home</a>
                            <!--                            <ul class="sub_menu">-->
                            <!--                                <li>-->
                            <!--                                    <a href="index.html">Home Version 01</a>-->
                            <!--                                </li>-->
                            <!--                                <li>-->
                            <!--                                    <a href="index_2.html">Home Version 02</a>-->
                            <!--                                </li>-->
                            <!--                            </ul>-->
                        </li>
                        <li>
                            <a href="<?php echo BASE; ?>/sobre">A Travi</a>
                        </li>
                        <li>
                            <a href="<?php echo BASE; ?>/produtos/produtos"></i>Produtos</a>
                        </li>
                        <?php
                        $Read->fullRead(
                            'SELECT svc_title, svc_name FROM '.DB_SVC.' WHERE svc_status = :st',
                            'st=1'
                        );
                    if ($Read->getResult()) {
                        echo "<li><a href='".BASE."/servicos'>Serviços</a><ul class='sub_menu'>";
                        foreach ($Read->getResult() as $svc) {
                            \extract($svc);
                            echo "<li><a href='".BASE.\sprintf(
                                "/servico/%s'>%s</a></li>",
                                $svc_name,
                                $svc_title
                            );
                        }
                        echo '</ul></li>';
                    }
                    ?>
                        <li>
                            <a href="<?php echo BASE.'/segmentos'; ?>">Segmentos</a>
                        </li>
                        <?php
                    $Read->exeRead(
                        DB_CATEGORIES,
                        'WHERE category_parent IS NULL ORDER BY category_name ASC'
                    );
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $Cat) {
                            echo "<li class='menu-item-has-children'><a title=' ".SITE_NAME.\sprintf(
                                " | %s' href='",
                                $Cat['category_title']
                            ).BASE.\sprintf(
                                "/artigos/%s'>%s</a>",
                                $Cat['category_name'],
                                $Cat['category_title']
                            );
                            $Read->exeRead(
                                DB_CATEGORIES,
                                'WHERE category_parent = :ct ORDER BY category_name ASC',
                                'ct='.$Cat['category_id']
                            );
                            if ($Read->getResult()) {
                                echo "<ul class='sub_menu'>";
                                foreach ($Read->getResult() as $SubCat) {
                                    echo \sprintf(
                                        "<li><a title='%s | %s' href='",
                                        $Cat['category_title'],
                                        $SubCat['category_title']
                                    ).BASE.\sprintf(
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
                    ?>
                        <li class=" menu-item-has-children">
                            <a href="javascript:void(0);">Contatos</a>
                            <ul class="sub_menu">
                                <li>
                                    <a href="<?php echo BASE; ?>/contato">Entrar em Contato</a>
                                </li>
                                <li>
                                    <a href="<?php echo BASE; ?>/contato">Trabalhe Conosco</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="col-xl-4 col-lg-3">
                <div class="top_social text-right">
                    <?php echo SITE_SOCIAL_TWITTER !== '' ? "<a title='Siga-nos no Twitter' href='https://www.twitter.com/".SITE_SOCIAL_TWITTER."'  target='_blank'><i class='fab fa-twitter'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_FB_PAGE !== '' ? "<a title='Siga-nos no Facebook' href='https://www.facebook.com/".SITE_SOCIAL_FB_PAGE."'  target='_blank'><i class='fab fa-facebook-f'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_INSTAGRAM !== '' ? "<a title='Siga-nos no Instagram' href='https://www.instagram.com/".SITE_SOCIAL_INSTAGRAM."'  target='_blank'><i class='fab fa-instagram'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_LINKEDIN !== '' ? "<a title='Siga-nos no Linkedin' href='https://www.linkedin.com/company/".SITE_SOCIAL_LINKEDIN."'  target='_blank'><i class='fab fa-linkedin'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_YOUTUBE !== '' ? "<a title='Inscreva-se em nosso canal no Youtube' href='https://www.youtube.com/user/".SITE_SOCIAL_YOUTUBE."?sub_confirmation=1'  target='_blank'><i class='fab fa-youtube'></i></a>" : ''; ?>
                </div>
            </div>
        </div>
    </div>
    <span class="right_bgs"></span>
</section>

<!-- Overlay Menu -->
<div class="popup popup__menu">
    <div class="header-container mobileContainer">
        <div class="row">
            <div class="col-lg-8 text-left">
                <div class="popup_logos">
                    <a href="<?php echo BASE; ?>">
                        <img src="<?php echo INCLUDE_PATH; ?>/images/logo-white-new.png"
                             alt="<?php echo SITE_ADDR_NAME; ?> - Logotipo"/>
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
                                <a href="<?php echo BASE; ?>"><i class="fa fa-home fa-2x"></i></a>
                            </li>
                            <li>
                                <a href="<?php echo BASE; ?>/sobre">A Travi</a>
                            </li>
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0);">Contatos</a>
                                <ul class="dl-submenu">
                                    <li>
                                        <a href="<?php echo BASE; ?>/contato">Entre em Contato</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo BASE; ?>/contato">Trabalhe Conosco</a>
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
                                        <a href="<?php echo BASE; ?>404">404</a>
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
                        <a href="tel:<?php echo SITE_ADDR_PHONE_A; ?>" title="Ligar para Industing"><i
                                    class="fa fa-phone"></i> Telefone: <?php echo SITE_ADDR_PHONE_A; ?>
                        </a>
                    </li>
                    <li>
                        <a href="mailto:<?php echo SITE_ADDR_EMAIL; ?>" target="_blank" title="Envie-nos um E-mail..."><i
                                    class="fa fa-envelope"></i> Email: <?php echo SITE_ADDR_EMAIL; ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://goo.gl/maps/mxwo37AoV7m86Jbt7" target="_blank"
                           title="Visite à Industing"><i class="fa fa-home"></i> Endereço: <?php echo SITE_ADDR_ADDR; ?>
                            , <?php echo SITE_ADDR_DISTRICT; ?>, <?php echo SITE_ADDR_CITY; ?>/<?php echo SITE_ADDR_UF; ?>
                            - <?php echo SITE_ADDR_COUNTRY; ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12 col-xs-12">
                <div class="foo_social popUp_social text-right">

                    <?php echo SITE_SOCIAL_TWITTER !== '' ? "<a title='Siga-nos no Twitter' href='https://www.twitter.com/".SITE_SOCIAL_TWITTER."'  target='_blank'><i class='fab fa-twitter'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_FB_PAGE !== '' ? "<a title='Siga-nos no Facebook' href='https://www.facebook.com/".SITE_SOCIAL_FB_PAGE."'  target='_blank'><i class='fab fa-facebook-f'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_INSTAGRAM !== '' ? "<a title='Siga-nos no Instagram' href='https://www.instagram.com/".SITE_SOCIAL_INSTAGRAM."'  target='_blank'><i class='fab fa-instagram'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_LINKEDIN !== '' ? "<a title='Siga-nos no Linkedin' href='https://www.linkedin.com/company/".SITE_SOCIAL_LINKEDIN."'  target='_blank'><i class='fab fa-linkedin'></i></a>" : ''; ?>

                    <?php echo SITE_SOCIAL_YOUTUBE !== '' ? "<a title='Inscreva-se em nosso canal no Youtube' href='https://www.youtube.com/user/".SITE_SOCIAL_YOUTUBE."?sub_confirmation=1'  target='_blank'><i class='fab fa-youtube'></i></a>" : ''; ?>

                </div>
            </div>
        </div>
    </div>
</div><!-- /Overlay Menu -->
