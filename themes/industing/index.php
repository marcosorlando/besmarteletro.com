<?php

use App\Conn\Read;
use App\Helpers\Check;
use App\Helpers\DateHelper;

require __DIR__.'/../../_cdn/widgets/slide/slide.wc.php';
?>
<section class="commonSection" id="content">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 noPaddingRight">
                <div class="video_01 mrm15 text-right">
                    <img src="<?php echo INCLUDE_PATH; ?>/images/home/1.jpg" alt="Industing"/>
                    <div class="vp">
                        <img src="<?php echo INCLUDE_PATH; ?>/images/home/2.jpg" alt="Assista o Vídeo Institucional"/>
                        <a class="videoPlayer" title="Assistir o Vídeo Institucional da Industing"
                           href="https://www.youtube.com/watch?v=wGkQTUe2aiA"><i class="fa fa-play"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="about_us_content">
                    <h6 class="sub_title">Olá somos à</h6>
                    <h2 class="sec_title">Industing</h2>
                    <p class="ind_lead">Desenvolvedores utilizam dados fictícios para testar aplicações.</p>
                    <p class="mb28">
	                    Desenvolvedores utilizam dados fictícios para testar aplicações em ambiente controlado. Esta ferramenta gera informações consistentes que simulam registros reais sem expor dados sensíveis. Ideal para prototipagem e desenvolvimento ágil de software.</p>
	                <p class='mb28'>
	                Desenvolvedores utilizam dados fictícios para testar aplicações em ambiente controlado. Esta ferramenta gera informações consistentes que simulam registros reais sem expor dados sensíveis. Ideal para prototipagem e desenvolvimento ágil de software.
	                </p>

                    <img src="<?= INCLUDE_PATH; ?>/images/sign.png" alt="Assinatura do CEO"/>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="soluctions" class="commonSection graySection">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 text-center">
                <h6 class="sub_title gray_sub_title">Solução em Plásticos Industrias</h6>
                <h2 class="sec_title with_bar">
                    <span><i class="fal fa-user-hard-hat"></i><span>Nossas Soluções</span></span>
                </h2>
            </div>
        </div>

        <div class="row align-content-md-stretch">
            <?php
            $Read ??= new Read();
$Read->fullRead(
    'SELECT svc_title, svc_subtitle, svc_name, svc_cover, svc_icon FROM '.DB_SVC.' WHERE svc_status >= :st',
    'st=1'
);

if (!$Read->getResult()) {
    echo Check::erro('Nenhum serviço cadastrado. Favor Volte mais tarde.', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Svc) {
        \extract($Svc);
        $svc_cover = $svc_cover ? 'uploads/'.$svc_cover : 'admin/_img/no_image.jpg';
        $svc_icon = $svc_icon ? 'uploads/'.$svc_icon : 'admin/_img/no_image.jpg';
        ?>
                    <div class="col-lg-4 col-md-6">
                        <a class="service-link" href="<?php echo BASE.('/servico/'.$svc_name); ?>">
                            <div class="icon_box_01 text-center">

                                <img class="svc-icon" src="<?php echo BASE; ?>/tim.php?src=<?php echo $svc_icon; ?>&w=90&h=90" alt="<?php echo $svc_title;
        ?>">
                                <span></span>
                                <h3><?php echo $svc_title; ?></h3>
                                <p><?php echo $svc_subtitle; ?></p>
                            </div>
                        </a>
                    </div>
                    <?php
    }
}
?>
        </div>
    </div>
</section>

<?php
// require REQUIRE_PATH. '/inc/cases.php';
require $_SESSION['REQUIRE_PATH'].'/inc/curiosities.php';
?>

<section id="segments" class="commonSection">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 text-left">
                <h6 class="sub_title ">A Industing atua nos seguintes </h6>
                <h2 class="sec_title with_bar">
                    <span>Segmentos</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="serviceSlider_ owl-carousel_ align-content-md-stretch row">
                    <?php
                    $Read->fullRead(
                        'SELECT seg_title, seg_name, seg_subtitle, seg_cover, seg_icon FROM '.DB_SEG
                        .' WHERE seg_status >= :st',
                        'st=1'
                    );
if (!$Read->getResult()) {
    echo Check::erro('Nenhum segmento cadastrado. Favor Volte mais tarde', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Seg) {
        \extract($Seg);
        $Seg['seg_icon'] = $Seg['seg_icon'] ? 'uploads/'.$Seg['seg_icon'] : 'admin/_img/no_image.jpg';

        echo "<div class='icon_box_03 m-2 col-md-4 col-sm-12 justify-content-between align-items-stretch'>";
        echo "<a href='".BASE.\sprintf(
            "/segmento/%s'><img alt='%s' src='",
            $seg_name,
            $seg_title
        )
            .BASE.\sprintf("/tim.php?src=%s&w=62&h=62'></a>", $Seg['seg_icon']);
        echo "<h3><a href='".BASE.\sprintf("/segmento/%s'>%s</a></h3>", $seg_name, $seg_title);
        echo \sprintf('<p>%s</p>', $seg_subtitle);
        echo '</div>';
    }
}
?>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="whyChooseUs">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-5 col-lg-6 noPadding">
                <div class="video_02 withBGImg div_gray">
                    <a class="videoPlay1 videoPlayer" href="https://www.youtube.com/watch?v=wGkQTUe2aiA"
                       title="Conheça os Processos da Industing"><i class="fas fa-play"></i></a>

                </div>
            </div>
            <div class="col-xl-7 col-lg-6 noPaddingRight">
                <div class="whyChooseUsContent">
                    <h6 class="sub_title">Porque escolher-nos</h6>
                    <h2 class="sec_title dark_sec_title">
                        <span>Ideal para prototipagem e desenvolvimento ágil</span>
                    </h2>
                    <p>Desenvolvedores utilizam dados fictícios para testar aplicações em ambiente controlado. Esta ferramenta gera informações consistentes que simulam registros reais sem expor dados sensíveis. Ideal para prototipagem e desenvolvimento ágil de software.
                    </p>
                    <a id="cotacao_btn_index" href="#" target="_blank"
                       class="ind_btn"><span
                                class="fa
                    fa-arrow-alt-right"> Obter uma cotação</span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__.'/inc/depositions.php';

