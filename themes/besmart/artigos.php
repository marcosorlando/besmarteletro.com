<?php
    if (!$Read):
        $Read = new Read;
    endif;

    $Read->ExeRead(DB_CATEGORIES, "WHERE category_name = :nm", "nm={$URL[1]}");
    if (!$Read->getResult()):
        require REQUIRE_PATH . '/404.php';
        return;
    else:
        extract($Read->getResult()[0]);
    endif;
?>
<section class="container blog-breadcrumps">
    <header class="content">
        <!-- start page title -->
        <h1 class="">
            <a class="" href="<?= BASE; ?>" title="<?= SITE_NAME; ?>"><?= SITE_NAME; ?></a>
            <i class="fa fa-bullhorn text-white"></i>
            <a class="" href="<?= BASE; ?>/artigos/<?= $category_name; ?>" title="Ver mais: <?= $category_title; ?> em <?= SITE_NAME; ?>!"><?= $category_title; ?></a>
        </h1>
        <!-- end page title -->
    </header>
</section>
<div class="clear"></div>

<!-- start post content section -->
<section class="container blog-categories">
    <div class="content">

        <main class="blog-articles">
            <h1 class="title-hidden"><?= $category_title; ?></h1>
            <?php
                $Page = (!empty($URL[2]) ? $URL[2] : 1);
                $Pager = new Pager(BASE . "/artigos/{$category_name}/", "<", ">", 5);
                $Pager->ExePager($Page, 10);

                $Read->FullRead("SELECT p.post_title, p.post_subtitle, p.post_name, p.post_cover, p.post_content, p.post_date, p.post_author, u.user_name, u.user_lastname, u.user_genre FROM " . DB_POSTS . " p, " . DB_USERS . " u WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent)) AND post_author = user_id ORDER BY post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ct={$category_id}");

                if (!$Read->getResult()):
                    $Pager->ReturnPage();
                    echo Erro("Ainda não existem posts cadastrados nesta seção. Favor volte mais tarde.",
	                    E_USER_NOTICE);
                else:
                    foreach ($Read->getResult() as $Post):
                        extract($Post);
                        $BOX = 2;
                        $AuthorName = "{$user_name} {$user_lastname}";
                        require REQUIRE_PATH . '/inc/post.php';
                    endforeach;
                endif;

            ?>
            <!-- start pagination -->
            <div class="col-md-12 col-sm-12 col-xs-12 text-center margin-100px-top sm-margin-50px-top wow fadeInUp">
                <div class="pagination text-small text-uppercase text-extra-dark-gray">
                    <?php
                        $Pager->ExePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent))", "ct={$category_id}");
                        echo $Pager->getPaginator();
                    ?>
                </div>
            </div>
            <!-- end pagination -->
        </main>
        <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
    </div>
</section>
