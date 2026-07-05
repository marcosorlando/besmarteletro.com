<?php
use App\Conn\Read;
use App\Conn\Create;

$AdminLevel = LEVEL_WC_PROJETOS;
if (!APP_PROJETOS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) :
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

    $Read ??= new Read();
    $Create ??= new Create();

$PostId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PostId) :
    $Read->exeRead(DB_PORTIFOLIO, "WHERE porti_id = :id", "id={$PostId}");
    if ($Read->getResult()) :
         $FormData = \array_map(
            fn ($v) => \htmlspecialchars((string) (\is_scalar($v) ? $v : ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            $Read->getResult()[0]
        );
        extract($FormData);
    else :
        $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou editar um projeto que não existe ou que foi removido recentemente!";
        header('Location: dashboard.php?wc=portifolios/home');
    endif;
else :
    $PostCreate = [
        'porti_date' => date('Y-m-d H:i:s'),
        'porti_type' => 'portifolio',
        'porti_status' => 0,
        'porti_author' => $Admin['user_id']
    ];
    $Create->exeCreate(DB_PORTIFOLIO, $PostCreate);
    header('Location: dashboard.php?wc=portifolios/create&id=' . $Create->getResult());
endif;
?>

<header class="dashboard_header">
    <div class="dashboard_header_title">
        <h1 class="icon-new-tab"><?= $porti_title ? $porti_title : "Novo Projeto"; ?></h1>
        <p class="dashboard_header_breadcrumbs">
            &raquo; <?= ADMIN_NAME; ?>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=home">Dashboard</a>
            <span class="crumb">/</span>
            <a title="<?= ADMIN_NAME; ?>" href="dashboard.php?wc=portifolios/home">Projetos</a>
            <span class="crumb">/</span> Gerenciar Projeto
        </p>
    </div>

    <div class="dashboard_header_search">
        <a target="_blank" title="Ver no site" href="<?= BASE; ?>/projeto/<?= $porti_name; ?>" class="wc_view btn btn_green icon-eye">Ver projeto no site!</a>
    </div>
</header>

<div class="workcontrol_imageupload none" id="porti_control">
    <div class="workcontrol_imageupload_content">
        <form name="workcontrol_porti_upload" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="callback" value="Portifolios" />
            <input type="hidden" name="callback_action" value="sendimage" />
            <input type="hidden" name="porti_id" value="<?= $PostId; ?>" />
            <div class="upload_progress none" style="padding: 5px; background: #00B594; color: #fff; width: 0%; text-align: center; max-width: 100%;">0%</div>
            <div style="overflow: auto; max-height: 300px;">
                <img class="image image_default" alt="Nova Imagem" title="Nova Imagem" src="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=admin/_img/no_image.jpg&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" />
            </div>
            <div class="workcontrol_imageupload_actions">
                <input class="wc_loadimage" type="file" name="image" required />
                <span class="workcontrol_imageupload_close icon-cancel-circle btn btn_red" id="porti_control" style="margin-right: 8px;">Fechar</span>
                <button class="btn btn_green icon-image">Enviar e Inserir!</button>
                <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
            </div>
            <div class="clear"></div>
        </form>
    </div>
</div>

<div class="dashboard_content">

    <form class="auto_save" name="porti_create" action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="callback" value="Portifolios" />
        <input type="hidden" name="callback_action" value="manager" />
        <input type="hidden" name="porti_id" value="<?= $PostId; ?>" />

        <div class="box box70">
            <div class="panel_header default">
                <h2 class="icon-blog">Dados sobre o Post</h2>
            </div>
            <div class="panel">
                <label class="label">
                    <span class="legend">Título:</span>
                    <input style="font-size: 1.4em;" type="text" name="porti_title" value="<?= $porti_title; ?>" required />
                </label>

                <label class="label">
                    <span class="legend">Subtítulo: <span style="font-size: 0.8em; padding-left: 20px ;"> caracteres restantes: <span class="caracteres" style="background-color: yellow;">150</span> </span> </span>
                    <textarea maxlength="150" class="contador" style="font-size: 1.2em;" name="porti_subtitle" rows="3" required><?= $porti_subtitle; ?></textarea>
                </label>

                <label class="label">
                    <span class="legend">TAGS:</span>
                    <input style="font-size: 1.2em;" type="text" name="porti_tags" value="<?= $porti_tags; ?>" list="tags" />

                    <datalist id="tags">
                        <?php
                        $Read->fullRead("SELECT DISTINCT upper(porti_tags) as porti_tags FROM " . DB_PORTIFOLIO . " WHERE porti_tags IS NOT NULL AND porti_tags != ''");
                        foreach ($Read->getResult() as $tags) :
                            echo '<option value="' . $tags['porti_tags'] . '">';
                        endforeach;
                        ?>
                    </datalist>
                </label>

                <?php if (APP_LINK_POSTS) : ?>
                    <label class="label">
                        <span class="legend">Link Alternativo (Opcional):</span>
                        <input type="text" name="porti_name" value="<?= $porti_name; ?>" placeholder="Link do Projeto:" />
                    </label>
                <?php endif; ?>


                <label class="label">
                    <span class="legend">Post:</span>
                    <textarea class="work_mce" rows="50" name="porti_content"><?= $porti_content; ?></textarea>
                </label>
            </div>
        </div>

        <div class="box box30">

            <div class="porti_create_cover">
                <div class="upload_progress none">0%</div>
                <?php
                $PostCover = (!empty($porti_cover) && file_exists("../uploads/{$porti_cover}") && !is_dir("../uploads/{$porti_cover}") ? "uploads/{$porti_cover}" : 'admin/_img/no_image.jpg');
                ?>
                <img class="porti_thumb porti_cover" alt="Capa" title="Capa" src="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" default="../tim.php?src=<?= $PostCover; ?>&w=<?= IMAGE_W; ?>&h=<?= IMAGE_H; ?>" />
            </div>
            <div class="label">
                    <label class="label">
                        <span class="legend">Capa: (JPG <?= IMAGE_W; ?>x<?= IMAGE_H; ?>px)</span>
                        <input type="file" class="wc_loadimage" name="porti_cover" />
                    </label>
                </div>

                <label class="label">
                    <span class="legend">Vídeo: (opcional)</span>
                    <input style="font-size: 1.2em;" type="text" name="porti_video" value="<?= $porti_video; ?>" />
                </label>


            <div class="porti_create_categories">
                <select name="porti_category" required>
                    <option value="" disabled="disabled" selected="selected">Selecione uma seção:</option>
                    <?php
                    $Read->fullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent IS NULL");
                    if (!$Read->getResult()) :
                        echo '<option value="" disabled="disabled">Não existem sessões cadastradas!</option>';
                    else :
                        foreach ($Read->getResult() as $CatPai) :
                            echo "<option";
                            if ($porti_category == $CatPai['category_id']) :
                                echo " selected='selected'";
                            endif;
                            echo " value='{$CatPai['category_id']}'>{$CatPai['category_title']}</option>";
                        endforeach;
                    endif;
                    ?>
                </select>

                <?php
                $Read->fullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent IS NULL");
                if (!$Read->getResult()) :
                    echo "<br><br>";
                    echo \App\Helpers\Check::erro('Não existe categorias cadastradas!',
	                    E_USER_WARNING);
                else :
                    foreach ($Read->getResult() as $Categories) :
                        $Read->fullRead("SELECT category_id, category_title FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent = :parent", "parent={$Categories['category_id']}");
                        if ($Read->getResult()) :
                            echo "<p class='porti_create_ses'>{$Categories['category_title']}</p>";
                            foreach ($Read->getResult() as $SubCategories) :
                                echo "<p class='porti_create_cat'><label class='label_check'><input type='checkbox' name='porti_category_parent[]' value='{$SubCategories['category_id']}'";
                                if (in_array($SubCategories['category_id'], explode(',', $porti_category_parent))) :
                                    echo " checked";
                                endif;
                                echo "> {$SubCategories['category_title']}</label></p>";
                            endforeach;
                        endif;
                    endforeach;
                endif;
                ?>
            </div>

            <div class="panel_header default">
                <h2>Publicar:</h2>
            </div>

            <div class="panel">

                <?php
                               if (APP_POSTS_AMP) :
                ?>
                    <label class="label">
                        <span class="legend">AMP:</span>
                        <select name="porti_amp" required>
                            <option value="0" <?= ($porti_amp != '0' ? "selected='selected'" : ''); ?>>Não</option>
                            <option value="1" <?= ($porti_amp == '1' ? "selected='selected'" : ''); ?>>Sim</option>
                        </select>
                    </label>
                <?php endif; ?>

                <label class="label">
                    <span class="legend">DIA:</span>
                    <input type="text" class="jwc_datepicker" data-timepicker="true" readonly="readonly" name="porti_date" value="<?= $porti_date ? date('d/m/Y H:i', strtotime($porti_date)) : date('d/m/Y H:i'); ?>" required />
                </label>

                <label class="label">
                    <span class="legend">AUTOR:</span>
                    <select name="porti_author" required>
                        <option value="<?= $Admin['user_id']; ?>"><?= $Admin['user_name']; ?> <?= $Admin['user_lastname']; ?></option>
                        <?php
                        $Read->fullRead("SELECT user_id, user_name, user_lastname FROM " . DB_USERS . " WHERE user_level >= :lv AND user_id != :uid", "lv=6&uid={$Admin['user_id']}");
                        if ($Read->getResult()) :
                            foreach ($Read->getResult() as $PostAuthors) :
                                echo "<option";
                                if ($PostAuthors['user_id'] == $porti_author) :
                                    echo " selected='selected'";
                                endif;
                                echo " value='{$PostAuthors['user_id']}'>{$PostAuthors['user_name']} {$PostAuthors['user_lastname']}</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </label>

                <div class="m_top">&nbsp;</div>
                <div class="wc_actions" style="text-align: center">
                    <label class="label_check label_publish <?= ($porti_status == 1 ? 'active' : ''); ?>"><input style="margin-top: -1px;" type="checkbox" value="1" name="porti_status" <?= ($porti_status == 1 ? 'checked' : ''); ?>> Publicar Agora!</label>
                    <button name="public" value="1" class="btn btn_green icon-share">ATUALIZAR</button>
                    <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!" title="Enviando Requisição!" src="_img/load.gif" />
                </div>
                <div class="clear"></div>

            </div>
        </div>
    </form>
</div>
