<?php use App\Helpers\Check; ?>
<section class="footer_contact" id="contato">
	<div class="container">
		<div class="content">
			<header>
				<h1 class="title-section-white">Fale com <br>a gente</h1>
			</header>

			<div class="flex-box">
				<form name='form_contact' class='j_formsubmit' action='' method='post' enctype='multipart/form-data'>
					<div class='callback_return'></div>
					<input type='hidden' name='callback' class='callback' value='contacts'/>
					<input type='hidden' name='callback_action' class='callback_action' value='sac'/>

				<div class="row">
					<div class='form_content'>
						<label>
							<input type='text' placeholder='Nome' name='name' required/>
						</label>
						<label>
							<input type='text' placeholder='E-mail' name='email' required/>
						</label>
						<label>
							<input type='text' class='formPhone' placeholder='Telefone' name='telephone'/>
						</label>
					</div>

					<div class='form_content'>
						<label>
							<textarea name='message' rows='5' placeholder='Mensagem' required></textarea>
						</label>
					</div>

					<button class='form_submit'>Enviar
						<img class='form_load'
						     src='<?= INCLUDE_PATH; ?>/images/loading.gif'/>
					</button>
				</div>
				</form>
				<div class="falecom">
					<a class="whats" href="<?=Check::WhatsMessage(SITE_ADDR_WHATS, "Olá! Estou navegando no site Be Smart!")?>" target="_blank"><i class="icon-whatsapp"></i> <?=
							SITE_ADDR_PHONE_A?></a>
					<a class="mail" href="mailto:<?= SITE_ADDR_EMAIL?>" target="_blank"><i class="icon-envelop"></i><?=
							SITE_ADDR_EMAIL?></a>
				</div>

			</div>
			<div class="clear"></div>
		</div>
	</div>
</section>


<footer class="footer container">
	<section class="content">
		<div class="row">
			<div class='footer_logo'>
				<a href='<?= BASE; ?>' title='<?= SITE_NAME; ?>'>
					<img src='<?= INCLUDE_PATH; ?>/images/logo.svg' alt='<?= SITE_NAME; ?>' title='<?=
						SITE_NAME; ?>'/>
				</a>
			</div>
			<nav class='footer_links box'>
				<ul>

					<li>
						<a href='<?= isset($URL[1]) ? BASE . '/#quem-somos' :	'#quem-somos';
						?>' class='wc_goto' title='Quem Somos'>
							Quem
							<br>Somos</a>
					</li>
					<li>
						<a href='<?= BASE ?>/produtos/produtos' title='Produtos'>Produtos</a>
					</li>

					<li>
						<a href='<?= isset($URL[1]) ? BASE . '/#contato' :	'#contato';
						?>' class='wc_goto' title='Entre em Contato'>Fale com
							<br>a gente</a>
					</li>
					<li>
						<a href='<?= BASE ?>/artigos/dicas-inteligentes' title='Visite nosso Blog!'>Dicas <br>Inteligentes</a>
					</li>
					<li>
						<div class='footer_about_social'>
							<?= SITE_SOCIAL_FB_PAGE ? "<a href='https://www.facebook.com/" . SITE_SOCIAL_FB_PAGE . "' target='_blank' title='" . SITE_NAME . " no Facebook'><i class='fa fa-facebook-square'></i></a>" : ''; ?>
							<?= SITE_SOCIAL_TWITTER ? "<a href='https://www.twitter.com/" . SITE_SOCIAL_TWITTER . "' target='_blank' title='" . SITE_NAME . " no Twitter'><i class='fa fa-twitter'></i></a>" : ''; ?>
							<?= SITE_SOCIAL_INSTAGRAM ? "<a href='https://www.instagram.com/" . SITE_SOCIAL_INSTAGRAM . "' target='_blank' title='" . SITE_NAME . " no Instagram'><i class='fa fa-instagram'></i></a>" : ''; ?>
							<?= SITE_SOCIAL_YOUTUBE ? "<a href='http://www.youtube.com/channel/" . SITE_SOCIAL_YOUTUBE . "?sub_confirmation=1' target='_blank' title='" . SITE_NAME . " no Youtube'><i class='fa fa-youtube'></i></a>" : ''; ?>
							<?= SITE_SOCIAL_LINKEDIN ? "<a href='https://www.linkedin.com/company/" . SITE_SOCIAL_LINKEDIN . "' target='_blank' title='" . SITE_NAME . " no Linkedin'><i class='fa fa-linkedin'></i></a>" : ''; ?>
						</div>
					</li>
				</ul>
			</nav>
		</div>

		<div class="extras">

				<div>
					<p>IMPORTADO POR:</p>
					<span>
				        MÓVEIS DORIPEL LTDA.<br>
						CNPJ 90.608.084/0004-86<br>
						R. Dona Francisca, 8300<br>
						Box Maceió .  Bloco 01<br>
						Bairro Zona Industrial Norte <br>
						JOINVILLE . SC<br>
						89219-600
			        </span>
				</div>
				<div>
					<p>DISTRIBUÍDO POR:</p>
					<span>
				        MÓVEIS DORIPEL LTDA<br>
						CNPJ 90.608.084/0001-33<br>
						R. Júlio Vanzin, 1600<br>
						Distrito industrial III<br>
						LAGOA VERMELHA . RS<br>
						CEP 95300.000
			        </span>
				</div>

					<div class='header_whatsapp'>
						<a target="_blank" href='<?= Check::WhatsMessage(SITE_ADDR_WHATS,"Olá! Obrigado pelo seu contato com a Be Smart! Nossa equipe está pronta para te atender.Responderemos o mais breve possível! Atenciosamente, Equipe Be Smart") ?>'><span class='fa fa-whatsapp'></span>VAMOS CONVERSAR</a>
					</div>


		</div>
	</section>
<div class="clear"></div>
	<div class="footer_copy container">
		<div class="content">
			<p>&copy; <?= Date('Y'); ?>
				<span class="uppercase"><?= SITE_NAME; ?></span> - Todos os Direitos Reservados
			</p>
			<a href="http://agenciacachola.com.br/" title="Conheça a Agência Cachola" target="_blank"><img
						src="<?= INCLUDE_PATH ?>/images/logo-cachola-cz.svg" alt="Agência Cachola - Logotipo"
						title="Agência Cachola"/></a>
			<div class="clear"></div>
		</div>
	</div>
</footer>
