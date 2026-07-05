<?php
if (!$Read):
  $Read = new Read;
endif;

$Read->exeRead(DB_CATEGORIES_PORTIFOLIO, "WHERE category_name = :nm", "nm={$URL[1]}");
if (!$Read->getResult()):
  $a = "EITA";
  var_dump($a);
  require REQUIRE_PATH . '/404.php';
  return;
else:
  extract($Read->getResult()[0]);
endif;
?>
<!--========================== -->
<!-- CTA ASSINAR BLOG -->
<!--========================== -->
<?php // require REQUIRE_PATH . "/inc/blog_cta.php"; ?>
<!-- ========================== -->
<!-- BLOG - CONTENT -->
<!-- ========================== -->
<section class="blog-content-section">
  <div class=" breadcrumbs">
    <div class="content">
      <p><a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a> <i class="icon icon-Arrow"> </i> <?= $category_title; ?></p>
      <div class="clear"></div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 col-md-8 left-column">		

        <?php
        $Page = (!empty($URL[2]) ? $URL[2] : 1);
        $Pager = new Pager(BASE . "/projetos/{$category_name}/", "<", ">", 5);
        $Pager->exePager($Page, 10);
        
        $Read->fullRead("SELECT ws_portifolio.porti_title, ws_portifolio.porti_name, ws_portifolio.porti_tags,  ws_portifolio.porti_subtitle, ws_portifolio.porti_cover, ws_portifolio.porti_video, ws_portifolio.porti_date, ws_portifolio.porti_views, ws_portifolio.porti_time,  ws_categories_portifolio.category_title FROM " . DB_PORTIFOLIO . " INNER JOIN " . DB_CATEGORIES_PORTIFOLIO . " ON (`ws_portifolio`.`porti_category` = `ws_categories_portifolio`.`category_id`) WHERE porti_status = 1 AND porti_date <= NOW() AND (porti_category = :ct OR FIND_IN_SET(:ct, porti_category_parent)) ORDER BY porti_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ct={$category_id}");

        if (!$Read->getResult()):
          $Pager->ReturnPage();
          echo Erro("Ainda Não existe posts cadastrados. Favor volte mais tarde :)", E_USER_NOTICE);
        else:
          foreach ($Read->getResult() as $Post):
            extract($Post);
            require REQUIRE_PATH . '/inc/projetoindex.php';
          endforeach;
        endif;       
        ?>
        <!--PAGINATOR-->			
        <div class="row wrap-pagination wow fadeInUp" >
          <div class="col-md-12">
            <?php
            $Pager->exePaginator(DB_PORTIFOLIO, "WHERE porti_status = 1 AND porti_date <= NOW() AND (porti_category = :ct OR FIND_IN_SET(:ct, porti_category_parent))", "ct={$category_id}");
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
