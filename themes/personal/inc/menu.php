<nav class="navbar">

    <a href="<?= BASE ?>" class="logo">Portfólio<b class="text-first-color">.</b></a>

    <div class="mobile-menu">
        <div class="line1"></div>
        <div class="line2"></div>
        <div class="line3"></div>
    </div>
    
    <ul class="nav-list">
        <li><a href="#home" class="wc_goto active" style="--i:1">Home</a></li>
        <li><a href="#about" class="wc_goto" style="--i:2">Sobre</a></li>
        <li><a href="#skills" class="wc_goto" style="--i:3">Habilidades</a></li>
        <li><a href="#portfolio" class="wc_goto" style="--i:4">Portfólio</a></li>
        <li><a href="<?= BASE . '/artigos/blog'?>" style="--i:5">Blog</a></li>
        <li><a href="#contact" class="wc_goto" style="--i:6">Contato</a></li>
    
        <?php
            if (ACC_MANAGER):
                echo "<li>";
                require '_cdn/widgets/account/account.bar.php';
                echo "</li>";
            endif;
        ?>
    </ul>
</nav>
