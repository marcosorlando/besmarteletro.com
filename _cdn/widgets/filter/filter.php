<?php

    /* check history */

    use App\Conn\Read;

    if (isset($_SESSION['filter']['history']) && $_SESSION['filter']['history'] != $URL[1]) {
        unset($_SESSION['filter']);
    }

    /* department | ids marcados no filtro (sempre inteiros, nunca texto livre no SQL) */
    $filterDepartments = array_values(array_unique(array_filter(array_map(
        'intval',
        explode(',', (string)($_SESSION['filter']['add']['pdt_department'] ?? ''))
    ))));

    // Cada categoria marcada casa com a subcategoria ou com o setor do produto; entre elas vale OU
    $findDepartment = implode(' OR ', array_map(
        static fn(int $cat): string => "FIND_IN_SET('{$cat}', p.pdt_subcategory) OR FIND_IN_SET('{$cat}', p.pdt_category)",
        $filterDepartments
    ));

    $condDepartment = '';
    if ('produtos' === $URL[0]) {
        // Sempre dentro da categoria da página; com filtro, apenas as categorias marcadas
        $condDepartment = ' AND (FIND_IN_SET(:category, p.pdt_category) OR FIND_IN_SET(:category, p.pdt_subcategory))'
            . ($findDepartment ? " AND ({$findDepartment})" : '');
    } elseif ('pesquisa' === $URL[0] && $findDepartment) {
        $condDepartment = " AND ({$findDepartment})";
    }
    $parseDepartment = ('produtos' === $URL[0] ? '&category=' . (int)($cat_id ?? 0) : '');

    /* search */
    $condSearch = ('pesquisa' === $URL[0] && !empty($URL[1]) ? " AND p.pdt_title LIKE '%' :search '%'" : '');
    $parseSearch = ('pesquisa' === $URL[0] && !empty($URL[1]) ? '&' . http_build_query(['search' => urldecode((string)$URL[1])]) : '');
?>

<div class="workcontrol_filter<?= (!empty($_SESSION['filter']['access']) ? ' active' : ''); ?>">

	<form class="workcontrol_filter_form" name="workcontrol_filter" action="" method="post">
		<input type="hidden" name="action" value="filter_add"/>
		<input type="hidden" name="url" value="<?= $URL[0] . '/' . $URL[1]; ?>"/>

        <?php
            $Read ??= new Read();
            /* department */
            $Read->fullRead(
                "SELECT c.cat_id, c.cat_title, (SELECT COUNT(DISTINCT (p.pdt_id)) FROM " . DB_PDT . " p WHERE p.pdt_status = :status AND (FIND_IN_SET(c.cat_id, p.pdt_subcategory) OR FIND_IN_SET(c.cat_id, p.pdt_category))" . ($URL[0] == 'produtos' ? ' AND (FIND_IN_SET(:category, p.pdt_category) OR FIND_IN_SET(:category, p.pdt_subcategory))' : '') . "{$condSearch}) AS total_pdt FROM " . DB_PDT_CATS . " c" . ($URL[0] == 'produtos' ? " WHERE c.cat_parent = :category" : '') . "",
                "status=1{$parseSearch}" . ($URL[0] == 'produtos' ? "&category={$cat_id}" : '') . ""
            );
            if ($Read->getResult()) {
                $total_pdt = 0;
                foreach ($Read->getResult() as $Department) {
                    if ($Department['total_pdt'] >= 1) {
                        $total_pdt++;
                    }
                }

                if ($total_pdt >= 1) {
                    ?>
					<div class="workcontrol_filter_form_item department">
						<p>TODAS AS CATEGORIAS</p>

						<div class="workcontrol_filter_form_item_options">
                            <?php
                                foreach ($Read->getResult() as $Department) {
                                    if ($Department['total_pdt'] >= 1) {
                                        $isChecked = in_array((int)$Department['cat_id'], $filterDepartments, true);
                                        $active = ($isChecked ? ' class="active"' : '');
                                        $checked = ($isChecked ? ' checked="checked"' : '');
                                        ?>
										<label class="j_filter_check<?= ($isChecked ? ' active' : ''); ?>" title="<?= $Department['cat_title']; ?>">
											<input type="checkbox" name="pdt_department[]"
											       value="<?= $Department['cat_id']; ?>"<?= $checked; ?>/>
											<span<?= $active; ?>><?= $Department['cat_title']; ?> <small>(<?= $Department['total_pdt']; ?>)</small></span>
										</label>
                                        <?php
                                    }
                                }
                            ?>
						</div>
                        <?php
                            if ($filterDepartments) { ?>
								<button type="button" class="workcontrol_filter_clear j_filter_clear">
									<i class="fa fa-times"></i> Limpar filtros
								</button>
                                <?php
                            } ?>
					</div>
                    <?php
                }
            }
        ?>

	</form>

	<div class="workcontrol_filter_access j_filter_access" data-history="<?= $cat_id ?? ''; ?>">
		<span class="icon-cog icon-notext"></span>
	</div>
</div>
