<!-- REVOLUTION SLIDER -->
<?php
$Read = new Read();
$Read->fullRead(
    'SELECT * FROM '.DB_SLIDES.' WHERE slide_status = 1 AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL) ORDER BY slide_date DESC'
);
if (!$Read->getResult()) {
    ?>
    <header>
        <div class="rev_slider_wrapper">
            <div class="rev_slider" id="slider1" data-version="5.0">
                <ul>
                    <!-- SLIDE -->
                    <li data-fstransition="fade"
                        data-transition="parallaxtoright"
                        data-slotamount="1"
                        data-masterspeed="1000"
                        data-delay="8000"
                        data-title="Próxima">

                        <!-- MAIN IMAGE -->
                        <img src="<?php echo INCLUDE_PATH; ?>/images/revolution/global-suprimentos-banner-1920x700.jpg"
                             alt="Global Suprimentos Industriais"
                             title="Global Suprimentos Industriais"
                             data-bgrepeat="no-repeat"
                             data-bgfit="cover"
                             data-bgposition="center center"
                             data-bgparallax="7"
                             class="rev-slidebg full-banner"
                        >
                        <img src="<?php echo INCLUDE_PATH; ?>/images/revolution/global-suprimentos-banner-640x900.jpg"
                             alt="Global Suprimentos Industriais"
                             title="Global Suprimentos Industriais"
                             data-bgrepeat="no-repeat"
                             data-bgfit="cover"
                             data-bgposition="center center"
                             data-bgparallax="7"
                             class="rev-slidebg mobile-banner"
                        >

                        <!-- LAYER NR. 1 -->
                        <div class="tp-caption hero-text size-70"
                             data-x="center"
                             data-y="center"
                             data-voffset="['-70', '-60', '-60', '-73']"
                             data-fontsize="['70', '65', '50', '40']"
                             data-whitespace="[nowrap, nowrap, nowrap, normal]"
                             data-lineheight="['70', '65', '50', '40']"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1000"
                             data-elementdelay="0.01"
                             style="z-index: 4; text-align: center;">Embalagens Global
                        </div>

                        <!-- LAYER NR. 2 -->
                        <div class="tp-caption medium-text"
                             data-x="center"
                             data-y="center"
                             data-voffset="0"
                             data-fontsize="['24', '24', '24', '20']"
                             data-lineheight="['24', '24', '24', '20']"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1200"
                             data-elementdelay="0.01"
                             style="z-index: 4;">Global Suprimentos Industriais
                        </div>

                        <div class="tp-caption medium-text"
                             data-x="center"
                             data-y="450"
                             data-voffset="0"
                             data-fontsize="['24', '24', '24', '20']"
                             data-lineheight="['24', '24', '24', '20']"
                             data-transform_idle="o:1;s:500"
                             data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                             data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                             data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                             data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                             data-start="1200"
                             data-elementdelay="0.01"
                        >
                            <a href='<?php echo BASE; ?>/portfolio' class='btn btn-white btn-lg' target='_blank'
                               title='Conheça nosso mix de suprimentos'>Ver Suprimentos</a>
                            <a href='<?php echo BASE; ?>/ferramentas' class='btn btn-lg'
                               title='Conheça nossa gama de ferramentas industriais para embalagens.'>&nbsp;&nbsp;Ver
                                Ferramentas&nbsp;&nbsp;</a>
                        </div>

                    </li>
                </ul>
            </div>
        </div>
    </header>
    <?php
} else {
    $baseDir = BASE;
    ?>
    <header>
        <div class="rev_slider_wrapper">
            <div class="rev_slider" id="slider1" data-version="5.0">
                <ul>
                    <?php
                    foreach ($Read->getResult() as $Slide) {
                        \extract($Slide);
                        ?>
                        <!-- SLIDE -->
                        <li data-fstransition="fade"
                            data-transition="parallaxtoright"
                            data-slotamount="1"
                            data-masterspeed="1000"
                            data-delay="8000"
                            data-title="Próxima"
                        >


                            <!-- MAIN IMAGE -->
                            <img src="<?php echo \sprintf('%s/uploads/%s', $baseDir, $slide_image); ?>"
                                 alt="<?php echo $slide_desc; ?>"
                                 title="<?php echo $slide_desc; ?>"
                                 data-bgrepeat="no-repeat"
                                 data-bgfit="cover"
                                 data-bgposition="center center"
                                 data-bgparallax="7"
                                 class="rev-slidebg full-banner"
                            >

                            <!--                                    <img src="-->
                            <?//= "{$baseDir}/uploads/{$mobile_image}"; ?><!--"-->
                            <!--                                         alt="--><?//= $slide_desc; ?><!--"-->
                            <!--                                         title="--><?//= $slide_desc; ?><!--"-->
                            <!--                                         data-bgrepeat="no-repeat"-->
                            <!--                                         data-bgfit="cover"-->
                            <!--                                         data-bgposition="center center"-->
                            <!--                                         data-bgparallax="7"-->
                            <!--                                         class="rev-slidebg mobile-banner"-->
                            <!--                                    >-->

                            <!-- LAYER NR. 1 -->
                            <h2 class="tp-caption hero-text size-70"
                                data-x="center"
                                data-y="center"
                                data-voffset="['-70', '-60', '-60', '-73']"
                                data-fontsize="['70', '65', '50', '40', '40','40','40','40','40','40','40']"
                                data-whitespace="[nowrap, nowrap, nowrap, normal]"
                                data-lineheight="['70', '65', '50', '40','40','40','40','40','40','40','40']"
                                data-transform_idle="o:1;s:500"
                                data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                                data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                                data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                                data-start="1000"
                                data-elementdelay="0.01"
                                style="z-index: 4; text-align: center;"><?php echo $slide_title; ?>
                            </h2>

                            <!-- LAYER NR. 2 -->
                            <div class="tp-caption medium-text"
                                 data-x="center"
                                 data-y="center"
                                 data-voffset="0"
                                 data-fontsize="['24', '24', '24', '20','40','40','40','40','40','40','40']"
                                 data-lineheight="['24', '24', '24', '20','40','40','40','40','40','40','40']"
                                 data-transform_idle="o:1;s:500"
                                 data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                                 data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                                 data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                 data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                                 data-width="[1920,1600,1360,1280,1080,900,800,768,600,480,320]"
                                 data-start="1200"
                                 data-elementdelay="0.01"
                                 style="z-index: 4;">
                                <h3>
                                    <?php echo $slide_desc; ?>
                                </h3>
                            </div>

                            <div class="tp-caption tp-bottom"
                                 data-x="center"
                                 data-y="450"
                                 data-voffset="0"
                                 data-fontsize="['24', '24', '24', '20']"
                                 data-lineheight="['24', '24', '24', '20']"
                                 data-transform_idle="o:1;s:500"
                                 data-transform_in="y:100;scaleX:1;scaleY:1;opacity:0;"
                                 data-transform_out="opacity:0;s:1000;e:Power3.easeInOut;"
                                 data-mask_in="x:0px;y:0px;s:inherit;e:inherit;"
                                 data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;"
                                 data-width="[1920,1600,1360,1280,1080,900,800,768,600,480,320]"
                                 data-start="1400"
                                 data-elementdelay="0.01">

                                <?php echo 1 == $slide_purchase ? \sprintf(
                                    "<a href='%s/cotacao' class='btn btn-white btn-lg animated fadeInLeft' target='_blank' title='Solicite um orçamento agora mesmo!'>Fazer Orçamento</a>",
                                    $baseDir
                                ) : ''; ?>

                                <?php echo 1 == $slide_information ? \sprintf(
                                    "<a href='%s/produtos/produtos' class='btn btn-lg animated fadeInRight' target='_blank' title='Conheça nosso mix de suprimentos'>Ver produtos</a>",
                                    $baseDir
                                ) : ''; ?>

                            </div>
                        </li>
                        <?php
                    }
    ?>
                </ul>
            </div>
        </div>
    </header>
    <?php
}
?>
<!-- END REVOLUTION SLIDER -->
