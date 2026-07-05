<?php
    use App\Conn\Read;
	use App\Conn\Create;
	use App\Helpers\Check;

    if (!APP_HOMEPAGE || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < LEVEL_WC_HOMEPAGE):
        die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
    endif;

    $Read ??= new Read();
    $Create ??= new Create();

    $homeId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($homeId === 1):
        $Read->exeRead(DB_HOMEPAGE, "WHERE home_id = :id", "id={$homeId}");

        if ($Read->getResult()):
             $FormData = \array_map(
            fn ($v) => \htmlspecialchars((string) (\is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
            extract($FormData);
        else:
            $homeCreate = [
                'first_line' => 'Primeira Linha',
                'second_line' => 'Segunda Linha',
                'thirty_line' => 'Terceira Linha'
            ];
            $Create->exeCreate(DB_HOMEPAGE, $homeCreate);
            header('Location: dashboard.php?wc=homepage/create&id=' . $Create->getResult());
        endif;
    endif;
?>


<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-home">Atualizar Home Page</h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            Home Page
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver no site" href="<?= BASE; ?>#home"
           class="wc_view btn btn_green icon-eye">Ver no site!</a>
    </div>
</header>

<div class="dashboard_content">
    <form class="auto_save" name="homepage_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Homepage"/>
        <input type="hidden" name="callback_action" value="manager"/>
        <input type="hidden" name="home_id" value="<?= $homeId; ?>"/>

        <main class="box box70">
            <div class="box_content">
                <label class="label" for="first_line">
                    <span class="legend">Primeira Linha:</span>
                    <input class="font_medium" type="text" name="first_line" id="first_line" value="<?= $first_line;
                    ?>" required/>
                </label>
                <label class="label" for="second_line">
                    <span class="legend">Segunda Linha:</span>
                    <input class="font_medium" type="text" name="second_line" id="second_line" value="<?=
                        $second_line; ?>" required/>
                </label>
                <label class="label" for="thirty_line">
                    <span class="legend">Terceira Linha:</span>
                    <input class="font_medium" type="text" name="thirty_line" id="thirty_line" value="<?=
                        $thirty_line; ?>" required/>
                </label>
                <label class="label" for="thirty_line_tags">
                    <span class="legend">Tags (separadas por vírgula [,]):</span>
                    <input class="font_medium" type="text" name="thirty_line_tags" id="thirty_line_tags" value="<?=
                        $thirty_line_tags; ?>"
                           required/>
                </label>

                <label class="label" for="home_description">
                    <span class="legend">Descrição:</span>
                    <textarea class="font_medium" name="home_description" id="home_description" rows="8"
                              required><?= $home_description; ?></textarea>
                </label>
                <label class="label" for="curriculum">
                    <span class="legend">Enviar Currículo (PDF até 10mb)</span>
                    <input type="file" id="curriculum" name="curriculum"/>
                </label>

                <div class="clear"></div>
            </div>
        </main>

        <aside class="box box30">

            <div class="panel_header default">
                <h2 class="icon-image">Background (JPG 1920x1080px):</h2>
                <label class="label" for="bg_image">
                    <input type="file" class="wc_loadimage font_small" id="bg_image" name="bg_image"/>
                </label>

                <div class="post_create_cover">
                    <div class="upload_progress none">0%</div>
                    <?php
                        $bgImage = (!empty($bg_image) && file_exists("../uploads/{$bg_image}") && !is_dir("../uploads/{$bg_image}"
                        ) ? "uploads/{$bg_image}" : 'admin/_img/no_image.jpg');
                    ?>
                    <img class="post_thumb bg_image" alt="Backgroung" title="Image de Fundo"
                         src="../tim.php?src=<?= $bgImage; ?>&w=1200&h=628"
                         default="../tim.php?src=<?= $bgImage; ?>&w=1200&h=628"/>
                </div>
            </div>

           <div class="panel_header default">
               <h2 class="icon-image">Imagem Home (PNG 600x600px):</h2>
               <label class="label" for="home_image">
                   <input type="file" class="wc_loadimage font_small" id="home_image" name="home_image"/>
               </label>
               <div class="post_create_cover">
                   <div class="upload_progress none">0%</div>
                   <?php
                       $homeImage = (!empty($home_image) && file_exists("../uploads/{$home_image}") && !is_dir(
                           "../uploads/{$home_image}"
                       ) ? "uploads/{$home_image}" : 'admin/_img/no_image.jpg');
                   ?>
                   <img class="post_thumb home_image" alt="Foto Principal" title="Foto Principal"
                        src="../tim.php?src=<?= $homeImage; ?>&w=500&h=500"
                        default="../tim.php?src=<?= $homeImage; ?>&w=500&h=500"/>
               </div>
           </div>

            <div class="panel_header default">

                <div class="wc_actions" style="display: flex; justify-content: space-between; align-items: center">
                    <label class="label_check label_publish <?= ($home_status == 1 ? 'active' : ''); ?>">
                        <input type="checkbox" value="1" name="home_status" <?= ($home_status == 1 ? 'checked' : '');
                        ?>><i class="icon-upload"></i> Publicar
                    </label>

                    <button name="public" value="1" class="btn btn_green icon-share">
                        <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load_w.gif"/>ATUALIZAR
                    </button>

                </div>
                <div class="clear"></div>
            </div>
        </aside>
    </form>
</div>
