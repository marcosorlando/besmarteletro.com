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

    if (!APP_ABOUTPAGE || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) ||
        $_SESSION['userLogin']['user_level'] < $NivelAcess):
        $jSON['trigger'] = Check::ajaxErro(
            '<b class="icon-warning">OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
        die;
    endif;

    //usleep(50000);

    //DEFINE O CALLBACK E RECUPERA O POST
    $jSON = null;
    $CallBack = 'Aboutpage';
    $PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //VALIDA AÇÃO
    if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack):
        //PREPARA OS DADOS
        $Case = $PostData['callback_action'];
        unset($PostData['callback'], $PostData['callback_action']);

        $Read ??= new Read();
        $Create ??= new Create();
        $Update ??= new Update();
        $Delete ??= new Delete();

        //SELECIONA AÇÃO
        switch ($Case):

            case 'manager':
                $AboutId = $PostData['about_id'];
                unset($PostData['about_id']);

                $Read->exeRead(DB_ABOUTPAGE, "WHERE about_id = :id", "id={$AboutId}");
                $ThisPost = $Read->getResult()[0];

                if (!empty($_FILES['about_image'])):
                    $File = $_FILES['about_image'];

                    if ($ThisPost['about_image'] && file_exists("../../uploads/{$ThisPost['about_image']}") && !is_dir(
                            "../../uploads/{$ThisPost['about_image']}"
                        )):
                        unlink("../../uploads/{$ThisPost['about_image']}");
                    endif;

                    $Upload = new Upload('../../uploads/');
                    $Upload->image($File, 'about-image-' . time(), AVATAR_W);
                    if ($Upload->getResult()):
                        $PostData['about_image'] = $Upload->getResult();
                    else:
                        $jSON['trigger'] = Check::ajaxErro(
                            "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem JPG ou PNG para enviar!",
                            E_USER_WARNING
                        );
                        echo json_encode($jSON);

                        return;
                    endif;
                else:
                    unset($PostData['about_image']);
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

                $PostData['about_status'] = (!empty($PostData['about_status']) ? '1' : '0');

                $Update->ExeUpdate(DB_ABOUTPAGE, $PostData, "WHERE about_id = :id", "id={$AboutId}");
                $jSON['trigger'] = Check::ajaxErro(
                    "<b class='icon-checkmark'>TUDO CERTO: </b> A página Sobre mim do site foi atualizada com sucesso!"
                );

                break;

        endswitch;

        //RETORNA O CALLBACK
        if ($jSON):
            echo json_encode($jSON);
        else:
            $jSON['trigger'] = Check::ajaxErro(
                '<b class="icon-warning">OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
                E_USER_ERROR
            );
            echo json_encode($jSON);
        endif;
    else:
        //ACESSO DIRETO
        die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
    endif;
