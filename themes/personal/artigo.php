<?php
    
    if (!$Read):
        $Read = new Read;
    endif;
    $Read->fullRead(
        "SELECT ws_posts.*, ws_users.user_name, ws_users.user_lastname, ws_users.user_thumb, ws_categories.category_title, ws_categories.category_name FROM " . DB_POSTS . " INNER JOIN " . DB_USERS . " ON (`ws_posts`.`post_author` = `ws_users`.`user_id`)" . " INNER JOIN " . DB_CATEGORIES . " ON (`ws_posts`.`post_category` = `ws_categories`.`category_id`)  WHERE post_status = 1 AND post_name = :nm",
        "nm={$URL[1]}"
    );
    
    if (!$Read->getResult()):
        require REQUIRE_PATH . '/404.php';
        
        return;
    else:
        extract($Read->getResult()[0]);
        $Update = new Update;
        $UpdateView = ['post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')];
        $Update->ExeUpdate(DB_POSTS, $UpdateView, "WHERE post_id = :id", "id={$post_id}");
    endif;
    
  
?>
<?php require REQUIRE_PATH . "/inc/blog_cta.php"; ?>
<!-- ========================== -->
<!-- BLOG - CONTENT -->
<!-- ========================== -->
<section class="blog-content-section">
    <div class="breadcrumbs">
        <div class="content">
            <p>
                <a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a>
                <i class='bx bxs-chevrons-right text-first-color'></i>
                <a href="<?= BASE; ?>/artigos/<?= $category_name; ?>"
                   title="Ver mais: <?= $category_title; ?> em <?= SITE_NAME; ?>!"><?= $category_title; ?></a>
                <b class="text-first-color">/</b> <?= $post_title; ?></p>
            <div class="clear"></div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="blog-main">
                <!--Blog post-->
                <div class="wrap-blog-post">
                    <header>
                        <h1 class="title"><?= $post_title; ?></h1>
                        
                        <h2 class="tagline"><?= $post_subtitle; ?></h2>
                    </header>
                    <?php
                        if (!empty($post_video)):
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
                        <div class="post-avatar">
                            <a href="#fakelink">
                                <img class="" alt="<?= $user_name . " " . $user_lastname; ?>"
                                     title="<?= $user_name . " " . $user_lastname; ?>"
                                     src="<?= BASE; ?>/tim.php?src=uploads/<?= $user_thumb; ?>"/>
                            </a>
                        </div>
                        <div class="meta">
                            <div class="meta-item">
                                <i class='bx bx-user' ></i>
                                <b><?= $user_name ?></b> <?= $user_lastname; ?>
                            </div>
                            <div class="meta-item">
                                <i class='bx bx-tag-alt'></i><?= $category_title; ?>
                            </div>
                            <div class="meta-item">
                                <i class='bx bx-calendar'></i>
                                <?= date(
                                    'd/m/Y H:m',
                                    strtotime($post_date)
                                ); ?>h
                            </div>
                            <div class="meta-item">
                                <i class='bx bx-show'></i><?= $post_views; ?> views</div>
                            <div class="meta-item">
                                <i class='bx bxs-hourglass-top'></i><?= $post_time; ?> min.
                            </div>
                        </div>
                    </div>
                    
                    <?php
                        $WC_TITLE_LINK = $post_title;
                        $WC_SHARE_HASH = "";
                        $WC_SHARE_LINK = BASE . "/artigo/{$post_name}";
                        require './_cdn/widgets/share/share.wc.php';
                    ?>

                    <div class="htmlchars post-body">
                        <?= $post_content; ?>
                    </div>
                    <?php
                        require './_cdn/widgets/share/share.wc.php'; ?>
                    <BR>
                    <article class="post_comments">
                        <h5>Deixe seu comentário aqui:</h5>
                        <?php if (APP_COMMENTS && COMMENT_ON_POSTS): ?>
                            <div class="container" style="background: #fff; padding: 20px 0;">
                                <div class="content">
                                    <?php
                                        $CommentKey = $post_id;
                                        $CommentType = 'post';
                                        require '_cdn/widgets/comments/comments.php';
                                    ?>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                       
                        <div class="fb-comments" data-href="<?= BASE; ?>/artigo/<?= $post_name; ?>" data-width="100%"
                             data-numposts="10" data-order-by="reverse_time"></div>
                    </article>

                </div>
                <div class="clear"></div>
            </div>
            <?php
                require REQUIRE_PATH . '/inc/sidebar.php'; ?>
        </div>
    </div>
</section>

<?php
    $Read->exeRead(
        DB_POSTS,
        "WHERE post_status = 1 AND post_date <= NOW() AND post_category_parent = :ct AND post_id != :id ORDER BY post_date DESC LIMIT 4",
        "ct={$post_category_parent}&id={$post_id}"
    );
    if ($Read->getResult()):
        echo '<section class="single_post_more">';
        echo "<div class='content'>";
        echo '<header class="site_header">';
        echo '<h2>Veja Também!</h2>';
        echo '<p>Os artigos relacionados podem te interessar:</p>';
        echo '</header>';
        
        foreach ($Read->getResult() as $More):
            extract($More);
            ?>
            <!-- Blog post-->
            <article class="wrap-blog-post wow fadeInUp box box4" style="padding: 5px; border: 1px solid #ccc">
                <div class="wrap-image">
                    <a class="post_list_thumb" href="<?= BASE; ?>/artigo/<?= $post_name; ?>"
                       title="<?= $post_title; ?>">
                        <img class="img-responsive"
                             src="<?= BASE; ?>/tim.php?src=uploads/<?= $post_cover; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>"
                             alt="<?= $post_title; ?>" title="<?= $post_title; ?>"/>
                    </a>
                </div>

                <div class="wrap-post-description">

                    <div class="meta" style="margin-top: 5px !important; line-height: 25px;">
                        <div class="meta-item"><span
                                    class="icon icon-User"></span><b><?= $user_name ?></b> <?= $user_lastname; ?></div>
                        <div class="meta-item"><span class="icon icon-Tag"></span><?= $category_title; ?></div>
                    </div>
                </div>
                <div class="post-body" style="margin-top: 10px !important">
                    <h2><a href="<?= BASE; ?>/artigo/<?= $post_name; ?>"
                           title="<?= $post_title; ?>"><?= $post_title; ?></a></h2>
                </div>
            </article><!--blog-post-->
        <?php
        endforeach;
        echo '<div class="clear"></div></div>';
        echo '</section>';
    endif;
?>
