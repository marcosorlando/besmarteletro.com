<?php
$Read->exeRead(DB_PORTIFOLIO);
if ($Read->getResult()) {
    $count = 0;
    echo "
        <section class='portfolio' id='portfolio'>
                <h2 class='heading'> Últimos <span>Projetos</span></h2>
        <div class='portfolio-container'>   ";

    foreach ($Read->getResult() as $portifolio) {
        $count = $count+1;
        if($count > 6){
            echo "<a href='https://localhost/blog_personal/projetos/php' class='btn'>Ver Mais Projetos</a>";
            break;
        } else{
            echo "<div class='portfolio-box'>
            <!--<= BASE . '/tim.php?src=admin/_img/no_image.jpg/&w=' . IMAGE_W . '&h=' . IMAGE_H; ?>-->
            <img src=" . BASE . "/tim.php?src=uploads/{$portifolio['porti_cover']}&w=" . IMAGE_W . "&h=" . IMAGE_H . " width='100%' alt=''>
            
            <div class='portfolio-layer'>
                <h4>{$portifolio['porti_title']}</h4>
                <a href='https://localhost/blog_personal/projeto/{$portifolio['porti_name']}'><i class='bx bx-link-external'></i></a>
            </div>
        </div>";
        }
    }
    echo "</div></section>
            ";
}
