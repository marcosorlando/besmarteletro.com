<?php

use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;

$Read ??= new Read();
$URL[1] ??= '';
$Read->fullRead(
    'SELECT p.*, cat.* FROM ' . DB_PDT_TRAVI . ' p INNER JOIN ' . DB_PDT_CATS_TRAVI . ' cat ON p.pdt_subcategory = cat.cat_id WHERE p.pdt_name = :nm',
    'nm=' . $URL[1]
);
if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';

    return;
}
extract($Read->getResult()[0]);

$CommentKey = $pdt_id;
$CommentType = 'product';

$pdtViewUpdate = [
    'pdt_views' => $pdt_views + 1,
    'pdt_lastview' => date('Y-m-d H:i:s'),
];
$Update = new Update();
$Update->exeUpdate(DB_PDT_TRAVI, $pdtViewUpdate, 'WHERE pdt_id = :id', 'id=' . $pdt_id);

$CommentModerate = (COMMENT_MODERATE !== 0 ? ' AND (status = 1 OR status = 3)' : '');
$Read->fullRead('SELECT id FROM ' . DB_COMMENTS . (' WHERE pdt_id = :pid' . $CommentModerate), 'pid=' . $pdt_id);
$Aval = $Read->getRowCount();

$Read->fullRead(
    'SELECT SUM(rank) as total, count(id) as reviews FROM ' . DB_COMMENTS . (' WHERE pdt_id = :pid' . $CommentModerate),
    'pid=' . $pdt_id
);
$TotalAval = $Read->getResult()[0]['total'];
$Reviews = $Read->getResult()[0]['reviews'];
$TotalRank = $Aval * 5;
$getRank = ($TotalAval ? (($TotalAval / $TotalRank) * 50) / 10 : 0);
$Rank = str_repeat("<li class='fa fa-star'></li>", intval($getRank)) . str_repeat(
        "<li class='fa fa-star-o'></li>",
        5 - intval($getRank)
    );

if ($pdt_hotlink) {
    header('Location: ' . $pdt_hotlink);

    exit;
}

?>
<section class="page_banner"
         style="background: #1d378a url('<?php
         echo BASE; ?>/uploads/<?php
         echo $pdt_scene; ?>') no-repeat center center / cover">
	<div class="container">
		<div class="col-xl-12 text-center">
			<h1 class="text-white"><?php
                echo $pdt_title; ?></h1>
			<div class="breadcrumbs">
				<a href="<?php
                echo BASE; ?>">Travi</a><i>|</i>
				<a href="<?php
                echo BASE; ?>/produtos/<?php
                echo $cat_name; ?>" title="<?php
                echo $cat_title; ?>"><?php
                    echo $cat_title; ?></a>

			</div>
		</div>
	</div>
</section>

