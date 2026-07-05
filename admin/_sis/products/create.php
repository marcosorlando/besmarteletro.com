<?php

	$AdminLevel = LEVEL_WC_PRODUCTS;
	if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
		die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
	endif;

	// AUTO INSTANCE OBJECT READ
	if (empty($Read)):
		$Read = new Read;
	endif;

	// AUTO INSTANCE OBJECT CREATE
	if (empty($Create)):
		$Create = new Create;
	endif;

	$PdtId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
	if ($PdtId):
		$Read->ExeRead(DB_PDT, "WHERE pdt_id = :id", "id={$PdtId}");
		if ($Read->getResult()):
			$FormData = array_map('htmlspecialchars', $Read->getResult()[0]);
			extract($FormData);
			//AUX. VALIDA PDF
			$pdtPDF = (!empty($pdt_drawing) ? BASE . "/uploads/{$pdt_drawing}" : null);
		else:
			$_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um produto que não existe ou que foi removido recentemente!";
			header('Location: dashboard.php?wc=products/home');
			exit;
		endif;
	else:

		$PdtCreate = [
			'pdt_created' => date('Y-m-d H:i:s'),
			'pdt_status' => 0
		];
		$Create->ExeCreate(DB_PDT, $PdtCreate);
		header('Location: dashboard.php?wc=products/create&id=' . $Create->getResult());

	endif;

?>

<header class="dashboard_header">
	<div class="dashboard_header_title">
		<h1 class="icon-new-tab"><?= $pdt_title ?: 'Novo Produto'; ?></h1>
		<p class="dashboard_header_breadcrumbs">
			&raquo; <?= ADMIN_NAME; ?>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
			<span class="crumb">/</span>
			<a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=products/home">Produtos</a>
			<span class="crumb">/</span> Gerenciar Produto
		</p>
	</div>

	<div class="dashboard_header_search">
		<a id="<?= $PdtId; ?>" title="Criar Variação Deste Produto"
		   href="dashboard.php?wc=products/reply&id=<?= ($pdt_parent ? $pdt_parent : $PdtId); ?>"
		   class="j_pdt_reply btn btn_blue icon-copy">Criar Variação!</a>

		<a target="_blank" title="Ver no site" href="<?= BASE; ?>/produto/<?= $pdt_name; ?>" class="wc_view btn
        btn_green icon-eye m_left">Ver no Site!</a>
	</div>
</header>

<div class="workcontrol_imageupload none" id="post_control">
	<div class="workcontrol_imageupload_content">
		<form name="workcontrol_post_upload" action="" method="post" enctype="multipart/form-data">
			<input type="hidden" name="callback" value="Products"/>
			<input type="hidden" name="callback_action" value="sendimage"/>
			<input type="hidden" name="pdt_id" value="<?= $PdtId; ?>"/>
			<div class="upload_progress none"
			     style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">
				0%
			</div>
			<div style="overflow: auto; max-height: 300px;">
				<img class="image image_default" alt="Nova Imagem" title="Nova Imagem"
				     src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"
				     default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>"/>
			</div>
			<div class="workcontrol_imageupload_actions">
				<input class="wc_loadimage" type="file" name="image" required/>
				<span class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="post_control"
				      style="margin-right: 8px;">Fechar</span>
				<button class="btn btn_green icon-image">Enviar e Inserir!</button>
				<img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
				     title="Enviando Requisição!" src="_img/load.gif"/>
			</div>
			<div class="clear"></div>
		</form>
	</div>
</div>

