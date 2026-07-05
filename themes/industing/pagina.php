<?php

use App\Conn\Read;
use App\Models\Email;

$Read ??= new Read();
$Email = new Email();
$URL[0] ??= '';

$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm', 'nm='.$URL[0]);
if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);

?>

<section class='product-banner'>
    <div class='container'>
        <div class='row'>
            <h1><?php echo $page_title; ?></h1>
        </div>
    </div>
</section>

<section class='commonSection pdb80 padding-top-50px'>
    <div class='container'>
        <div class='row htmlchars-'>
            <?php echo isset($page_cover) ? \sprintf(
                "<img class='cover' title='%s' alt='%s' src='",
                $page_title,
                $page_title
            ).BASE.\sprintf(
                '/tim.php?src=uploads/%s&w=',
                $page_cover
            ).IMAGE_W.'&h='.IMAGE_H."'/>" : ''; ?>
        </div>
        <div class='htmlchars'>
            <?php echo $page_content; ?>
        </div>
        <?php
        if (APP_COMMENTS && COMMENT_ON_PAGES) { ?>
            <div class="container" style="background: #fff; padding: 20px 0;">
                <div class="content">
                    <?php
                    $CommentKey = $page_id;
            $CommentType = 'page';

            require __DIR__.'/_cdn/widgets/comments/comments.php';
            ?>
                    <div class="clear"></div>
                </div>
            </div>
            <?php
        } ?>
    </div>
</section>
