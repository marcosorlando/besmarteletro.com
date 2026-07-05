<?php

use App\Conn\Create;
use App\Conn\Read;

$AdminLevel = LEVEL_WC_PARTNERS;
if (!APP_PARTNERS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit(
        '<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; '
        . 'background: #fff; float: left; width: 100%; padding: 30px 0;">'
        . '<b>ACESSO NEGADO:</b> Você não está logado<br> ou não tem permissão para acessar essa página!'
        . '</div>'
    );
}
// AUTO INSTANCE OBJECT READ
$Read ??= new Read();
// AUTO INSTANCE OBJECT CREATE
$Create ??= new Create();

$PartnerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PartnerId) {
    $Read->exeRead(DB_PARTNERS, 'WHERE partner_id = :id', 'id=' . $PartnerId);
    if ($Read->getResult()) {
        $FormData = array_map(
            fn($v) => htmlspecialchars((string)(is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
        extract($FormData);
    } else {
        $_SESSION['trigger_controll'] = sprintf(
            '<b>OPPSS %s</b>, você tentou editar um parceiro que não existe ou que foi removido recentemente!',
            $Admin['user_name']
        );
        header('Location: dashboard.php?wc=partners/home');
    }
} else {
    $PartnerCreate = ['partner_name' => '', 'partner_page' => ''];
    $Create->exeCreate(DB_PARTNERS, $PartnerCreate);
    header('Location: dashboard.php?wc=partners/create&id=' . $Create->getResult());
}
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-pen"><?php
            echo $partner_name ? $partner_name : 'Novo Parceiro'; ?></h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?php
            echo ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?php
            echo ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="<?php
            echo ADMIN_NAME; ?>" href="dashboard.php?wc=partners/home">Parceiros</a>
			<span class="crumb">/</span>
			Gerenciar Parceiro
		</p>
	</div>

	<div class="dashboard_header_search">
		<a title="Ver Parceiros" href="dashboard.php?wc=partners/home" class="btn btn_blue icon-eye">Ver Todos</a>
		<a title="Novo Parceiro" href="dashboard.php?wc=partners/create" class="btn btn_green icon-plus">Adicionar</a>
	</div>
</header>

<div class="dashboard_content">
	<form name="partner_create" class="auto_save" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="callback" value="Partners"/>
		<input type="hidden" name="callback_action" value="manager"/>
		<input type="hidden" name="partner_id" value="<?php
        echo $PartnerId; ?>"/>

		<div class="box box70">
			<div class="box_content">
				<label class="label">
					<span class="legend">Nome do parceiro:</span>
					<input class="font_medium" type="text" name="partner_name" value="<?php
                    echo $partner_name; ?>"
					       placeholder="Nome do Parceiro:" required/>
				</label>
				<label class="label">
					<span class="legend">Site do parceiro:</span>
					<input class="font_medium" type="text" name="partner_page" value="<?php
                    echo $partner_page; ?>"
					       placeholder="Site do Parceiro:" required/>
				</label>
				<label class="label border_top">
					<span class="legend">Foto: (JPG 300X200px):</span>
					<input type="file" class="wc_loadimage" name="partner_image"/>
				</label>
				<div class="clear"></div>
			</div>
		</div>
		<div class="box box30">
			<div class="box_content">
                <?php
                $Image = (file_exists('../uploads/' . $partner_image) && !is_dir(
                    '../uploads/' . $partner_image
                ) ? 'uploads/' . $partner_image : 'admin/_img/no_image.jpg');
                ?>
				<img class="partner_image" src="../tim.php?src=<?php
                echo $Image; ?>&w=300&h=200"
				     default="../tim.php?src=<?php
                     echo $Image; ?>&w=300&h=200">
				<div class="box_content">
					<div class="m_top">&nbsp;</div>
					<div class="wc_actions" style="text-align: center">
						<button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
						<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
						     title="Enviando Requisição!" src="_img/load.gif"/>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</form>
</div>