<div class="dashboard_content single_pdt_form">
	<form class="auto_save" name="manage_pdt" action="" method="post" enctype="multipart/form-data">
		<input type="hidden" name="callback" value="Products"/>
		<input type="hidden" name="callback_action" value="manager"/>
		<input type="hidden" name="pdt_id" value="<?= $PdtId; ?>"/>

		<div class="box box70">
			<div class="box_content">
				<label class="label">
					<span class="legend">Produto:</span>
					<input style="font-size: 1.4em;" type="text" name="pdt_title" value="<?= $pdt_title; ?>"
					       placeholder="Nome do Produto:" required/>
				</label>

				<label class="label">
					<span class="legend">Breve Descrição (SEO):</span>
					<textarea style="font-size: 1.2em;" name="pdt_subtitle" rows="3"
					          required><?= $pdt_subtitle; ?></textarea>
				</label>

				<?php
					if (APP_LINK_PRODUCTS): ?>
						<label class="label">
							<span class="legend">Link Alternativo (Opcional):</span>
							<input type="text" name="pdt_name" value="<?= $pdt_name; ?>"
							       placeholder="Link do Produto:"/>
						</label>
					<?php
					endif; ?>

				<!----------------------------------
				 ######## CUSTOM BY ALISSON #########
				----------------------------------->
				<div class="label_33">
					<label class="label">
						<span class="legend">Código:</span>
						<input type="text" name="pdt_code"
						       value="<?= ($pdt_code ? $pdt_code : str_pad($pdt_id, 4, 0, STR_PAD_LEFT)); ?>" required/>
					</label>

					<label class='label'>
						<span class='legend'>Voltagem (110/220V):</span>
						<?php
							echo "<select name='pdt_voltage' required>";
							echo "<option value='' selected disabled>Selecione a voltagem</option>";

							foreach (getWcVoltage() as $key => $value) {
								echo "<option " . ($pdt_voltage == $key ? 'selected' : '') . " 
									value='{$key}'>{$value}</option>";
							}
							echo '</select>';

						?>
					</label>

					<label class="label">
						<span class="legend">Modelo:</span>
						<input type="text" name="pdt_model" value="<?= $pdt_model ?>" placeholder="Modelo:" required/>
					</label>

				</div>

				<div class="label_50">

					<label class='label category_selection'>
						<span class='category_selection_legend'><b class='font_red'>* </b>Categoria:</span>
						<span class='category_selection_title j_open_category_selection'>
                        <?php

	                        if (empty($pdt_subcategory)):
		                        echo 'Selecione a(s) Categoria(s)';
	                        else:
		                        $Read->FullRead('SELECT cat_title FROM ' . DB_PDT_CATS . ' WHERE FIND_IN_SET(cat_id, :category) ORDER BY cat_title ASC',
			                        "category={$pdt_subcategory}");
		                        if ($Read->getResult()):
			                        $cats = [];
			                        foreach ($Read->getResult() as $PDT):
				                        $cats[] = $PDT['cat_title'];
			                        endforeach;

			                        echo implode(', ', $cats);
		                        endif;
	                        endif;
                        ?>
                    </span>

						<div class="category_selection_content j_category_selection_content">
							<?php

								$Read->FullRead('SELECT cat_id, cat_parent, cat_title FROM ' . DB_PDT_CATS . ' WHERE cat_parent IS NULL ORDER BY cat_title ASC');

								function loopCat()
								{
									global $Read, $pdt_subcategory;
									if ($Read->getResult()):
										foreach ($Read->getResult() as $CAT):
											$Read->FullRead('SELECT cat_id, cat_parent, cat_title FROM ' . DB_PDT_CATS . ' WHERE cat_parent = :parent ORDER BY cat_title ASC',
												"parent={$CAT['cat_id']}");
											$checked = (!empty($pdt_subcategory) && in_array($CAT['cat_id'],
												explode(',', $pdt_subcategory)) ? ' checked="checked"' : '');

											echo '<li>';
											echo '<span>';
											echo "<i class='" . ($Read->getResult() ? 'icon-plus icon-notext' : 'icon-minus icon-notext') . "'></i>";
											echo "<input id='checkbox-category-{$CAT['cat_id']}' class='j_category_selection multiple' type='checkbox' name='pdt_subcategory[]' value='{$CAT['cat_id']}' data-title='{$CAT['cat_title']}'" . (empty($CAT['cat_parent']) ? ' disabled="disabled"' : '') . $checked . '/>';
											echo '<label' . (empty($CAT['cat_parent']) ? ' class="disabled"' : '') . " for='checkbox-category-{$CAT['cat_id']}'>{$CAT['cat_title']}</label>";
											echo '</span>';

											if ($Read->getResult()):
												echo '<ul>';
												loopCat();
												echo '</ul>';
											endif;
											echo '</li>';
										endforeach;
									endif;
								}

								echo '<ul>';
								loopCat();
								echo '</ul>';
							?>
						</div>
					</label>

					<label class='label'>
						<span class='legend'>Linha:</span>
						<?php
							$Read->ExeRead(DB_PDT_LINES, 'ORDER BY line_title ASC');
							if (!$Read->getResult()):
								echo Erro("<span class='icon-warning'>Cadastre algumas <b>linhas de produtos</b> antes de começar!</span>",
									E_USER_WARNING);
							else:
								echo "<select name='pdt_line' required>";
								echo "<option value=''>Selecione uma Linha</option>";
								foreach ($Read->getResult() as $Line):
									echo '<option';
									if ($pdt_line == $Line['line_id']):
										echo " selected='selected'";
									endif;
									echo " value='{$Line['line_id']}'>{$Line['line_title']}</option>";
								endforeach;

								echo '</select>';
							endif;
						?>
					</label>

				</div>

				<label class="label">
					<span class="legend">Descrição do Produto:</span>
					<textarea name="pdt_content" class="work_mce" rows="20"><?= $pdt_content; ?></textarea>
				</label>

				<div class="clear"></div>

			</div>
		</div>

		<div class="box box30">
			<div class="panel">
				<label class='label'>
					<span class='legend'>Principal (JPG <?= THUMB_W; ?>x<?= THUMB_H; ?>px) - máx: 1mb</span>
					<input type="file" class="wc_loadimage" name="pdt_cover"/>
				</label>
			</div>
			<?php

				$Image = (file_exists("../uploads/{$pdt_cover}") && !is_dir("../uploads/{$pdt_cover}") ? "uploads/{$pdt_cover}" : 'admin/_img/no_image.jpg');
			?>
			<img class="pdt_cover" alt="Capa do Produto" title="Capa do Produto"
			     src="../tim.php?src=<?= $Image; ?>&w=<?= THUMB_W; ?>&h=<?= THUMB_H; ?>"
			     default="../tim.php?src=<?= $Image; ?>&w=<?= THUMB_W; ?>&h=<?= THUMB_H; ?>">
			<?php

				$Read->ExeRead(DB_PDT_GALLERY, "WHERE product_id = :id", "id={$pdt_id}");
				if ($Read->getResult()):
					echo '<div class="pdt_images gallery pdt_single_image">';
					foreach ($Read->getResult() as $Image):
						$ImageUrl = ($Image['image'] && file_exists("../uploads/{$Image['image']}") && !is_dir("../uploads/{$Image['image']}") ? "../uploads/{$Image['image']}" : '_img/no_image.jpg');
						echo "<img rel='Products' id='{$Image['id']}' alt='Imagem em {$pdt_title}' title='Imagem em {$pdt_title}' src='{$ImageUrl}'/>";
					endforeach;
					echo '</div>';
				else:
					echo '<div class="pdt_images gallery pdt_single_image"></div>';
				endif;
			?>

			<div class="box_content">

				<label class="label">
					<span class="legend">Fotos Adicionais (JPG <?= THUMB_W; ?>x<?= THUMB_H; ?>px):</span>
					<input type="file" name="image[]" multiple/>
				</label>


				<label class="label">
                        <span class="legend">
                            <a href="<?= $pdtPDF ?>" target="_blank" title="Visualizar"> <i
			                            class="icon-file-pdf <?= !empty($pdt_drawing) && !is_dir($pdtPDF) ? 'font_green' : 'font_red' ?>"></i></a>Desenho Técnico (PDF - máx. 5mb): </span>
					<input type="file" class="wc_load_pdf" name="pdt_drawing"
					       value="<?= ($pdt_drawing ? $pdt_drawing : ''); ?>"/>
				</label>

				<div class="wc_actions">
					<span><b>MOSTRAR EM DESTAQUE:</b></span>
					<?= switchYesNo('pdt_destaque', $pdt_destaque); ?>
				</div>

				<div id="period" class="ds_none">

					<label class='label'>
						<span class='legend'><b class='font_red'>* </b>Início da Divulgação:</span>
						<input type='text' class='formTime jwc_datepicker' data-timepicker='true' name='pdt_offer_start'
						       value="<?= ($pdt_offer_start ? date('d/m/Y H:i',
							       strtotime($pdt_offer_start)) : null); ?>"/>
					</label>

					<label class='label'>
						<span class='legend'><b class='font_red'>* </b>Fim da Divulgação:</span>
						<input type='text' class='formTime jwc_datepicker' data-timepicker='true' name='pdt_offer_end'
						       value="<?= ($pdt_offer_end ? date('d/m/Y H:i', strtotime($pdt_offer_end)) : null); ?>"/>
					</label>
				</div>

				<div class="wc_actions">
					<?= switchOnOff('pdt_status', $pdt_status); ?>
					<button name="public" value="1" class="btn btn_save">
						<img class='form_load' alt='Enviando Requisição!' title='Enviando Requisição!'
						     src='_img/load_w.gif'/> Salvar
					</button>

				</div>
			</div>
		</div>
		<div class="clear"></div>
	</form>
</div>
