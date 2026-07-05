<?php

$Read ??= new Read();
if (ACC_MANAGER === 0) {
    require REQUIRE_PATH.'/404.php';
} else {
    ?>

    <section class="product-banner">
        <div class="container">
            <div class="row">
                <h1 class="text-center">Conta do Usuário</h1>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="row">
            <?php
            require __DIR__.'/../../_cdn/widgets/account/account.php';
    ?>
        </div>
    </div>

    <?php
}
