<?php

use App\Helpers\Check;

?>
<aside class="col-md-4 sidebar mt-10">
    <!-- SEARCH FORM -->
    <div class="widget search">
        <form class="relative" name="search" action="" method="post" enctype="multipart/form-data">
            <input type="text" name="p" class="searchbox mb-0" placeholder="Pesquisar artigos..." autocomplete="off">
            <button type="submit" class="search-button"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <!-- END SEARCH FORM -->

    <!-- COLOR -->
    <div class="widget">
        <h3 class="widget-title heading relative heading-small uppercase bottom-line style-2 left-align">Padrão/Cor</h3>

        <form name="search" action="" method="post" enctype="multipart/form-data">
            <ul class="radio-buttons mb-20">
                <?php
                $Read->exeRead(
                    DB_PDT_COLORS_GLOBAL,
                    'WHERE color_title IN(SELECT pdt_color FROM '.DB_PDT_GLOBAL.' WHERE pdt_status <> 0 AND pdt_created <= NOW()) ORDER BY color_title ASC'
                );
$Radio = 1;
if (!$Read->getResult()) {
    echo Check::erro('Ainda não existem cores cadastradas!', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Colors) {
        \extract($Colors);
        echo \sprintf(
            "<li><input id='radio-%d' name='p' type='radio' class='input-check-filter' value='%s'>",
            $Radio,
            $color_name
        );
        echo \sprintf("<label for='radio-%d'>%s</label></li>", $Radio, $color_title);
        ++$Radio;
    }
}
?>
            </ul>
            <button class="btn"><i class="fa fa-filter"></i> FILTRAR</button>
        </form>
    </div>
    <!-- END COLOR -->

    <!-- CATEGORIES -->
    <div class="widget categories">
        <h3 class="widget-title heading relative heading-small uppercase bottom-line style-2 left-align">Categorias</h3>

        <?php
        $Read->exeRead(
            DB_PDT_CATS_GLOBAL,
            'WHERE cat_parent IS NULL AND cat_id IN(SELECT pdt_category FROM '.DB_PDT_GLOBAL.' WHERE pdt_status <> 0 AND pdt_created <= NOW()) ORDER BY cat_title ASC'
        );
if (!$Read->getResult()) {
    echo Check::erro('Ainda não existem sessões cadastradas!', E_USER_NOTICE);
} else {
    echo "<ul class='list-dividers'>";
    foreach ($Read->getResult() as $Ses) {
        echo \sprintf("<li><a title='artigos/%s' href='", $Ses['cat_name']).BASE.\sprintf(
            "/produtos/%s'>&raquo; %s</a></li>",
            $Ses['cat_name'],
            $Ses['cat_name']
        );
        $Read->exeRead(
            DB_PDT_CATS_GLOBAL,
            'WHERE cat_parent = :cp AND cat_id IN(SELECT pdt_subcategory FROM '.DB_PDT_GLOBAL.' WHERE pdt_status = 1 AND pdt_created <= NOW()) ORDER BY cat_title ASC',
            'cp='.$Ses['cat_id']
        );
        if ($Read->getResult()) {
            foreach ($Read->getResult() as $Cat) {
                echo \sprintf("<li><a title='produtos/%s' href='", $Cat['cat_name']).BASE.\sprintf(
                    "/produtos/%s'>&raquo;&raquo; %s</a><span></span></li>",
                    $Cat['cat_name'],
                    $Cat['cat_title']
                );
            }
        }
    }
    echo '</ul>';
}
?>
    </div>
    <!-- END CATEGORIES -->

    <!-- GALLERIES -->
    <div class="widget weather">
        <h3 class="widget-title heading relative heading-small uppercase bottom-line style-2 left-align">
            Protudos mais visitados</h3>
        <ul class="gallery-list clearfix">
            <?php
    $Read->fullRead(
        'SELECT pdt_title, pdt_name, pdt_cover FROM '.DB_PDT_GLOBAL.' WHERE pdt_status = :pt ORDER BY pdt_views ASC LIMIT 6',
        'pt=1'
    );

if (!$Read->getResult()) {
    echo Check::erro('Ainda Não existe produtos cadastrados. Favor volte mais tarde :', E_USER_NOTICE);
} else {
    foreach ($Read->getResult() as $Product) {
        \extract($Product);
        ?>
                    <li>
                        <article class="entry-img hover-scale">
                            <a href="<?php echo BASE; ?>/produto/<?php echo $pdt_name; ?>">
                                <img src="<?php echo BASE; ?>/tim.php?src=uploads/<?php echo $pdt_cover; ?>&w=90&h=90"
                                     alt="<?php echo $pdt_title; ?>" title="<?php echo $pdt_title; ?>">
                            </a>
                        </article>
                    </li>
                    <?php
    }
}
?>
        </ul>
    </div>
    <!-- END GALLERIES -->

    <!-- NEWSLETTER -->
    <div class="widget newsletter clearfix">
        <h3 class="widget-title heading relative heading-small uppercase bottom-line style-2 left-align">
            Newsletter</h3>
        <?php
        $CAPTION = 'news1';

require REQUIRE_PATH.'/inc/activeform.php';
?>
    </div>
    <!-- END NEWSLETTER -->
</aside>
