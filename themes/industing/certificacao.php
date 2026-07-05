<?php

use App\Conn\Read;

$Read ??= new Read();
$URL[1] ??= '';

$Read->exeRead(DB_CERT, 'WHERE cert_name = :nm AND cert_status = :st', \sprintf('nm=%s&st=1', $URL[1]));

if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);

?>

<section class="product-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h2><?php echo $cert_title; ?></h2>
            </div>
            <div class="col-sm-12 col-md-6 breadcrumbs">
                <a href="<?php echo BASE; ?>">Home</a><i>|</i>
                <a href="<?php echo BASE.'/certificacoes'; ?>" title="Certificações">Certificações</a>
            </div>
        </div>
    </div>
</section>

<section class="commonSection serviceDetailsSecions">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="service_details_area htmlchars">
                    <h2 class="entry_title">
                        <?php echo $cert_title; ?>
                    </h2>
                    <div class="sda_gall">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="sda_gl">
                                    <img src="<?php echo ($cert_cover ? BASE.\sprintf(
                                        '/tim.php?src=uploads/%s&w=1200&h=628',
                                        $cert_cover
                                    ) : BASE.'/tim.php?src=admin/_img/no_image.jpg').'&w=1200&h=628'; ?>"
                                         alt="<?php echo $cert_title; ?>" title="<?php echo $cert_title; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sda_content">
                        <p>
                            <?php echo $cert_description; ?>
                        </p>
                        <!--                        <div class="sda_tags clearfix">-->
                        <!--                            <div class="icon_box_04">-->
                        <!--                                <i class="fal fa-home"></i>-->
                        <!--                                <h4>Construction</h4>-->
                        <!--                            </div><div class="icon_box_04">-->
                        <!--                                <i class="fal fa-industry"></i>-->
                        <!--                                <h4>Industry</h4>-->
                        <!--                            </div><div class="icon_box_04">-->
                        <!--                                <i class="fal fa-mountain"></i>-->
                        <!--                                <h4>Fire Brick</h4>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 sidebar_1">
                <aside class="widget">
                    <h3 class="widget_title">Certificações</h3>
                    <ul>
                        <?php
                        $Read->fullRead(
                            'SELECT cert_title, cert_name FROM '.DB_CERT.' WHERE cert_status >= :st',
                            'st=1'
                        );
if ($Read->getResult()) {
    foreach ($Read->getResult() as $svc) {
        \extract($svc);
        echo "<li><a href='".BASE.\sprintf(
            "/certificacao/%s'>%s</a></li>",
            $cert_name,
            $cert_title
        );
    }
}
?>
                    </ul>
                </aside>
                <?php
                $Read->fullRead(
                    'SELECT post_title, post_name, post_date, post_cover FROM '.DB_POSTS.' WHERE post_status = :ps AND post_category = :cat ORDER BY post_date DESC LIMIT 3',
                    'ps=1&cat=8'
                );
if ($Read->getResult()) {
    echo "<aside class='widget last-news'>";
    echo "<h3 class='widget_title'>Últimos artigos do Blog</h3>";

    foreach ($Read->getResult() as $Post) {
        \extract($Post);

        $Post['post_cover'] = $Post['post_cover'] ? 'uploads/'.$Post['post_cover'] : 'admin/_img/no_image.jpg';

        echo "<div class='allLatestWorks'>";
        echo "<div class='ltworks'>";
        echo "<a href='".BASE.\sprintf(
            "/artigo/%s'><img class='res' alt='%s' src='",
            $post_name,
            $post_title
        )
            .BASE
            .\sprintf("/tim.php?src=%s&w=1200&h=628'></a>", $Post['post_cover']);
        echo "<h4><a href='".BASE.\sprintf("/artigo/%s'>%s </a></h4>", $post_name, $post_title);
        echo "<p><i class='fal fa-calendar-check'></i> ".\date(
            'd-m-Y',
            \strtotime((string) $post_date)
        ).'</p>';
        echo '</div>';
        echo '</div>';
    }
    echo '</aside>';
}
?>
                <aside class="widget havqueswidget">
                    <h3 class="widget_title">Alguma dúvida?</h3>
                    <div class="hqw_content">
                        <p> Entre em contato conosco que responderemos em breve.</p>
                        <span><i class="fa fa-envelope text-blue"></i> <a
                                    href="mailto:<?php echo SITE_ADDR_EMAIL; ?>"><?php echo SITE_ADDR_EMAIL; ?></a></span>

                        <p> Ou ligue agora!</p>
                        <span><i class="fa fa-phone text-blue"></i> <a
                                    href="tel:<?php echo SITE_ADDR_PHONE_A; ?>"><?php echo SITE_ADDR_PHONE_A; ?></a></span>

                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<section class="calltoactions2 overlays div_gray">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-md-8">
                <div class="cta_left_02">
                    <i class="fa fa-calculator-alt"></i>
                    <h4>Estamos disponíveis</h4>
                    <h3>Solicite seu orçamento agora mesmo!</h3>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 text-right pdt25">
                <a href="https://marketing.seu-dominio.com.br/orcamento-site"
                   class="ind_btn id_dark2"><span><i class="fa fa-calculator-alt"></i> Solicite uma Cotação</span></a>
            </div>
        </div>
    </div>
</section>
