<?php

use App\Conn\Read;
use App\Helpers\Arr;
use App\Helpers\Check;
use App\View\Template;

$Read = new Read();
$Read->exeRead(DB_CARD_USER, 'WHERE carduser_url = :nm AND carduser_status = :st ', \sprintf('nm=%s&st=1', $URL[0]));

if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}

$arrayData = $Read->getResult()[0];

$root = "<style> 
					@import url('".BASE."/_cdn/bootcss/fonticon.css');

					:root {
						--page-bg-color: #06173A;			
						--page-subtitle-color: #fff;
						--page-complement-color: #BF0712;
						--page-footer-color: #fff;
						--page-btn-text-color: #fff;
						--page-btn-bg-color: transparent;
						--page-btn-bg-color-hover: green;
					}
				</style>";

$arrayData['background'] = INCLUDE_PATH.'/images/bg/linktree-bg.png';
$arrayData['page_logo'] = INCLUDE_PATH.'/images/logo-color.png';

$arrayData['carduser_thumb'] = (empty($arrayData['carduser_thumb']) ? '' : "<img class='page_cover' src='".BASE
    .\sprintf("/uploads/linktree/%s' alt='' />", $arrayData['carduser_thumb']));

$arrayData['carduser_email'] = \sprintf(
    "<a target='_blank' title='Enviar e-mail para %s' href='mailto:%s'><i class='icon-envelop'></i>%s</a>",
    $arrayData['carduser_name'],
    $arrayData['carduser_email'],
    $arrayData['carduser_email']
);

$arrayData['carduser_whatsapp'] = Check::cardButton(
    Check::whatsMessage($arrayData['carduser_phone'], ''),
    \sprintf('Clique para enviar uma mensagem ao %s.', $arrayData['carduser_name']),
    'icon-whatsapp',
    'Enviar Mensagem no Whatsapp'
);

$arrayData['website'] = Check::cardButton(BASE, 'Visite nosso Site', 'icon-earth', 'Visite nosso Site');
$arrayData['sobre'] = Check::cardButton(
    '',
    'Clique para conhecer a Travi',
    'icon-newspaper',
    'Conheça a Industing'
);
$arrayData['cotacao'] = Check::cardButton(
    '',
    'Clique para receber uma cotação',
    'icon-calculator',
    'Solicitar Orçamento'
);
$arrayData['catalogo'] = Check::cardButton(
    '',
    'Baixar Catálogo Completo',
    'icon-book',
    'Catálogo em PDF'
);
$arrayData['map'] = Check::cardButton(
    'https://maps.app.goo.gl/1rL3d2xv6b1GE6hK9',
    'Clique para ver a rota',
    'icon-location',
    'Como chegar?'
);

$socialMedia['instagram'] = SITE_SOCIAL_INSTAGRAM !== '' ? "<li><a target='_blank' href='https://instagram.com/"
    .SITE_SOCIAL_INSTAGRAM
    ."'><i class='icon-instagram'></i></a></li>" : '';
$socialMedia['facebook'] = SITE_SOCIAL_FB_PAGE !== '' ? "<li><a target='_blank' href='https://facebook.com/".SITE_SOCIAL_FB_PAGE
    ."'><i class='icon-facebook'></i></a></li>" : '';
$socialMedia['linkedin'] = SITE_SOCIAL_LINKEDIN !== '' ? "<li><a target='_blank' href='https://linkedin.com/company/"
    .SITE_SOCIAL_LINKEDIN
    ."'><i class='icon-linkedin'></i></a></li>" : '';
$socialMedia['youtube'] = SITE_SOCIAL_YOUTUBE !== '' ? "<li><a target='_blank' href='https://youtube.com/@".SITE_SOCIAL_YOUTUBE
    ."'><i class='icon-youtube'></i></a></li>" : '';

// $arrayData['social_media'] = implode(' ', (array)$socialMedia);
$arrayData['social_media'] = Arr::join(' ', $socialMedia);

$arrayData['copyright'] = \date('Y').' - '.SITE_ADDR_NAME;
$arrayData['page_event'] = 'Lead';
$arrayData['alt_logo'] = 'Logotipo da '.SITE_ADDR_NAME;

echo $root;

echo Template::setTemplate(
    Template::getTemplate('card1.html', __DIR__.'/_app_capture/templates/'),
    $arrayData
);
