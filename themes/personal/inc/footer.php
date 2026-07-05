<footer class="footer">
    <?php
        require REQUIRE_PATH . '/inc/blog_cta.php'; ?>
    <!--========================== -->
    <!--FOOTER - SOCIAL -->
    <!--========================== -->
    <h4 class="heading">Siga-me nas <span>Redes Sociais</span></h4>
    
    <?php include __DIR__ . "/social-media.php"; ?>

    <!--========================== -->
    <!--FOOTER - MENU -->
    <!--========================== -->

    <div class="copyright-section">
        <p>©2009 - <?= date('Y'); ?> <span><?= SITE_NAME ?> </span>. Todos os direitos reservados. By <a target="_blank" href="<?= AGENCY_URL ?>"><?= AGENCY_NAME ?></a>
        </p>
    </div>
</footer>
