<?php
    setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "ptb");
    date_default_timezone_set('America/Sao_Paulo');
    if (!$Read):
        $Read = new Read;
    endif;
    $Read->ExeRead(DB_POSTS, "WHERE post_name = :nm", "nm={$URL[1]}");
    if (!$Read->getResult()):
        require REQUIRE_PATH . '/404.php';
        return;
    else:
        extract($Read->getResult()[0]);
        $Update = new Update;
        $UpdateView = [
            'post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')
        ];
        $Update->ExeUpdate(DB_POSTS, $UpdateView, "WHERE post_id = :id", "id={$post_id}");

		$Read->FullRead("SELECT category_title, category_name FROM " . DB_CATEGORIES . " WHERE category_id = :id", "id={$post_category_parent}");
        $PostCategory = $Read->getResult()[0];

        $Read->FullRead("SELECT user_name, user_lastname, user_thumb, user_description, user_genre,user_twitter, user_youtube, user_google FROM " . DB_USERS . " WHERE user_id = :user", "user={$post_author}");
        $AuthorName = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
    endif;
    extract($Read->getResult()[0]);
?>


<section class="container blog-categories">
    <div class="content">

        <main class="blog-articles single_blog">
            <header class="heading">
                <h1><?= $post_title; ?></h1>
            </header>

            <ul class="breadcrumb">
                <li>
                    <i class="icon-bookmark"></i>
                    <a class="text-blue" href="<?= BASE; ?>/artigos/<?= $PostCategory['category_name']; ?>" title="Ver mais: <?= $PostCategory['category_title']; ?> em <?= SITE_NAME; ?>!"><?= $PostCategory['category_title']; ?></a>
                </li>
                <li class="text-dark-gray">
                    <i class="icon-blog"></i>
                    <a href="#">Por <?= $AuthorName; ?></a>
                </li>
            </ul>

            <div class="post_wrap">
                <?php
                    if ($post_video):
                        echo "<div class='embed-container'>";
                        echo "<iframe id='mediaview' width='100%' height='auto' src='https://www.youtube.com/embed/{$post_video}?rel=0&amp;showinfo=0&autoplay=0&origin=" . BASE . "' frameborder='0' allowfullscreen></iframe>";
                        echo "</div>";
                    else:
                        echo "<img class='width-100' title='{$post_title}' alt='{$post_title}' src='" . BASE . "/tim.php?src=uploads/{$post_cover}&w=" . IMAGE_W . "&h=" . IMAGE_H . "'/>";
                    endif;
                ?>
                <h2><?= $post_subtitle; ?></h2>
                <p class="postby">
                    <i class="icon-pencil2"></i>
                    <b><?= $AuthorName; ?></b>
                    <i class="icon-calendar"></i>
                    <time datetime="<?= date('Y-m-d', strtotime($post_date)); ?>" pubdate="pubdate"><?= utf8_encode(strftime(" %d de %B de %Y", strtotime($post_date))); ?></time>
                    <i class="icon-bookmark"></i><b><?= $PostCategory['category_title']; ?></b>
                    <i class="icon-laptop"></i><b><?= $post_views; ?></b> views
                    <i class="icon-clock"></i><b><?= $post_time; ?> </b> min. de leitura
                </p>
                <?php
                    $WC_TITLE_LINK = $post_title;
                    $WC_SHARE_HASH = "#besmart";
                    $WC_SHARE_LINK = BASE . "/artigo/{$post_name}";
                    require './_cdn/widgets/share/share.wc.php';
                ?>
                <div class="htmlchars">
                    <?= $post_content; ?>
                </div>
                <?php require './_cdn/widgets/share/share.wc.php'; ?>
            </div>

            <div class="blog_tags">
                <h5>TAGS:</h5>
                <?php
                    $tags = explode(',', $post_tags);
                    foreach ($tags as $key => $value) :
                        ?>
                        <a href="<?= BASE; ?>/pesquisa-blog/<?= urlencode(trim(strtolower($value))) ?>" title="Pesquise por essa palavra-chave.
                        Clique
                        e Confira!"><?= trim($value) ?></a>
                    <?php endforeach; ?>
            </div>

            <div class="blog_author">
                <div class="">
                    <?php $user_thumb = !empty($user_thumb) ? BASE . "/uploads/{$user_thumb}" : BASE . "/admin/_img/no_avatar.jpg"; ?>
                    <img src="<?= $user_thumb; ?>" class="img-circle" alt="<?= $user_name; ?> <?= $user_lastname; ?>" title="<?= $user_name; ?> <?= $user_lastname; ?>"/>
                </div>
                <div class="">
                    <h5><?= $user_name; ?> <?= $user_lastname; ?></h5>
                    <p><?= $user_description; ?></p>
                    <a href="<?= BASE; ?>/pesquisa-blog/<?= $post_author; ?>?author" title="Quer ler outros artigos
                    deste autor?">Todas as publicações do(a) autor(a)</a>
                </div>
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 margin-lr-auto text-center margin-80px-tb sm-margin-50px-tb xs-margin-30px-tb">
                <div class="position-relative overflow-hidden width-100">

                    <?php
                        if (APP_COMMENTS && COMMENT_ON_POSTS):
                            $CommentKey = $post_id;
                            $CommentType = 'post';
                            require '_cdn/widgets/comments/comments.php';
                        endif;
                    ?>
                </div>
            </div>
            <!-- start post item -->

            <?php
                $Read->ExeRead(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND post_category_parent != :ct AND post_id != :id ORDER BY post_date DESC LIMIT 3", "ct={$post_category_parent}&id={$post_id}");
                if ($Read->getResult()):
                    echo '<div class="posts_related">';
                    echo '<div class="">';
                    echo '<header class="">';
                    echo '<h5 class="">Artigos Relacionados</h5>';
                    echo '</header>';
                    echo '</div>';
                    foreach ($Read->getResult() as $More):
                        extract($More);
                        $BOX = 3;
                        require REQUIRE_PATH . '/inc/post.php';
                    endforeach;
                    echo '</div>';
                endif;
            ?>
        </main>
        <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
    </div>
</section>
