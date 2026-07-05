<?php

use App\Conn\Read;
use App\View\Template;

$Read ??= new Read();
$URL[0] = isset($URL) ? $URL[0] : '';
$Read->exeRead(DB_THANKYOU_PAGES, 'WHERE page_name = :nm AND page_status = :st', 'nm=' . $URL[0] . '&st=1');

if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';

    return;
}

$arrayData = $Read->getResult()[0];

$root = "<style> :root {
			--page-bg-color:{$arrayData['page_bg_color']};			
			--page-subtitle-color:{$arrayData['page_subtitle_color']};
			--page-complement-color:{$arrayData['page_complement_color']};
			--page-footer-color:{$arrayData['page_footer_color']};
			--page-btn-text-color:{$arrayData['page_btn_text_color']};
			--page-btn-bg-color:{$arrayData['page_btn_bg_color']};
			--page-btn-bg-color-hover:{$arrayData['page_btn_bg_color_hover']};
			}
			</style>";

$arrayData['page_logo'] = $arrayData['page_logo'] ? BASE . '/uploads/thankyoupages/'
    . $arrayData['page_logo']
    : INCLUDE_PATH . '/images/logo-color.png';

$arrayData['page_cover'] = (empty($arrayData['page_cover']) ? '' : "<img class='page_cover' src='" . BASE . sprintf(
        "/uploads/thankyoupages/%s' alt='Fundo da Página' />",
        $arrayData['page_cover']
    ));

$arrayData['page_pdf'] = $arrayData['page_pdf'] ? BASE . '/uploads/thankyoupages/'
    . $arrayData['page_pdf'] : '';

$arrayData['page_btn_icon'] = '&#128210';
$arrayData['copyright'] = date('Y') . ' - ' . SITE_ADDR_NAME;
$arrayData['page_event'] = 'Lead';
$arrayData['alt_logo'] = 'Logotipo da ' . SITE_ADDR_NAME;

echo $root;
echo Template::setTemplate(
    Template::getTemplate('tp1.html', __DIR__ . '/_app_capture/templates/'),
    $arrayData
);
