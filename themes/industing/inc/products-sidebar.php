<?php

use App\Helpers\Check;

?>
<div class="col-xl-3 col-md-12 col-lg-3">
    <div class="sidebar_2">
        <aside class="widget">
            <form class="searchForms" name="search" action="" method="post" enctype="multipart/form-data">
                <input type="search" name="p" placeholder="Pesquisar produtos..." autocomplete="off">
                <button type="submit">
                    <i class="fal fa-search"></i>
                </button>
            </form>
        </aside>

        <aside class="widget">
            <h3 class="widget_title">Categorias</h3>
            <?php
            $Read->exeRead(
                DB_PDT_CATS_TRAVI,
                'WHERE cat_parent IS NULL AND cat_id IN(SELECT pdt_category FROM '.DB_PDT_TRAVI.' WHERE pdt_status <> 0 AND pdt_created <= NOW()) ORDER BY cat_title ASC'
            );
if (!$Read->getResult()) {
    echo Check::erro('Ainda não existem sessões cadastradas!', E_USER_NOTICE);
} else {
    echo "<ul class='list-dividers'>";
    foreach ($Read->getResult() as $Ses) {
        // echo "<li><a class='fa fa-bookmark' title='produtos/{$Ses['cat_name']}' href='" . BASE . "/produtos/{$Ses['cat_name']}'>&raquo; {$Ses['cat_name']}</a></li>";
        $Read->exeRead(
            DB_PDT_CATS_TRAVI,
            'WHERE cat_parent = :cp AND cat_id IN(SELECT pdt_subcategory FROM '.DB_PDT_TRAVI.' WHERE pdt_status = 1 AND pdt_created <= NOW()) ORDER BY cat_title ASC',
            'cp='.$Ses['cat_id']
        );

        if ($Read->getResult()) {
            foreach ($Read->getResult() as $Cat) {
                echo \sprintf("<li><a title='produtos/%s' href='", $Cat['cat_name']).BASE.\sprintf(
                    "/produtos/%s'><i class='text-red fa fa-bookmark'></i> %s</a><span></span></li>",
                    $Cat['cat_name'],
                    $Cat['cat_title']
                );
            }
        }
    }
    echo '</ul>';
}
?>
        </aside>
        <!--        <aside class="widget">-->
        <!--            <h3 class="widget_title">Filter By Price</h3>-->
        <!--            <div class="price_slider_wrapper">-->
        <!--                <form action="#" method="get" class="clearfix">-->
        <!--                    <div id="slider-range"></div>-->
        <!--                    <p id="amount"></p>-->
        <!--                    <input type="submit" value="filter">-->
        <!--                </form>-->
        <!--            </div>-->
        <!--        </aside>-->
        <aside class="widget">
            <h3 class="widget_title">Mais Vistos</h3>
            <div class="product_wrap">
                <?php
    $Read->fullRead(
        'SELECT pdt_title, pdt_name, pdt_cover FROM '.DB_PDT_TRAVI.' WHERE pdt_status = :pt ORDER BY pdt_views ASC LIMIT 5',
        'pt=1'
    );

if (!$Read->getResult()) {
    echo Check::erro('Ainda Não existe produtos cadastrados. Favor volte mais tarde :', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Product) {
        \extract($Product);
        ?>
                        <div class="singlePro clearfix">
                            <a href="<?php echo BASE; ?>/produto/<?php echo $pdt_name; ?>"
                               title="Ir para produto: <?php echo $pdt_title; ?>">
                                <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $pdt_cover; ?>&w=80&h=80"
                                     alt="<?php echo $pdt_title; ?>" title="<?php echo $pdt_title; ?>">
                                <h6>
                                    <?php echo $pdt_title; ?>
                                </h6>
                            </a>
                        </div>
                        <?php
    }
}
?>
            </div>
        </aside>
    </div>
</div>
