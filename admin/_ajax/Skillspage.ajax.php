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

    if (!APP_SKILLSPAGE || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) ||
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
    $CallBack = 'Skillspage';
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

            //DELETE
            case 'delete':

                $Read->fullRead(
                    "SELECT skill_image FROM " . DB_SKILLSPAGE . " WHERE skill_id = :id",
                    "id={$PostData['del_id']}"
                );

                if ($Read->getResult()):
                    $ImageRemove = "../../uploads/skills/{$Read->getResult()[0]['skill_image']}";

                if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                        unlink($ImageRemove);
                    endif;

                endif;

                $Delete->exeDelete(DB_SKILLSPAGE, "WHERE skill_id = :id", "id={$PostData['del_id']}");

                $jSON['success'] = true;
                break;

            case 'manager':
                $SkillId = $PostData['skill_id'];
                unset($PostData['skill_id']);

                $Read->exeRead(DB_SKILLSPAGE, "WHERE skill_id = :id", "id={$SkillId}");
                $ThisPost = $Read->getResult()[0];

                if (!empty($_FILES['skill_image'])):
                    $File = $_FILES['skill_image'];

                    if ($ThisPost['skill_image'] && file_exists(
                            "../../uploads/skills/{$ThisPost['skill_image']}"
                        ) && !is_dir(
                            "../../uploads/skills/{$ThisPost['skill_image']}"
                        )):
                        unlink("../../uploads/skills/{$ThisPost['skill_image']}");
                    endif;

                    $Upload = new Upload('../../uploads/skills/');
                    $Upload->image($File, 'skill-image-' . time(), AVATAR_W);
                    if ($Upload->getResult()):
                        $PostData['skill_image'] = $Upload->getResult();
                    else:
                        $jSON['trigger'] = Check::ajaxErro(
                            "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá {$_SESSION['userLogin']['user_name']}, selecione uma imagem PNG sem fundo para enviar!",
                            E_USER_WARNING
                        );
                        echo json_encode($jSON);

                        return;
                    endif;
                else:
                    unset($PostData['skill_image']);
                endif;

                $PostData['skill_status'] = (!empty($PostData['skill_status']) ? '1' : '0');

                $Update->ExeUpdate(DB_SKILLSPAGE, $PostData, "WHERE skill_id = :id", "id={$SkillId}");
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
