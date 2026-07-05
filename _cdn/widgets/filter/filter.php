<?php

    /* check history */

    use App\Conn\Read;

    if (isset($_SESSION['filter']['history']) && $_SESSION['filter']['history'] != $URL[1]) {
        unset($_SESSION['filter']);
    }

    /* department */
    if (
        !empty($_SESSION['filter']['add']['pdt_department']) && strpos(
            $_SESSION['filter']['add']['pdt_department'],
            ','
        )
    ) {
        $arrDepartment = explode(',', $_SESSION['filter']['add']['pdt_department']);
        $findDepartment = null;

        foreach ($arrDepartment as $CAT) {
            if ($findDepartment) {
                $findDepartment .= " OR FIND_IN_SET('{$CAT}', p.pdt_subcategory)";
            } else {
                $findDepartment = "FIND_IN_SET('{$CAT}', p.pdt_subcategory)";
            }
        }
    }

    $condDepartment = ($URL[0] == 'produtos' && !empty($_SESSION['filter']['add']['pdt_department']) && strpos(
        $_SESSION['filter']['add']['pdt_department'],
        ','
    ) ? " AND (FIND_IN_SET(:category, p.pdt_category) OR ({$findDepartment}))" : ($URL[0] == 'produtos' && !empty($_SESSION['filter']['add']['pdt_department']) && !strpos(
        $_SESSION['filter']['add']['pdt_department'],
        ','
    ) ? " AND FIND_IN_SET(:category, p.pdt_category) AND FIND_IN_SET('{$_SESSION['filter']['add']['pdt_department']}', p.pdt_subcategory)" : ($URL[0] == 'produtos' && empty($_SESSION['filter']['add']['pdt_department']) ? ' AND (FIND_IN_SET(:category, p.pdt_category) OR FIND_IN_SET(:category, p.pdt_subcategory))' : ($URL[0] == 'pesquisa' && !empty($_SESSION['filter']['add']['pdt_department']) && strpos(
        $_SESSION['filter']['add']['pdt_department'],
        ','
    ) ? " AND ({$findDepartment})" : (($URL[0] == 'pesquisa') && !empty($_SESSION['filter']['add']['pdt_department']) && !strpos(
        $_SESSION['filter']['add']['pdt_department'],
        ','
    ) ? " AND FIND_IN_SET('{$_SESSION['filter']['add']['pdt_department']}', p.pdt_subcategory)" : '')))));
    $parseDepartment = ($URL[0] == 'produtos' ? "&category={$cat_id}" : '');

    /* search */
    $condSearch = ($URL[0] == 'pesquisa' && !empty($URL[1]) ? " AND p.pdt_title LIKE '%' :search '%'" : '');
    $parseSearch = ($URL[0] == 'pesquisa' && !empty($URL[1]) ? '&search=' . urldecode($URL[1]) . '' : '');
?>

<div class="workcontrol_filter<?= (!empty($_SESSION['filter']['access']) ? ' active' : ''); ?>">

	<form class="workcontrol_filter_form" name="workcontrol_filter" action="" method="post">
		<input type="hidden" name="action" value="filter_add"/>
		<input type="hidden" name="url" value="<?= $URL[0] . '/' . $URL[1]; ?>"/>

        <?php
            $Read ??= new Read();
            /* department */
            $Read->fullRead(
                "SELECT c.cat_id, c.cat_title, (SELECT COUNT(DISTINCT (p.pdt_id)) FROM " . DB_PDT . " p WHERE p.pdt_status = :status AND FIND_IN_SET(c.cat_id, p.pdt_subcategory){$condSearch}) AS total_pdt FROM " . DB_PDT_CATS . " c" . ($URL[0] == 'produtos' ? " WHERE c.cat_parent = :category" : '') . "",
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
                                        $active = (!empty($_SESSION['filter']['add']['pdt_department']) && strpos(
                                            $_SESSION['filter']['add']['pdt_department'],
                                            ','
                                        ) && in_array(
                                            $Department['cat_id'],
                                            explode(',', $_SESSION['filter']['add']['pdt_department'])
                                        ) ? ' class="active"' : (!empty($_SESSION['filter']['add']['pdt_department']) && !strpos(
                                            $_SESSION['filter']['add']['pdt_department'],
                                            ','
                                        ) && $Department['cat_id'] == $_SESSION['filter']['add']['pdt_department'] ? ' class="active"' : ''));
                                        $checked = (!empty($_SESSION['filter']['add']['pdt_department']) && strpos(
                                            $_SESSION['filter']['add']['pdt_department'],
                                            ','
                                        ) && in_array(
                                            $Department['cat_id'],
                                            explode(',', $_SESSION['filter']['add']['pdt_department'])
                                        ) ? ' checked="checked"' : (!empty($_SESSION['filter']['add']['pdt_department']) && !strpos(
                                            $_SESSION['filter']['add']['pdt_department'],
                                            ','
                                        ) && $Department['cat_id'] == $_SESSION['filter']['add']['pdt_department'] ? ' checked="checked"' : ''));
                                        ?>
										<label class="j_filter_check" title="<?= $Department['cat_title']; ?>">
											<input type="checkbox" name="pdt_department[]"
											       value="<?= $Department['cat_id']; ?>"<?= $checked; ?>/>
											<span<?= $active; ?>><?= $Department['cat_title']; ?></span>
										</label>
                                        <?php
                                    }
                                }
                            ?>
						</div>
					</div>
                    <?php
                }
            }
        ?>

	</form>

	<div class="workcontrol_filter_access j_filter_access" data-history="<?= $cat_id; ?>">
		<span class="icon-cog icon-notext"></span>
	</div>
</div>
