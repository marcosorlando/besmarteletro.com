<?php
use App\Conn\Delete;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Pager;

    $AdminLevel = 6;
    if (!APP_SKILLSPAGE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;

    //AUTO DELETE MATERIAL TRASH
    if (DB_AUTO_TRASH):
        $Delete = new Delete();
        $Delete->exeDelete(
            DB_SKILLSPAGE,
            "WHERE skill_title IS NULL AND skill_description IS NULL and skill_status = :st",
            "st=0"
        );
    endif;

    $Read = new Read();
    $Search = filter_input_array(INPUT_POST);
    if ($Search && $Search['s']):
        $S = urlencode($Search['s']);
        header("Location: dashboard.php?wc=skillspage/search&s={$S}");
    endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-wrench">Habilidades</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Habilidades
        </p>
    </div>

    <div class="dashboard_header_search">
        <form name="searchPosts" action="" method="post" enctype="multipart/form-data" class="ajax_off">
            <input type="search" name="s" placeholder="Pesquisar Habilidade:" required/>
            <button class="btn btn_green icon icon-search icon-notext"></button>
        </form>
    </div>
</header>
<div class="dashboard_content">
    <?php
        $getPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
        $Page = ($getPage ? : 1);
        $Paginator = new Pager('dashboard.php?wc=skillspage/home&pg=', '<<', '>>', 5);
        $Paginator->exePager($Page, 12);

        $Read->exeRead(
            DB_SKILLSPAGE,
            "ORDER BY skill_status ASC, created_at DESC LIMIT :limit OFFSET :offset",
            "limit={$Paginator->getLimit()}&offset={$Paginator->getOffset()}"
        );

        if (!$Read->getResult()):
            $Paginator->ReturnPage();
            echo Check::erro(
                "Ainda não existem habilidades cadastradas {$Admin['user_name']}. Comece agora mesmo cadastrando sua primeira habilidade!",
                E_USER_NOTICE
            );
        else:
            foreach ($Read->getResult() as $SKILL):
                extract($SKILL);

                $SkillImage = (file_exists("../uploads/skills/{$skill_image}") && !is_dir(
                    "../uploads/skills/{$skill_image}"
                ) ? "uploads/skills/{$skill_image}" : 'admin/_img/icon-default.png');

                $skill_title = (!empty($skill_title) ? $skill_title : 'Edite esse rascunho para poder exibir como habilidade em seu site!');

                $skill_status = ($skill_status == 1 ? '<span class="icon-checkmark font_green">Publicada</span>' : '<span class="icon-warning font_yellow">Rascunho</span>');

                echo "<article class='box box25 post_single' id='{$skill_id}'>
                <div class='post_single_cover'>
                    <img alt='{$skill_title}' title='{$skill_title}' src='../tim.php?src={$SkillImage}&w=500&h=500'/>
                </div>
                <div class='box_content wc_normalize_height'>
                
                    <h1 class='title'>" . Check::chars($skill_title, 56) . "</h1>
                    <a title='Ver habilidade no site' target='_blank' href='" . BASE . "/habilidades#{$skill_url}' class='icon-notext icon-eye btn btn_green'></a>
                    <a title='Editar Habilidade' href='dashboard.php?wc=skillspage/create&id={$skill_id}' class='post_single_center icon-notext icon-pencil btn btn_blue'></a>

                    <span rel='post_single' class='j_delete_action icon-notext icon-cancel-circle btn btn_red' id='{$skill_id}'></span>
                   
                    <span rel='post_single' callback='Mats' callback_action='delete' class='j_delete_action_confirm icon-warning btn btn_yellow' style='display: none' id='{$skill_id}'>Deletar?</span>
                    
                    <p>{$skill_status}</p>
                    
                </div>
            </article>";
            endforeach;

            $Paginator->exePaginator(DB_SKILLSPAGE);
            echo $Paginator->getPaginator();
        endif;
    ?>
</div>
