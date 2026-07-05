<!-- Blog post-->
<article class="post-item">
  <?php
  if ($post_video):
    echo "<div class='embed-container'>";
    echo "<iframe id='mediaview' class='embed-responsive-item' width='100%' height='360' src='https://www.youtube.com/embed/{$post_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>";
    echo "</div>";
  else:
    echo "<div class='wrap-image'>"
    . "<a class='post_list_thumb' href='" . BASE . "/artigo/{$post_name}' title='{$post_title}'>"
    . "<img class='img-responsive' src='" . BASE . "/tim.php?src=uploads/{$post_cover}&w=" . IMAGE_W . "&h=" . IMAGE_H . "' alt='{$post_title}' title='{$post_title}'/>"
    . "</a>"
    . "</div>";

  endif;
  ?>

  <div class="wrap-post-description">
    <a class="post-avatar" href="#fakelink">
      <img class="" alt="<?= $user_name . " " . $user_lastname; ?>" title="<?= $user_name . " " . $user_lastname; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $user_thumb; ?>" />
    </a>
    <div class="meta">
      <div class="meta-item"><span class="icon icon-User"></span><b><?= $user_name ?></b> <?= $user_lastname; ?></div>
      <div class="meta-item"><span class="icon icon-Tag"></span><?= $category_title; ?></div>
      <div class="meta-item"><span class="icon icon-Agenda"></span><?= date('d/m/Y H:m', strtotime($post_date)); ?>h</div>
      <div class="meta-item"><span class="icon icon-Eye"></span><?= $post_views; ?> views</div>
      <div class="meta-item"><span class="icon icon-Watch"></span><?= $post_time; ?> minutos</div>
    </div>
  </div>
</article><!--end blog-post-->
