<?php
    
    $Read->exeRead(DB_SKILLSPAGE, "WHERE skill_status = :st ORDER BY skill_title ASC LIMIT :limit", "st=1&limit=6");
    
    if ($Read->getResult()) {
        echo '<section class="services" id="skills">
                <h2 class="heading">Minhas <span>Habilidades</span></h2>';
       
        echo "<div class='services-container'>";
       
        foreach ($Read->getResult() as $skill) {
            
            $icon = ($skill['skill_image'] ? BASE ."/uploads/skills/{$skill['skill_image']}" : BASE."/admin/_img/icon-default.png");
            
        echo "<div class='services-box'>
                            <img src='{$icon}' alt='{$skill['skill_title']}'>
                            <h3>{$skill['skill_title']}</h3>
                            <p>{$skill['skill_description']}</p>
                            <a href='" . BASE . "/habilidades/{skill_url}' class='btn'>Leia Mais</a>
                        </div>
                        ";
                   
        }
        
        echo '</div></section>';
    }

?>
