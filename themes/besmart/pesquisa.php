<?php

	$Search = urldecode($URL[1]);

	if (empty($_SESSION['search']) || !in_array($Search, $_SESSION['search'])):
		$Read->FullRead("SELECT search_id, search_count FROM " . DB_SEARCH . " WHERE search_key = :key",
			"key={$Search}");
		if ($Read->getResult()):
			$Update = new Update;
			$DataSearch = ['search_count' => $Read->getResult()[0]['search_count'] + 1];
			$Update->ExeUpdate(DB_SEARCH, $DataSearch, "WHERE search_id = :id",
				"id={$Read->getResult()[0]['search_id']}");
		else:
			$Create = new Create;
			$DataSearch = [
				'search_key' => $Search,
				'search_count' => 1,
				'search_date' => date('Y-m-d H:i:s'),
				'search_commit' => date('Y-m-d H:i:s')
			];
			$Create->ExeCreate(DB_SEARCH, $DataSearch);
		endif;
		$_SESSION['search'][] = $Search;
	endif;
?>
<div class="categories container">
	<div class="content">
		<?php
			require '_cdn/widgets/filter/filter.php'; ?>

		<section class="products">
			<header class="heading">
				<h1>
					Resultados de <span><?= $Search; ?></span>
				</h1>
			</header>

			<ul class="breadcrumb">
				<li>
					<a href="<?= BASE; ?>" title="<?= SITE_NAME; ?>">
						Home <i class="fa fa-angle-right"></i>
					</a>
				</li>

				<li>
					Pesquisa <i class="fa fa-angle-right"></i>
				</li>

				<li class="active">
					<?= $Search; ?>
				</li>
			</ul>

			<?php
				$Read->FullRead("SELECT COUNT(p.pdt_id) AS total_pdt FROM " . DB_PDT . " p WHERE p.pdt_status = :status {$condSearch}{$condDepartment}",
					"status=1{$parseSearch}{$parseDepartment}");
				$total_pdt = $Read->getResult()[0]['total_pdt'];

				$getPage = (!empty($URL[2]) && filter_var($URL[2], FILTER_VALIDATE_INT) ? $URL[2] : 1);
				$Pager = new Pager(BASE . "/pesquisa/" . urlencode($URL[1]) . "/",
					"<i class='fa fa-angle-left'></i><i class='fa fa-angle-left'></i>",
					"<i class='fa fa-angle-right'></i><i class='fa fa-angle-right'></i>", 3, $total_pdt);
				$Pager->ExePager($getPage, 1);

				$Read->FullRead("SELECT p.*, l.line_title, l.line_image FROM " . DB_PDT . " p, " . DB_PDT_LINES . " l WHERE l.line_id = p.pdt_line AND p.pdt_status = :status {$condSearch}{$condDepartment} ORDER BY p.pdt_created DESC LIMIT :limit OFFSET :offset",
					"status=1{$parseSearch}{$parseDepartment}&limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");

				if ($Read->getResult()):
					foreach ($Read->getResult() as $PDT):
						extract($PDT);

						require REQUIRE_PATH . '/inc/product_int.php';
					endforeach;

					$Pager->ExeFullPaginator("SELECT p.* FROM " . DB_PDT . " p WHERE p.pdt_status = :status {$condSearch}{$condDepartment}",
						"status=1{$parseSearch}{$parseDepartment}");
					echo $Pager->getPaginator();
				else:
					Erro("<p class='al_center'><b>OPPSSS:</b> Desculpe, mas a sua pesquisa não retornou resultados!</p>",
						E_USER_NOTICE);
					$Pager->ReturnPage();
				endif;
			?>
		</section>

		<div class="clear"></div>
	</div>
</div>
