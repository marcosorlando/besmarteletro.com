<?php

    require_once __DIR__ . '/vendor/autoload.php';
    //@todo Something REMOVER ou comentar DEPOIS DE ATUALIZAR
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // REMOVER ou comentar DEPOIS DE ATUALIZAR

    ini_set('allow_url_fopen', 'On');
    ob_start();
    session_start();

    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    use App\Conn\Read;
    use App\Conn\Update;
    use App\Models\Seo;
    use App\Models\Session;

    // CHANCE THEME IN SESSION
    $WC_THEME = filter_input(INPUT_GET, 'wctheme');
    if ($WC_THEME && $WC_THEME != null) {
        $_SESSION['WC_THEME'] = $WC_THEME;
        $_SESSION['INCLUDE_PATH'] = BASE . '/themes/' . $WC_THEME;
        $_SESSION['REQUIRE_PATH'] = 'themes/' . $WC_THEME;
    } else {
        $_SESSION['WC_THEME'] = THEME;
        $_SESSION['INCLUDE_PATH'] = BASE . '/themes/' . THEME;
        $_SESSION['REQUIRE_PATH'] = 'themes/' . THEME;
    }

    // READ CLASS AUTO INSTANCE
    $Read ??= new Read();
    $Sesssion = new Session(SIS_CACHE_TIME);

    // USER SESSION VALIDATION
    if (!empty($_SESSION['userLogin']) && !empty($_SESSION['userLogin']['user_id'])) {
        if (empty($Read)) {
            $Read = new Read();
        }
        $Read->exeRead(DB_USERS, 'WHERE user_id = :user_id', 'user_id=' . $_SESSION['userLogin']['user_id']);
        if ($Read->getResult()) {
            $_SESSION['userLogin'] = $Read->getResult()[0];
        } else {
            unset($_SESSION['userLogin']);
        }
    }

    // GET PARAMETER URL
    $getURL = strip_tags(trim((string)filter_input(INPUT_GET, 'url', FILTER_DEFAULT)));
    $setURL = ('' === $getURL || '0' === $getURL ? 'index' : $getURL);
    $URL = explode('/', $setURL);
    $SEO = new Seo($setURL);

    // CHECK IF THIS POST TABLE TO AMP
    if (
        APP_POSTS_AMP
        && (isset($URL[0])
            && ('' !== $URL[0]
                && '0' !== $URL[0])
            && 'artigo' == $URL[0])
        && file_exists($_SESSION['REQUIRE_PATH'] . '/amp.php')
    ) {
        $Read->exeRead(DB_POSTS, 'WHERE post_name = :name', 'name=' . $URL[1]);
        $PostAmp = (1 == $Read->getResult()[0]['post_amp']);
    }

    // INSTANCE AMP (valid single article only)
    if (
        APP_POSTS_AMP
        && (isset($URL[0])
            && ('' !== $URL[0]
                && '0' !== $URL[0])
            && 'artigo' == $URL[0])
        && file_exists($_SESSION['REQUIRE_PATH'] . '/amp.php')
        && (isset($URL[2])
            && ('' !== $URL[2]
                && '0' !== $URL[2])
            && 'amp' == $URL[2])
        && (!empty($PostAmp)
            && true == $PostAmp)
    ) {
        require $_SESSION['REQUIRE_PATH'] . '/amp.php';
    } else {
        ?>
		<!DOCTYPE html>
		<html lang="pt-br" itemscope itemtype="https://schema.org/<?= $SEO->getSchema(); ?>">

		<head>
			<meta charset="UTF-8">
			<meta name="mit" content="2017-11-16T11:05:36-02:00+24186">
			<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
			<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0,user-scalable=0">
			<meta property="fb:pages" content="#"/>
			<title><?= $SEO->getTitle(); ?></title>
			<meta name="description" content="<?= $SEO->getDescription(); ?>"/>
			<meta name="robots" content="index, follow"/>
			<meta name="msvalidate.01" content=""/>
			<link rel="base" href="<?= BASE; ?>"/>
			<link rel="canonical" href="<?= BASE; ?>/<?= $getURL; ?>"/>
            <?php

                if (
                    APP_POSTS_AMP
                    && (isset($URL[0])
                        && ('' !== $URL[0]
                            && '0' !== $URL[0])
                        && 'artigo' == $URL[0])
                    && file_exists($_SESSION['REQUIRE_PATH'] . '/amp.php')
                    && (!empty($PostAmp)
                        && true == $PostAmp)
                ) {
                    echo '<link rel="amphtml" href="' . BASE . '/' . $getURL . '/amp" />' . "\r\n";
                }
            ?>
			<link rel="alternate" type="application/rss+xml" href="<?= BASE; ?>/rss.php"/>
			<link rel="sitemap" type="application/xml" href="<?= BASE; ?>/sitemap.xml"/>
			<meta itemprop="name" content="<?= $SEO->getTitle(); ?>"/>
			<meta itemprop="description" content="<?= $SEO->getDescription(); ?>"/>
			<meta itemprop="image" content="<?= $SEO->getImage(); ?>"/>
			<meta itemprop="url" content="<?= BASE; ?>/<?= $getURL; ?>"/>
			<meta property="og:type" content="article"/>
			<meta property="og:title" content="<?= $SEO->getTitle(); ?>"/>
			<meta property="og:description" content="<?= $SEO->getDescription(); ?>"/>
			<meta property="og:image" content="<?= $SEO->getImage(); ?>"/>
			<meta property="og:url" content="<?= BASE; ?>/<?= $getURL; ?>"/>
			<meta property="og:site_name" content="<?= SITE_NAME; ?>"/>
			<meta property="og:locale" content="pt_BR"/>
			<meta name="facebook-domain-verification" content=""/>
            <?php

                if (SITE_SOCIAL_FB !== 0) {
                    echo '<meta property="article:author" content="https://www.facebook.com/'
                        . SITE_SOCIAL_FB_AUTHOR . '" />' . "\r\n";
                    echo '<meta property="article:publisher" content="https://www.facebook.com/'
                        . SITE_SOCIAL_FB_PAGE . '" />' . "\r\n";

                    if (SITE_SOCIAL_FB_APP !== '') {
                        echo '<meta property="og:app_id" content="' . SITE_SOCIAL_FB_APP . '" />' . "\r\n";
                    }

                    if (SEGMENT_FB_PAGE_ID !== '') {
                        echo '<meta property="fb:pages" content="' . SEGMENT_FB_PAGE_ID . '" />' . "\r\n";
                    }
                }
            ?>

			<meta property="twitter:card" content="summary_large_image"/>
            <?php

                if (SITE_SOCIAL_TWITTER !== '') {
                    echo '<meta property="twitter:site" content="@' . SITE_SOCIAL_TWITTER . '" />' . "\r\n";
                }
            ?>
			<meta property="twitter:domain" content="<?= BASE; ?>"/>
			<meta property="twitter:title" content="<?= $SEO->getTitle(); ?>"/>
			<meta property="twitter:description" content="<?= $SEO->getDescription(); ?>"/>
			<meta property="twitter:image" content="<?= $SEO->getImage(); ?>"/>
			<meta property="twitter:url" content="<?= BASE; ?>/<?= $getURL; ?>"/>
			<link rel='preconnect' href='https://fonts.googleapis.com'>
			<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
			<link href='https://fonts.googleapis.com/css2?family=<?= SITE_FONT_NAME; ?>:wght@<?= SITE_FONT_WEIGHT; ?>&display=swap'
			      rel='stylesheet'>

            <?php
                /* Presets */
                require REQUIRE_PATH . '/presets.php';
            ?>
			<style> * {
                    font-family: '<?= SITE_FONT_NAME; ?>', sans-serif;
                }</style>

			<!-- favicon -->
			<link rel="shortcut icon" href="<?= $_SESSION['INCLUDE_PATH']; ?>/images/icons/favicon.png">
			<link rel="apple-touch-icon"
			      href="<?= $_SESSION['INCLUDE_PATH']; ?>/images/icons/apple-touch-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="72x72"
			      href="<?= $_SESSION['INCLUDE_PATH']; ?>/images/icons/apple-touch-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="114x114"
			      href="<?= $_SESSION['INCLUDE_PATH']; ?>/images/icons/apple-touch-icon-114x114.png">

			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/reset.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/fonticon.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/font-awesome.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/bootcss/font-awesome-animation.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/owl.carousel.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/owl.theme.default.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/jquery-ui.min.css"/>
			<link rel="stylesheet" href="<?= BASE; ?>/_cdn/widgets/filter/filter.min.css"/>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css"/>

			<link rel='stylesheet' href='<?= INCLUDE_PATH; ?>/_css/responsive.min.css'/>

            <?php
                // MAIN STYLE THEME
                if (file_exists('themes/' . $_SESSION['WC_THEME'] . '/style.css')) {
                    echo '<link rel="stylesheet" href="' . $_SESSION['INCLUDE_PATH'] . '/style.min.css"/>' . "\r\n";
                }

                //WC THEME CSS FILES
                /*    if (file_exists('themes/' . $_SESSION['WC_THEME'] . '/_css')) {
                        foreach (scandir('themes/' . $_SESSION['WC_THEME'] . '/_css') as $wcCssThemeFiles) {
                            if (
                                file_exists('themes/' . $_SESSION['WC_THEME'] . "/_css/{$wcCssThemeFiles}") && !is_dir(
                                    'themes/' . $_SESSION['WC_THEME'] . "/_css/{$wcCssThemeFiles}"
                                ) && pathinfo(
                                    'themes/' . $_SESSION['WC_THEME'] . "/_css/{$wcCssThemeFiles}"
                                )['extension'] == 'css'
                            ) {
                                echo '<link rel="stylesheet" href="' . $_SESSION['INCLUDE_PATH'] . '/_css/' . $wcCssThemeFiles . '"/>';
                            }
                        }
                    }*/
            ?>


			<script src="<?= BASE; ?>/_cdn/jquery.js"></script>


			<!-- Facebook Pixel Code -->
            <?php
                if (SEGMENT_FB_PIXEL_ID !== '' && SEGMENT_FB_PIXEL_ID !== '0') {
                    echo "<script>
                ! function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                    'https://connect.facebook.net/en_US/fbevents.js');
                fbq('init', '" . SEGMENT_FB_PIXEL_ID . "');
                fbq('track', 'PageView');
             </script>";
                }
            ?>
			<!-- End Facebook Pixel Code -->

            <?php
                // GOOGLE ANALYTICS GA4 WITH DEFINE IN CONFIG
                if (SEGMENT_GL_ANALYTICS !== '' && SEGMENT_GL_ANALYTICS !== '0') {
                    // Global site tag (gtag.js) - Google Analytics
                    echo "<script async src='https://www.googletagmanager.com/gtag/js?id="
                        . SEGMENT_GL_ANALYTICS . "'></script>";
                    echo "<script>
					window.dataLayer = window.dataLayer || [];
						function gtag() { dataLayer.push(arguments); }
                            gtag('js', new Date());
                            gtag('config', '" . SEGMENT_GL_ANALYTICS . "'); "
                        . (SEGMENT_GL_ADWORDS_ID !== '' ? "gtag('config', '" . SEGMENT_GL_ADWORDS_ID . "');" : '')
                        . '</script>';
                }

                if (SEGMENT_GL_TAGMANAGER !== '' && SEGMENT_GL_TAGMANAGER !== '0') {
                    // <!-- Google Tag Manager -->
                    echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':"
                        . " new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],"
                        . " j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= "
                        . "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f); })"
                        . "(window,document,'script','dataLayer','" . SEGMENT_GL_TAGMANAGER . "');</script>";
                    // <!-- End Google Tag Manager -->
                }
            ?>
		</head>

		<body>
        <?php
            if (SEGMENT_GL_TAGMANAGER !== '' && SEGMENT_GL_TAGMANAGER !== '0') {
                // <!-- Google Tag Manager (noscript) -->
                echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='
                    . SEGMENT_GL_TAGMANAGER
                    . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
                // <!-- End Google Tag Manager (noscript) -->
            }
        ?>


        <?php
            // MESSAGE MAINTENANCE FOR ADMIN
            if (
                ADMIN_MAINTENANCE
                && !empty($_SESSION['userLogin']['user_level'])
                && $_SESSION['userLogin']['user_level'] >= 6
            ) {
                echo "<div class='workcontrol_maintenance'>&#x267A; O MODO de manutenção está ativo. "
                    . 'Somente administradores podem ver o site assim &#x267A;</div>';
            }

            // REDIRECT PUBLIC TO MAINTENANCE
            if (
                ADMIN_MAINTENANCE
                && (empty($_SESSION['userLogin']['user_level'])
                    || $_SESSION['userLogin']['user_level'] < 6)
            ) {
                require __DIR__ . '/maintenance.php';
            } else {
                // PESQUISA PRODUTOS
                $Search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if ($Search && !empty($Search['p'])) {
                    $Search = urlencode(strip_tags(trim($Search['p'])));
                    header('Location: ' . BASE . '/pesquisa-produtos/' . $Search);

                    exit;
                }

                // PESQUISA
                $Search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if ($Search && !empty($Search['s'])) {
                    $Search = urlencode(strip_tags(trim($Search['s'])));
                    header('Location: ' . BASE . '/pesquisa/' . $Search);

                    exit;
                }

                // LANDING_PAGES MODULE
                // LP
                $Customers = [];
                $Read->fullRead('SELECT page_name FROM ' . DB_LANDING_PAGES);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $SinglePage) {
                        $Customers[] = $SinglePage['page_name'];
                    }
                }

                // TP
                $Tps = [];
                $Read->fullRead('SELECT page_name FROM ' . DB_THANKYOU_PAGES);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $SinglePage) {
                        $Tps[] = $SinglePage['page_name'];
                    }
                }

                // LINKTREE
                $Cards = [];
                $Read->fullRead('SELECT carduser_url FROM ' . DB_CARD_USER);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $SinglePage) {
                        $Cards[] = $SinglePage['carduser_url'];
                    }
                }

                if (in_array($URL[0], $Tps) && file_exists($_SESSION['REQUIRE_PATH'] . '/thankyou-page.php')) {
                    if (file_exists($_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php")) {
                        require $_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php";
                    } else {
                        require $_SESSION['REQUIRE_PATH'] . '/thankyou-page.php';
                    }
                } elseif (
                    in_array($URL[0], $Customers) && file_exists(
                        $_SESSION['REQUIRE_PATH'] . '/landing-page.php'
                    )
                ) {
                    if (file_exists($_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php")) {
                        require $_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php";
                    } else {
                        require $_SESSION['REQUIRE_PATH'] . '/landing-page.php';
                    }
                    // END LANDING_PAGES MODULE
                } elseif (
                    in_array($URL[0], $Cards) && file_exists(
                        $_SESSION['REQUIRE_PATH'] . '/cartao-de-contato.php'
                    )
                ) {
                    if (file_exists($_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php")) {
                        require $_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php";
                    } else {
                        require $_SESSION['REQUIRE_PATH'] . '/cartao-de-contato.php';
                    }
                } elseif (isset($_SESSION['WC_THEME'])) {
                    // HEADER
                    if (file_exists('themes/' . $_SESSION['WC_THEME'] . '/inc/header.php')) {
                        require 'themes/' . $_SESSION['WC_THEME'] . '/inc/header.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/header.php na pasta do tema!');
                    }

                    // CONTENT
                    echo "<main class='main-wrapper oh'>";
                    $URL[1] = (empty($URL[1]) ? null : $URL[1]);

                    if ('rss' == $URL[0] || 'feed' == $URL[0] || 'rss.xml' == $URL[0]) {
                        header('Location: ' . BASE . '/rss.php');

                        exit;
                    }

                    $Pages = [];
                    $Read->fullRead('SELECT page_name FROM ' . DB_PAGES);
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $SinglePage) {
                            $Pages[] = $SinglePage['page_name'];
                        }
                    }

                    if (
                        in_array($URL[0], $Pages) && file_exists(
                            'themes/' . $_SESSION['WC_THEME'] . '/pagina.php'
                        )
                    ) {
                        if (file_exists('themes/' . $_SESSION['WC_THEME'] . "/page-{$URL[0]}.php")) {
                            require 'themes/' . $_SESSION['WC_THEME'] . "/page-{$URL[0]}.php";
                        } else {
                            require 'themes/' . $_SESSION['WC_THEME'] . '/pagina.php';
                        }
                    } elseif (file_exists('themes/' . $_SESSION['WC_THEME'] . '/' . $URL[0] . '.php')) {
                        if (
                            'artigos' == $URL[0] && file_exists(
                                'themes/' . $_SESSION['WC_THEME'] . "/cat-{$URL[1]}.php"
                            )
                        ) {
                            require 'themes/' . $_SESSION['WC_THEME'] . "/cat-{$URL[1]}.php";
                        } else {
                            require 'themes/' . $_SESSION['WC_THEME'] . '/' . $URL[0] . '.php';
                        }
                    } elseif (
                        file_exists(
                            'themes/' . $_SESSION['WC_THEME'] . '/' . $URL[0] . '/' . $URL[1] . '.php'
                        )
                    ) {
                        require 'themes/' . $_SESSION['WC_THEME'] . '/' . $URL[0] . '/' . $URL[1] . '.php';
                    } elseif (file_exists('themes/' . $_SESSION['WC_THEME'] . '/404.php')) {
                        require 'themes/' . $_SESSION['WC_THEME'] . '/404.php';
                    } else {
                        trigger_error(
                            'Não foi possível incluir o arquivo themes/'
                            . THEME . "/{$getURL}.php <b>(O arquivo 404 também não existe!)</b>"
                        );
                    }
                    echo '</main>';

                    // FOOTER
                    if (file_exists('themes/' . $_SESSION['WC_THEME'] . '/inc/footer.php')) {
                        require 'themes/' . $_SESSION['WC_THEME'] . '/inc/footer.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/footer.php na pasta do tema!');
                    }
                } else {
                    // HEADER
                    if (file_exists($_SESSION['REQUIRE_PATH'] . '/inc/header.php')) {
                        require $_SESSION['REQUIRE_PATH'] . '/inc/header.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/header.php na pasta do tema!');
                    }

                    // CONTENT
                    echo "<main class='main-wrapper oh'>";
                    $URL[1] = (empty($URL[1]) ? null : $URL[1]);

                    if ('rss' == $URL[0] || 'feed' == $URL[0] || 'rss.xml' == $URL[0]) {
                        header('Location: ' . BASE . '/rss.php');

                        exit;
                    }

                    $Pages = [];
                    $Read->fullRead('SELECT page_name FROM ' . DB_PAGES);
                    if ($Read->getResult()) {
                        foreach ($Read->getResult() as $SinglePage) {
                            $Pages[] = $SinglePage['page_name'];
                        }
                    }

                    if (in_array($URL[0], $Pages) && file_exists($_SESSION['REQUIRE_PATH'] . '/pagina.php')) {
                        if (file_exists($_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php")) {
                            require $_SESSION['REQUIRE_PATH'] . "/page-{$URL[0]}.php";
                        } else {
                            require $_SESSION['REQUIRE_PATH'] . '/pagina.php';
                        }
                    } elseif (file_exists($_SESSION['REQUIRE_PATH'] . '/' . $URL[0] . '.php')) {
                        if ('artigos' == $URL[0] && file_exists($_SESSION['REQUIRE_PATH'] . "/cat-{$URL[1]}.php")) {
                            require $_SESSION['REQUIRE_PATH'] . "/cat-{$URL[1]}.php";
                        } else {
                            require $_SESSION['REQUIRE_PATH'] . '/' . $URL[0] . '.php';
                        }
                    } elseif (file_exists($_SESSION['REQUIRE_PATH'] . '/' . $URL[0] . '/' . $URL[1] . '.php')) {
                        require $_SESSION['REQUIRE_PATH'] . '/' . $URL[0] . '/' . $URL[1] . '.php';
                    } elseif (file_exists($_SESSION['REQUIRE_PATH'] . '/404.php')) {
                        require $_SESSION['REQUIRE_PATH'] . '/404.php';
                    } else {
                        trigger_error(
                            'Não foi possível incluir o arquivo themes/'
                            . THEME . "/{$getURL}.php <b>(O arquivo 404 também não existe!)</b>"
                        );
                    }
                    echo '</main>';

                    // FOOTER
                    if (file_exists($_SESSION['REQUIRE_PATH'] . '/inc/footer.php')) {
                        require $_SESSION['REQUIRE_PATH'] . '/inc/footer.php';
                    } else {
                        trigger_error('Crie um arquivo /inc/footer.php na pasta do tema!');
                    }
                }
            }

            // WC CODES
            $Read->exeRead(DB_WC_CODE);
            if ($Read->getResult()) {
                if (empty($Update)) {
                    $Update = new Update();
                }

                $ActiveCodes = filter_input(INPUT_GET, 'url');
                echo "\r\n\r\n\r\n<!--WorkControl Codes-->\r\n";
                foreach ($Read->getResult() as $HomeCodes) {
                    if (empty($HomeCodes['code_condition'])) {
                        echo $HomeCodes['code_script'];
                        $UpdateCodes = ['code_views' => $HomeCodes['code_views'] + 1];
                        $Update->exeUpdate(
                            DB_WC_CODE,
                            $UpdateCodes,
                            'WHERE code_id = :id',
                            'id=' . $HomeCodes['code_id']
                        );
                    } elseif (
                        preg_match(
                            '/' . str_replace('/', '\/', $HomeCodes['code_condition']) . '/',
                            $ActiveCodes
                        )
                    ) {
                        echo $HomeCodes['code_script'];
                        $UpdateCodes = ['code_views' => $HomeCodes['code_views'] + 1];
                        $Update->exeUpdate(
                            DB_WC_CODE,
                            $UpdateCodes,
                            'WHERE code_id = :id',
                            'id=' . $HomeCodes['code_id']
                        );
                    }
                }
                echo "\r\n<!--/WorkControl Codes-->\r\n\r\n\r\n";
            }
            /*if (!empty(SEGMENT_FB_PIXEL_ID)) {
        require '_cdn/wc_track.php';
        }*/
        ?>

		<script src='<?= BASE; ?>/_cdn/workcontrol.min.js'></script>
		<script src='<?= BASE; ?>/_cdn/owl.carousel.min.js'></script>
		<script src='<?= BASE; ?>/_cdn/jquery-ui.min.js'></script>
		<script src='<?= BASE; ?>/_cdn/jquery.zoom.min.js'></script>
		<script src='<?= BASE; ?>/_cdn/widgets/filter/filter.min.js'></script>

        <?php
            // WC THEME JS FILES
            if (file_exists('themes/' . THEME . '/_js')) {
                foreach (scandir('themes/' . THEME . '/_js') as $wcJsThemeFiles) {
                    if (
                        file_exists('themes/' . THEME . "/_js/{$wcJsThemeFiles}") && !is_dir(
                            'themes/' . THEME . "/_js/{$wcJsThemeFiles}"
                        ) && pathinfo('themes/' . THEME . "/_js/{$wcJsThemeFiles}")['extension'] == 'js'
                    ) {
                        echo '<script src="' . $_SESSION['INCLUDE_PATH'] . '/_js/' . $wcJsThemeFiles . '"></script>' . "\r\n";
                    }
                }
            }
            // MAIN SCRIPT THEME
            if (file_exists('themes/' . THEME . '/scripts.js')) {
                echo '<script src="' . $_SESSION['INCLUDE_PATH'] . '/scripts.min.js"></script>' . "\r\n";
            }
        ?>
		<!--ACCESS-->
        <?php
            //require __DIR__ . '/_cdn/widgets/accessibility/accessibility.inc.php';
        ?>

		</body>

		</html>
        <?php
    }
    ob_end_flush();

    if (!file_exists('.htaccess')) {
        $htaccesswrite = "RewriteEngine On\r\nOptions All -Indexes\r\n\r\n"
            . "# WC WWW Redirect.\r\n#RewriteCond %{HTTP_HOST} !^www\\. [NC]\r\n"
            . "#RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n"
            . "# WC HTTPS Redirect\r\nRewriteCond %{HTTP:X-Forwarded-Proto} !https\r\n"
            . "RewriteCond %{HTTPS} off\r\nRewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]\r\n\r\n"
            . "# WC URL Rewrite\r\nRewriteCond %{SCRIPT_FILENAME} !-f\r\n"
            . "RewriteCond %{SCRIPT_FILENAME} !-d\r\nRewriteRule ^(.*)$ index.php?url=$1";
        $htaccess = fopen('.htaccess', 'w');
        fwrite($htaccess, str_replace("'", '"', $htaccesswrite));
        fclose($htaccess);
    }

?>
