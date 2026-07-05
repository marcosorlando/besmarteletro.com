<?php

use App\Conn\Create;
use App\Conn\Delete;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Upload;

\session_start();

require __DIR__.'/../../vendor/autoload.php';
$NivelAcess = LEVEL_WC_PRODUCTS_TRAVI;

if (!APP_PRODUCTS_TRAVI || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess) {
    $jSON['trigger'] = Check::ajaxErro(
        '<b>OPSS:</b> Você não tem permissão para essa ação ou não está logado como administrador!',
        E_USER_ERROR
    );
    echo \json_encode($jSON);

    exit;
}

\usleep(50000);

// DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'ProductsTravi';
$PostData = \filter_input_array(INPUT_POST, FILTER_DEFAULT);

// VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack) {
    // PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)) {
        $Read = new Read();
    }

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)) {
        $Create = new Create();
    }

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)) {
        $Update = new Update();
    }

    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)) {
        $Delete = new Delete();
    }

    $Upload = new Upload('../../uploads/');

    // SELECIONA AÇÃO
    switch ($Case) {
        case 'manager':
            $PdtId = $PostData['pdt_id'];
            $PostData['pdt_status'] = (empty($PostData['pdt_status']) ? '0' : '1');

            $Read->exeRead(DB_PDT_TRAVI, 'WHERE pdt_id = :id', 'id='.$PdtId);

            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>Erro ao atualizar:</b> Desculpe %s, mas não foi possível encontrar o produto. Experimente atualizar a página!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Product = $Read->getResult()[0];

                unset($PostData['pdt_id'], $PostData['pdt_cover'], $PostData['pdt_scene'], $PostData['image']);

                $PostData['pdt_name'] = (empty($PostData['pdt_name']) ? Check::name(
                    $PostData['pdt_title']
                ) : Check::name($PostData['pdt_name']));

                // COVER UPLOAD
                if (!empty($_FILES['pdt_cover'])) {
                    $File = $_FILES['pdt_cover'];

                    if (
                        $Product['pdt_cover'] && \file_exists('../../uploads/'.$Product['pdt_cover']) && !\is_dir(
                            '../../uploads/'.$Product['pdt_cover']
                        )
                    ) {
                        \unlink('../../uploads/'.$Product['pdt_cover']);
                    }

                    $Upload->image($File, $PostData['pdt_name'], 900);
                    if ($Upload->getResult()) {
                        $PostData['pdt_cover'] = $Upload->getResult();
                    } else {
                        $jSON['trigger'] = Check::ajaxErro(
                            \sprintf(
                                "<b class='icon-image'>ERRO AO ENVIAR CAPA:</b> Olá %s, selecione uma imagem JPG de 900x900px para a capa!",
                                $_SESSION['userLogin']['user_name']
                            ),
                            E_USER_WARNING
                        );
                        echo \json_encode($jSON);

                        return;
                    }
                }

                // SCENE UPLOAD
                if (!empty($_FILES['pdt_scene'])) {
                    $File = $_FILES['pdt_scene'];

                    if (
                        $Product['pdt_scene'] && \file_exists('../../uploads/'.$Product['pdt_scene']) && !\is_dir(
                            '../../uploads/'.$Product['pdt_scene']
                        )
                    ) {
                        \unlink('../../uploads/'.$Product['pdt_scene']);
                    }

                    $Upload->image($File, $PostData['pdt_name'].'-scene', 1920);
                    if ($Upload->getResult()) {
                        $PostData['pdt_scene'] = $Upload->getResult();
                    } else {
                        $jSON['trigger'] = Check::ajaxErro(
                            \sprintf(
                                "<b class='icon-image'>ERRO AO ENVIAR CENA:</b> Olá %s, selecione uma imagem JPG de 1920x1152px para a cena!",
                                $_SESSION['userLogin']['user_name']
                            ),
                            E_USER_WARNING
                        );
                        echo \json_encode($jSON);

                        return;
                    }
                }

                if (!empty($_FILES['image'])) {
                    $File = $_FILES['image'];
                    $gbFile = [];
                    $gbCount = \count($File['type']);
                    $gbKeys = \array_keys($File);
                    $gbLoop = 0;

                    for ($gb = 0; $gb < $gbCount; ++$gb) {
                        foreach ($gbKeys as $Keys) {
                            $gbFiles[$gb][$Keys] = $File[$Keys][$gb];
                        }
                    }

                    $jSON['gallery'] = null;
                    foreach ($gbFiles as $UploadFile) {
                        ++$gbLoop;
                        $Upload->image(
                            $UploadFile,
                            \sprintf('%s-%d-', $PostData['pdt_name'], $gbLoop).\time().\base64_encode(\time()),
                            1000
                        );
                        if ($Upload->getResult()) {
                            $gbCreate = [
                                'product_id' => $PdtId,
                                'image' => $Upload->getResult(),
                            ];
                            $Create->exeCreate(DB_PDT_GALLERY_TRAVI, $gbCreate);
                            $jSON['gallery'] .= \sprintf(
                                "<img rel='Products' id='%s' alt='Imagem em %s' title='Imagem em %s' src='../uploads/%s'/>",
                                $Create->getResult(),
                                $PostData['pdt_title'],
                                $PostData['pdt_title'],
                                $Upload->getResult()
                            );
                        }
                    }
                }

                if (isset($PostData['pdt_subcategory'])) {
                    $Read->fullRead(
                        'SELECT cat_parent FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_id = :id',
                        'id='.$PostData['pdt_subcategory']
                    );
                    $PostData['pdt_category'] = ($Read->getResult() ? $Read->getResult()[0]['cat_parent'] : null);
                }

                $Read->fullRead(
                    'SELECT pdt_id FROM '.DB_PDT_TRAVI.' WHERE pdt_name = :nm AND pdt_id != :id',
                    \sprintf('nm=%s&id=%s', $PostData['pdt_name'], $PdtId)
                );
                if ($Read->getResult()) {
                    $PostData['pdt_name'] = $PostData['pdt_name'].'-'.\time();
                }

                $Update->exeUpdate(DB_PDT_TRAVI, $PostData, 'WHERE pdt_id = :id', 'id='.$PdtId);
                $jSON['view'] = BASE.'/produto/'.$PostData['pdt_name'];
                $jSON['name'] = $PostData['pdt_name'];
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<span><b>PRODUTO ATUALIZADO:</b> Olá %s. O produto %s foi atualizado com sucesso!<span>',
                        $_SESSION['userLogin']['user_name'],
                        $PostData['pdt_title']
                    )
                );
            }

            break;

        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->fullRead(
                'SELECT pdt_title, pdt_name FROM '.DB_PDT_TRAVI.' WHERE pdt_id = :id',
                'id='.$PostData['pdt_id']
            );
            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe %s, mas não foi possível identificar o produto vinculado!",
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Upload = new Upload('../../uploads/');
                $Upload->image($NewImage, $Read->getResult()[0]['pdt_title'].'-'.\time(), IMAGE_W);
                if ($Upload->getResult()) {
                    $PostData['product_id'] = $PostData['pdt_id'];
                    $PostData['image'] = $Upload->getResult();
                    unset($PostData['pdt_id']);

                    $Create->exeCreate(DB_PDT_IMAGE_TRAVI, $PostData);
                    $jSON['tinyMCE'] = \sprintf(
                        "<img title='%s' alt='%s' src='../uploads/%s'/>",
                        $Read->getResult()[0]['pdt_title'],
                        $Read->getResult()[0]['pdt_title'],
                        $PostData['image']
                    );
                } else {
                    $jSON['trigger'] = Check::ajaxErro(
                        \sprintf(
                            "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá %s, selecione uma imagem JPG ou PNG para inserir no produto!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                }
            }

            break;

        case 'sendimagecat':
            $NewImage = $_FILES['image'];
            $Read->fullRead(
                'SELECT cat_title, cat_name FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_id = :id',
                'id='.$PostData['cat_id']
            );
            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Desculpe %s, mas não foi possível identificar a categoria vinculada!",
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Upload = new Upload('../../uploads/');
                $Upload->image($NewImage, $Read->getResult()[0]['cat_title'].'-'.\time(), IMAGE_W);
                if ($Upload->getResult()) {
                    $PostData['category_id'] = $PostData['cat_id'];
                    $PostData['image'] = $Upload->getResult();
                    unset($PostData['cat_id']);

                    $Create->exeCreate(DB_PDT_IMAGE_CAT_TRAVI, $PostData);
                    $jSON['tinyMCE'] = \sprintf(
                        "<img title='%s' alt='%s' src='../uploads/%s'/>",
                        $Read->getResult()[0]['cat_title'],
                        $Read->getResult()[0]['cat_title'],
                        $PostData['image']
                    );
                } else {
                    $jSON['trigger'] = Check::ajaxErro(
                        \sprintf(
                            "<b class='icon-image'>ERRO AO ENVIAR IMAGEM:</b> Olá %s, selecione uma imagem JPG ou PNG para inserir no produto!",
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                }
            }

            break;

        case 'delete':
            $PdtId = $PostData['del_id'];

            $Read->exeRead(DB_PDT_TRAVI, 'WHERE pdt_id = :id', 'id='.$PdtId);

            if (!$Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPSS:</b> Desculpe %s. Não foi possível deletar pois o produto não existe ou foi removido recentemente!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Product = $Read->getResult()[0];
                $PdtCover = '../../uploads/'.$Product['pdt_cover'];

                if (\file_exists($PdtCover) && !\is_dir($PdtCover)) {
                    \unlink($PdtCover);
                }

                $Read->exeRead(DB_PDT_IMAGE_TRAVI, 'WHERE product_id = :id', 'id='.$Product['pdt_id']);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $PdtImage) {
                        $PdtImageIs = '../../uploads/'.$PdtImage['image'];
                        if (\file_exists($PdtImageIs) && !\is_dir($PdtImageIs)) {
                            \unlink($PdtImageIs);
                        }
                    }

                    $Delete->exeDelete(DB_PDT_IMAGE_TRAVI, 'WHERE product_id = :id', 'id='.$Product['pdt_id']);
                }

                $Read->exeRead(DB_PDT_GALLERY_TRAVI, 'WHERE product_id = :id', 'id='.$Product['pdt_id']);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $PdtGB) {
                        $PdtGBImage = '../../uploads/'.$PdtGB['image'];
                        if (\file_exists($PdtGBImage) && !\is_dir($PdtGBImage)) {
                            \unlink($PdtGBImage);
                        }
                    }

                    $Delete->exeDelete(DB_PDT_GALLERY_TRAVI, 'WHERE product_id = :id', 'id='.$Product['pdt_id']);
                }

                $Delete->exeDelete(DB_PDT_TRAVI, 'WHERE pdt_id = :id', 'id='.$Product['pdt_id']);
                $Delete->exeDelete(DB_COMMENTS, 'WHERE pdt_id = :id', 'id='.$Product['pdt_id']);
                $jSON['success'] = true;
            }

            break;

        case 'gbremove':
            $Read->fullRead(
                'SELECT image FROM '.DB_PDT_GALLERY_TRAVI.' WHERE id = :id',
                'id='.$PostData['img']
            );
            if ($Read->getResult()) {
                $ImageRemove = '../../uploads/'.$Read->getResult()[0]['image'];
                if (\file_exists($ImageRemove) && !\is_dir($ImageRemove)) {
                    \unlink($ImageRemove);
                }

                $Delete->exeDelete(DB_PDT_GALLERY_TRAVI, 'WHERE id = :id', 'id='.$PostData['img']);
                $jSON['success'] = true;
            }

            break;

        case 'cat_manager':
            $CatId = $PostData['cat_id'];
            unset($PostData['cat_id']);

            $PostData['cat_name'] = Check::name($PostData['cat_title']);
            $PostData['cat_parent'] = ($PostData['cat_parent'] ? $PostData['cat_parent'] : null);

            $Read->fullRead(
                'SELECT cat_id FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_name = :cn AND cat_id != :ci',
                \sprintf('cn=%s&ci=%s', $PostData['cat_name'], $CatId)
            );

            if ($Read->getResult()) {
                $PostData['cat_name'] = $PostData['cat_name'].'-'.$CatId;
            }

            $Read->fullRead('SELECT cat_id FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_parent = :ci', 'ci='.$CatId);

            if (
                $Read->getResult(
                ) && (isset($PostData['cat_parent']) && ('' !== $PostData['cat_parent'] && '0' !== $PostData['cat_parent']))
            ) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPPSSS: </b> %s, uma categoria PAI (que possui subcategorias) não pode ser atribuida como subcategoria',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Read->fullRead(
                    'SELECT cat_parent FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_id = :id AND cat_parent != :parent',
                    \sprintf('id=%s&parent=%s', $CatId, $PostData['cat_parent'])
                );
                if ($Read->getResult()) {
                    // Contriuição do André Dorneles #1856

                    $PdtUpdate['pdt_category'] = $PostData['cat_parent'];
                    $Update->exeUpdate(
                        DB_PDT_TRAVI,
                        $PdtUpdate,
                        'WHERE pdt_category != :catpai AND pdt_subcategory = :catfilha',
                        \sprintf('catpai=%s&catfilha=%s', $PostData['cat_parent'], $CatId)
                    );
                }

                $Update->exeUpdate(DB_PDT_CATS_TRAVI, $PostData, 'WHERE cat_id = :id', 'id='.$CatId);
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>TUDO CERTO: </b> A categoria <b>%s</b> foi atualizada com sucesso!',
                        $PostData['cat_title']
                    )
                );
            }

            break;

        case 'cat_delete':
            $CatId = $PostData['del_id'];
            $Read->fullRead(
                'SELECT pdt_id FROM '.DB_PDT_TRAVI.' WHERE pdt_category = :cat OR pdt_subcategory = :cat',
                'cat='.$CatId
            );
            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPSS: </b>Desculpe %s, mas não é possível remover categorias com produtos cadastrados nela!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Read->fullRead(
                    'SELECT cat_id FROM '.DB_PDT_CATS_TRAVI.' WHERE cat_parent = :cat',
                    'cat='.$CatId
                );
                if ($Read->getResult()) {
                    $jSON['trigger'] = Check::ajaxErro(
                        \sprintf(
                            '<b>OPSS: </b>Desculpe %s, mas não é possível remover categorias com subcategorias ligadas a ela!',
                            $_SESSION['userLogin']['user_name']
                        ),
                        E_USER_WARNING
                    );
                } else {
                    $Delete->exeDelete(DB_PDT_CATS_TRAVI, 'WHERE cat_id = :cat', 'cat='.$CatId);
                    $jSON['success'] = true;
                }
            }

            break;

        case 'brand_manager':
            $BrandId = $PostData['brand_id'];
            $PostData['brand_name'] = Check::name($PostData['brand_title']);

            $Read->fullRead(
                'SELECT brand_id FROM '.DB_PDT_BRANDS_TRAVI.' WHERE brand_name = :nm AND brand_id != :id',
                \sprintf('nm=%s&id=%s', $PostData['brand_name'], $BrandId)
            );
            if ($Read->getResult()) {
                $PostData['brand_name'] = \sprintf('%s-%s', $PostData['brand_name'], $BrandId);
            }

            unset($PostData['brand_id']);
            $Update->exeUpdate(DB_PDT_BRANDS_TRAVI, $PostData, 'WHERE brand_id = :id', 'id='.$BrandId);
            $jSON['trigger'] = Check::ajaxErro(
                \sprintf(
                    '<b>TUDO CERTO:</b> A unidade de venda <b>%s</b> foi atualizada com sucesso!',
                    $PostData['brand_title']
                )
            );

            break;

        case 'brand_remove':
            $BrandId = $PostData['del_id'];
            $Read->fullRead(
                'SELECT pdt_id FROM '.DB_PDT_TRAVI.' WHERE pdt_brand = :brand',
                'brand='.$BrandId
            );
            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPSS: </b>Desculpe %s, mas não é possível remover uma unidade de venda quando existem produtos cadastrados com ela!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Delete->exeDelete(DB_PDT_BRANDS_TRAVI, 'WHERE brand_id = :brand', 'brand='.$BrandId);
                $jSON['success'] = true;
            }

            break;

        case 'pcs_manager':
            $FmtId = $PostData['pcs_id'];
            $PostData['pcs_name'] = Check::name($PostData['pcs_title']);

            $Read->fullRead(
                'SELECT pcs_id FROM '.DB_PDT_PROCESS_TRAVI.' WHERE pcs_name = :nm AND pcs_id != :id',
                \sprintf('nm=%s&id=%s', $PostData['pcs_name'], $FmtId)
            );
            if ($Read->getResult()) {
                $PostData['pcs_name'] = \sprintf('%s-%s', $PostData['pcs_name'], $FmtId);
            }

            unset($PostData['pcs_id']);
            $Update->exeUpdate(DB_PDT_PROCESS_TRAVI, $PostData, 'WHERE pcs_id = :id', 'id='.$FmtId);
            $jSON['trigger'] = Check::ajaxErro(
                \sprintf(
                    '<b>TUDO CERTO: </b> O processo de fabricação <b>%s</b> foi atualizado com sucesso!',
                    $PostData['pcs_title']
                )
            );

            break;

        case 'pcs_delete':
            $FmtId = $PostData['del_id'];
            $Read->fullRead(
                'SELECT pdt_id FROM '.DB_PDT_TRAVI.' WHERE pdt_process = :process',
                'process='.$FmtId
            );
            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPSS: </b>Desculpe %s, mas não é possível remover um processo quando existem produtos cadastrados com ele!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Delete->exeDelete(DB_PDT_PROCESS_TRAVI, 'WHERE pcs_id = :process', 'process='.$FmtId);
                $jSON['success'] = true;
            }

            break;

        case 'fmt_manager':
            $FmtId = $PostData['fmt_id'];
            $PostData['fmt_name'] = Check::name($PostData['fmt_title']);

            $Read->fullRead(
                'SELECT fmt_id FROM '.DB_PDT_FORMAT_TRAVI.' WHERE fmt_name = :nm AND fmt_id != :id',
                \sprintf('nm=%s&id=%s', $PostData['fmt_name'], $FmtId)
            );
            if ($Read->getResult()) {
                $PostData['fmt_name'] = \sprintf('%s-%s', $PostData['fmt_name'], $FmtId);
            }

            unset($PostData['fmt_id']);
            $Update->exeUpdate(DB_PDT_FORMAT_TRAVI, $PostData, 'WHERE fmt_id = :id', 'id='.$FmtId);
            $jSON['trigger'] = Check::ajaxErro(
                \sprintf(
                    '<b>TUDO CERTO: </b> O formato de produto <b>%s</b> foi atualizado com sucesso!',
                    $PostData['fmt_title']
                )
            );

            break;

        case 'fmt_delete':
            $FmtId = $PostData['del_id'];
            $Read->fullRead(
                'SELECT pdt_id FROM '.DB_PDT_TRAVI.' WHERE pdt_format = :format',
                'format='.$FmtId
            );
            if ($Read->getResult()) {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        '<b>OPSS: </b>Desculpe %s, mas não é possível remover um formato quando existem produtos cadastrados com ele!',
                        $_SESSION['userLogin']['user_name']
                    ),
                    E_USER_WARNING
                );
            } else {
                $Delete->exeDelete(DB_PDT_FORMAT_TRAVI, 'WHERE fmt_id = :process', 'process='.$FmtId);
                $jSON['success'] = true;
            }

            break;
    }

    // RETORNA O CALLBACK
    if ($jSON) {
        echo \json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o desenvolvedor!',
            E_USER_ERROR
        );
        echo \json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
