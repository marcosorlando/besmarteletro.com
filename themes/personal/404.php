<?php
if (!$Read):
  $Read = new Read;
endif;
?>

<section class="top-header countact-us-header with-bottom-effect transparent-effect dark dark-strong">
  <div class="bottom-effect"></div>
  <div class="header-container">	
    <div class="header-title">
      <div class="header-icon"><span class="icon icon-Dislike"></span></div>
      <div class="title">Desculpe,</div>
      <h3 style="color:#fff;">mas não encontramos o que você procura!</h3>
    </div>
  </div><!--container-->
</section> 


<section class="not_found blog-content-section">
  <header class="site_header">
    <h2>Conteúdo não encontrado. Você pode fazer uma pesquisa, ou navegar na lista de nossos conteúdos mais acesssados!</b></h2>
  </header>

  <form class="sidebar_search" name="search" action="" method="post" enctype="multipart/form-data">
    <input type="text" name="s" placeholder="Pesquisar Artigos:" required/>

    <button class="icon icon-Search"></button>
  </form>

  <div class="container">
    <div class="row">
      <div class="left-column box">	

        <?php
        $Read->fullRead("SELECT ws_posts.post_title, ws_posts.post_name, ws_posts.post_subtitle, ws_posts.post_cover, ws_posts.post_video, ws_posts.post_date, ws_posts.post_views, ws_users.user_name, ws_users.user_lastname, ws_users.user_thumb, ws_categories.category_title FROM " . DB_POSTS . " INNER JOIN " . DB_USERS . " ON (`ws_posts`.`post_author` = `ws_users`.`user_id`)" . " INNER JOIN " . DB_CATEGORIES . " ON (`ws_posts`.`post_category` = `ws_categories`.`category_id`) WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_views DESC LIMIT 4");

        if ($Read->getResult()):
          foreach ($Read->getResult() as $Post):
            extract($Post);
            ?>
            <!-- Blog post-->
            <article class="wrap-blog-post wow fadeInUp box box4" style="padding: 5px; border: 1px solid #ccc">
              <div class="wrap-image">
                <a class="post_list_thumb" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">
                  <img class="img-responsive" src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>" alt="<?= $post_title; ?>" title="<?= $post_title; ?>"/>
                </a>
              </div>

              <div class="wrap-post-description">
                <a class="post-avatar" href="">
                  <img class="" style="margin: -30px 130px 0 0 !important;" alt="<?= $user_name . " " . $user_lastname; ?>" title="<?= $user_name . " " . $user_lastname; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $user_thumb; ?>" />
                </a>
                <div class="meta" style="margin-top: -25px !important;">
                  <div class="meta-item"><span class="icon icon-User"></span><b><?= $user_name ?></b> <?= $user_lastname; ?></div>
                  <div class="meta-item"><span class="icon icon-Tag"></span><?= $category_title; ?></div>
                </div>
              </div>
              <div class="post-body" style="margin-top: -20px !important">
                <h2><a href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>"><?= $post_title; ?></a></h2>	
              </div>
            </article><!--blog-post-->
            <?php
          endforeach;
        endif;
        ?>
      </div>
    </div>
  </div>
</section>
<div class="clear"></div>
