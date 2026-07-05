<?php

use App\Conn\Read;

$Read ??= new Read();
$Read->exeRead(DB_PAGES, 'WHERE page_name = :nm AND page_status = 1', 'nm=' . $URL[0]);
if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';
    return;
}
extract($Read->getResult()[0]);

?>

<section class="page_banner page_banner_clear div_gray"
         style="background: url(<?php
         echo INCLUDE_PATH; ?>/images/bg/7.jpg) no-repeat
		         center center / cover;">
	<div class="container">
		<div class="col-xl-12 text-center">
			<h2>Trabalhe conosco</h2>
			<div class="breadcrumbs">
				<a href="<?php
                echo BASE; ?>"><i class="fa fa-home"></i></a><i>|</i><span>Envie seu currículo</span>
			</div>
		</div>
	</div>
</section>

<section class="commonSection pdb90">
	<div class="container">
		<div class="row justify-content-lg-center">
			<div class="col-md-8">
				<div class="row">
					<div class="col-xl-12 text-center">
						<h6 class="sub_title dark_sub_title ">PARA SE CANDIDATAR A UMA VAGA</h6>
						<h2 class=" mb45">
							<span>Preencha o Formulário</span>
						</h2>
					</div>
				</div>
				<div class="cotactForm light_form">
					<form id="curriculo_form" class="row form_capitalize" method="post" action="javascript:void(0)"
					      enctype="multipart/form-data">

						<input type="hidden" class="callback" name="callback" value="Leads">
						<input type="hidden" class="callback-action" name="callback_action" value="curriculum">

						<div class="callback_return trigger_ajax col-md-12"></div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="full_name" id="full_name"
								       placeholder="DIGITE SEU NOME COMPLETO" required/>
								<i class="fal fa-user"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input class="formPhone" type="text" name="phone" id="phone"
								       placeholder="DIGITE SEU NÚMERO DE CELULAR" required/>
								<i class="fal fa-mobile"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input class="required" type="email" name="email" id="email"
								       placeholder="DIGITE SEU E-MAIL DE CONTATO" required/>
								<i class="fal fa-envelope"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<select name="area" id="area" required>
									<option value="" selected disabled>SELECIONE A ÁREA:</option>
									<option value="Almoxarifado">Almoxarifado</option>
									<option value="Administrativa">Administrativa</option>
									<option value="Comercial">Comercial</option>
									<option value="Expedição">Expedição</option>
									<option value="Injeção">Injeção</option>
									<option value="Matrizaria">Matrizaria</option>
									<option value="Projetos">Projetos</option>
									<option value="RH">RH</option>
									<option value="Usinagem">Usinagem</option>
								</select>
								<i class="fal fa-building"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<label class="curriculum" for="cv_pdf" tabindex="5">
								<input type="file" name="cv_pdf" id="cv_pdf" required>
								<img src="<?php
                                echo INCLUDE_PATH; ?>/images/curriculo-pdf.svg" title="CLIQUE OU ARRASTE SEU
                                CURRÍCULO DENTRO DA ÁREA PONTILHADA"
								     alt="CLIQUE OU ARRASTA DE CURRÍCULO DENTRO DA ÁREA PONTILHADA">
							</label>
						</div>

						<div class="col-xl-12">
							<div class="">
								<label for='privacy' id='field-privacy' class='label_check'>
									<input type='checkbox' id='privacy' name='privacy' required>
									<span> <b class='text-red font_medium'>*</b>Consinto com a <i class='fake-link'
									                                                              data-toggle='modal'
									                                                              data-target='#myModal'>Política de Privacidade.</i><small> Ao enviar meus dados, eu autorizo o uso dos mesmos para receber comunicações.</small></span>
								</label>
							</div>
						</div>

						<div class="col-xl-12 text-center">
							<button class="btn btn-outline-dark">
								<img src="<?php
                                echo INCLUDE_PATH; ?>/images/icons/load_white.gif" title="Enviando..."
								     class="none form_load">
								<i class="fa fa-envelope-open-text fa-1x"></i> Enviar Currículo
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="<?= BASE; ?>/_cdn/text.control.min.js"></script>

<?php
require_once REQUIRE_PATH . '/inc/mymodal.inc.php';
?>
