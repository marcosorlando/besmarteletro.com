<?php

use App\Conn\Read;
use App\Conn\Update;

$Read ??= new Read();

$Read->exeRead(DB_SEG, 'WHERE seg_name = :nm AND seg_status = :st', sprintf('nm=%s&st=1', $URL[1]));

if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';

    return;
}
extract($Read->getResult()[0]);
$Update = new Update();
$UpdateView = [
    'seg_views' => $seg_views + 1,
    'seg_lastview' => date('Y-m-d H:i:s'),
];
$Update->exeUpdate(DB_SEG, $UpdateView, 'WHERE seg_id = :id', 'id=' . $seg_id);

?>

<section class="product-banner">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-6">
				<h1><?php
                    echo $seg_title; ?></h1>
			</div>
			<div class="col-sm-12 col-md-6 breadcrumbs">
				<a href="<?php
                echo BASE; ?>">Home</a><i>|</i>
				<a href="<?php
                echo BASE . '/segmentos'; ?>" title="Segmentos da Indústria">Segmentos da Indústria</a>
			</div>
		</div>
	</div>
</section>

<section class="commonSection serviceDetailsSecions">
	<div class="container">
		<div class="row">
			<div class="col-lg-8 col-md-12">
				<div class="service_details_area">
					<h2 class="entry_title">
                        <?php
                        echo $seg_title; ?>
					</h2>
					<div class="sda_gall">
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<div class="sda_gl">
									<img src="<?php
                                    echo ($seg_cover ? BASE . sprintf(
                                                '/tim.php?src=uploads/%s&w=800&h=800',
                                                $seg_cover
                                            ) : BASE . '/tim.php?src=admin/_img/no_image.jpg') . '&w=800&h=800'; ?>"
									     alt="<?php
                                         echo $seg_title; ?>" title="<?php
                                    echo $seg_title; ?>"/>
								</div>
							</div>
						</div>
					</div>
					<div class="sda_content  htmlchars"><?php
                        echo $seg_description; ?></div>
				</div>
			</div>
			<div class="col-lg-4 col-md-6 sidebar_1">
				<aside class="widget">
					<h3 class="widget_title">Segmentos da Indústria</h3>
					<ul>
                        <?php
                        $Read->fullRead(
                            'SELECT seg_title, seg_name FROM ' . DB_SEG . ' WHERE seg_status >= :st',
                            'st=1'
                        );
                        if ($Read->getResult()) {
                            foreach ($Read->getResult() as $svc) {
                                extract($svc);
                                echo "<li><a href='" . BASE . sprintf(
                                        "/segmento/%s'>%s</a></li>",
                                        $seg_name,
                                        $seg_title
                                    );
                            }
                        }
                        ?>
					</ul>
				</aside>
                <?php
                $Read->fullRead(
                    'SELECT post_title, post_name, post_date, post_cover FROM ' . DB_POSTS . ' WHERE post_status = :ps ORDER BY post_date DESC LIMIT 3',
                    'ps=1'
                );
                if ($Read->getResult()) {
                    echo "<aside class='widget last-news'>";
                    echo "<h3 class='widget_title'>Últimas Notícias</h3>";

                    foreach ($Read->getResult() as $Post) {
                        extract($Post);

                        $Post['post_cover'] = $Post['post_cover'] ? 'uploads/' . $Post['post_cover'] : 'admin/_img/no_image.jpg';

                        echo "<div class='allLatestWorks'>";
                        echo "<div class='ltworks'>";
                        echo "<a href='" . BASE . sprintf(
                                "/artigo/%s'><img class='res' alt='%s' src='",
                                $post_name,
                                $post_title
                            )
                            . BASE
                            . sprintf("/tim.php?src=%s&w=128&h=62'></a>", $Post['post_cover']);
                        echo "<h4><a href='" . BASE . sprintf("/artigo/%s'>%s </a></h4>", $post_name, $post_title);
                        echo "<p><i class='fal fa-calendar-check'></i> " . date(
                                'd-m-Y',
                                strtotime((string)$post_date)
                            ) . '</p>';
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
									href="mailto:<?php
                                    echo SITE_ADDR_EMAIL; ?>"><?php
                                echo SITE_ADDR_EMAIL; ?></a></span>

						<p> Ou ligue agora!</p>
						<span><i class="fa fa-phone text-blue"></i> <a
									href="tel:<?php
                                    echo SITE_ADDR_PHONE_A; ?>"><?php
                                echo SITE_ADDR_PHONE_A; ?></a></span>

					</div>
				</aside>
			</div>
		</div>
	</div>
</section>
<?php
include_once __DIR__ . '/inc/cta.inc.php';
?>
