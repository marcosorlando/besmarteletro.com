<?php
use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

session_start();

require __DIR__.'/../../vendor/autoload.php';

$NivelAcess = LEVEL_WC_PROJETOS;

if (!APP_PROJETOS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess) :
    $jSON['trigger'] = Check::ajaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Portifolios';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack) :
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
        $Read ??= new Read();
    // AUTO INSTANCE OBJECT CREATE
        $Create ??= new Create();
    // AUTO INSTANCE OBJECT UPDATE
        $Update ??= new Update();
    // AUTO INSTANCE OBJECT DELETE
        $Delete ??= new Delete();

    //SELECIONA AÇÃO
    switch ($Case):
            //DELETE
        case 'delete':
            $PostData['porti_id'] = $PostData['del_id'];
            $Read->fullRead("SELECT porti_cover FROM " . DB_PORTIFOLIO . " WHERE porti_id = :ps", "ps={$PostData['porti_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['porti_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['porti_cover']}")) :
                unlink("../../uploads/{$Read->getResult()[0]['porti_cover']}");
            endif;

            $Read->fullRead("SELECT image FROM " . DB_PORTIFOLIO_IMAGES . " WHERE porti_id = :ps", "ps={$PostData['porti_id']}");
            if ($Read->getResult()) :
                foreach ($Read->getResult() as $PostImage) :
                    $ImageRemove = "../../uploads/{$PostImage['image']}";
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)) :
                        unlink($ImageRemove);
                    endif;
                endforeach;
            endif;

            $Delete->exeDelete(DB_PORTIFOLIO, "WHERE porti_id = :id", "id={$PostData['porti_id']}");
            /*  $Delete->exeDelete(DB_PORTIFOLIO_IMAGES, "WHERE porti_id = :id", "id={$PostData['porti_id']}");
            $Delete->exeDelete(DB_COMMENTS, "WHERE porti_id = :id", "id={$PostData['porti_id']}"); */
            $jSON['success'] = true;
            break;

        case 'manager':
            $PostId = $PostData['porti_id'];
            unset($PostData['porti_id']);

            $Read->exeRead(DB_PORTIFOLIO, "WHERE porti_id = :id", "id={$PostId}");
            $ThisPost = $Read->getResult()[0];

            $PostData['porti_name'] = (!empty($PostData['porti_name']) ? Check::name($PostData['porti_name']) : Check::name($PostData['porti_title']));
            $Read->exeRead(DB_PORTIFOLIO, "WHERE porti_id != :id AND porti_name = :name", "id={$PostId}&name={$PostData['porti_name']}");
            if ($Read->getResult()) :
                $PostData['porti_name'] = "{$PostData['porti_name']}-{$PostId}";
            endif;

            $jSON['name'] = $PostData['porti_name'];

            if (!empty($_FILES['porti_cover'])) :
                $File = $_FILES['porti_cover'];

                if ($ThisPost['porti_cover'] && file_exists("../../uploads/{$ThisPost['porti_cover']}") && !is_dir("../../uploads/{$ThisPost['porti_cover']}")) :
                    unlink("../../uploads/{$ThisPost['porti_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->image($File, $PostData['porti_name'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()) :
                    $PostData['porti_cover'] = $Upload->getResult();
                else :
                    $jSON['trigger'] = Check::ajaxErro("<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar como capa!", E_USER_WARNING);
                    echo json_encode($jSON);
                    return;
                endif;
            else :
                unset($PostData['porti_cover']);
            endif;

            $PostData['porti_status'] = (!empty($PostData['porti_status']) ? '1' : '0');
            $PostData['porti_month'] = date('m');
            $PostData['porti_date'] = (!empty($PostData['porti_date']) ? Check::Data($PostData['porti_date']) : date('Y-m-d H:i:s'));
            $PostData['porti_category_parent'] = (!empty($PostData['porti_category_parent']) ? implode(',', $PostData['porti_category_parent']) : null);

            $Update->ExeUpdate(DB_PORTIFOLIO, $PostData, "WHERE porti_id = :id", "id={$PostId}");
            $jSON['trigger'] = Check::ajaxErro("<b class='icon-checkmark'>TUDO CERTO: </b> O projeto <b>{$PostData['porti_title']}</b> foi atualizado com sucesso!");
            $jSON['view'] = BASE . "/projeto/{$PostData['porti_name']}";
            break;

        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->fullRead("SELECT porti_title, porti_title FROM " . DB_PORTIFOLIO . " WHERE porti_id = :id", "id={$PostData['porti_id']}");
            if (!$Read->getResult()) :
                $jSON['trigger'] = Check::ajaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe {$_SESSION['userLogin']['user_name']}, mas não foi possível identificar o projeto vinculado!", E_USER_WARNING);
            else :
                $Upload = new Upload('../../uploads/');
                $Upload->image($NewImage, $PostData['porti_id'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()) :
                    $PostData['image'] = $Upload->getResult();
                    $Create->exeCreate(DB_PORTIFOLIO_IMAGES, $PostData);
                    $jSON['tinyMCE'] = "<img title='{$Read->getResult()[0]['porti_title']}' alt='{$Read->getResult()[0]['porti_title']}' src='../uploads/{$PostData['image']}'/>";
                else :
                    $jSON['trigger'] = Check::ajaxErro("<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para inserir no projeto!", E_USER_WARNING);
                endif;
            endif;
            break;

        case 'category_add':
            $PostData = array_map('strip_tags', $PostData);

            $CatId = $PostData['category_id'];
            unset($PostData['category_id']);

            $PostData['category_name'] = Check::name($PostData['category_title']);
            $PostData['category_parent'] = ($PostData['category_parent'] ? $PostData['category_parent'] : null);

            $Read->fullRead("SELECT category_id FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_name = :cn AND category_id != :ci", "cn={$PostData['category_name']}&ci={$CatId}");
            if ($Read->getResult()) :
                $PostData['category_name'] = $PostData['category_name'] . '-' . $CatId;
            endif;

            $Read->fullRead("SELECT category_id FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent = :ci", "ci={$CatId}");
            if ($Read->getResult() && !empty($PostData['category_parent'])) :
                $jSON['trigger'] = Check::ajaxErro("<b class='icon-warning'>OPPSSS: </b> {$_SESSION['userLogin']['user_name']}, uma categoria PAI (que possui subcategorias) não pode ser atribuida como subcategoria", E_USER_WARNING);
            else :
                $Update->ExeUpdate(DB_CATEGORIES_PORTIFOLIO, $PostData, "WHERE category_id = :id", "id={$CatId}");
                $jSON['trigger'] = Check::ajaxErro("<b class='icon-checkmark'>TUDO CERTO: </b> A categoria <b>{$PostData['category_title']}</b> foi atualizada com sucesso!");
            endif;
            break;

        case 'category_remove':
            $PostData['category_id'] = $PostData['del_id'];
            $Read->fullRead("SELECT category_title, category_id FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent = :cat", "cat={$PostData['category_id']}");

            if ($Read->getResult()) :
                $jSON['trigger'] = Check::ajaxErro("<b class='icon-notification'>OPPSSS: </b> Olá {$_SESSION['userLogin']['user_name']}, para deletar uma categoria certifique-se que ela não tem subcategoria cadastradas!", E_USER_WARNING);
            else :
                $Read->fullRead("SELECT porti_id FROM " . DB_PORTIFOLIO . " WHERE porti_category = :cat OR FIND_IN_SET(:cat, porti_category_parent)", "cat={$PostData['category_id']}");
                if ($Read->getResult()) :
                    $jSON['trigger'] = Check::ajaxErro("<b class='icon-warning'>{$Read->getRowCount()} POST(S): </b> Olá {$_SESSION['userLogin']['user_name']}, não é possível remover categorias quando existem projetos cadastrados na mesma!", E_USER_WARNING);
                else :
                    $Delete->exeDelete(DB_CATEGORIES_PORTIFOLIO, "WHERE category_id = :cat", "cat={$PostData['category_id']}");
                    $jSON['success'] = true;
                endif;
            endif;
            break;
    endswitch;

    //RETORNA O CALLBACK
    if ($jSON) :
        echo json_encode($jSON);
    else :
        $jSON['trigger'] = Check::ajaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else :
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
