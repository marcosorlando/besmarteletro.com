<?php
    $Read->exeRead(DB_ABOUTPAGE);
    
    if($Read->getResult()){
        $about = $Read->getResult()[0];
        echo "
            <section id='about' class='about'>
            <article>
            
                <div class='about-img'>
                    <img src='".BASE."/uploads/{$about['about_image']}' alt='{$about['about_title']}'>
                </div>
                
                <div class='about-content'>
                    <h2 class='heading'>Sobre <span>Mim</span></h2>
                    <h3>{$about['about_title']}</h3>
                    <p>{$about['about_description']}</p>
                    
                    <div id='about_text' class='ds_none'> {$about['about_text']} </div>
                
                    " .
                    (!empty($about['about_text']) ? "<a href='#about_text' id='btn-about' class='btn'>+ Leia Mais</a> " : '') . "
                </div>
            </article>
            </section>
        ";
        
        
    }
