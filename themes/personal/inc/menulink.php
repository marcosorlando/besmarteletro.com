<nav class="navbar">
    <a href="<?= BASE; ?>#home" class="active" style="--i:1">Home</a>
    <a href="<?= BASE; ?>#about" style="--i:2">Sobre</a>
    <a href="<?= BASE; ?>#skills" style="--i:3">Habilidades</a>
    <a href="<?= BASE; ?>#portfolio" style="--i:4">Portfólio</a>
    <a href="<?= BASE . '/artigos/blog'?>" style="--i:5">Blog</a>
    <a href="<?= BASE; ?>#contact" style="--i:6">Contato</a>
    
    <?php
        if (ACC_MANAGER):
            echo "<a class='open-signup'>";
            require '_cdn/widgets/account/account.bar.php';
            echo "</a>";
        endif;
    ?>
</nav>
