<article class="products_item">
	<a href="<?= BASE; ?>/produto/<?= $pdt_name; ?>" title="<?= $pdt_title; ?>">
		<div class="products_item_image">
			<h2 class="products_item_name"><?= $pdt_title; ?></h2>
			<?php // alt vazio: o nome do produto já está no <h2> do mesmo link (leitor de tela não repete) ?>
			<img class="principal"
			     src="<?= BASE; ?>/tim.php?src=uploads/<?= $pdt_cover; ?>&w=<?= THUMB_W / 2 ?>&h=<?= THUMB_H / 2 ?>&fm=webp&q=80"
			     width="<?= THUMB_W / 2 ?>" height="<?= THUMB_H / 2 ?>" loading="lazy" decoding="async" alt=""/>
            <?php
                $voltage = ($pdt_voltage == 127 ? '<img class="voltage" src="' . INCLUDE_PATH . '/images/svg/127v.svg" alt="Voltagem">' : ($pdt_voltage == 220 ? '<img class="voltage" src="' . INCLUDE_PATH . '/images/svg/220v.svg" alt="Voltagem">' : ''));
                echo $voltage;
            ?>
			<img class="line" src="<?= BASE . '/uploads/' . $line_image; ?>" alt="<?= $line_title ?>">
		</div>
	</a>
</article>
