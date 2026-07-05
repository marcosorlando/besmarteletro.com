<?php

use App\Conn\Read;
use App\Models\Pager;

$Read ??= new Read();

$Read->exeRead(DB_PDT_CATS_TRAVI, 'WHERE cat_name = :nm', 'nm='.$URL[1]);
if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
\extract($Read->getResult()[0]);

?>

<section class="product-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h2>Produtos</h2>
            </div>
            <div class="col-sm-12 col-md-6 breadcrumbs">
                <a href="<?php echo BASE; ?>">Home</a><i>|</i>
                <a href="<?php echo BASE; ?>/produtos/<?php echo $cat_name; ?>" title="<?php echo $cat_title; ?>"><?php echo $cat_title; ?></a>
            </div>
        </div>
    </div>
</section>

<section class="commonSection shopLoopPage padding-top-50px">
    <div class="container">
        <div class="row">
            <div class="col-xl-9 col-md-12 col-lg-9">
                <div class="row htmlchars">
                    <h1 class="text-uppercase"><?php echo $cat_title; ?></h1>
                    <h2 class="font_medium"><?php echo $cat_subtitle; ?></h2>
                    <?php echo $cat_content; ?>
                </div>

                <div class="row products">
                    <header class="title">
                        <h3 class="uppercase">PRODUTOS DA FAMÍLIA <span class="text-red"><?php echo $cat_title; ?></span></h3>
                    </header>

                    <?php
                    $Page = (empty($URL[2]) ? 1 : $URL[2]);
$Pager = new Pager(BASE.\sprintf('/produtos/%s/', $cat_name), '<<', '>>', 5);
$Pager->exePager($Page, 10);

$Read->fullRead(
    'SELECT pdt_title, pdt_subtitle, pdt_name, pdt_cover, pdt_created, pdt_color FROM '.DB_PDT_TRAVI.' WHERE pdt_status = 1 AND pdt_created <= NOW() AND (pdt_category = :ct OR FIND_IN_SET(:ct, pdt_subcategory)) ORDER BY pdt_created DESC LIMIT :limit OFFSET :offset',
    \sprintf('limit=%d&offset=%d&ct=%s', $Pager->getLimit(), $Pager->getOffset(), $cat_id)
);

if (!$Read->getResult()) {
    $Pager->returnPage();
    echo Check::erro(
        'Não existem produtos cadastrados nesta seção. Por favor, volte mais tarde.',
        E_USER_NOTICE
    );
} else {
    foreach ($Read->getResult() as $Post) {
        \extract($Post);
        $BOX = 1;

        require REQUIRE_PATH.'/inc/produto.php';
    }
}

?>
                </div>

                <div class="row mt20">
                    <div class="col-lg-12">
                        <div class="ind_pagination text-center">
                            <?php
        $Pager->exePaginator(
            DB_PDT_TRAVI,
            'WHERE pdt_status = 1 AND pdt_created <= NOW() AND (pdt_category = :ct OR FIND_IN_SET(:ct, pdt_subcategory))',
            'ct='.$cat_id
        );

echo $Pager->getPaginator();
?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            require REQUIRE_PATH.'/inc/category-sidebar.php'; ?>
        </div>
    </div>
</section>
<script>
    $(document).ready(function () {
        $(".title h3:contains(PRODUTOS DA FAMÍLIA Produtos)").text("TODOS OS PRODUTOS");
    });
</script>
