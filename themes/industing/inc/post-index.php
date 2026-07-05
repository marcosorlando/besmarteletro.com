<?php

use App\Helpers\Check;

?>
<div class="singleBlog">
    <div class="sbThumb">
        <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"
           title="<?php echo $post_title.' - Clique para continuar a leitura...'; ?>">
            <img src="<?php echo BASE.\sprintf(
                '/tim.php?src=uploads/%s&w=',
                $post_cover
            ).IMAGE_W / 2 .'&h='.IMAGE_H / 2; ?>"
                 alt="<?php echo $post_title; ?>" title="<?php echo $post_title; ?>"/>
        </a>
    </div>
    <div class="sbDetails">
        <h4 class="sb_cats">
            <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"><?php echo $post_title; ?></a>
        </h4>
        <p>
            <?php echo Check::chars($post_subtitle, 70); ?>
            <a href="<?php echo BASE.('/artigo/'.$post_name); ?>"><i class='fa fa-eye'></i> Continue lendo...</a>
        </p>
    </div>
    <div class="sb_footer">
        <span><i class="fal fa-folder-open"></i><?php echo $category_title; ?></span>
        <span><i class="fal fa-user"></i><?php echo $AuthorName; ?></span>
        <span><i class="fal fa-eye"></i><?php echo $post_views; ?></span>
    </div>
</div>
