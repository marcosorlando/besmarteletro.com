<?php

if (!$Read) :
  $Read = new Read;
endif;
$Read->fullRead(
  "SELECT ws_portifolio.*, cat.category_title , cat.category_name FROM " . DB_PORTIFOLIO . " INNER JOIN " . DB_CATEGORIES_PORTIFOLIO . " cat ON  (`ws_portifolio`.`porti_category` = `cat`.`category_id`)  WHERE porti_status = 1 AND porti_name = :nm",
  "nm={$URL[1]}"
);

if (!$Read->getResult()) :
  require REQUIRE_PATH . '/404.php';

  return;
else :
  extract($Read->getResult()[0]);
  //var_dump($Read->getResult()[0]);
  $Update = new Update;
  $UpdateView = ['porti_views' => $porti_views + 1, 'porti_lastview' => date('Y-m-d H:i:s')];
  $Update->ExeUpdate(DB_PORTIFOLIO, $UpdateView, "WHERE porti_id = :id", "id={$porti_id}");
endif;
?>

<!-- ========================== -->
<!-- projeto - CONTENT -->
<!-- ========================== -->
<section class="blog-content-section">
  <div class="breadcrumbs">
    <div class="content">
      <p>
        <a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a>
        <i class='bx bxs-chevrons-right'> </i>
        <a href="<?= BASE; ?>/projetos/<?= $category_name; ?>" title="Ver mais: <?= $category_title; ?> em <?= SITE_NAME; ?>!"><?= $category_title; ?></a>
        / <?= $porti_title; ?>
      </p>
      <div class="clear"></div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="blog-main">
        <!--Blog post-->
        <div class="wrap-blog-post">
          <header>
            <h1 class="title"><?= $porti_title; ?></h1>
            <h2 class="tagline"><?= $porti_subtitle; ?></h2>
          </header>
          <?php
          if (!empty($porti_video)) :
            echo "<div class='embed-container'>";
            echo "<iframe id='mediaview' class='embed-responsive-item' width='100%' height='360' src='https://www.youtube.com/embed/{$porti_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>";
            echo "</div>";
          else :
            echo "<div class='wrap-image'>"
              . "<a class='porti_list_thumb' href='" . BASE . "/projeto/{$porti_name}' title='{$porti_title}'>"
              . "<img class='img-responsive' src='" . BASE . "/tim.php?src=uploads/{$porti_cover}&w=" . IMAGE_W . "&h=" . IMAGE_H . "' alt='{$porti_title}' title='{$porti_title}'/>"
              . "</a>"
              . "</div>";

          endif;
          ?>

          <div class="portifolio-category">
              <i class='bx bx-tag-alt'></i>
              <h3><?= $category_title; ?></h3>
          </div>
        
          <div class="htmlchars post-body">
            <?= $porti_content; ?>
          </div>
         

        </div>
        <div class="clear"></div>
      </div>
      <?php
      require REQUIRE_PATH . '/inc/sidebar_portifolio.php'; ?>
    </div>
  </div>
</section>

<?php
$Read->exeRead(
  DB_PORTIFOLIO,
  "WHERE porti_status = 1 AND porti_date <= NOW() AND porti_category_parent = :ct AND porti_id != :id ORDER BY porti_date DESC LIMIT 4",
  "ct={$porti_category_parent}&id={$porti_id}"
);
if ($Read->getResult()) :
  echo '<section class="single_porti_more">';
  echo "<div class='content'>";
  echo '<header class="site_header">';
  echo '<h2>Veja Também!</h2>';
  echo '<p>Os projetos relacionados podem te interessar:</p>';
  echo '</header>';

  foreach ($Read->getResult() as $More) :
    extract($More);
?>
    <!-- Blog post-->
    <article class="wrap-blog-post wow fadeInUp box box4" style="padding: 5px; border: 1px solid #ccc">
      <div class="wrap-image">
        <a class="porti_list_thumb" href="<?= BASE; ?>/projeto/<?= $porti_name; ?>" title="<?= $porti_title; ?>">
          <img class="img-responsive" src="<?= BASE; ?>/tim.php?src=uploads/<?= $porti_cover; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>" alt="<?= $porti_title; ?>" title="<?= $porti_title; ?>" />
        </a>
      </div>

      <div class="wrap-post-description">

        <div class="meta" style="margin-top: 5px !important; line-height: 25px;">
          <div class="meta-item"><span class="icon icon-User"></span><b><?= $user_name ?></b> <?= $user_lastname; ?></div>
          <div class="meta-item"><span class="icon icon-Tag"></span><?= $category_title; ?></div>
        </div>
      </div>
      <div class="post-body" style="margin-top: 10px !important">
        <h2><a href="<?= BASE; ?>/projeto/<?= $porti_name; ?>" title="<?= $porti_title; ?>"><?= $porti_title; ?></a></h2>
      </div>
    </article><!--blog-post-->
<?php
  endforeach;
  echo '<div class="clear"></div></div>';
  echo '</section>';
endif;
?>
