<?php
   use App\Conn\Read;
   use App\Conn\Create;

    if (!APP_ABOUTPAGE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < LEVEL_WC_ABOUTPAGE):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;

    $Read = new Read();
    $Create = new Create();

    $aboutId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($aboutId === 1):
        $Read->exeRead(DB_ABOUTPAGE, "WHERE about_id = :id", "id={$aboutId}");

        if ($Read->getResult()):
             $FormData = \array_map(
            fn ($v) => \htmlspecialchars((string) (\is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
            extract($FormData);
        else:
            $aboutCreate = [
                'about_id' => 1,
                'about_title' => '',
                'about_description' => ''
            ];
            $Create->exeCreate(DB_ABOUTPAGE, $aboutCreate);
           header('Location: dashboard.php?wc=aboutpage/create&id=' . $Create->getResult());
        endif;
    endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-user-tie">Atualizar Sobre Mim</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Sobre mim
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver no site" href="<?= BASE; ?>#about"
           class="wc_view btn btn_green icon-eye">Ver no site!</a>
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="aboutpage_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Aboutpage"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="about_id" value="<?= $aboutId; ?>"/>

        <main class="box box70">
            <div class="box_content">
                <label class="label" for="about_title">
                    <span class="legend">Título:</span>
                    <input class="font_big" type="text" name="about_title" id="about_title" value="<?= $about_title; ?>" required/>
                </label>

                <label class="label" for="about_description">
                    <span class="legend">Breve descrição: Restam(<mark class="caracteres"></mark>) caracteres.</span>
                    <textarea class="font_medium contador" name="about_description" id="about_description"
                              maxlength="150" rows="5"
                              required><?= $about_description; ?></textarea>
                </label>

                <label class="label" for="about_text">
                    <span class="legend">Descrição completa:</span>
                    <textarea class="work_mce_basic" name="about_text" id="about_text" rows="8"><?= $about_text; ?></textarea>
                </label>

                <div class="clear"></div>
            </div>
        </main>

        <aside class="box box30">

            <div class="panel_header default">
                <h2 class="icon-image">Imagem About (PNG 600x600px):</h2>
                <label class="label" for="about_image">
                    <input type="file" class="wc_loadimage font_small" id="about_image" name="about_image"/>
                </label>
                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                        $aboutImage = (!empty($about_image) && file_exists("../uploads/{$about_image}") && !is_dir(
                            "../uploads/{$about_image}"
                        ) ? "uploads/{$about_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb about_image" alt="Foto Principal" title="Foto Principal"
                         src="../tim.php?src=<?= $aboutImage; ?>&w=500&h=500"
                         default="../tim.php?src=<?= $aboutImage; ?>&w=500&h=500"/>
                </div>
            </div>

            <div class="panel_header default">

                <div class="wc_actions">
                    <label class="label_check label_publish <?= ($about_status == 1 ? 'active' : ''); ?>">
                        <input type="checkbox" value="1" name="about_status" <?= ($about_status == 1 ? 'checked' : '');
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
