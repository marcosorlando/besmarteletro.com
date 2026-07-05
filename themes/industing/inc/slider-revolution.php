<?php

$Read->fullRead(
    'SELECT slide_image_mobile, slide_image_tablet, slide_image_desktop, slide_title, slide_desc, slide_category, slide_product, slide_link_pdt, slide_link_pdt_btn, slide_link_cat, slide_link_cat_btn, show_title, show_desc FROM '.DB_SLIDES.' WHERE slide_status = :status AND slide_start <= NOW() AND (slide_end >= NOW() OR slide_end IS NULL) ORDER BY slide_date DESC',
    'status=1'
);

if ($Read->getResult()) {
    ?>
    <section class="slider_01">
        <div class="rev_slider_wrapper">
            <div id="rev_slider_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
                <ul>
                    <?php
                    $i = 5;
    foreach ($Read->getResult() as $SLIDE) {
        $image_desktop = $SLIDE['slide_image_desktop'];
        $image_tablet = (empty($SLIDE['slide_image_tablet']) ? $image_desktop : $SLIDE['slide_image_tablet']);
        $image_mobile = (empty($SLIDE['slide_image_mobile']) ? $image_desktop : $SLIDE['slide_image_mobile']);
        ++$i;
        ?>

                        <li data-index="rs-304<?php echo $i; ?>" data-transition="random" data-slotamount="default"
                            data-hideafterloop="0" data-hideslideonmobile="off" data-easein="Power4.easeInOut"
                            data-easeout="Power4.easeInOut" data-masterspeed="2000"
                            data-thumb="<?php echo BASE; ?>/uploads/<?php echo $image_desktop; ?>" data-rotate="0"
                            data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7"
                            data-saveperformance="off" data-title="<?php echo $SLIDE['slide_title']; ?>" data-param1=""
                            data-param2="" data-param3="" data-param4="" data-param5="" data-param6=""
                            data-param7="" data-param8="" data-param9="" data-param10="" data-description="">

                            <img src="<?php echo BASE; ?>/uploads/<?php echo $image_desktop; ?>" alt="<?php echo $SLIDE['slide_title']; ?>" title="<?php echo $SLIDE['slide_title']; ?>"
                                 data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat"
                                 data-bgparallax="10" class="rev-slidebg on-contrast-force-gray"
                                 data-no-retina/>

                            <?php
            if ($SLIDE['show_title']) {
                ?>
                                <div class="tp-caption barlow tp-resizeme rs-parallaxlevel-3 elemnt_3"
                                     data-x="['left', 'left', 'left', 'left']" data-hoffset="['0', '0',
                                                 '0', '0']" data-y="['top','middle','middle','middle']"
                                     data-voffset="['41','-40','-50','-50']" data-fontsize="['90','80',
                                                 '60','50']" data-lineheight="['100','90','60','50']"
                                     data-fontweight="600" data-letterspacing="['-3', '-3', '-2', '-1']"
                                     data-width="['700','100%','100%','100%']" data-height="['auto']"
                                     data-whitesapce="['normal', 'normal', 'normal', 'normal']"
                                     data-color="['#FFF']" data-type="text" data-responsive_offset="on"
                                     data-frames='[{"delay":1500,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"}, {"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                     data-textAlign="['left','center','center','center']"
                                     data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]"
                                     data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                     style="z-index: 8; white-space: normal;">
                                    <?php echo $SLIDE['slide_title']; ?>
                                </div>
                                <?php
            }
        if ($SLIDE['show_desc']) {
            ?>
                                <div class="tp-caption tp-resizeme textRes"
                                     data-x="['left','center','center','center']"
                                     data-hoffset="['0','0','0','0']"
                                     data-y="['middle','middle','middle','middle']"
                                     data-voffset="['55','80','30','30']"
                                     data-fontsize="['18','18','18','15']" data-fontweight="400"
                                     data-lineheight="['22','22','22','22']"
                                     data-width="['auto', '100%', '100%', '100%']" data-height="['auto']"
                                     data-whitesapce="['normal', 'normal', 'normal', 'normal']"
                                     data-color="['#FFF']" data-type="text" data-responsive_offset="on"
                                     data-frames='[{"delay":1800,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","to":"o:1;","ease":"Power4.easeInOut"}, {"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'
                                     data-textAlign="['left','center','center','center']"
                                     data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]"
                                     data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                     style="z-index: 5; word-break: break-all;  white-space: nowrap; text-transform: none;">
                                    <?php echo $SLIDE['slide_desc']; ?>
                                </div>
                                <?php
        }
        ?>

                            <div class="tp-caption tp-resizeme" data-x="['left','center','center','center']"
                                 data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']"
                                 data-voffset="['135','140','100','100']" data-fontsize="['18','18','18','18']"
                                 data-fontweight="400" data-lineheight="['22','22','22','22']" data-width="auto"
                                 data-height="none" data-whitespace="nowrap"
                                 data-color="['#FFF', '#FFF', '#FFF', '#FFF']" data-type="text"
                                 data-responsive_offset="on"
                                 data-frames='[{"delay":2100,"speed":2000,"frame":"0","from":"y:50px;opacity:0;","to":"o:1;","ease":"Power3.easeInOut"}, {"delay":"wait","speed":300,"frame":"999","to":"opacity:0;","ease":"Power3.easeInOut"}]'
                                 data-textAlign="['center','center','center','center']"
                                 data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]"
                                 data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]"
                                 style="z-index: 5; white-space: nowrap; text-transform: none;">
                                <?php
            if ($SLIDE['slide_product']) {
                echo \sprintf(
                    "<a class='ind_btn_2' href='%s' title=\"%s\"> <span style='transition: none 0s ease 0s; text-align: inherit; line-height: 12px; border-width: 0px; margin: 0px; padding: 0px; letter-spacing: 0px; font-weight: 500; font-size: 16px;'>&nbsp;&nbsp;%s&nbsp;&nbsp;</span> </a>",
                    $SLIDE['slide_link_pdt'],
                    $SLIDE['slide_link_pdt_btn'],
                    $SLIDE['slide_link_pdt_btn']
                );
            }
        if ($SLIDE['slide_category']) {
            echo \sprintf(
                "<a class='ind_btn' href='%s' title='%s'> <span style='transition: none 0s ease 0s; text-align: inherit; line-height: 12px; border-width: 0px; margin: 0px; padding: 0px; letter-spacing: 0px; font-weight: 500; font-size: 16px;'>&nbsp;&nbsp;%s&nbsp;&nbsp;</span></a>",
                $SLIDE['slide_link_cat'],
                $SLIDE['slide_link_cat_btn'],
                $SLIDE['slide_link_cat_btn']
            );
        }
        ?>
                            </div>
                        </li>
                        <?php
    } ?>

                </ul>
            </div>
        </div>
    </section>
    <?php
}
?>