$Read->exeRead(DB_PARTNERS, 'ORDER BY partner_name ASC');
if ($Read->getResult()) {
    ?>
    <section class="commonSection clientSecion div_gray">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 text-center">
                    <h6 class="sub_title light_sub_title">Nossos Parceiros</h6>
                    <h2 class="sec_title with_bar light_sec_title">
                        <span>Quem confia?</span>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="clientSlider owl-carousel">
                        <?php
                        foreach ($Read->getResult() as $Partners) {
                            \extract($Partners);
                            ?>
                            <div class="csItem">
                                <a href="<?php echo $partner_page; ?>" target="_blank" title="<?php echo $partner_name; ?>">
                                    <img src="tim.php?src=uploads/<?php echo $partner_image; ?>&w=200&h=auto"
                                         alt="<?php echo $partner_name; ?> - Logotipo"/>
                                </a>
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

<section class="commonSection newsSection">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 text-center">
                <h6 class="sub_title">Quer ficar por dentro das Novidades do setor</h6>
                <h2 class="sec_title with_bar">
                    <span>Já conhece nosso Blog?</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-7 col-md-7">
                <?php
                $Read->fullRead(
                    'SELECT p.*, a.user_name FROM '.DB_POSTS.' p INNER JOIN '.DB_USERS.' a ON p.post_author = a.user_id WHERE p.post_status = :st AND p.post_date < NOW() ORDER BY p.post_id DESC LIMIT :limit',
                    'st=1&limit=1'
                );

if (!$Read->getResult()) {
    echo Check::erro('Ainda não temos artigos cadastrados. Volte mais tarde!', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Post) {
        \extract($Post);
        $iso = DateHelper::iso($post_date); // "2025-09-14"
        $human = DateHelper::human($post_date); // "14 de setembro de 2025"
        ?>
                        <div class="blogItem">
                            <div class="bi_thumb">
                                <img src="<?php echo BASE.\sprintf('/tim.php?src=uploads/%s&w=1200&h=628', $post_cover); ?>"
                                     alt="<?php echo $post_title; ?>" title="<?php echo $post_title; ?>"/>
                            </div>
                            <div class="bi_details">
                                <div class="bi_meta">
                                    <span>
                                        <i class="fal fa-calendar-alt"></i>
                                            <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
                                               title="<?php echo $post_title; ?>">
                                                <time datetime="<?php echo DateHelper::e($iso); ?>">
                                                    <?php echo DateHelper::e($human); ?>
                                                </time>
                                            </a>
                                    </span>
                                    <span>
                                        <i class="fal fa-user"></i>Por
                                        <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
                                           title="<?php echo $post_title; ?>"><?php echo $user_name; ?>
                                        </a>
                                    </span>
                                    <span>
                                        <i class="fal fa-eye"></i>
                                        <a href="<?php echo BASE.('/artigo/'.$post_name); ?>" title="<?php echo $post_title; ?>">
                                            <?php echo $post_views; ?>
                                        </a>
                                    </span>
                                </div>
                                <h3>
                                    <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
                                       title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a>
                                </h3>
                                <p><?php echo Check::Chars($post_subtitle, 70); ?></p>
                                <a href="<?php echo BASE.('/artigo/'.$post_name); ?>" title="<?php echo $post_title; ?>"
                                   class="read_more">Continue Lendo</a>
                            </div>
                        </div>
                        <?php
    }
}
?>
            </div>
            <div class="col-xl-5 col-md-5">
                <?php
$Read->fullRead(
    'SELECT post_name, post_title, post_date, post_views FROM '.DB_POSTS.' WHERE post_status = :st AND post_date < NOW() AND post_id < (SELECT MAX(post_id) FROM '.DB_POSTS.') ORDER BY post_id DESC LIMIT :limit',
    'st=1&limit=4'
);

if ($Read->getResult()) {
    foreach ($Read->getResult() as $Post) {
        \extract($Post);
        $iso = DateHelper::iso($post_date); // "2025-09-14"
        $human = DateHelper::human($post_date); // "14 de setembro de 2025"

        ?>
                        <div class="blogItem2">
                            <div class="bi_meta">
                                <span>
                                    <i class="fal fa-calendar-alt"></i>
                                    <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
                                       title="<?php echo $post_title; ?>">
                                        <time datetime="<?php echo DateHelper::e($iso); ?>">
                                            <?php echo DateHelper::e($human); ?>
                                        </time>
                                    </a>
                                </span>
                                <span><i class="fal fa-eye"></i><a href="#"><?php echo $post_views; ?></a></span>
                            </div>
                            <h3>
                                <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
                                   title="<?php echo $post_title; ?>"><?php echo $post_title; ?></a>
                            </h3>
                        </div>
                        <?php
    }
}
?>
            </div>
        </div>
    </div>
</section>
