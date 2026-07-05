<?php
use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

session_start();

require __DIR__.'/../../vendor/autoload.php';
$NivelAcess = 6;

if (!APP_HOMEPAGE || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) ||
    $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['trigger'] = Check::ajaxErro('<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!', E_USER_ERROR);
    echo json_encode($jSON);
    die;
endif;

//usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Homepage';
    $PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    $Read = new Read;
    $Create = new Create;
    $Update = new Update;
    $Delete = new Delete;

    //SELECIONA AÇÃO
    switch ($Case):
        //DELETE
        case 'delete':

            $Read->fullRead(
                "SELECT home_image, bg_image FROM " . DB_HOMEPAGE . " WHERE home_id = :id",
                "id={$PostData['del_id']}"
            );

            if ($Read->getResult()) {
                $imgArr = $Read->getResult()[0];
                if ($imgArr['home_image'] && file_exists("../../uploads/{$imgArr['home_image']}") && !is_dir
                    (
                        "../../uploads/{$imgArr['home_image']}"
                    )) {
                    unlink("../../uploads/{$imgArr['home_image']}");
                }
                if ($imgArr['bg_image'] && file_exists("../../uploads/{$imgArr['bg_image']}") && !is_dir
                    (
                        "../../uploads/{$imgArr['bg_image']}"
                    )) {
                    unlink("../../uploads/{$imgArr['bg_image']}");
                }
            }

            $Delete->exeDelete(DB_HOMEPAGE, "WHERE home_id = :id", "id={$PostData['del_id']}");

            $jSON['success'] = true;
            break;

        case 'manager':
            $PostId = $PostData['home_id'];
            unset($PostData['home_id']);

            $Read->exeRead(DB_HOMEPAGE, "WHERE home_id = :id", "id={$PostId}");
            $ThisPost = $Read->getResult()[0];


            if (!empty($_FILES['home_image'])):
                $File = $_FILES['home_image'];

                if ($ThisPost['home_image'] && file_exists("../../uploads/{$ThisPost['home_image']}") && !is_dir(
                        "../../uploads/{$ThisPost['home_image']}"
                    )):
                    unlink("../../uploads/{$ThisPost['home_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->image($File, 'home-image-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['home_image'] = $Upload->getResult();
                else:
                    $jSON['trigger'] = Check::ajaxErro(
                        "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar como principal!",
                        E_USER_WARNING
                    );
                    echo json_encode($jSON);

                    return;
                endif;
            else:
                unset($PostData['home_image']);
            endif;

            if (!empty($_FILES['bg_image'])):
                $File = $_FILES['bg_image'];

                if ($ThisPost['bg_image'] && file_exists("../../uploads/{$ThisPost['bg_image']}") && !is_dir(
                        "../../uploads/{$ThisPost['bg_image']}"
                    )):
                    unlink("../../uploads/{$ThisPost['bg_image']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->image($File, 'bg-image-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['bg_image'] = $Upload->getResult();
                else:
                    $jSON['trigger'] = Check::ajaxErro(
                        "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar como fundo!",
                        E_USER_WARNING
                    );
                    echo json_encode($jSON);

                    return;
                endif;
            else:
                unset($PostData['bg_image']);
            endif;

            if (!empty($_FILES['curriculum'])):
                $File = $_FILES['curriculum'];

                if ($ThisPost['curriculum'] && file_exists("../../uploads/pdf/{$ThisPost['curriculum']}") && !is_dir(
                        "../../uploads/pdf/{$ThisPost['curriculum']}"
                    )):
                    unlink("../../uploads/pdf/{$ThisPost['curriculum']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->File($File, 'curriculo-' . time(), 'pdf', 10);

                if ($Upload->getResult()):
                    $PostData['curriculum'] = $Upload->getResult();
                else:
                    $jSON['trigger'] = Check::ajaxErro(
                        "<b class='icon-image'>ERRO AO ENVIAR CV:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione um arquivo (.pdf ou .docx) com no máximo 10mb para enviar!",
                        E_USER_WARNING
                    );
                    echo json_encode($jSON);

                    return;
                endif;
            else:
                unset($PostData['curriculum']);
            endif;

            $PostData['home_status'] = (!empty($PostData['home_status']) ? '1' : '0');

            $Update->ExeUpdate(DB_HOMEPAGE, $PostData, "WHERE home_id = :id", "id={$PostId}");
            $jSON['trigger'] = Check::ajaxErro(
                "<b class='icon-checkmark'>TUDO CERTO: </b> A homepage do site foi atualizada com sucesso!"
            );

            break;

    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['trigger'] = Check::ajaxErro('<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!', E_USER_ERROR);
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
