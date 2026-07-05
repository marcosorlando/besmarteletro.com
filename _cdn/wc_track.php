<?php

if (!empty($_SESSION['userLogin'])) {
    $wcFbUserSection = \array_map('mb_strtolower', $_SESSION['userLogin']);
    $wcFbUserSection['user_lastname'] = \substr(
        $wcFbUserSection['user_lastname'],
        \strrpos($wcFbUserSection['user_lastname'], ' ')
    );
    $wcFbUserSection['user_phone'] = (empty($wcFbUserSection['user_cell']) ? (isset($wcFbUserSection['user_telephone']) && ('' !== $wcFbUserSection['user_telephone'] && '0' !== $wcFbUserSection['user_telephone']) ? $wcFbUserSection['user_telephone'] : null) : ($wcFbUserSection['user_cell']));

    if (isset($wcFbUserSection['user_phone']) && ('' !== $wcFbUserSection['user_phone'] && '0' !== $wcFbUserSection['user_phone'])) {
        $wcFbUserSection['user_phone'] = \str_replace(['(', ')', ' ', '-', '.'], '', $wcFbUserSection['user_phone']);
    }

    $wcFbUserSection['user_datebirth'] = \date('Ymd', \strtotime((string) $wcFbUserSection['user_datebirth']));

    $Read = new Read();
    $Read->fullRead(
        'SELECT addr_city, addr_state, addr_zipcode FROM '.DB_USERS_ADDR.' WHERE user_id = :id',
        'id='.$wcFbUserSection['user_id']
    );
    if ($Read->getResult()) {
        $wcFbUserAddr = \array_map('mb_strtolower', $Read->getResult()[0]);
        $wcFbUserAddr['addr_zipcode'] = \str_replace(['.', '-'], '', $wcFbUserAddr['addr_zipcode']);
    }
}
?>
<script>
    $(function () {
        FB_PIXEL = "<?php echo SEGMENT_FB_PIXEL_ID === '' || SEGMENT_FB_PIXEL_ID === '0' ? '"null"' : SEGMENT_FB_PIXEL_ID; ?>";
        WC_USER = <?php echo empty($wcFbUserSection) ? '"null"' : \json_encode($wcFbUserSection); ?>;
        WC_ADDR = <?php echo empty($wcFbUserAddr) ? '"null"' : \json_encode($wcFbUserAddr); ?>;
        WC_LINK = window.location.href;

        //FACEBOOK PIXEL
        !function (f, b, e, v, n, t, s) {
            if (f.fbq)
                return;
            n = f.fbq = function () {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq)
                f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');

        //WC TRACK
        WC_SEGMENT_USER = <?php echo SEGMENT_WC_USER === 0 ? 0 : 1; ?>;
        if (WC_USER === 'null' || !WC_SEGMENT_USER) {
            fbq('init', FB_PIXEL);
        } else {
            fbq('init', FB_PIXEL, {
                em: WC_USER.user_email, //Email
                fn: WC_USER.user_name, //Nome
                ln: WC_USER.user_lastname, //Sobrenome
                ph: WC_USER.user_phone, //Telefone
                ge: WC_USER.user_genre, //Genero
                db: WC_USER.user_datebirth, //Data de nascimento YYYYMMDD
                ct: WC_ADDR.addr_city, //Cidade
                st: WC_ADDR.addr_state, //Estado
                zp: WC_ADDR.addr_zipcode //Cep
            });
        }

        //WC EVENTS
        fbq('track', 'PageView');

        <?php if (APP_POSTS === 1) { ?>
        //SITE :: ARTIGO
        WC_SEGMENT_BLOG = <?php echo SEGMENT_WC_BLOG === 0 ? 0 : 1; ?>;
        if (WC_LINK.match('artigo/') && WC_SEGMENT_BLOG) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($post_title) ? null : $post_title; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_post',
                wc_post_id: '<?php echo empty($post_id) ? null : $post_id; ?>',
                wc_post_title: '<?php echo empty($post_title) ? null : $post_title; ?>',
                wc_post_category: '<?php echo empty($Category['category_title']) ? 'null' : $Category['category_title']; ?>'
            });
        }

        //SITE :: ARTIGOS
        if (WC_LINK.match('artigos/') && WC_SEGMENT_BLOG) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($category_title) ? null : $category_title; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_category',
                wc_category_id: '<?php echo empty($category_id) ? null : $category_id; ?>',
                wc_category_title: '<?php echo empty($category_title) ? null : $category_title; ?>'
            });
        }
        <?php } ?>

        //SITE :: PESQUISA
        if (WC_LINK.match('pesquisa/')) {
            fbq('track', 'Search', {
                search_string: '<?php echo empty($URL[1]) ? null : $URL[1]; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'blog_search',
                wc_search: '<?php echo empty($URL[1]) ? null : $URL[1]; ?>'
            });
        }

        //SITE :: CADASTRO
        if (WC_LINK.match('conta/home') && document.referrer.match('conta/cadastro')) {
            fbq('track', 'CompleteRegistration', {
                content_name: 'cadasrtou-se no site',
                status: 'active',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'acc_register'
            });
        }

        <?php if (APP_PRODUCTS_TRAVI !== 1) { ?>
        //E-COMMERCE :: PRODUTO
        WC_SEGMENT_ECOMMERCE = <?php echo empty(SEGMENT_WC_ECOMMERCE) ? 0 : 1; ?>;
        if (WC_LINK.match('produto/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($pdt_title) ? null : $pdt_title; ?>',
                content_ids: '<?php echo empty($pdt_id) ? null : 'product_'.$pdt_id; ?>',
                content_type: 'product',
                content_category: '<?php echo empty($Category['cat_title']) ? 'null' : $Category['cat_title']; ?>',
                value: '<?php echo empty($pdt_price) ? null : $pdt_price; ?>',
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_product',
                wc_pdt_id: '<?php echo empty($pdt_id) ? null : $pdt_id; ?>',
                wc_pdt_code: '<?php echo empty($pdt_code) ? null : $pdt_code; ?>',
                wc_pdt_title: '<?php echo empty($pdt_title) ? null : $pdt_title; ?>',
                wc_pdt_price: '<?php echo empty($pdt_price) ? null : $pdt_price; ?>',
                wc_pdt_category: '<?php echo empty($Category['cat_title']) ? 'null' : $Category['cat_title']; ?>'
            });
        }

        //E-COMMERCE :: PRODUTOS
        if (WC_LINK.match('produtos/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($cat_title) ? null : $cat_title; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_products_categories',
                wc_cat_id: '<?php echo empty($cat_id) ? null : $cat_id; ?>',
                wc_cat_title: '<?php echo empty($cat_title) ? null : $cat_title; ?>'
            });
        }

        //E-COMMERCE :: MARCA
        if (WC_LINK.match('marca/') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($brand_title) ? null : $brand_title; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_products_brands',
                wc_brand_id: '<?php echo empty($brand_id) ? null : $brand_id; ?>',
                wc_brand_title: '<?php echo empty($brand_title) ? null : $brand_title; ?>'
            });
        }

        //E-COMMERCE :: CARRINHO
        var CartValue = $('.wc_cart_price span').text().replace('.', '').replace(',', '.');
        if (WC_LINK.match('pedido/home') && CartValue && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'AddToCart', {
                content_ids: [<?php echo empty($wcCartIds) ? null : "'product_".\implode(
                    "', 'product_",
                    $wcCartIds
                )."'"; ?>],
                content_type: 'product',
                value: CartValue,
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart'
            });
        }
        <?php } ?>

        <?php if (APP_ORDERS == 1) { ?>
        //E-COMMERCE :: CHECKOUT
        if (WC_LINK.match('pedido/endereco') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'InitiateCheckout', {
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_addr'
            });
        }

        //E-COMMERCE :: PAGAMENTO
        if (WC_LINK.match('pedido/pagamento') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'AddPaymentInfo', {
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_payment'
            });
        }

        //E-COMMERCE :: COMPRA CONCLUÍDA
        if (WC_LINK.match('pedido/obrigado') && WC_SEGMENT_ECOMMERCE) {
            fbq('track', 'Purchase', {
                content_ids: [<?php echo empty($wcCartIds) ? null : "'product_".\implode(
                    "', 'product_",
                    $wcCartIds
                )."'"; ?>],
                content_type: 'product',
                value: CartValue,
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ecommerce_cart_purchase'
            });
        }
        <?php } ?>

        <?php if (APP_IMOBI == 1) { ?>
        //IMOBI :: IMÓVEL
        WC_SEGMENT_IMOBI = <?php echo empty(SEGMENT_WC_IMOBI) ? 0 : 1; ?>;
        if (WC_LINK.match('imovel/') && WC_SEGMENT_IMOBI) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($realty_title) ? null : $realty_title; ?>',
                content_category: '<?php echo empty($realty_type) ? 'null' : \getWcRealtyType($realty_type); ?>',
                content_transaction: '<?php echo empty($realty_transaction) ? 'null' : \getWcRealtyTransaction(
                    $realty_transaction
                ); ?>',
                content_finality: '<?php echo empty($realty_finality) ? 'null' : \getWcRealtyFinality($realty_finality); ?>',
                content_ids: '<?php echo empty($realty_id) ? null : 'realty_'.$realty_id; ?>',
                content_type: 'product',
                value: '<?php echo empty($realty_price) ? null : $realty_price; ?>',
                currency: 'BRL',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'imobi_realty',
                wc_realty_id: '<?php echo empty($realty_id) ? null : $realty_id; ?>',
                wc_realty_ref: '<?php echo empty($realty_ref) ? null : $realty_ref; ?>',
                wc_realty_title: '<?php echo empty($realty_title) ? null : $realty_title; ?>',
                wc_realty_price: '<?php echo empty($realty_price) ? null : $realty_price; ?>',
                wc_realty_finality: '<?php echo empty($realty_finality) ? null : \getWcRealtyFinality($realty_finality); ?>',
                wc_realty_type: '<?php echo empty($realty_type) ? null : \getWcRealtyType($realty_type); ?>',
                wc_realty_transaction: '<?php echo empty($realty_transaction) ? null : \getWcRealtyTransaction(
                    $realty_transaction
                ); ?>'
            });
        }

        //IMOBI :: IMÓVEIS
        if (WC_LINK.match('imoveis/') && WC_SEGMENT_IMOBI) {
            fbq('track', 'ViewContent', {
                content_name: '<?php echo empty($URL[1]) ? null : $URL[1]; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'imobi_realtys',
                wc_realty_type: '<?php echo empty($URL[2]) ? null : $URL[2]; ?>',
                wc_realty_transaction: '<?php echo empty($URL[1]) ? null : $URL[1]; ?>'
            });
        }
        <?php } ?>

        <?php if (APP_EAD == 1) { ?>
        //EAD :: CURSO
        WC_SEGMENT_EAD = <?php echo empty(SEGMENT_WC_EAD) ? 0 : 1; ?>;
        if (WC_LINK.match('campus/curso/') && WC_SEGMENT_EAD) {
            fbq('track', 'ViewContent', {
                content_name: 'Curso <?php echo empty($course_title) ? null : $course_title; ?>',
                content_category: '<?php echo empty($CourseSegment['segment_title']) ? null : $CourseSegment['segment_title']; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_course',
                wc_course_title: '<?php echo empty($course_title) ? null : $course_title; ?>',
                wc_course_segment: '<?php echo empty($CourseSegment['segment_title']) ? null : $CourseSegment['segment_title']; ?>'
            });
        }

        //EAD :: TAREFA
        if (WC_LINK.match('campus/tarefa') && WC_SEGMENT_EAD) {
            fbq('track', 'ViewContent', {
                content_name: 'Aula <?php echo empty($class_title) ? null : $class_title; ?>',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_class',
                wc_class_title: '<?php echo empty($class_title) ? null : $class_title; ?>',
                wc_class_time: '<?php echo empty($class_time) ? null : $class_time; ?>'
            });
        }

        //EAD :: LEAD
        if (WC_LINK.match('campus/ativar') && WC_SEGMENT_EAD) {
            fbq('track', 'Lead', {
                content_name: 'Cadastrou-se no EAD',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_register'
            });
        }

        //EAD :: CADASTRO
        if (WC_LINK.match('campus') && document.referrer.match('campus/ativar') && WC_SEGMENT_EAD) {
            fbq('track', 'CompleteRegistration', {
                content_name: 'Completou cadastro no EAD',
                status: 'active',
                referrer: document.referrer,
                userAgent: navigator.userAgent,
                language: navigator.language,
                //WC SEGMENT
                wc_source: 'ead_complete_registration'
            });
        }
        <?php } ?>
    });
</script>
