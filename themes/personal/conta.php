<?php
    
    if (!$Read) {
        $Read = new Read;
    }
    if (!ACC_MANAGER) {
        require REQUIRE_PATH . '/404.php';
    } else {
        require '_cdn/widgets/account/account.php';
    }
