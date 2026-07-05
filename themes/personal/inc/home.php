<?php
    use App\Conn\Read;

	$Read ??= new Read();
    $Read->exeRead(DB_HOMEPAGE);
    if ($Read->getResult() && $Read->getResult()[0]['home_status'] == 1) {
        $home = $Read->getResult()[0];
        $tags = explode(',', $home['thirty_line_tags']);
        $tags = array_map('trim', $tags);

        ?>
        <script>
            var tags = <?= '["' . implode('", "', $tags) . '"]'; ?>
        </script>


        <section class="home" style="background: var(--second-color) url('<?= BASE . "/uploads/{$home['bg_image']}" ?>')
                no-repeat
                center
                center
                ; background-size: cover;">


            <div class="home-content">
                <h3><?= $home['first_line'] ?></h3>
                <h1><?= $home['second_line'] ?></h1>
                <h3><?= $home['thirty_line'] ?> <span class="multiple-text"></span></h3>
                <p><?= $home['home_description'] ?></p>

                <?php include __DIR__ . "/social-media.php"; ?>

                <a href="<?= BASE. "/uploads/{$home['curriculum']}"?>" download="Curriculo do <?= $home['second_line']?>"
                   class="btn"><i
                            class = "bx
                bxl-download"></i> Download CV</a>


            </div>
            <div class="home-img">
                <img src="<?= BASE. "/uploads/{$home['home_image']}" ?>" alt="<?= $home['second_line'] ?>" title="<?=
                    $home['second_line'] ?>">
            </div>
        </section>
        <?php
    }
?>
