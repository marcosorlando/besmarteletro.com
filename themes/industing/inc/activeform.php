<form name="lead_capture" class="j_formsubmit newsletter-form style-2" action="" method="post"
      enctype="multipart/form-data">
    <div class="position-relative">
        <div class="callback_return trigger_ajax"></div>
        <input type="hidden" name="callback" value="Leads"/>
        <input type="hidden" name="callback_action" value="<?php echo empty($CAPTION) ? 'news1' : $CAPTION; ?>"/>
        <input type="text" name="name" class="newsletter-input" placeholder="Digite seu nome..." required/>
        <input type="email" name="email" class="newsletter-input" placeholder="Digite seu email..." required/>
        <!--    <button type="submit" class="bg-transparent text-large btn position-absolute right-0 top-3"><i class="fa fa-envelope-o no-margin-left"></i></button>-->

        <button name="public" type="submit" class="newsletter-submit"><i class="fa fa-envelope-o no-margin-left"></i>
            <img class="form_load none" style="margin-left: 10px;" alt="Enviando Requisição!"
                 title="Enviando Requisição!" src="<?php echo INCLUDE_PATH; ?>/images/icons/load.gif"/>
        </button>
    </div>
</form>

