<?php
$Read = new Read();

$Read->FullRead("SELECT p.*, l.line_title, l.line_image FROM ".DB_PDT." p, ".DB_PDT_LINES." l WHERE l.line_id = p.pdt_line AND pdt_name = :name AND pdt_status = :status", "name={$URL[1]}&status=1");

	if (!$Read->getResult()):
		header('Location: ' . BASE . '/404.php');
		exit;
	else:
		extract($Read->getResult()[0]);
		$CommentKey = $pdt_id;
		$CommentType = 'product';

		$pdtViewUpdate = [
			'pdt_views' => $pdt_views + 1,
			'pdt_lastview' => date('Y-m-d H:i:s')
		];
		$Update = new Update;
		$Update->ExeUpdate(DB_PDT, $pdtViewUpdate, "WHERE pdt_id = :id", "id={$pdt_id}");

		$CommentModerate = (COMMENT_MODERATE ? " AND (status = 1 OR status = 3)" : '');
		$Read->FullRead("SELECT id FROM " . DB_COMMENTS . " WHERE pdt_id = :pid{$CommentModerate}", "pid={$pdt_id}");
		$Aval = $Read->getRowCount();

		/* browsing history */
		$browsing_history = (filter_input(INPUT_COOKIE, 'browsing_history', FILTER_DEFAULT) ? filter_input(INPUT_COOKIE,
			'browsing_history', FILTER_DEFAULT) : null);
		if ($browsing_history):
			$arrIds = explode(',', $browsing_history);
			if (!in_array($pdt_id, $arrIds)):
				$arrIds[] = $pdt_id;
				$strIds = implode(',', $arrIds);
				setcookie('browsing_history', "{$strIds}", time() + 60 * 60 * 24 * 30, '/');
			endif;

			unset($browsing_history);
		else:
			setcookie('browsing_history', "{$pdt_id}", time() + 60 * 60 * 24 * 30, '/');
		endif;
	endif;

	$Category = $Read->LinkResult(DB_PDT_CATS, 'cat_id', $pdt_category, "cat_title, cat_name");
	$Subcategory = $Read->LinkResult(DB_PDT_CATS, 'cat_id', $pdt_subcategory, "cat_title, cat_name");
?>

<section class="product container padding-top-0" id="pdt">
	<header class="product_info_heading">
		<h1><?= $pdt_title; ?></h1>
		<ul>
			<li>CÓDIGO:
				<a href="" title="Esse é o CÓDIGO deste item!"> <?= $pdt_code; ?></a>
			</li>
			<li>CATEGORIA:
				<a href="<?= BASE; ?>/produtos/<?= $Category['cat_name']; ?>"
				   title="Clique para ver mais produtos dessa Categoria!"> <?= $Category['cat_title']; ?></a>
			</li>
			<li>SUBCATEGORIA:
				<a href="<?= BASE; ?>/produtos/<?= $Subcategory['cat_name']; ?>"
				   title="Clique para ver mais produtos dessa Subcategoria!"> <?= $Subcategory['cat_title']; ?></a>
			</li>
		</ul>
	</header>

	<div class="content">
		<div class="product_main">
			<div class="product_image">
				<div class="product_image_focus">
					<img class="j_focus_image image-zoom" src="<?= BASE; ?>/uploads/<?= $pdt_cover; ?>"
					     alt="<?= $pdt_title; ?>" title="<?= $pdt_title; ?>"
					     data-zoom="<?= BASE; ?>/uploads/<?= $pdt_cover; ?>"/>

					<?php
						$voltage = ($pdt_voltage == 127 ? '<img class="voltage" src="'.INCLUDE_PATH .'/images/svg/127v.svg" alt="Voltagem">' : ($pdt_voltage == 220 ? '<img class="voltage" src="'.INCLUDE_PATH .'/images/svg/220v.svg" alt="Voltagem">' : ''));
					 echo $voltage;
					?>
					<img class="line" src="<?= BASE.'/uploads/'.$line_image; ?>" alt="<?= $line_title ?>">

					<img class="zoom-plus wc_tooltip" src="<?= INCLUDE_PATH?>/images/svg/zoom-plus.svg" title="Passe o
					mouse na imagem para aumentar o Zoom" alt="Lupa ZoomIn">
					<span class="wc_tooltip_balloon">Passe o
					mouse na imagem para aumentar o Zoom</span>

				</div>

				<div class="product_image_gallery">
					<img class="j_select_gallery active" src="<?= BASE; ?>/uploads/<?= $pdt_cover; ?>"
					     alt="<?= $pdt_title; ?>" title="<?= $pdt_title; ?>"/>
					<?php

						$Read->ExeRead(DB_PDT_GALLERY, "WHERE product_id = :id", "id={$pdt_id}");
						if ($Read->getResult()):
							foreach ($Read->getResult() as $GALLERY):
								?>
								<img class="j_select_gallery" src="<?= BASE; ?>/uploads/<?= $GALLERY['image']; ?>"
								     alt="<?= $pdt_title; ?>" title="<?= $pdt_title; ?>"/>
							<?php
							endforeach;
						endif;
					?>
				</div>
			</div>
			<div class="product_info">
				<header>
					<h3>CARACTERÍSTICAS DO PRODUTO</h3>
				</header>
				<div class="htmlchars">
					<?= $pdt_content; ?>
				</div>
				<?= (!empty($pdt_drawing)? "<a class='manual' rel='shadowbox' href='".BASE."/uploads/{$pdt_drawing}'
				title='Manual de Instruções'><img src='".INCLUDE_PATH."/images/svg/baixar-manual.svg' alt='Download do Manual de Instruções'/></a>" : '')?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</section>

<section class="products container similares">
	<div class="content">
		<header><h1 class="title-section">Produtos Similares</h1></header>

		<div class="products_wrap">
			<div class="owl-carousel">
				<?php

					$arrDepartment = explode(',', $pdt_subcategory);
					$findDepartment = null;

					foreach ($arrDepartment as $CAT):
						if ($findDepartment):
							$findDepartment .= " OR FIND_IN_SET('{$CAT}', p.pdt_subcategory)";
						else:
							$findDepartment = "FIND_IN_SET('{$CAT}', p.pdt_subcategory)";
						endif;
					endforeach;

					$Read->FullRead("SELECT p.pdt_id, p.pdt_name, p.pdt_title,p.pdt_voltage,p.pdt_line, p.pdt_cover, l.line_title, l.line_image FROM " . DB_PDT . " p,".DB_PDT_LINES." l WHERE l.line_id = p.pdt_line AND p.pdt_id != :id AND p.pdt_status = :status AND ({$findDepartment}) ORDER BY p.pdt_created DESC LIMIT :limit OFFSET :offset", "id={$pdt_id}&status=1&limit=9&offset=0");

					if ($Read->getResult()):

						foreach ($Read->getResult() as $PDT):
							extract($PDT);
							require REQUIRE_PATH . '/inc/product_int.php';
						endforeach;

					endif;
				?>
			</div>
		</div>
		<div class="clear"></div>
	</div>
</section>
