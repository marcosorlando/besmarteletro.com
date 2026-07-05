<?php

$AdminLevel = LEVEL_WC_PRODUCTS;
if (!APP_PRODUCTS || empty($DashboardLogin) || empty($Admin) || $Admin['user_level'] < $AdminLevel):
    die('<div style="text-align: center; margin: 5% 0; color: #C54550; font-size: 1.6em; font-weight: 400; background: #fff; float: left; width: 100%; padding: 30px 0;"><b>ACESSO NEGADO:</b> Você não esta logado<br>ou não tem permissão para acessar essa página!</div>');
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
    $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
    $Create = new Create;
endif;

// AUTO INSTANCE OBJECT UPDATE
if (empty($Update)):
    $Update = new Update;
endif;

$PdtId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Read->ExeRead(DB_PDT, "WHERE pdt_id = :id", "id={$PdtId}");

$ProductReply = $Read->getResult()[0];

unset($ProductReply['pdt_id'], $ProductReply['pdt_cover']);

$ProductReply['pdt_parent'] = $PdtId;
$ProductReply['pdt_created'] = date('Y-m-d H:i:s');
$ProductReply['pdt_inventory'] = 0;
$ProductReply['pdt_delivered'] = 0;
$ProductReply['pdt_views'] = 0;
$ProductReply['pdt_drawing'] = null;
$ProductReply['pdt_status'] = 0;

$Create->ExeCreate(DB_PDT, $ProductReply);

$PDTCRTUPDATE =
    [
        'pdt_name' => Check::Name($ProductReply['pdt_title']) . "-{$Create->getResult()}",
        'pdt_code' => str_pad($Create->getResult(), 4, 0, STR_PAD_LEFT)
    ];
$Update->ExeUpdate(DB_PDT, $PDTCRTUPDATE, "WHERE pdt_id = :id", "id={$Create->getResult()}");

header("Location: dashboard.php?wc=products/create&id={$Create->getResult()}");
exit;
