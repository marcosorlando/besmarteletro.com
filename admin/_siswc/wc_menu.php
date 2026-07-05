<?php

use App\Conn\Read;

$Read ??= new Read();
if (APP_PRODUCTS_TRAVI && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PRODUCTS_TRAVI) {
    $wc_pdt_alerts = null;
    $Read->fullRead('SELECT count(pdt_id) as total FROM ' . DB_PDT_TRAVI . ' WHERE pdt_status != 1');
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1) {
        $wc_pdt_alerts .= sprintf("<span class='wc_alert bar_yellow'>%s</span>", $Read->getResult()[0]['total']);
    }
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'products/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-bullhorn" title="Produtos"
		   href="dashboard.php?wc=products/home">Produtos <?php
            echo $wc_pdt_alerts; ?></a>
		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'products/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Ver Produtos" href="dashboard.php?wc=products/home">&raquo; Ver Produto</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo strstr(
                (string)$getViewInput,
                'products/home&opt=outsale'
            ) ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Fora de Estoque ou Inativos" href="dashboard.php?wc=products/home&opt=outsale">&raquo;
					Indisponíveis <?php
                    echo $wc_pdt_alerts; ?></a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo strstr(
                (string)$getViewInput,
                'products/categor'
            ) ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Categorias de Produtos" href="dashboard.php?wc=products/categories">&raquo; Categorias</a>
			</li>

			<li class="dashboard_nav_menu_sub_li <?php
            echo strstr(
                (string)$getViewInput,
                'products/format'
            ) ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Processos de Produtos" href="dashboard.php?wc=products/formats">&raquo; Formatos</a>
			</li>

			<li class="dashboard_nav_menu_sub_li <?php
            echo 'products/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Novo Produto" href="dashboard.php?wc=products/create">&raquo; Novo Produto</a>
			</li>
		</ul>
	</li>
    <?php
}

if (APP_SERVICES && $Admin['user_level'] >= LEVEL_WC_SERVICES) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'services/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-hammer2" href="dashboard.php?wc=services/home">Processos</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'services/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=services/create">&raquo; Novo Processo</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'services/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=services/home">&raquo; Ver Processo</a>
			</li>
		</ul>

	</li>
    <?php
}

if (APP_SEGMENTS && $Admin['user_level'] >= LEVEL_WC_SEGMENTS) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'segments/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-tree" href="dashboard.php?wc=segments/home">Segmentos</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'segments/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=segments/create">&raquo; Novo Segmento</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'segments/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=segments/home">&raquo; Ver Segmentos</a>
			</li>
		</ul>

	</li>
    <?php
}
if (APP_CURIOSITIES && $Admin['user_level'] >= LEVEL_WC_CURIOSITIES) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'curiosities/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-newspaper" href="dashboard.php?wc=curiosities/create&id=1">Curiosidades</a>
	</li>
    <?php
}
if (APP_CV && $Admin['user_level'] >= LEVEL_WC_CV) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'curriculum/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-drawer" href="dashboard.php?wc=curriculos/home">Base de Currículos</a>
	</li>
    <?php
}

