<div class="social-media">
    <?php if (SITE_SOCIAL_FB): ?>
        <a target="_blank" style="--i: 1" title="<?= SITE_NAME; ?> no Facebook" href="https://www.facebook.com/<?= SITE_SOCIAL_FB_PAGE; ?>"><i class = "bx bxl-facebook"></i></a>
    <?php
    endif;
        if (SITE_SOCIAL_INSTAGRAM):
            ?>
            <a target="_blank" style="--i: 2" title="<?= SITE_NAME; ?> no Instagram" href="https://www.instagram.com/<?= SITE_SOCIAL_INSTAGRAM; ?>"><i class = "bx bxl-instagram"></i></a>
        <?php
        endif;
        if (SITE_SOCIAL_LINKEDIN):
            ?>
            <a target="_blank" style="--i: 3" title="<?= SITE_NAME; ?> no Linkedin" href="https://www.linkedin.com/in/<?= SITE_SOCIAL_LINKEDIN; ?>"><i class = "bx bxl-linkedin"></i></a>
        <?php
        endif;
        if (SITE_SOCIAL_TWITTER):
            ?><a target="_blank" style="--i: 4" title="<?= SITE_NAME; ?> no Twitter" href="https://twitter.com/<?= SITE_SOCIAL_TWITTER; ?>"><i class = "bx bxl-twitter"></i></a><?php
        endif;

        if (SITE_SOCIAL_YOUTUBE):
            ?><a target="_blank" style="--i: 6" title="<?= SITE_NAME; ?> no YouTube" href="https://www.youtube.com/@<?= SITE_SOCIAL_YOUTUBE; ?>?sub_confirmation=1"><i class = "bx bxl-youtube"></i></a><?php
        endif;
		?>


</div>
