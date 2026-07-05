<?php

use App\Conn\Read;

$Read ??= new Read();

$Read->fullRead(
    'SELECT seg_title, seg_subtitle, seg_name, seg_cover FROM '.DB_SEG.' WHERE seg_status >= :st',
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
            <h1>Segmentos</h1>
        </div>
    </div>
</section>

<section class="commonSection pdb80 padding-top-50px">
    <div class="container">
        <div class="row align-items-lg-stretch align-content-lg-stretch">
            <?php
            foreach ($Read->getResult() as $Service) {
                \extract($Service);
                ?>
                <div class=" col-lg-4 col-md-6">
                    <a href="<?php echo BASE.('/segmento/'.$seg_name); ?>">
                        <div class="singleService">
                            <div class="serviceThumb">
                                <img src="<?php echo BASE.('/tim.php?src=uploads/'.$seg_cover); ?>&w=628&h=628"
                                     alt="<?php echo $seg_title; ?>" title="<?php echo $seg_title; ?>">
                            </div>
                            <div class="serviceDetails">
                                <h2><?php echo $seg_title; ?></h2>
                                <p><?php echo $seg_subtitle; ?></p>
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
