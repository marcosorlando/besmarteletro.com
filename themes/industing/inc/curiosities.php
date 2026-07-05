<?php

use App\Conn\Read;
use App\View\View;

$Read ??= new Read();
$Read->exeRead(DB_CURIOSITIES, 'WHERE cur_id = :id AND cur_status = :st', 'id=1&st=1');

if ($Read->getResult()) {
    $data = $Read->getResult()[0];

    $data['cur_cover'] = BASE.('/uploads/'.$data['cur_cover']);
    $data['cur_line_one_icon'] = BASE.('/uploads/'.$data['cur_line_one_icon']);
    $data['cur_line_two_icon'] = BASE.('/uploads/'.$data['cur_line_two_icon']);
    $data['cur_line_three_icon'] = BASE.('/uploads/'.$data['cur_line_three_icon']);
    $data['cur_line_four_icon'] = BASE.('/uploads/'.$data['cur_line_four_icon']);

    $view = new View(__DIR__.'/_tpl');

    $template = $view->load('curiosities');
    $view->show($data, $template);
} else {
    echo '';
}
