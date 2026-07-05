<?php

use App\Conn\Read;
use App\Models\Pager;

$Read = new Read();
$Read->exeRead(DB_CATEGORIES, 'WHERE category_name = :nm', 'nm='.($URL[1] ?? ''));
if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);

?>
<!-- PAGE TITLE -->
<section class="blog-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <h2>
                    <a href="<?php echo BASE; ?>" title="<?php echo SITE_NAME; ?>"><?php echo SITE_NAME; ?></a>
                    <i class="fal fa-book"></i>
                    <a href="<?php echo BASE; ?>/artigos/<?php echo $category_name; ?>"
                       title="Ver mais: <?php echo $category_title; ?> em <?php echo SITE_NAME; ?>"><?php echo $category_title; ?></a>
                </h2>
            </div>
        </div>
    </div>
</section>
<!-- END PAGE TITLE -->

<section class="commonSection newslistpage">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-md-12 col-lg-8">
                <div class="row bloglistgrid">
                    <?php
                    $Page = (empty($URL[2]) ? 1 : $URL[2]);
$Pager = new Pager(BASE.\sprintf('/artigos/%s/', $category_name), '<', '>', 5);
$Pager->exePager($Page, 12);

$Read->fullRead(
    'SELECT p.post_title, p.post_subtitle, p.post_name, p.post_cover, p.post_date, p.post_author, 	        p.post_views, u.user_name, u.user_lastname, u.user_genre
						FROM '.DB_POSTS.' p
						JOIN '.DB_USERS.' u ON p.post_author = u.user_id
						WHERE p.post_status = 1 
							AND p.post_date <= NOW() 
							AND (p.post_category = :ct OR FIND_IN_SET(:ct, p.post_category_parent))
						ORDER BY post_date DESC 
						LIMIT :limit OFFSET :offset',
    "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&ct={$category_id}"
);

if (!$Read->getResult()) {
    $Pager->returnPage();
    echo Check::erro(
        'Ainda não existem posts cadastrados nesta seção. Por favor, volte mais tarde.',
        E_USER_NOTICE
    );
} else {
    foreach ($Read->getResult() as $Post) {
        \extract($Post);
        $BOX = 1;
        $AuthorName = \sprintf('%s %s', $user_name, $user_lastname);

        require REQUIRE_PATH.'/inc/post.php';
    }
}
?>
                </div>
                <div class="row mt3">
                    <div class="col-lg-12">
                        <div class="ind_pagination text-center">
                            <?php
        $Pager->exePaginator(
            DB_POSTS,
            'WHERE post_status = 1 
                                        AND post_date <= NOW() 
                                        AND (post_category = :ct OR FIND_IN_SET(:ct, post_category_parent))',
            "ct={$category_id}"
        );
echo $Pager->getPaginator();
?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            require REQUIRE_PATH.'/inc/sidebar.php'; ?>
        </div>
    </div>
</section>
