<?php

	$AdminLevel = LEVEL_WC_PRODUCTS;
	if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
		die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
	endif;

	// AUTO INSTANCE OBJECT READ
	if (empty($Read)):
		$Read = new Read;
	endif;

	// AUTO INSTANCE OBJECT READ
	if (empty($Create)):
		$Create = new Create;
	endif;

	$LineId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

	if ($LineId):
		$Read->ExeRead(DB_PDT_LINES, "WHERE line_id = :id", "id={$LineId}");
		if ($Read->getResult()):
			$FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
			extract($FormData);
		else:
			$_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar uma linha que não existe ou que foi removida recentemente!";
			header('Location: dashboard.php?wc=products/lines');
			exit;
		endif;
	else:
		$CreateLine = [
			"line_registration" => date('Y-m-d H:i:s'),
			"line_status" => '0'
		];

		//var_dump($CreateLine);
		$Create->ExeCreate(DB_PDT_LINES, $CreateLine);

		//var_dump($Create);
		header("Location: dashboard.php?wc=products/line&id={$Create->getResult()}");
		exit;
	endif;
?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-list-numbered">Nova Linha</h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/lines">Linhas</a>
			<span class="crumb">/</span> Nova Linha
		</p>
	</div>
</header>

<div class="dashboard_content dashboard_users">
	<div class="box box70">
		<article class="wc_tab_target wc_active" id="profile">

			<div class="panel_header default">
				<h2 class=" icon-list-numbered">Dados de <?= $line_title; ?></h2>
			</div>

			<div class="panel">
				<form class="auto_save" name="line_manager" action="" method="post"
				      enctype="multipart/form-data">
					<input type="hidden" name="callback" value="Products"/>
					<input type="hidden" name="callback_action" value="line_manager"/>
					<input type="hidden" name="line_id" value="<?= $LineId; ?>"/>

					<div class="label_50">
						<label class="label">
							<span class="legend">Nome da Linha:</span>
							<input value="<?= $line_title; ?>" type="text" name="line_title" placeholder="Nome da linha"
							       required/>
						</label>

						<label class="label">
							<span class="legend">Imagem da Linha:</span>
							<input class='wc_loadimage' type="file" name="line_image"/>
						</label>
					</div>

					<div class="clear"></div>
					<button name="public" value="1" class="btn btn_green fl_right icon-share" style="margin-left: 5px;">
						Atualizar Linha <img class='form_load none fl_right' alt='Enviando Requisição!'
						                     title='Enviando Requisição!' src='_img/load.gif'/>
					</button>
					<div class="clear"></div>
				</form>
			</div>

		</article>
	</div>
	<div class="box box30">
		<div class="panel">
			<?php
				$Image = (file_exists("../uploads/{$line_image}") && !is_dir("../uploads/{$line_image}") ? "uploads/{$line_image}" : 'admin/_img/no_image.jpg');
			?>
			<img class="line_image" style="width: 100%;" src="../tim.php?src=<?= $Image; ?>&w=400&h=auto" alt=""
			     title=""/>
		</div>

	</div>
</div>