if (APP_OUVIDORIA && $Admin['user_level'] >= LEVEL_WC_OUVIDORIA) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'ouvidoria/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-bullhorn" href="dashboard.php?wc=ouvidoria/home">Ouvidoria</a>
	</li>
    <?php
}
if (APP_REPRESENTATIVES && $Admin['user_level'] >= LEVEL_WC_REPRESENTATIVES) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'representatives/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-user-tie" href="dashboard.php?wc=representatives/home">Representantes</a>
		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'representatives/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=representatives/create">&raquo; Novo Representante</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'representatives/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=representatives/home">&raquo; Representantes </a>
			</li>
		</ul>
	</li>
    <?php
}
if (APP_LINKTREE && $Admin['user_level'] >= LEVEL_WC_LINKTREE) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'linktree/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-tree" href="dashboard.php?wc=linktree/home">Cartões LinkTree</a>
		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'linktree/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=linktree/create">&raquo; Novo Cartão</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'linktree/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=linktree/home">&raquo; Todos os Cartões </a>
			</li>
		</ul>
	</li>
    <?php
}
if (APP_CERTIFICATIONS && $Admin['user_level'] >= LEVEL_WC_CERTIFICATIONS) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'certifications/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-file-text" href="dashboard.php?wc=certifications/home">Certificações</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'certifications/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=certifications/create">&raquo; Nova Certificação</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'certifications/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=certifications/home">&raquo; Ver Certificações</a>
			</li>
		</ul>

	</li>
    <?php
}
if (APP_HELLO && $Admin['user_level'] >= LEVEL_WC_HELLO) {
    $wc_hellobars_alerts = null;
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'hello/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-bullhorn" title="Hellobar"
		   href="dashboard.php?wc=hello/home">Hellobar <?php
            echo $wc_hellobars_alerts; ?></a>
	</li>
    <?php
}
if (APP_ALBUMS !== 0) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'albums/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-camera" href="dashboard.php?wc=albums/home">Álbuns de Fotos</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'albums/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=albums/create">&raquo; Novo Álbum</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'albums/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=albums/home">&raquo; Ver Álbuns </a>
			</li>
		</ul>

	</li>
    <?php
}
if (APP_LEADS && $Admin['user_level'] >= LEVEL_WC_LEADS) {
    $wc_leads = null;
    $Read->fullRead('SELECT count(lead_id) as total FROM ' . DB_LEADS . ' WHERE lead_status != 1');
    if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1) {
        $wc_leads .= sprintf("<span class='wc_alert bar_yellow'>%s</span>", $Read->getResult()[0]['total']);
    }
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'leads/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-users" title="Leads" href="dashboard.php?wc=leads/home">Base de Leads <?php
            echo $wc_leads; ?></a>
	</li>
    <?php
}
if (APP_THANKYOU_PAGES && $Admin['user_level'] >= LEVEL_WC_THANKYOU_PAGES) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'thankyoupages/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-heart" href="dashboard.php?wc=thankyoupages/home">Thank You Pages</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'thankyoupages/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=thankyoupages/create">&raquo; Nova Thank You Page</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'thankyoupages/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=thankyoupages/home">&raquo; Ver Thank You Pages </a>
			</li>
		</ul>

	</li>
    <?php
}
if (APP_LANDING_PAGES && $Admin['user_level'] >= LEVEL_WC_LANDING_PAGES) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'landingpages/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-download" href="dashboard.php?wc=landingpages/home">Landing Pages</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'landingpages/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=landingpages/create">&raquo; Nova Landing Pages</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'landingpages/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=landingpages/home">&raquo; Ver Landing Pages </a>
			</li>
		</ul>

	</li>
    <?php
}
if (APP_MATERIALS !== 0) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'materiais/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-book" title="Materiais" href="dashboard.php?wc=materiais/home">Materiais</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'materiais/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Ver Materiais" href="dashboard.php?wc=materiais/home">&raquo; Ver Materials </a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo strstr(
                (string)$getViewInput,
                'materiais/categor'
            ) ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Categorias" href="dashboard.php?wc=materiais/categories">&raquo; Categorias</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'materiais/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Novo Material" href="dashboard.php?wc=materiais/create">&raquo; Novo Material</a>
			</li>
		</ul>
	</li>
    <?php
}

if (APP_DEPOSITIONS !== 0) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'depositions/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-man-woman" href="dashboard.php?wc=depositions/home">Depoimentos</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'depositions/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=depositions/create">&raquo; Novo Depoimento</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'depositions/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=depositions/home">&raquo; Depoimentos </a>
			</li>
		</ul>
	</li>
    <?php
}
if (APP_PARTNERS !== 0) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'partners/home'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-users" href="dashboard.php?wc=partners/home">Parceiros</a>

		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'partners/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=partners/create">&raquo; Novo Parceiro</a>
			</li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'partners/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href="dashboard.php?wc=partners/home">&raquo; Parceiros </a>
			</li>
		</ul>
	</li>
    <?php
}

