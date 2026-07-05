<?php

use App\Helpers\Check;
use App\Helpers\DateHelper;

?>
<div class="col-xl-6 col-md-6 col-lg-6 blog_mash">
    <div class="singleBlog">
        <div class="sbThumb">
            <a href="<?php echo BASE.('/artigo/'.$post_name); ?>" title="<?php echo $post_title; ?>">
                <img src="<?php echo BASE.\sprintf('/tim.php?src=uploads/%s&w=360&h=188', $post_cover); ?>"
                     alt="<?php echo $post_title; ?>" title="<?php echo $post_title; ?>"/>
            </a>
        </div>
        <div class="sbDetails">
            <h4 class="sb_cats">
                <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"><?php echo $post_title; ?></a>
            </h4>
            <p>
                <?php echo Check::chars($post_subtitle, 70); ?>
                <a href="<?php echo BASE.('/artigo/'.$post_name); ?>" title="<?php echo $post_title; ?>">Continue Lendo <i
                            class="fa fa-angle-right"></i></a>
            </p>
        </div>
        <div class="sb_footer">
            <span><i class="fal fa-clock"></i>
                <time datetime="<?php echo DateHelper::iso($Post['post_date'] ?? null); ?>" pubdate="pubdate">
    <?php echo DateHelper::human($Post['post_date'] ?? null); ?>
</time>
            </span>
            <span><i class="fal fa-user"></i><?php echo $AuthorName; ?></span>
        </div>
    </div>
</div>
