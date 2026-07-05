CREATE TABLE `ws_home` (
    `home_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `first_line` varchar(245) DEFAULT NULL,
    `second_line` varchar(245) DEFAULT NULL,
    `thirty_line` varchar(245) DEFAULT NULL,
    `thirty_line_tags` varchar(350) DEFAULT NULL,
    `bg_image` varchar(245) DEFAULT NULL,
    `home_image` varchar(245) DEFAULT NULL,
    `curriculum` varchar(255) DEFAULT NULL,
    `home_description` varchar(500) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    `home_status` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`home_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ws_about` (
    `about_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `about_title` varchar(245) DEFAULT NULL,
    `about_description` varchar(245) DEFAULT NULL,
    `about_text` text DEFAULT NULL,
    `about_image` varchar(245) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    `about_status` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`about_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ws_portifolio` (
    `porti_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `porti_name` varchar(255) DEFAULT NULL,
    `porti_title` varchar(255) DEFAULT NULL,
    `porti_subtitle` text DEFAULT NULL,
    `porti_content` longtext DEFAULT NULL,
    `porti_cover` varchar(255) DEFAULT NULL,
    `porti_video` varchar(255) DEFAULT NULL,
    `porti_date` timestamp NULL DEFAULT NULL,
    `porti_author` int(11) unsigned DEFAULT NULL,
    `porti_category` int(11) DEFAULT NULL,
    `porti_category_parent` varchar(255) DEFAULT NULL,
    `porti_views` decimal(10,0) DEFAULT 0,
    `porti_lastview` timestamp NULL DEFAULT NULL,
    `porti_status` int(11) NOT NULL DEFAULT 0,
    `porti_type` varchar(255) DEFAULT NULL,
    `porti_instant_article` int(11) DEFAULT NULL,
    `porti_amp` int(11) DEFAULT NULL,
    `porti_tags` varchar(255) DEFAULT NULL,
    `porti_time` int(11) DEFAULT NULL,
    `porti_month` int(11) DEFAULT NULL,
    PRIMARY KEY (`porti_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ws_portifolio_images` (
    `porti_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `id` int(11) unsigned NOT NULL,
    `image` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`porti_id`),
    CONSTRAINT `Portifolio_images_FK` FOREIGN KEY (`porti_id`) REFERENCES `ws_portifolio` (`porti_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ws_skills` (
    `skill_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `skill_title` varchar(245) DEFAULT NULL,
    `skill_url` varchar(245) DEFAULT NULL,
    `skill_description` varchar(245) DEFAULT NULL,
    `skill_text` text DEFAULT NULL,
    `skill_image` varchar(245) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `update_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
    `skill_status` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*
if (APP_HOMEPAGE) {
    echo "<li class='dashboard_nav_menu_li " . (strstr(
        $getViewInput,
        'homepage/'
    ) ? 'dashboard_nav_menu_active' : '') . "'><a class='icon-home' title='Home Page' href='dashboard.php?wc=homepage/create&id=1'>Home Page</a></li>";
}
if (APP_ABOUTPAGE) {
    echo "<li class='dashboard_nav_menu_li " . (strstr($getViewInput, 'aboutpage/') ? 'dashboard_nav_menu_active'
        : '') . "'><a class='icon-user-tie' title='Home Page' href='dashboard.php?wc=aboutpage/create&id=1'>Sobre mim</a></li>";
}

if (APP_SKILLSPAGE) {
    echo "<li class='dashboard_nav_menu_li " . (strstr($getViewInput, 'skillspage/') ? 'dashboard_nav_menu_active'
        : '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/home'>Habilidades</a>";

echo "<ul class='dashboard_nav_menu_sub'>";
echo "<li class='dashboard_nav_menu_sub_li " . (strstr(
        $getViewInput,
        'skillspage/'
    ) ? 'dashboard_nav_menu_active'
        : '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/home'>Ver Todas</a></li>";

echo "<li class='dashboard_nav_menu_sub_li " . (strstr(
        $getViewInput,
        'skillspage/'
    ) ? 'dashboard_nav_menu_active'
        : '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/create'>Criar Nova</a></li>";

echo "</ul>";
echo "</li>";
}

if (APP_PROJETOS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PROJETOS){
    $wc_posts_alerts = null;
$Read->FullRead("SELECT count(porti_id) as total FROM " . DB_PORTIFOLIO . " WHERE porti_status != 1");
if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1){
        $wc_posts_alerts .= "<span class='wc_alert bar_yellow'>{$Read->getResult()[0]['total']}</span>";
}
?>
    <li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'portifolios/') ? 'dashboard_nav_menu_active' : ''; ?>"><a class="icon-stack" title="Projetos" href="dashboard.php?wc=portifolios/home">Projetos <?= $wc_posts_alerts; ?></a>
        <ul class="dashboard_nav_menu_sub">
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'porfiolios/home' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Ver Projetos" href="dashboard.php?wc=portifolios/home">&raquo; Ver Projetos <?= $wc_posts_alerts; ?></a></li>
            <li class="dashboard_nav_menu_sub_li <?= strstr($getViewInput, 'porfiolios/categor') ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Categorias" href="dashboard.php?wc=portifolios/categories">&raquo; Categorias</a></li>
            <li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'porfiolios/create' ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Novo Projetos" href="dashboard.php?wc=portifolios/create">&raquo; Novo Projeto</a></li>
        </ul>
    </li>
<?php
}
?>*/
