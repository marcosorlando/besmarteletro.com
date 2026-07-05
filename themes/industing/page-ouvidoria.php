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
         echo INCLUDE_PATH; ?>/images/bg/7.jpg) no-repeat center center; background-size: cover;">
	<div class="container">
		<div class="col-xl-12 text-center">
			<h2>Ouvidoria</h2>
			<div class="breadcrumbs">
				<a href="<?php
                echo BASE; ?>"><i class="fa fa-home"></i></a><i>|</i><span>Clique no links abaixo</span>
			</div>
		</div>
	</div>
</section>

<section class="commonSection pdb90">
	<div class="container">
		<div class="row align-content-md-stretch ouvidoria">

			<div class="col-lg-4 col-md-4">
				<a class="service-link wc_goto" href="#denuncia">
					<div id="icb-01" class="icon_box_01 text-center">
						<img class="svc-icon" src="<?php
                        echo INCLUDE_PATH; ?>/images/ouvidoria/denuncia.png" alt="Denúncia">
						<span></span>
						<h3>Denúncia</h3>
					</div>
				</a>
			</div>

			<div class="col-lg-4 col-md-4">
				<a class="service-link" href="#reclamacao">
					<div id="icb-02" class="icon_box_01 text-center">
						<img class="svc-icon" src="<?php
                        echo INCLUDE_PATH; ?>/images/ouvidoria/reclamacao.png"
						     alt="Denúncia">
						<span></span>
						<h3>Reclamação</h3>
					</div>
				</a>
			</div>

			<div class="col-lg-4 col-md-4">
				<a class="service-link" href="#sugestao">
					<div id="icb-03" class="icon_box_01 text-center">
						<img class="svc-icon" src="<?php
                        echo INCLUDE_PATH; ?>/images/ouvidoria/sugestao.png"
						     alt="Denúncia">
						<span></span>
						<h3>Sugestão</h3>
					</div>
				</a>
			</div>
		</div>

		<div class="row justify-content-lg-center padding-top-50px destiny">

			<div id="denuncia" class="none col-md-8">
				<div class="row">
					<div class="col-xl-12 text-center">
						<h6 class="sub_title dark_sub_title">PARA SE FAZER UMA DENÚNCIA</h6>
						<h2 class="mb45">
							<span>Preencha o Formulário</span>
						</h2>
					</div>
				</div>

				<div class="cotactForm light_form">
					<form class="row form_capitalize j_formsubmit" method="post" action="javascript:void(0)"
					      enctype="multipart/form-data">

						<input type="hidden" class="callback" name="callback" value="Ouvidoria">
						<input type="hidden" class="callback-action" name="callback_action" value="manager">

						<div class="callback_return trigger_ajax col-md-12"></div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="first_name" id="first_name"
								       placeholder="NOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="last_name" id="last_name"
								       placeholder="SOBRENOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input class="required" type="email" name="email" id="email"
								       placeholder="E-MAIL DE CONTATO" required/>
								<i class="fal fa-envelope"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<select name="sector" id="sector" required>
									<option selected value="DENÚNCIA">DENÚNCIA</option>
								</select>
								<i class="fal fa-building"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="iconInput">
                                <textarea class="required" name="complaint"
                                          placeholder="ESCREVA AQUI SUA DENÚNCIA..." required></textarea>
								<i class="fal fa-pencil-alt"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="">
								<label for="privacyD" id='field-privacy' class="label_check">
									<input type="checkbox" id="privacyD" name="privacy" required>
									<span> <b class="text-red font_medium">*</b>Consinto com a <i class='fake-link'
									                                                              data-toggle='modal'
									                                                              data-target='#myModal'>Política de Privacidade.</i><small> Ao enviar meus dados, eu autorizo o uso dos mesmos para receber o retorno da minha denúncia.</small>
                                    </span>
								</label>
							</div>
						</div>

						<div class="col-xl-12 text-center">
							<button class="btn btn-outline-dark">
								<img src="<?php
                                echo INCLUDE_PATH; ?>/images/icons/load_white.gif" title="Enviando..."
								     class="none form_load">
								<i class="fa fa-envelope-open-text fa-1x"></i> ENVIAR DENÚNCIA
							</button>
						</div>
					</form>
				</div>
			</div>

			<div id="reclamacao" class="none col-md-8">
				<div class="row">
					<div class="col-xl-12 text-center">
						<h6 class="sub_title dark_sub_title">PARA SE FAZER UMA RECLAMAÇÃO</h6>
						<h2 class="mb45">
							<span>Preencha o Formulário</span>
						</h2>
					</div>
				</div>

				<div class="cotactForm light_form">
					<form class="row form_capitalize j_formsubmit" method="post" action="javascript:void(0)"
					      enctype="multipart/form-data">

						<input type="hidden" class="callback" name="callback" value="Ouvidoria">
						<input type="hidden" class="callback-action" name="callback_action" value="manager">

						<div class="callback_return trigger_ajax col-md-12"></div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="first_name" id="first_name"
								       placeholder="NOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="last_name" id="last_name"
								       placeholder="SOBRENOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input class="required" type="email" name="email" id="email"
								       placeholder="E-MAIL DE CONTATO" required/>
								<i class="fal fa-envelope"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<select name="sector" id="sector" required>
									<option selected value='RECLAMAÇÃO'>RECLAMAÇÃO</option>
								</select>
								<i class="fal fa-building"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="iconInput">
                                <textarea class="required" name="complaint"
                                          placeholder="ESCREVA AQUI SUA RECLAMAÇÃO..." required></textarea>
								<i class="fal fa-pencil-alt"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="">
								<label for="privacyR" id='field-privacy' class="label_check">
									<input type="checkbox" id="privacyR" name="privacy" required>
									<span> <b class="text-red font_medium">*</b>Consinto com a <i class='fake-link'
									                                                              data-toggle='modal'
									                                                              data-target='#myModal'>Política de Privacidade.</i><small> Ao enviar meus dados, eu autorizo o uso dos mesmos para receber o retorno da minha reclamação.</small>
                                    </span>
								</label>
							</div>
						</div>

						<div class="col-xl-12 text-center">
							<button class="btn btn-outline-dark">
								<img src="<?php
                                echo INCLUDE_PATH; ?>/images/icons/load_white.gif" title="Enviando..."
								     class="none form_load">
								<i class="fa fa-envelope-open-text fa-1x"></i> ENVIAR RECLAMAÇÃO
							</button>
						</div>
					</form>
				</div>
			</div>

			<div id="sugestao" class="none col-md-8">
				<div class="row">
					<div class="col-xl-12 text-center">
						<h6 class="sub_title dark_sub_title">PARA SE FAZER UMA SUGESTÃO</h6>
						<h2 class="mb45">
							<span>Preencha o Formulário</span>
						</h2>
					</div>
				</div>

				<div class="cotactForm light_form">
					<form class="row form_capitalize j_formsubmit" method="post" action="javascript:void(0)"
					      enctype="multipart/form-data">

						<input type="hidden" class="callback" name="callback" value="Ouvidoria">
						<input type="hidden" class="callback-action" name="callback_action" value="manager">

						<div class="callback_return trigger_ajax col-md-12"></div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="first_name" id="first_name"
								       placeholder="NOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>
						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input type="text" name="last_name" id="last_name"
								       placeholder="SOBRENOME (Opcional)"/>
								<i class="fal fa-user"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<input class="required" type="email" name="email" id="email"
								       placeholder="E-MAIL DE CONTATO" required/>
								<i class="fal fa-envelope"></i>
							</div>
						</div>

						<div class="col-xl-6 col-lg-6 col-md-6">
							<div class="iconInput">
								<select name="sector" id="sector" required>
									<option selected value="SUGESTÃO">SUGESTÃO</option>
								</select>
								<i class="fal fa-building"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="iconInput">
                                <textarea class="required" name="complaint"
                                          placeholder="ESCREVA AQUI SUA SUGESTÃO..." required></textarea>
								<i class="fal fa-pencil-alt"></i>
							</div>
						</div>

						<div class="col-xl-12">
							<div class="">
								<label for="privacyS" id='field-privacy' class="label_check">
									<input type="checkbox" id="privacyS" name="privacy" required>
									<span> <b class="text-red font_medium">*</b>Consinto com a <i class='fake-link'
									                                                              data-toggle='modal'
									                                                              data-target='#myModal'>Política de Privacidade.</i><small> Ao enviar meus dados, eu autorizo o uso dos mesmos para receber o retorno da minha sugestão.</small>
                                    </span>
								</label>
							</div>
						</div>

						<div class="col-xl-12 text-center">
							<button class="btn btn-outline-dark">
								<img src="<?php
                                echo INCLUDE_PATH; ?>/images/icons/load_white.gif" title="Enviando..."
								     class="none form_load">
								<i class="fa fa-envelope-open-text fa-1x"></i> ENVIAR SUGESTÃO
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="<?php
echo BASE; ?>/_cdn/text.control.min.js"></script>

<div class='modal' id='myModal' style='align-items: center; justify-content: center;'>
	<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
            <?php
            $Read = new Read();
            $page = $Read->LinkResult(
                DB_PAGES,
                'page_name',
                'politica-de-protecao-de-dados',
                'page_title, page_content'
            );
            ?>
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title"><?php
                    echo $page['page_title']; ?></h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
                <?php
                echo $page['page_content']; ?>
			</div>
			<!-- Modal footer -->
			<div class="modal-footer">
				<label for="privacy" class="btn btn-danger" data-dismiss="modal">
					<i class="fa
                fa-window-close"></i> Fechar</label>
			</div>
		</div>
	</div>
</div>
