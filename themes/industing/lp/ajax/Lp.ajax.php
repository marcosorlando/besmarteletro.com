<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Helpers\Check;
use App\Models\Email;
use App\View\Template;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$jSON = null;
// DEFINE O CALLBACK E RECUPERA O POST
$CallBack = 'Lp';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack) {
    // PREPARA OS DADOS
    $Case = $PostData['callback_action'];

    if ('load_cities' != $Case) {
        $arrayData['ebook_title'] = $PostData['callback_title'];
        $arrayData['link'] = $PostData['page_destino'];
        $arrayData['ebook_mokup'] = $PostData['page_mockup'];

        $humanA = $PostData['checkHuman_a'];
        $humanB = $PostData['checkHuman_b'];
        $humanCheck = $PostData['senderHuman'];
        $human = $humanCheck == $humanA + $humanB;
        $Link = $PostData['page_destino'];

        unset($PostData['callback'], $PostData['callback_action'], $PostData['callback_title'], $PostData['callback_link'], $PostData['checkHuman_a'], $PostData['checkHuman_b'], $PostData['senderHuman'], $PostData['page_destino'], $PostData['page_mockup'], $humanA, $humanB);
    }

    // ELIMINA CÓDIGOS
    $PostData = array_map('strip_tags', $PostData);
    $Read ??= new Read();

    // SELECIONA AÇÃO
    switch ($Case) {
        // CAPTURA DE ACORDO COM CALLBACK-ACTION
        case 'load_cities':
            $jSON['cities'] = null;
            $PostData['lead_city'] = empty($PostData['lead_city']) ? null : $PostData['lead_city'];

            if (!$PostData['uf']) {
                $jSON['trigger'] = Check::ajaxErro('<b>OPPSSS:</b> Selecione um estado por favor!', E_USER_NOTICE);
            } elseif ($PostData['uf'] && !$PostData['lead_city']) {
                $Read->fullRead(
                    'SELECT id, name, uf FROM ' . DB_CITIES . ' WHERE uf = :uf ORDER BY name ASC',
                    'uf=' . $PostData['uf']
                );

                $jSON['cities'] .= "<option value='' selected disabled>Selecione a sua cidade</option>";
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $Cities) {
                        extract($Cities);
                        $jSON['cities'] .= sprintf("<option value='%s'>%s - %s</option> ", $name, $name, $uf);
                    }
                }
            }

            break;

        case 'manage':
            if (array_search('', $PostData, true)) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b>OPPSSS:</b> Por favor, preencha todos os campos.',
                    E_USER_NOTICE
                );
                $jSON['field'] = array_search('', $PostData, true);
            } elseif (
                !Check::Email($PostData['lead_email']) || !filter_var(
                    $PostData['lead_email'],
                    FILTER_VALIDATE_EMAIL
                )
            ) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b>OPPSSS:  </b>' . $PostData['lead_name'] . ' o e-mail informado não é válido.',
                    E_USER_NOTICE
                );
                $jSON['field'] = 'lead_email';
            } elseif (false == $human) {
                $jSON['trigger'] = Check::ajaxErro(
                    '<b>OPPSSS:  </b>' . $PostData['lead_name'] . ', para receber seu material, realize a soma dos números corretamente',
                    E_USER_WARNING
                );
                $jSON['field'] = 'senderHuman';
            } else {
                $Create = new Create();
                $Create->exeCreate(DB_LEADS, $PostData);
                // Prepara dados para enviar E-mail para o Lead
                $arrayData['basedir'] = BASE;
                $arrayData['path'] = INCLUDE_PATH;
                $arrayData['lead_name'] = trim(Check::getCapilalize($PostData['lead_name']));
                $arrayData['lead_email'] = trim($PostData['lead_email']);
                $arrayData['ebook_title'] = $PostData['lead_conversion'];
                $arrayData['SITE_ADDR_NAME'] = SITE_ADDR_NAME;
                $arrayData['SITE_ADDR_EMAIL'] = SITE_ADDR_EMAIL;
                $arrayData['SITE_ADDR_CITY'] = SITE_ADDR_CITY;
                $arrayData['SITE_ADDR_UF'] = SITE_ADDR_UF;
                $arrayData['SITE_ADDR_COUNTRY'] = SITE_ADDR_COUNTRY;
                $arrayData['SITE_SOCIAL_FB_PAGE'] = SITE_SOCIAL_FB_PAGE;
                $arrayData['SITE_SOCIAL_INSTAGRAM'] = SITE_SOCIAL_INSTAGRAM;
                $arrayData['SITE_SOCIAL_LINKEDIN'] = SITE_SOCIAL_LINKEDIN;
                $arrayData['SITE_SOCIAL_YOUTUBE'] = SITE_SOCIAL_YOUTUBE;

                $MailContent = Template::setTemplate(
                    Template::getTemplate('lp_mail.html', __DIR__ . '/../html/'),
                    $arrayData
                );

                $Email = new Email();
                $Email->enviarMontando(
                    'E-book: ' . $arrayData['ebook_title'],
                    $MailContent,
                    SITE_ADDR_NAME,
                    MAIL_USER,
                    $PostData['lead_name'],
                    $PostData['lead_email']
                );

                $jSON['trigger'] = Check::ajaxErro(
                    '<b>Obrigado!</b> Um link foi enviado para seu e-mail para salvar seu E-book!'
                );
                $jSON['redirect'] = $Link;
            }

            break;
    }

    // RETORNA O CALLBACK
    if ($jSON) {
        echo json_encode($jSON);
    } else {
        $jSON['trigger'] = Check::ajaxErro(
            '<b>OPSS:</b> Desculpe. Mas uma ação do sistema não respondeu corretamente. Ao persistir, contate o empresa por e-mail: ' . SITE_ADDR_EMAIL,
            E_USER_ERROR
        );
        echo json_encode($jSON);
    }
} else {
    // ACESSO DIRETO
    exit('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
}
