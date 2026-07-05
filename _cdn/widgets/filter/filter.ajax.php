<?php

    use App\Conn\Read;

    \session_start();

    $getPost = \filter_input_array(INPUT_POST);

    if (empty($getPost) || empty($getPost['action'])) {
        exit('Acesso Negado!');
    }

    $getPost['url'] = \explode('/', $getPost['url']);
    $getPost['file'] = $getPost['url'][0];
    $getPost['history'] = $getPost['url'][1];

    $Action = $getPost['action'];
    $jSON = null;
    $jSON['redirect'] = $getPost['url'][0] . '/' . $getPost['url'][1];
    unset($getPost['url'], $getPost['action']);

    $_SESSION['filter']['history'] = $getPost['history'];

    \usleep(2000);

    require __DIR__ . '/../../../vendor/autoload.php';
    $Read = new Read();

    switch ($Action) {
        case 'filter_add':
            /* reseta o filtro */
            if (!empty($_SESSION['filter']['add'])) {
                unset($_SESSION['filter']['add']);
            }

            /* department */
            if (!empty($getPost['pdt_department'])) {
                $_SESSION['filter']['add']['pdt_department'] = \implode(',', $getPost['pdt_department']);
            }

            break;

        case 'filter_access':
            if (!empty($_SESSION['filter']['access'])) {
                unset($_SESSION['filter']['access']);
            } else {
                $_SESSION['filter']['access'] = true;
            }

            break;
    }

    echo \json_encode($jSON);
