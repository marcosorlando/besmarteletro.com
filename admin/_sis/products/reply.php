<?php

use App\Conn\Create;
use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;


$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PDT || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel) {
    exit('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
}

// AUTO INSTANCE OBJECT READ
if (empty($Read)) {
    $Read ??= new Read();
}

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)) {
    $Create ??= new Create();
}

// AUTO INSTANCE OBJECT UPDATE
if (empty($Update)) {
    $Update ??= new Update();
}

$PdtId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($PdtId) {
    $Read->exeRead(DB_PDT, "WHERE pdt_id = :id", "id={$PdtId}");
}

if (!$PdtId || !$Read->getResult()) {
    $_SESSION['trigger_controll'] = "<b>OPPSS {$Admin['user_name']}</b>, você tentou criar a variação de um produto que não existe ou que foi removido recentemente!";
    header('Location: dashboard.php?wc=products/home');
    exit;
}

$ProductReply = $Read->getResult()[0];

unset($ProductReply['pdt_id'], $ProductReply['pdt_cover']);

$ProductReply['pdt_parent'] = $PdtId;
$ProductReply['pdt_created'] = date('Y-m-d H:i:s');
$ProductReply['pdt_inventory'] = 0;
$ProductReply['pdt_delivered'] = 0;
$ProductReply['pdt_views'] = 0;
$ProductReply['pdt_drawing'] = null;
$ProductReply['pdt_status'] = 0;

$Create->exeCreate(DB_PDT, $ProductReply);

$PDTCRTUPDATE =
    [
        'pdt_name' => Check::name($ProductReply['pdt_title']) . "-{$Create->getResult()}",
        'pdt_code' => str_pad((string)$Create->getResult(), 4, '0', STR_PAD_LEFT)
    ];
$Update->exeUpdate(DB_PDT, $PDTCRTUPDATE, "WHERE pdt_id = :id", "id={$Create->getResult()}");

header("Location: dashboard.php?wc=products/create&id={$Create->getResult()}");
exit;
