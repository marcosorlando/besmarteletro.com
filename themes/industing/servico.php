<?php

use App\Conn\Read;
use App\Conn\Update;

$Read ??= new Read();

$Read->exeRead(DB_SVC, 'WHERE svc_name = :nm', 'nm='.$URL[1]);

if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);
$Update = new Update();
$UpdateView = [
    'svc_views' => $svc_views + 1,
    'svc_lastview' => \date('Y-m-d H:i:s'),
];
$Update->exeUpdate(DB_SVC, $UpdateView, 'WHERE svc_id = :id', 'id='.$svc_id);

?>

<section class="product-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h1><?php echo $svc_title; ?></h1>
            </div>
            <div class="col-sm-12 col-md-6 breadcrumbs">
                <a href="<?php echo BASE; ?>">Home</a><i>|</i>
                <a href="<?php echo BASE.'/servicos'; ?>" title="Processos">Processos</a>
            </div>
        </div>
    </div>
</section>

<section class="commonSection serviceDetailsSecions padding-top-50px">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <div class="service_details_area htmlchars">
                    
                    <div class="sda_gall">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="sda_gl">
                                    <div id="serviceSlide" class="carousel slide serviceSlide" data-ride="carousel">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <div class="ps_img">
                                                    <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $svc_cover; ?>&w=800
                                                     ?>&h=800" alt="<?php echo $svc_title; ?>"/>
                                                </div>
                                            </div>
                                            <?php
                                            $Read->exeRead(
                                                DB_SVC_GALLERY,
                                                'WHERE svc_id = :svc ORDER BY id',
                                                'svc='.$svc_id
                                            );
if ($Read->getResult()) {
    foreach ($Read->getResult() as $Images) {
        ?>
                                                    <div class="carousel-item">
                                                        <div class="ps_img">
                                                            <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $Images['image']; ?>&w=800&h=800"
                                                                 alt="<?php echo $svc_title; ?>"/>
                                                        </div>
                                                    </div>
                                                    <?php
    }
}
?>
                                        </div>
                                        <ol class="carousel-indicators clearfix">
                                            <li data-target="#serviceSlide" data-slide-to="0" class="active">
                                                <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $svc_cover; ?>&w=202
                                                ?>&h=202&fit=crop"
                                                     alt="<?php echo $svc_title; ?>"/>
                                            </li>
                                            <?php
if ($Read->getResult()) {
    $count = 1;
    foreach ($Read->getResult() as $Images) {
        ?>
                                                    <li data-target="#serviceSlide" data-slide-to="<?php echo $count; ?>">
                                                        <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $Images['image']; ?>&w=400&h=400"
                                                             alt="<?php echo $svc_title; ?>"/>
                                                    </li>
                                                    <?php
        ++$count;
    }
}
?>
                                        </ol>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sda_content">
                        <p>
                            <?php echo $svc_description; ?>
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
                    <h3 class="widget_title">Nossos Processos</h3>
                    <ul>
                        <?php
                        $Read->fullRead(
                            'SELECT svc_title, svc_name FROM '.DB_SVC.' WHERE svc_status >= :st',
                            'st=1'
                        );
if ($Read->getResult()) {
    foreach ($Read->getResult() as $svc) {
        \extract($svc);
        echo "<li><a href='".BASE.\sprintf(
            "/servico/%s'>%s</a></li>",
            $svc_name,
            $svc_title
        );
    }
}
?>
                    </ul>
                </aside>
                <?php
                $Read->fullRead(
                    'SELECT post_title, post_name, post_date, post_cover FROM '.DB_POSTS.' WHERE post_status = :ps ORDER BY post_date DESC LIMIT 3',
                    'ps=1'
                );
if ($Read->getResult()) {
    foreach ($Read->getResult() as $post) {
        \extract($post);
        ?>
                        <aside class="widget">
                            <h3 class="widget_title">Novidades do Blog</h3>
                            <div class="allLatestWorks">
                                <div class="ltworks">
                                    <h3><a href="<?php echo BASE.('/artigo/'.$post_name); ?>"><?php echo $post_title; ?></a></h3>
                                    <p><a href="#"><?php echo $post_date; ?></a></p>
                                </div>
                            </div>
                        </aside>
                        <?php
    }
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

<?php
include_once __DIR__.'/inc/cta.inc.php';
?>