if (APP_VIDEOS !== 0) {
    ?>
	<li class="dashboard_nav_menu_li <?php
    echo strstr(
        (string)$getViewInput,
        'videos/'
    ) ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class='icon-youtube' title='Vídeos Youtube' href='dashboard.php?wc=videos/home'>Vídeos Youtube</a>
		<ul class='dashboard_nav_menu_sub'>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'videos/create' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href='dashboard.php?wc=videos/create'>&raquo; Novo Vídeo</a></li>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'videos/home' == $getViewInput ? 'dashboard_nav_menu_active' : ''; ?>">
				<a href='dashboard.php?wc=videos/home'>&raquo; Ativos </a>
			<li class="dashboard_nav_menu_sub_li <?php
            echo 'videos/end' == $getViewInput ? 'dashboard_nav_menu_active' : '';
            ?>"><a href='dashboard.php?wc=videos/end'>&raquo; Expirados </a>
			</li>
		</ul>
	</li>
    <?php
}

	if (APP_HOMEPAGE && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_HOMEPAGE) {
	echo "
	<li class='dashboard_nav_menu_li " . (strstr(
        $getViewInput,
        ' homepage/'
	) ? 'dashboard_nav_menu_active' : '') . "'><a class='icon-home' title='Home Page'
	                                              href='dashboard.php?wc=homepage/create&id=1'>Home Page</a></li>";
	}
	if (APP_ABOUTPAGE && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_ABOUTPAGE) {
	echo "
	<li class='dashboard_nav_menu_li " . (strstr($getViewInput, ' aboutpage/') ? 'dashboard_nav_menu_active'
	: '') . "'><a class='icon-user-tie' title='Home Page' href='dashboard.php?wc=aboutpage/create&id=1'>Sobre
	mim</a></li>";
	}

	if (APP_SKILLSPAGE && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_SKILLSPAGE) {
	echo "
	<li class='dashboard_nav_menu_li " . (strstr($getViewInput, ' skillspage/') ? 'dashboard_nav_menu_active'
	: '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/home'>Habilidades</a>";

	echo "
	<ul class='dashboard_nav_menu_sub'>";
		echo "
		<li class='dashboard_nav_menu_sub_li " . (strstr(
        $getViewInput,
        ' skillspage
		/'
		) ? 'dashboard_nav_menu_active'
		: '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/home'>Ver Todas</a></li>
		";

		echo "
		<li class='dashboard_nav_menu_sub_li " . (strstr(
        $getViewInput,
        ' skillspage
		/'
		) ? 'dashboard_nav_menu_active'
		: '') . "'><a class='icon-wrench' title='Habilidades' href='dashboard.php?wc=skillspage/create'>Criar
			Nova</a></li>";

		echo '
	</ul>';
	echo '</li>';
	}

	if (APP_PROJETOS && $_SESSION['userLogin']['user_level'] >= LEVEL_WC_PROJETOS){
	$wc_posts_alerts = null;
	$Read->fullRead('SELECT count(porti_id) as total FROM ' . DB_PORTIFOLIO . ' WHERE porti_status != 1');
	if ($Read->getResult() && $Read->getResult()[0]['total'] >= 1){
	$wc_posts_alerts .= "<span class='wc_alert bar_yellow'>{$Read->getResult()[0]['total']}</span>";
	}
	?>
	<li class="dashboard_nav_menu_li <?= strstr($getViewInput, 'portifolios/') ? 'dashboard_nav_menu_active' : ''; ?>">
		<a class="icon-stack" title="Projetos"
		   href="dashboard.php?wc=portifolios/home">Projetos <?= $wc_posts_alerts; ?></a>
		<ul class="dashboard_nav_menu_sub">
			<li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'porfiolios/home' ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Ver Projetos" href="dashboard.php?wc=portifolios/home">&raquo; Ver
					Projetos <?= $wc_posts_alerts; ?></a></li>
			<li class="dashboard_nav_menu_sub_li <?= strstr(
                $getViewInput,
                'porfiolios/categor'
            ) ? 'dashboard_nav_menu_active' : ''; ?>"><a title="Categorias"
			                                             href="dashboard.php?wc=portifolios/categories">&raquo;
					Categorias</a></li>
			<li class="dashboard_nav_menu_sub_li <?= $getViewInput == 'porfiolios/create' ? 'dashboard_nav_menu_active' : ''; ?>">
				<a title="Novo Projetos" href="dashboard.php?wc=portifolios/create">&raquo; Novo Projeto</a></li>
		</ul>
	</li>
<?php
}
?>
