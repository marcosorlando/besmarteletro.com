<?php

use App\Conn\Delete;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Pager;

$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PDT || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

//AUTO DELETE USER TRASH
if (DB_AUTO_TRASH) {
    $Delete ??= new Delete();
    $Delete->exeDelete(DB_PDT_LINES, "WHERE line_name IS NULL AND line_image IS NULL AND line_status = :st", "st=0");
}

// AUTO INSTANCE OBJECT READ
if (empty($Read)) {
    $Read ??= new Read();
}

$S = trim((string)filter_input(INPUT_GET, "s", FILTER_DEFAULT));

// Termo vai por placeholder (:s), nunca concatenado no SQL
$WhereString = ('' !== $S ? " AND (line_title LIKE '%' :s '%' OR line_id = :s) " : "");
$WhereParse = ('' !== $S ? http_build_query(['s' => $S]) : '');

$Search = filter_input_array(INPUT_POST);
if ($Search && !empty($Search['s'])) {
    $S = urlencode(trim((string)$Search['s']));
    header("Location: dashboard.php?wc=products/lines&s={$S}");
    exit;
}
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-list-numbered">Linhas</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Linhas
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchUsers" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" value="<?= Check::safeHtmlChars($S); ?>" name="s" placeholder="Pesquisar:"/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
	    <a title='Nova linha' href='dashboard.php?wc=products/line' class='btn btn_blue icon-plus'>Adicionar
		    Linha</a>
    </div>

</header>

<div class="dashboard_content">
    <?php
    $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $Page = ($getPage ? $getPage : 1);
    $Pager = new Pager("dashboard.php?wc=products/lines" . ('' !== $S ? '&s=' . urlencode($S) : '') . "&page=", "<<", ">>", 5);
    $Pager->exePager($Page, 12);

	$Read->exeRead(DB_PDT_LINES, "WHERE 1 = 1 {$WhereString} ORDER BY line_title ASC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}" . ('' !== $WhereParse ? "&{$WhereParse}" : ''));

    if (!$Read->getResult()) {
        $Pager->returnPage();
        echo Check::erro("Ainda não existem linhas cadastradas {$Admin['user_name']}. Comece agora mesmo cadastrando uma nova linha.", E_USER_NOTICE);
    } else {
        foreach ($Read->getResult() as $Lines) {
            extract($Lines);
            $line_title = Check::safeHtmlChars($line_title ?: 'Linha sem nome');
            $LineImage = "../uploads/{$line_image}";
            $line_image = (file_exists($LineImage) && !is_dir($LineImage) ? "uploads/{$line_image}" : 'admin/_img/no_image.jpg');

            echo "<article class='box box25 al_center panel'>
                    <div class='box_content wc_normalize_height'>
                        <img alt='Este é {$line_title}' title='Este é {$line_title}' src='../tim.php?src={$line_image}&w=400&h=auto'/>
                        <h1>{$line_title}</h1>                       
                        <p class='info icon-calendar'>Desde " . date('d/m/Y \à\s H\hi', strtotime((string)$line_registration)) . "</p>
                    </div>
                    <div class='single_line_actions'>
                        <a class='btn btn_green' href='dashboard.php?wc=products/line&id={$line_id}' title='Gerenciar Linha!'>Gerenciar linha</a>
                    </div>
                </article>";
        }
        $Pager->exePaginator(DB_PDT_LINES, "WHERE 1 = 1 {$WhereString}", $WhereParse);
        echo $Pager->getPaginator();
    }
    ?>
</div>
