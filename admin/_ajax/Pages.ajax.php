<?php

use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

session_start();

require __DIR__ . '/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_PAGES;

if (!APP_PAGES || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess) {
    $jSON['trigger'] = Check::ajaxErro(
        '<b>OPPSSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
        E_USER_ERROR
    );
    echo json_encode($jSON);

    exit;
}

usleep(50000);

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Pages';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack) {
    // PREPARA OS DADOS
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

    // SELECIONA AÇÃO
    switch ($Case) {
        // DELETE
        case 'delete':
            $Read->fullRead(
                'SELECT image FROM ' . DB_PAGES_IMAGE . ' WHERE page_id = :ps',
                'ps=' . $PostData['del_id']
            );
            if ($Read->getResult()) {
                foreach ($Read->getResult() as $PageImage) {
                    $ImageRemove = '../../uploads/' . $PageImage['image'];
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)) {
                        unlink($ImageRemove);
                    }
                }
            }

            $Delete->exeDelete(DB_PAGES, 'WHERE page_id = :id', 'id=' . $PostData['del_id']);
            $Delete->exeDelete(DB_PAGES_IMAGE, 'WHERE page_id = :id', 'id=' . $PostData['del_id']);
            $Delete->exeDelete(DB_COMMENTS, 'WHERE page_id = :id', 'id=' . $PostData['del_id']);
            $jSON['success'] = true;

            break;

        // MANAGER
        case 'manage':
            $PageId = $PostData['page_id'];
            unset($PostData['page_id']);

            $PostData['page_status'] = (empty($PostData['page_status']) ? '0' : '1');
            $PostData['page_order'] = (empty($PostData['page_order']) ? null : $PostData['page_order']);
            $PostData['page_name'] = (empty($PostData['page_name']) ? Check::name(
                $PostData['page_title']
            ) : Check::name($PostData['page_name']));

            $Read->exeRead(DB_PAGES, 'WHERE page_id= :id', 'id=' . $PageId);
            $ThisPage = $Read->getResult()[0];

            if (!empty($_FILES['page_cover'])) {
                $File = $_FILES['page_cover'];

                if (
                    $ThisPage['page_cover'] && file_exists('../../uploads/' . $ThisPage['page_cover']) && !is_dir(
                        '../../uploads/' . $ThisPage['page_cover']
                    )
                ) {
                    unlink('../../uploads/' . $ThisPage['page_cover']);
                }

                $Upload = new Upload('../../uploads/');
                $Upload->image($File, $PostData['page_name'] . '-' . time(), IMAGE_W, 'pages');
                if ($Upload->getResult()) {
                    $PostData['page_cover'] = $Upload->getResult();
                } else {
                    $jSON['trigger'] = Check::ajaxErro(
                        sprintf(
                            "<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá %s, selecione uma imagem JPG ou PNG para enviar como capa!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                    echo json_encode($jSON);

                    return;
                }
            } else {
                unset($PostData['page_cover']);
            }

            $Read->fullRead(
                'SELECT page_name FROM ' . DB_PAGES . ' WHERE page_name = :nm AND page_id != :id',
                sprintf('nm=%s&id=%s', $PostData['page_name'], $PageId)
            );
            if ($Read->getResult()) {
                $PostData['page_name'] = sprintf('%s-%s', $PostData['page_name'], $PageId);
            }

            $jSON['name'] = $PostData['page_name'];
            $jSON['view'] = BASE . ('/' . $PostData['page_name']);

            $Update->exeUpdate(DB_PAGES, $PostData, 'WHERE page_id = :id', 'id=' . $PageId);
            $jSON['trigger'] = Check::ajaxErro('Página atualizada com sucesso!');

            break;

        // PAGE IMAGE
        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->fullRead(
                'SELECT page_title, page_name FROM ' . DB_PAGES . ' WHERE page_id = :id',
                'id=' . $PostData['page_id']
            );
            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    sprintf(
                        "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe %s, mas não foi possível identificara página vinculado!",
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Upload = new Upload('../../uploads/');
                $Upload->image($NewImage, $PostData['page_id'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()) {
                    $PostData['image'] = $Upload->getResult();
                    $Create->exeCreate(DB_PAGES_IMAGE, $PostData);
                    $jSON['tinyMCE'] = sprintf(
                        "<img title='%s' alt='%s' src='../uploads/%s'/>",
                        $Read->getResult()[0]['page_title'],
                        $Read->getResult()[0]['page_title'],
                        $PostData['image']
                    );
                } else {
                    $jSON['trigger'] = Check::ajaxErro(
                        sprintf(
                            "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá %s, selecione uma imagem JPG ou PNG para inserir na página!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                }
            }

            break;

        case 'sendvideo':
            $newVideo = $_FILES['video'];
            $Read->fullRead(
                'SELECT page_title, page_name FROM ' . DB_PAGES . ' WHERE page_id = :id',
                'id=' . $PostData['page_id']
            );

            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    "<b class='icon-video-camera'>ERRO AO ENVIAR VÍDEO:</b> Desculpe, mas não foi possível identificar a página vinculada!",
                    E_USER_WARNING
                );
            } else {
                $Upload = new Upload('../../uploads/');
                $Upload->media(
                    $newVideo,
                    Check::name($newVideo['name']) . '-' . time()
                );

                if ($Upload->getResult()) {
                    $PostData['video'] = $Upload->getResult();

                    $Create->exeCreate('ws_pages_videos', $PostData);

                    $jSON['tinyMCEvideo'] = sprintf(
                        "<video controls autoplay width='100%%' height='auto'><source src='%s/uploads/%s' type='%s'></video>",
                        BASE,
                        $PostData['video'],
                        $newVideo['type']
                    );
                } else {
                    $jSON['trigger'] = Check::ajaxErro(
                        sprintf(
                            "<b class='icon-video-camera'>ERRO AO ENVIAR VÍDEO:</b> Olá %s, selecione uma vídeo (.mp4 ou .webm) para inserir.",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                }
            }

            break;

        case 'pages_order':
            if (is_array($PostData['Data'])) {
                foreach ($PostData['Data'] as $RE) {
                    $UpdateCourse = ['page_order' => $RE[1]];
                    $Update->exeUpdate(DB_PAGES, $UpdateCourse, 'WHERE page_id = :page', 'page=' . $RE[0]);
                }

                $jSON['sucess'] = true;
            }

            break;
    }

    // RETORNA O CALLBACK
    if ($jSON) {
        echo json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
            E_USER_ERROR
        );
        echo json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
