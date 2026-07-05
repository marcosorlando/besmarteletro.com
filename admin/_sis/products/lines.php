<?php
$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

//AUTO DELETE USER TRASH
if (DB_AUTO_TRASH):
    $Delete = new Delete;
    $Delete->ExeDelete(DB_PDT_LINES, "WHERE line_name IS NULL AND line_image IS NULL AND line_status = :st", "st=0");
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

$S = filter_input(INPUT_GET, "s", FILTER_DEFAULT);

$WhereString = (!empty($S) ? " AND line_title LIKE '%{$S}%' OR line_id LIKE '%{$S}%' " : "");

$Search = filter_input_array(INPUT_POST);
if ($Search && $Search['s']):
    $S = urlencode($Search['s']);
    header("Location: dashboard.php?wc=products/lines&s={$S}");
    exit;
endif;
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
            <input type="search" value="<?= $S; ?>" name="s" placeholder="Pesquisar:"/>
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
    $Pager = new Pager("dashboard.php?wc=products/lines&page=", "<<", ">>", 5);
    $Pager->ExePager($Page, 12);

	$Read->ExeRead(DB_PDT_LINES, "WHERE 1 = 1 $WhereString ORDER BY line_title ASC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");

    if (!$Read->getResult()):
        $Pager->ReturnPage();
        echo Erro("<span class='al_center icon-notification'>Ainda não existem linhas cadastradas {$Admin['user_name']}. Comece agora mesmo cadastrando uma nova linha.</span>", E_USER_NOTICE);
    else:
        foreach ($Read->getResult() as $Lines):
            extract($Lines);
            $line_title = ($line_title ?: '');
            $LineImage = "../uploads/{$line_image}";
            $line_image = (file_exists($LineImage) && !is_dir($LineImage) ? "uploads/{$line_image}" : 'admin/_img/no_image.jpg');

            echo "<article class='box box25 al_center panel'>
                    <div class='box_content wc_normalize_height'>
                        <img alt='Este é {$line_title}' title='Este é {$line_title}' src='../tim.php?src={$line_image}&w=400&h=auto'/>
                        <h1>{$line_title}</h1>                       
                        <p class='info icon-calendar'>Desde " . date('d/m/Y \a\s H\h\si', strtotime($line_registration)) . "</p>
                    </div>
                    <div class='single_line_actions'>
                        <a class='btn btn_green' href='dashboard.php?wc=products/line&id={$line_id}' title='Gerenciar Usuário!'>Gerenciar linha</a>
                    </div>
                </article>";
        endforeach;
        $Pager->ExePaginator(DB_USERS);
        echo $Pager->getPaginator();
    endif;
    ?>
</div>
