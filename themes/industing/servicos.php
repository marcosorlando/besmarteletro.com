<?php

use App\Conn\Read;

$Read ??= new Read();

$Read->fullRead(
    'SELECT svc_title, svc_subtitle, svc_name, svc_cover FROM '.DB_SVC.' WHERE svc_status >= :st',
    'st=1'
);
if (!$Read->getResult()) {
    require REQUIRE_PATH.'/404.php';

    return;
}
?>
<section class="product-banner">
    <div class="container">
        <div class="row">
            <h1>Processos</h1>
        </div>
    </div>
</section>

<section class="commonSection pdb80 padding-top-50px">
    <div class="container">
        <div class="row">
            <?php
            foreach ($Read->getResult() as $Service) {
                \extract($Service);
                ?>
                <div class="col-lg-4 col-md-6">
                    <a href="<?php echo BASE.('/servico/'.$svc_name); ?>">
                        <div class="singleService">
                            <div class="serviceThumb">
                                <img src="<?php echo BASE.('/tim.php?src=uploads/'.$svc_cover); ?>&w=628&h=628"
                                     alt="<?php echo $svc_title; ?>" title="<?php echo $svc_title; ?>">
                            </div>
                            <div class="serviceDetails">
                                <h2><?php echo $svc_title; ?></h2>
                                <p><?php echo $svc_subtitle; ?></p>

                            </div>
                        </div>
                    </a>
                </div>
                <?php
            }
?>
        </div>
    </div>
</section>