<section class="commonSection shopDetails">
	<div class="container">
		<div class="row mb80">
			<div class="col-sm-12 col-md-7">
				<div id="productSlide" class="carousel slide productSlide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active">
							<div class="ps_img">
								<img src="<?= BASE; ?>/tim.php?src=uploads/<?= $pdt_cover; ?>&w=652&h=652"
								     alt="<?= $pdt_title; ?>"/>
							</div>
						</div>
                        <?php
                        $Read->exeRead(DB_PDT_GALLERY_TRAVI, 'WHERE product_id = :pi ORDER BY id ASC', 'pi=' . $pdt_id);
                        if ($Read->getResult()) {
                            foreach ($Read->getResult() as $Images) {
                                ?>
								<div class="carousel-item">
									<div class="ps_img">
										<img src="<?= BASE; ?>/tim.php?src=uploads/<?= $Images['image']; ?>&w=652&h=652"
										     alt="<?= $pdt_title; ?>"/>
									</div>
								</div>                                                      <?php
                            }
                        }
                        ?>
					</div>
					<ol class="carousel-indicators clearfix">
						<li data-target="#productSlide" data-slide-to="0" class="active">
							<img src="<?php
                            echo BASE; ?>/tim.php?src=uploads/<?php
                            echo $pdt_cover; ?>&w=92&h=92"
							     alt="<?php
                                 echo $pdt_title; ?>"/>
						</li>
                        <?php
                        if ($Read->getResult()) {
                            $count = 1;
                            foreach ($Read->getResult() as $Images) {
                                ?>
								<li data-target="#productSlide" data-slide-to="<?php
                                echo $count; ?>">
									<img src="<?php
                                    echo BASE; ?>/tim.php?src=uploads/<?php
                                    echo $Images['image']; ?>&w=92&h=92"
									     alt="<?php
                                         echo $pdt_title; ?>"/>
								</li>
                                <?php
                                ++$count;
                            }
                        }
                        ?>
					</ol>
				</div>
			</div>
			<div class="col-sm-12 col-md-5">
				<div class="product_decp">
					<h1 class="proTitle text-blue"><?php
                        echo $pdt_title; ?></h1>
					<div class="htmlchars">
                        <?php
                        echo Check::words($pdt_content, 155); ?>
						<a href="#content" class="wc_goto" title="Continue lendo...">Continue Lendo!</a>
					</div>
					<div class="pd_details_meta">
						<div class="row">
							<div class="col-sm-12">
								<div class="metaTitle text-blue"><i class="fa fa-tag"></i> CATEGORIA
									<a class="text_meta" href="<?php
                                    echo BASE; ?>/produtos/<?php
                                    echo $cat_name; ?>"
									   title="Clique para ver mais produtos dessa Categoria!">
										<h3><?php
                                            echo $cat_title; ?></h3></a>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<h3 class="metaTitle text-blue"><i class='fa fa-tags'></i> TAGS:</h3>
								<div class="text_meta"> <?php
                                    $tags = explode(',', (string)$pdt_tags);

                                    foreach ($tags as $tag) {
                                        echo sprintf(
                                                "<a class='tag' title='%s' href='",
                                                $tag
                                            ) . BASE . '/pesquisa-produtos/' . urlencode(
                                                trim(mb_strtolower($tag))
                                            ) . sprintf("'><h4>%s</h4></a>", $tag);
                                    }
                                    ?></div>
							</div>
						</div>
					</div>
					<div class="pd_details_meta pdb0">
                        <?php
                        $WC_TITLE_LINK = $pdt_title;
                        $WC_SHARE_HASH = '#hashtags';
                        $WC_SHARE_LINK = BASE . ('/produto/' . $pdt_name);

                        require __DIR__ . '/../../_cdn/widgets/share/share.wc.php';
                        ?>
					</div>
				</div>
			</div>
		</div>

		<div class="row" id="content">
			<div class="col-lg-12">
				<ul class="nav nav-tabs productTabs" id="productTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="descriptions-tab" data-toggle="tab" href="#descriptions"
						   role="tab" aria-controls="descriptions" aria-selected="true">DESCRIÇÃO</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="additionalinformation-tab" data-toggle="tab"
						   href="#additionalinformation" role="tab" aria-controls="additionalinformation"
						   aria-selected="false">INFORMAÇÕES ADICIONAIS</a>
					</li>
                    <?php
                    if (APP_COMMENTS && COMMENT_ON_PRODUCTS) { ?>
						<li class="nav-item">
							<a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
							   aria-controls="reviews" aria-selected="false">COMENTÁRIOS (<?php
                                echo $Reviews; ?>)</a>
						</li>
                        <?php
                    } ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-xl-12 col-lg-12">
				<div class="tab-content productTabContent" id="productTabContent">
					<div class="tab-pane fade show active" id="descriptions" role="tabpanel"
					     aria-labelledby="descriptions-tab">
						<div class="descriptionContent htmlchars">
                            <?php
                            echo $pdt_content; ?>
						</div>
					</div>
					<div class="tab-pane fade" id="additionalinformation" role="tabpanel"
					     aria-labelledby="additionalinformation-tab">
						<div class="descriptionContent htmlchars">
                            <?php
                            echo $pdt_infos; ?>
						</div>
					</div>

                    <?php
                    if (APP_COMMENTS && COMMENT_ON_PRODUCTS) { ?>
						<div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
							<div class="comment_area">
                                <?php
                                require __DIR__ . '/_cdn/widgets/comments/comments.php'; ?>
							</div>
						</div>
                        <?php
                    } ?>

				</div>
			</div>
		</div>

        <?php
        $Read->fullRead(
            'SELECT pdt_name, pdt_title, pdt_subtitle, pdt_cover FROM ' . DB_PDT_TRAVI . ' WHERE pdt_subcategory = :scat AND pdt_id != :pdt ORDER BY RAND() LIMIT 12',
            sprintf('scat=%s&pdt=%s', $cat_id, $pdt_id)
        );
        if ($Read->getResult()) {
            $products = $Read->getResult();
            ?>
			<div class="row">
				<div class="col-xl-12">
					<div class="relatedProductArea">
						<div class="row mb40">
							<div class="col-xl-8 col-md-8">
								<h2 class="sec_title">Produtos Relacionados</h2>
							</div>
							<div class="col-xl-4 col-md-4 text-right">
								<div class="relatedNavs"></div>
							</div>
						</div>
						<div class="row">
							<div class="col-xl-12">
								<div class="relatedProductSlider owl-carousel">
                                    <?php
                                    foreach ($products as $pdt) {
                                        ?>
										<div class="single_product offerProduct">
											<a href="<?php
                                            echo BASE . ('/produto/' . $pdt['pdt_name']); ?>">
												<div class="productImg">
													<img src="<?php
                                                    echo BASE; ?>/tim.php?src=uploads/<?php
                                                    echo $pdt['pdt_cover'];
                                                    ?>&w=300&h=300"
													     alt="<?php
                                                         echo $pdt['pdt_title']; ?>"
													     title="<?php
                                                         echo $pdt['pdt_title']; ?>">
												</div>
												<div class="product_dec">
													<div class="product_decIn">
														<h2 class="productTitle"><?php
                                                            echo $pdt['pdt_title']; ?></h2>
														<div class="product_price">
                                                            <span><?php
                                                                echo $pdt['pdt_subtitle']; ?></span>
														</div>
													</div>
												</div>
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
			</div>
            <?php
        }
        ?>
	</div>
</section>
