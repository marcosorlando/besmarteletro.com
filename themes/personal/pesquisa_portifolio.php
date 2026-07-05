<?php
$Search = urldecode($URL[1]);
$SearchPage = urlencode($Search);

if (empty($_SESSION['search']) || !in_array($Search, $_SESSION['search'])) :
    $Read->fullRead("SELECT search_id, search_count FROM " . DB_SEARCH . " WHERE search_key = :key", "key={$Search}");
    if ($Read->getResult()) :
        $Update = new Update;
        $DataSearch = ['search_count' => $Read->getResult()[0]['search_count'] + 1];
        $Update->ExeUpdate(DB_SEARCH, $DataSearch, "WHERE search_id = :id", "id={$Read->getResult()[0]['search_id']}");
    else :
        $Create = new Create;
        $DataSearch = ['search_key' => $Search, 'search_count' => 1, 'search_date' => date('Y-m-d H:i:s'), 'search_commit' => date('Y-m-d H:i:s')];
        $Create->exeCreate(DB_SEARCH, $DataSearch);
    endif;
    $_SESSION['search'][] = $Search;
endif;
?>



<!--========================== -->
<!-- CTA ASSINAR BLOG -->
<!--========================== -->
<div class="clear"></div>
<div class="buy-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-1 col-sm-9 wow fadeInLeft">
                <div class="section-text">
                    <div class=" vcenter like">
                        <span class="icon icon-Like"></span>
                    </div>
                    <div class="buy-text vcenter">
                        <div class="top-text">
                            <span>Assine nosso Blog sobre <em>Marketing Digital</em></span>
                        </div>
                        <div class="bottom-text">Clique no botão ao lado para realizar sua assinatura agora...</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4  wow fadeInRight">
                <a href="<?= BASE; ?>/conta/cadastro#acc" title="Clique!" tabindex="2" class="btn btn-info">ASSINAR</a>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<!-- ========================== -->
<!-- BLOG - CONTENT -->
<!-- ========================== -->
<section class="blog-content-section">
    <div class="top_conversion breadcrumbs">
        <div class="content">
            <p><a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a> / Pesquisa por <b><?= $Search; ?></b></p>
            <div class="clear"></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 left-column">

                <?php
                $Page = (!empty($URL[2]) ? $URL[2] : 1);
                $Pager = new Pager(BASE . "/pesquisa_portifolio/{$SearchPage}/", "<<", ">>", 5);
                $Pager->exePager($Page, 10);

                $Read->fullRead("SELECT ws_portifolio.porti_title, ws_portifolio.porti_tags, ws_portifolio.porti_name, ws_portifolio.porti_subtitle, ws_portifolio.porti_cover, ws_portifolio.porti_video, ws_portifolio.porti_date, ws_portifolio.porti_time, ws_portifolio.porti_views,  ws_categories_portifolio.category_title FROM " . DB_PORTIFOLIO . " INNER JOIN " . DB_CATEGORIES_PORTIFOLIO . " ON (`ws_portifolio`.`porti_category` = `ws_categories_portifolio`.`category_id`) WHERE porti_status = 1 AND porti_date <= NOW() AND (porti_title LIKE '%' :s '%' OR porti_subtitle LIKE '%' :s '%' OR porti_tags LIKE '%' :s '%') ORDER BY porti_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&s={$Search}");

                if (!$Read->getResult()) :
                    $Pager->ReturnPage();
                    echo Erro("Ainda Não existe projetos cadastrados. Favor volte mais tarde :)", E_USER_NOTICE);
                else :
                    foreach ($Read->getResult() as $Post) :
                        extract($Post);
                        require REQUIRE_PATH . '/inc/projetoindex.php';
                    endforeach;
                endif;
                ?>
                <!--PAGINATOR-->
                <div class="row wrap-pagination wow fadeInUp">
                    <div class="col-md-12">
                        <?php
                        $Pager->exePaginator(DB_PORTIFOLIO, "WHERE porti_status = 1 AND porti_date <= NOW() AND (porti_title LIKE '%' :s '%' OR porti_subtitle LIKE '%' :s '%' OR porti_tags LIKE '%' :s '%')", "s={$Search}");
                        echo $Pager->getPaginator();
                        ?>
                    </div>
                </div><!--END PAGINATOR-->
            </div>
            <?php require REQUIRE_PATH . '/inc/sidebar_portifolio.php'; ?>
        </div>
    </div>
</section>
<!-- ========================== -->
