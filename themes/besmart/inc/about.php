<section class="about" id="quem-somos">
	<div class="container">
		<div class="content">
			<article class="flex-box">
				<div class='info'>
					<h1 class='title-section'>QUEM SOMOS</h1>
					<p>
						A <b>Be Smart</b> está aqui
						para levar toda a praticidade
						que a sua vida merece.
						Nossos produtos foram feitos
						para valorizar o seu dia a dia, assim
						sobra mais tempo para cuidar daquilo
						que mais interessa: você.
						São <b> eletrodomésticos, utilidades para
							a sua casa</b> e <b>móveis</b> que são pensados
						para fazer parte da sua vida de
						forma inteligente.
						<br>
						<b>Seja feliz,
							seja você.</b>
					</p>
				</div>
				<div class='image'>
					<img src='<?= INCLUDE_PATH ?>/images/about/about-image-720.webp'
					     srcset='<?= INCLUDE_PATH ?>/images/about/about-image-480.webp 480w, <?= INCLUDE_PATH ?>/images/about/about-image-560.webp 560w, <?= INCLUDE_PATH ?>/images/about/about-image-720.webp 720w, <?= INCLUDE_PATH ?>/images/about/about-image.webp 1024w'
					     sizes='(min-width: 992px) 35vw, 76vw' width='1024' height='768'
					     loading='lazy' decoding='async' alt='Be Smart'>
				</div>
			</article>
		</div>

		<div class="belt">
			<?php
				// width/height = proporção do viewBox de cada SVG (o CSS define a largura; a altura sai proporcional)
				$beltIcons = [
					'panela' => ['Panela', 189, 177],
					'sofa-2l' => ['Sofá', 189, 148],
					'forno' => ['Forno', 189, 189],
					'aspirador' => ['Aspirador', 189, 189],
					'torradeira' => ['Torradeira', 177, 189],
					'liquidificador' => ['Liquidificador', 150, 189],
					'rack-tv' => ['Rack TV', 189, 189],
					'churrasqueira' => ['Churrasqueira', 177, 189],
					'secador' => ['Secador de Cabelo', 189, 189],
					'ventilador' => ['Ventilador', 163, 189],
					'fogao' => ['Fogão', 189, 176],
					'ferro-de-passar' => ['Ferro de Passar', 189, 162],
					'geladeira' => ['Geladeira', 133, 189],
					'batedeira' => ['Batedeira', 189, 189],
					'som' => ['Som', 189, 162],
					'abajur' => ['Abajur', 128, 173],
				];
				foreach ($beltIcons as $beltFile => [$beltAlt, $beltW, $beltH]) {
					echo "<img src='" . INCLUDE_PATH . "/images/svg/{$beltFile}.svg' width='{$beltW}' height='{$beltH}' loading='lazy' alt='{$beltAlt}'>";
				}
			?>
		</div>
	</div>
	<div class="container dna">
		<div class="content">
			<div class='row'>
				<h2 class='title-section'>ESTÁ EM NOSSO DNA</h2>
				<ul class="dna-list">
					<li>
						<p id="praticidade">PRATICIDADE</p>
						<span>Vamos levar até você produtos que facilitem o seu dia a dia, fazendo
que o seu tempo seja valorizado.</span>
					</li>
					<li>
						<p id="autoestima">AUTOESTIMA</p>
						<span>Queremos levar até você, produtos que lhe façam bem, por dentro e por fora,
para que você possa cuidar de sí mesmo.</span>
					</li>
					<li>
						<p id="modernidade">MODERNIDADE</p>
						<span>A Be Smart nasceu para ser uma marca que vai levar novidades tecnlógicas
que vão facilitar a vida das pessoas.</span>
					</li>
				</ul>
			</div>

		</div>
	</div>
</section>
