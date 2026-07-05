<?php
use App\Conn\Create;
use App\Conn\Read;

    $AdminLevel = 6;

    if (!APP_SKILLSPAGE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;

    $Read ??= new Read();
    $Create ??= new Create();

    $skillId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($skillId):
        $Read->exeRead(DB_SKILLSPAGE, "WHERE skill_id = :id", "id={$skillId}");

        if ($Read->getResult()):
            $FormData = \array_map(
                fn ($v) => \htmlspecialchars((string) (\is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                $Read->getResult()[0]
            );
            extract($FormData);
        endif;
    else:
        $skillCreate = [
            'skill_title' => null,
            'skill_description' => null,
            'skill_status' => '0'
        ];
        $Create->exeCreate(DB_SKILLSPAGE, $skillCreate);
        header('Location: dashboard.php?wc=skillspage/create&id=' . $Create->getResult());
    endif;
?>


<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user-tie">Atualizar Habilidades</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/<a title="<?= ADMIN_NAME; ?>" href="dashboard
            .php?wc=skillspage/home"> Habilidades </a>/</span>
            Habilidade
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver no site" href="<?= BASE; ?>#skills"
           class="wc_view btn btn_green icon-eye">Ver no site!</a>
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="skillspage_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Skillspage"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="skill_id" value="<?= $skillId; ?>"/>

        <main class="box box70">
            <div class="box_content">
                <label class="label" for="skill_title">
                    <span class="legend">Título:</span>
                    <input class="font_big" type="text" name="skill_title" id="skill_title" value="<?= $skill_title; ?>"
                           required/>
                </label>

                <label class="label" for="skill_description">
                    <span class="legend">Breve descrição: Restam(<mark class="caracteres"></mark>) caracteres.</span>
                    <textarea class="font_medium contador" name="skill_description" id="skill_description"
                              maxlength="150" rows="5"
                              required><?= $skill_description; ?></textarea>
                </label>

                <label class="label" for="skill_text">
                    <span class="legend">Descrição completa:</span>
                    <textarea class="work_mce_basic" name="skill_text" id="skill_text"
                              rows="8"><?= $skill_text; ?></textarea>
                </label>

                <div class="clear"></div>
            </div>
        </main>

        <aside class="box box30">

            <div class="panel_header default">
                <h2 class="icon-image">Imagem ícone (PNG 500X5000px):</h2>
                <label class="label" for="skill_image">
                    <input type="file" class="wc_loadimage font_small" id="skill_image" name="skill_image"/>
                </label>
                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                        $skillImage = (!empty($skill_image) && file_exists("../uploads/skills/{$skill_image}") && !is_dir("../uploads/skills/{$skill_image}") ? "uploads/skills/{$skill_image}" : 'admin/_img/icon-default.png');
                    ?>
                    <img class="post_thumb skill_image" alt="Ícone title="Ícone"
                         src="../tim.php?src=<?= $skillImage; ?>&w=500&h=500"
                         default="../tim.php?src=<?= $skillImage; ?>&w=500&h=500"/>
                </div>
            </div>

            <div class="panel_header default">

                <div class="wc_actions">
                    <label class="label_check label_publish <?= ($skill_status == 1 ? 'active' : ''); ?>">
                        <input type="checkbox" value="1" name="skill_status" <?= ($skill_status == 1 ? 'checked' : '');
                        ?>><i class="icon-upload"></i> Publicar
                    </label>

                    <button name="public" value="1" class="btn btn_green icon-share">
                        <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
                             title="Enviando Requisição!" src="_img/load_w.gif"/>ATUALIZAR
                    </button>
                </div>
                <div class="clear"></div>
            </div>
        </aside>
    </form>
</div>
