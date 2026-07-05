<?php use App\Helpers\Check; ?>
<!-- start post item -->
<article class="box box<?= $BOX ?> wow fadeIn" data-wow-delay="0.2s">
  <div class="blog_post">
    <div class="blog-post-images">
      <a class="post_list_thumb" href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>">
        <img src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" alt="<?= $post_title; ?>" title="<?= $post_title; ?>"/>
      </a>
    </div>
    <div class="post_details">
      <span class="post-author">
	      <i class='fa fa-calendar-check-o'></i> <?= date('d-m-Y', strtotime($post_date)); ?><i class='icon-pencil'></i> por <?= $AuthorName; ?>
      </span>

        <h3 class="post_title"><a href="<?= BASE; ?>/artigo/<?= $post_name; ?>" ><?= $post_title; ?></a></h3>

      <p><?= Check::Chars($post_content, 75); ?>
          <a href="<?= BASE; ?>/artigo/<?= $post_name; ?>" title="<?= $post_title; ?>"> Continue Lendo <i class="fa fa-rocket"></i></a>
      </p>
    </div>
    <div class="separator-line-horrizontal-full bg-medium-light-gray margin-20px-tb sm-margin-15px-tb"></div>
  </div>
</article>

<!-- end post item -->
