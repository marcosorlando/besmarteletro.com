<?php
if (!$Read):
  $Read = new Read;
endif;

$Read->exeRead(DB_CATEGORIES, "WHERE category_name = :nm", "nm={$URL[1]}");
if (!$Read->getResult()):
  require REQUIRE_PATH . '/404.php';
  return;
else:
  extract($Read->getResult()[0]);
endif;
?>
<!--========================== -->
<!-- CTA ASSINAR BLOG -->
<!--========================== -->
<?php require REQUIRE_PATH . "/inc/blog_cta.php"; ?>
<!-- ========================== -->
<!-- BLOG - CONTENT -->
<!-- ========================== -->
<section class="blog-content-section">
  <div class=" breadcrumbs">
    <div class="content">
      <p><a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a> <i class='bx bxs-chevrons-right text-first-color'></i><?= $category_title; ?></p>
      <div class="clear"></div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="blog-main">

        <?php
        $Page = (!empty($URL[2]) ? $URL[2] : 1);
        $Pager = new Pager(BASE . "/artigos/{$category_name}/", "<", ">", 5);
        $Pager->exePager($Page, 10);
        
        $Read->fullRead("SELECT ws_posts.post_title, ws_posts.post_name, ws_posts.post_tags,  ws_posts.post_subtitle, ws_posts.post_cover, ws_posts.post_video, ws_posts.post_date, ws_posts.post_views, ws_posts.post_time, ws_users.user_name, ws_users.user_lastname, ws_users.user_thumb, ws_categories.category_title FROM " . DB_POSTS . " INNER JOIN " . DB_USERS . " ON (`ws_posts`.`post_author` = `ws_users`.`user_id`)" . " INNER JOIN " . DB_CATEGORIES . " ON (`ws_posts`.`post_category` = `ws_categories`.`category_id`) WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent)) ORDER BY post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ct={$category_id}");

        if (!$Read->getResult()):
          $Pager->ReturnPage();
          echo Erro("Ainda Não existe posts cadastrados. Favor volte mais tarde :)", E_USER_NOTICE);
        else:
          foreach ($Read->getResult() as $Post):
            extract($Post);
            require REQUIRE_PATH . '/inc/post.php';
          endforeach;
        endif;
        ?>
        <!--PAGINATOR-->
        <div class="row wrap-pagination wow fadeInUp" >
          <div class="col-md-12">
            <?php
            $Pager->exePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent))", "ct={$category_id}");
            echo $Pager->getPaginator();
            ?>
          </div>
        </div><!--END PAGINATOR-->
      </div>
      <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
    </div>
  </div>
</section>
<!-- ========================== -->
