<article class="products_item">
	<a href="<?= BASE; ?>/produto/<?= $pdt_name; ?>" title="<?= $pdt_title; ?>">
		<div class="products_item_image">
			<div class="products_item_title auto_height">
				<h1>
					<?= $pdt_title; ?>
				</h1>
			</div>

			<img class="principal"
			     src="<?= BASE; ?>/tim.php?src=uploads/<?= $pdt_cover; ?>&w=<?= THUMB_W / 2 ?>&h=<?= THUMB_H / 2 ?>"
			     alt="<?= $pdt_title; ?>" title="<?= $pdt_title; ?>"/>
			<?php
				$voltage = ($pdt_voltage == 127 ? '<img class="voltage" src="'.INCLUDE_PATH .'/images/svg/127v.svg" alt="Voltagem">' : ($pdt_voltage == 220 ? '<img class="voltage" src="'.INCLUDE_PATH .'/images/svg/220v.svg" alt="Voltagem">' : ''));
				echo $voltage;
			?>
			<img class="line" src="<?= BASE . '/uploads/' . $line_image; ?>" alt="<?= $line_title ?>">
		</div>
	</a>
</article>
