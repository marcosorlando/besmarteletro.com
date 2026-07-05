<!-- Blog post-->
<article class="wrap-blog-post wow fadeInUp">
    <div class="post-body">
        <h2><a href="<?= BASE; ?>/projeto/<?= $porti_name; ?>" title="<?= $porti_title; ?>"><?= $porti_title; ?></a></h2>
        <h4><?= Check::Chars($porti_subtitle, 120); ?></h4>
    </div>
    <?php
    if ($porti_video) :
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

    <div class="wrap-post-description">
        
        <div class="meta">
            
            <div class="meta-item"><span class="icon icon-Tag"></span><?= $category_title; ?></div>
            <div class="meta-item"><span class="icon icon-Agenda"></span><?= date('d/m/Y H:m', strtotime($porti_date)); ?>h</div>
            <div class="meta-item"><span class="icon icon-Eye"></span><?= $porti_views; ?> views</div>
            <div class="meta-item"><span class="icon icon-Watch"></span><?= $porti_time; ?> minutos</div>
        </div>
    </div>
</article><!--end blog-post-->