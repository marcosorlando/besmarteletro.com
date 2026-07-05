<?php

use App\Conn\Create;
use App\Conn\Update;
use App\Helpers\Check;
use App\Models\Pager;

$parseUrl = parse_url((string)$_SERVER['REQUEST_URI']);

$Search = urldecode((string)$URL[1]);
$SearchPage = urlencode($Search);

$parse = (isset($parseUrl['query']) ? $parseUrl['query'] : null);

if (empty($_SESSION['search']) || !in_array($Search, $_SESSION['search'])) {
    $Read->fullRead(
        'SELECT search_id, search_count FROM ' . DB_SEARCH . ' WHERE search_key = :key',
        'key=' . $Search
    );

    if ($Read->getResult()) {
        $Update = new Update();
        $DataSearch = [
            'search_count' => $Read->getResult()[0]['search_count'] + 1,
            'search_origin' => 'POST',
            'search_parse' => $parse,
        ];

        $Update->exeUpdate(
            DB_SEARCH,
            $DataSearch,
            'WHERE search_id = :id',
            'id=' . $Read->getResult()[0]['search_id']
        );
    } else {
        $Create = new Create();
        $DataSearch = [
            'search_key' => $Search,
            'search_count' => 1,
            'search_date' => date('Y-m-d H:i:s'),
            'search_commit' => date('Y-m-d H:i:s'),
            'search_origin' => 'POST',
            'search_parse' => $parse,
        ];
        $Create->exeCreate(DB_SEARCH, $DataSearch);
    }
    $_SESSION['search'][] = $Search;
}

$SearchText = $Search;

if (isset($parseUrl['query'])) {
    if ('author' == $parseUrl['query']) {
        $Read->linkResult(DB_USERS, 'user_id', $Search, 'user_name, user_lastname');

        if ($Read->getResult()) {
            $SearchText = 'Autor: ' . $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'];
        }
    }

    if ('month' == $parseUrl['query']) {
        $SearchText = 'Mês: ' . Check::getWcMonths($Search);
    }
}

?>

<!-- PAGE TITLE -->
<section class="blog-banner">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<h1 class="search-title">Pesquisa por <span><?php
                        echo $SearchText; ?></span></h1>
			</div>
		</div>
	</div>
</section>
<!-- END PAGE TITLE -->

<!-- POSTS -->
<section class="commonSection newslistpage">
	<div class="container">
        <?php
        $Page = (empty($URL[2]) ? 1 : $URL[2]);
        $Pager = new Pager(
            BASE . sprintf('/pesquisa/%s/', $SearchPage),
            '<',
            '>',
            5
        );
        $Pager->exePager($Page, 12);

        $Read->fullRead(
            'SELECT p.post_title, p.post_name,p.post_tags, p.post_cover, p.post_date, p.post_author, p.post_views, p.post_subtitle, u
    .user_name, u.user_lastname, c.category_title FROM '
            . DB_POSTS . ' p, ' . DB_USERS . ' u, ' . DB_CATEGORIES . " c WHERE post_status = 1 AND post_category = category_id AND post_date <= NOW() AND post_author = user_id AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE
                         '%' :s '%' OR post_tags LIKE
                         '%' :s '%'  OR MONTH(post_date) = :s) ORDER BY post_date DESC LIMIT :limit OFFSET :offset",
            sprintf('limit=%d&offset=%d&s=%s', $Pager->getLimit(), $Pager->getOffset(), $Search)
        );

        if (!$Read->getResult()) {
            $Pager->returnPage();
            echo Check::erro(
                sprintf(
                    "Não encontramos conteúdo para a palavra-chave <b class='text-extra-dark-gray'>( %s )</b>.",
                    $Search
                ),
                E_USER_NOTICE
            );

            $Read->fullRead(
                'SELECT p.post_title, p.post_name, p.post_cover, p.post_date, p.post_author, p.post_views, u.user_name, p.post_subtitle, u.user_lastname, c.category_title FROM '
                . DB_POSTS . ' p, ' . DB_USERS . ' u, ' . DB_CATEGORIES . ' c WHERE post_status = 1 AND post_category = category_id AND post_date <= NOW() AND post_author = user_id ORDER BY post_date DESC LIMIT :limit',
                'limit=3'
            );

            if (!$Read->getResult()) {
                echo Check::erro(
                    'Ainda Não existe posts cadastrados nesta seção. Favor volte mais tarde.',
                    E_USER_NOTICE
                );
            } else {
                echo '<h3>Posts Mais Vistos</h3>';
                echo "<div class='row'>";
                foreach ($Read->getResult() as $Post) {
                    extract($Post);
                    $AuthorName = sprintf('%s %s', $user_name, $user_lastname);
                    echo "<div class='col-xl-4 col-md-6 col-lg-4 mb51'>";

                    require REQUIRE_PATH . '/inc/post-index.php';
                    echo '</div>';
                }
                echo '</div>';
            }
        } else {
            echo "<div class='row'>";
            foreach ($Read->getResult() as $Post) {
                extract($Post);
                $Read->fullRead(
                    'SELECT user_name, user_lastname FROM ' . DB_USERS . ' WHERE user_id = :user',
                    'user=' . $post_author
                );
                $AuthorName = sprintf(
                    '%s %s',
                    $Read->getResult()[0]['user_name'],
                    $Read->getResult()[0]['user_lastname']
                );

                echo "<div class='col-xl-4 col-md-6 col-lg-4 mb51'>";

                require REQUIRE_PATH . '/inc/post-index.php';
                echo '</div>';
            }
            echo '</div>';
        }

        ?>

		<div class="row mt10">
			<div class="col-lg-12">
				<div class="ind_pagination text-center">
                    <?php
                    $Pager->exePaginator(
                        DB_POSTS,
                        "WHERE post_status = 1 AND post_date <= NOW() AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%' OR post_tags LIKE '%' :s '%' OR MONTH(post_date) = :s)",
                        's=' . $Search
                    );
                    echo $Pager->getPaginator();
                    ?>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- END POSTS -->
