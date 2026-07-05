<?php

use App\Conn\Create;
use App\Helpers\Check;
use App\Models\Email;
use App\View\Template;

require_once __DIR__.'/../../../vendor/autoload.php';

$jSON = null;
$CallBack = 'Ouvidoria';
$PostData = \filter_input_array(INPUT_POST, FILTER_DEFAULT);

// VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] = $CallBack) {
    // PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // ELIMINA CÓDIGOS
    $PostData = \array_map('strip_tags', $PostData);

    if ('manager' === $Case) {
        if (
            \array_search('', $PostData, true)
            && 'first_name' != \array_search('', $PostData, true)
            && 'last_name' != \array_search('', $PostData, true)
        ) {
            $jSON['trigger'] = Check::ajaxErro(
                '<b class="fal fa-info-circle"> OOPS! </b> Preencha todos os campos obrigatórios e Envie novamente!',
                E_USER_NOTICE
            );
        } elseif (!Check::Email($PostData['email']) || !\filter_var($PostData['email'], FILTER_VALIDATE_EMAIL)) {
            $jSON['trigger'] = Check::ajaxErro(
                '<b class="fal fa-envelope"> OOPS! </b> E-mail informado não é válido!',
                E_USER_ERROR
            );
        } else {
            $PostData['first_name'] = Check::getCapilalize($PostData['first_name']);
            $PostData['last_name'] = Check::getCapilalize($PostData['last_name']);
            $PostData['email'] = \mb_strtolower((string) $PostData['email']);
            $PostData['complaint'] = $PostData['complaint'];
            $PostData['privacy'] = '' !== $PostData['privacy'] && '0' !== $PostData['privacy'] ? 1 : 0;
            $PostData['status'] = 1;

            // REGISTRA NO BANCO DE DADOS
            $Create = new Create();
            $Create->exeCreate(DB_OUVIDORIA, $PostData);

            if ($Create->getResult()) {
                $jSON['trigger'] = Check::ajaxErro('<b> Registro enviado com sucesso!</b>');
            }

            $PostData['full_name'] = empty($PostData['first_name']) ? 'ANÔNIMO(A)' : \sprintf(
                '%s %s',
                $PostData['first_name'],
                $PostData['last_name']
            );

            // ENVIA E-MAILS PARA 2 PONTAS
            $arrayData = $PostData;
            $arrayData['INCLUDE_PATH'] = INCLUDE_PATH;
            $arrayData['SITE_NAME'] = SITE_NAME;
            $arrayData['SITE_ADDR_NAME'] = SITE_ADDR_NAME;
            $arrayData['SITE_ADDR_PHONE_A'] = SITE_ADDR_PHONE_A;
            $arrayData['SITE_ADDR_SITE'] = SITE_ADDR_SITE;
            $arrayData['BASE'] = BASE;
            $arrayData['saudacao'] = Check::Salutation();

            $arrayData['SITE_ADDR_EMAIL'] = 'DENÚNCIA' == $PostData['sector'] ? 'denuncia@seu-dominio.com.br' : 'ouvidoria@seu-dominio.com.br';

            $MailContent = Template::setTemplate(
                Template::getTemplate(
                    'ouvidoria_mail.html',
                    __DIR__.'/../templates/'
                ),
                $arrayData
            );

            $Email = new Email();
            // $Email->addFile($anexo);
            $Email->enviarMontando(
                \sprintf('Nova %s recebida', $PostData['sector']),
                $MailContent,
                $PostData['full_name'],
                $PostData['email'],
                SITE_ADDR_NAME,
                MAIL_TESTER// $arrayData['SITE_ADDR_EMAIL']
            );

            if (!$Email->getError()) {
                $MailConfirmation = Template::setTemplate(
                    Template::getTemplate(
                        'ouvidoria_return_mail.html',
                        __DIR__.'/../templates/'
                    ),
                    $arrayData
                );

                $ResponseEmail = new Email();

                $ResponseEmail->enviarMontando(
                    'Confirmação de recebimento',
                    $MailConfirmation,
                    MAIL_SENDER,
                    MAIL_USER,
                    $PostData['full_name'],
                    $PostData['email']
                );
            } else {
                $jSON['trigger'] = Check::ajaxErro(
                    \sprintf(
                        'Desculpe, não foi possível enviar sua %s. Entre em contato via E-mail: ',
                        $PostData['sector']
                    ).$arrayData['SITE_ADDR_EMAIL'],
                    E_USER_ERROR
                );
            }
        }
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
