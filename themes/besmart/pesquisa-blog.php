<?php

    use App\Conn\Create;
    use App\Conn\Read;
    use App\Conn\Update;
    use App\Helpers\Check;
    use App\Models\Pager;

    $Read ??= new Read();

    // $URL[1] já chega decodificado pelo rewrite (index.php?url=...)
    $Search = trim(strip_tags((string)($URL[1] ?? '')));
    $SearchPage = urlencode($Search);

    // Tipo de pesquisa: ?author (id do autor), ?month (mês do post) ou termo livre
    $SearchParse = (string)parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_QUERY);
    $SearchParse = in_array($SearchParse, ['author', 'month'], true) ? $SearchParse : null;
    $SearchQuery = ($SearchParse ? "?{$SearchParse}" : '');

    // REGISTRA A PESQUISA (uma vez por sessão) para o relatório do admin
    $SessionKey = 'POST|' . ($SearchParse ?? 'term') . '|' . $Search;
    if ('' !== $Search && (empty($_SESSION['search']) || !in_array($SessionKey, $_SESSION['search'], true))) {
        $Read->fullRead(
            "SELECT search_id, search_count FROM " . DB_SEARCH . " WHERE search_key = :key AND search_origin = :origin AND "
            . ($SearchParse ? "search_parse = :parse" : "search_parse IS NULL"),
            http_build_query(['key' => $Search, 'origin' => 'POST', 'parse' => $SearchParse])
        );
        if ($Read->getResult()) {
            $Update ??= new Update();
            $DataSearch = [
                'search_count' => $Read->getResult()[0]['search_count'] + 1,
                'search_commit' => date('Y-m-d H:i:s')
            ];
            $Update->exeUpdate(DB_SEARCH, $DataSearch, "WHERE search_id = :id", "id={$Read->getResult()[0]['search_id']}");
        } else {
            $Create ??= new Create();
            $DataSearch = [
                'search_key' => $Search,
                'search_origin' => 'POST',
                'search_parse' => $SearchParse,
                'search_count' => 1,
                'search_date' => date('Y-m-d H:i:s'),
                'search_commit' => date('Y-m-d H:i:s')
            ];
            $Create->exeCreate(DB_SEARCH, $DataSearch);
        }
        $_SESSION['search'][] = $SessionKey;
    }

    // FILTRO conforme o tipo de pesquisa (mesmo WHERE na listagem e na paginação)
    if ('author' === $SearchParse) {
        $SearchWhere = "post_author = :s";
    } elseif ('month' === $SearchParse) {
        $SearchWhere = "post_month = :s";
    } else {
        $SearchWhere = "(post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%' OR post_tags LIKE '%' :s '%')";
    }

    $SearchText = $Search;
    if ('author' === $SearchParse) {
        $Read->fullRead("SELECT user_name, user_lastname FROM " . DB_USERS . " WHERE user_id = :id", "id=" . (int)$Search);
        if ($Read->getResult()) {
            $SearchText = "{$Read->getResult()[0]['user_name']} {$Read->getResult()[0]['user_lastname']}";
        }
    } elseif ('month' === $SearchParse) {
        $SearchText = Check::getWcMonths((int)$Search) ?: $Search;
    }
    $SearchText = htmlspecialchars((string)$SearchText, ENT_QUOTES, 'UTF-8');

?>

<section class="container blog-breadcrumps">
	<header class="content">
		<!-- start page title -->
		<h1 class="">
			<i class="fa fa-search-plus"></i> Resultados para
			<span class="text-white"><?= $SearchText; ?></span>
		</h1>
		<!-- end page title -->
	</header>
</section>
<div class="clear"></div><!-- start post content section -->
<section class="container blog-categories">
	<div class="content">
		<main class="blog-articles">
            <?php
                $Page = (!empty($URL[2]) ? (int)$URL[2] : 1);
                $Pager = new Pager(BASE . "/pesquisa-blog/{$SearchPage}/", "<<", ">>", 5);
                $Pager->exePager($Page, 10);

                $Read->fullRead(
                    "SELECT p.post_title, p.post_subtitle, p.post_content, p.post_name, p.post_cover, p.post_date, p.post_author, u.user_name, u.user_lastname, u.user_genre FROM " . DB_POSTS . " p, " . DB_USERS . " u WHERE post_status = 1 AND post_date <= NOW() AND post_author = user_id AND {$SearchWhere} ORDER BY post_date DESC LIMIT :limit OFFSET :offset",
                    http_build_query(['limit' => $Pager->getLimit(), 'offset' => $Pager->getOffset(), 's' => $Search])
                );

                if (!$Read->getResult()) {
                    $Pager->returnPage();
                    echo Check::erro(
                        "Não encontramos conteúdo para a palavra-chave <b class='text-blue text-uppercase'>( {$SearchText} )</b>.",
                        E_USER_NOTICE
                    );
                    ?>
					<!-- start blog section -->
					<section class="padding-40px-top">
						<div style="padding: 20px">
							<div class="row">
								<div class="text-center">
									<h1 class="text-orange">Confira os Blog Posts mais lidos!</h1>
								</div>
							</div>
							<div class="">
								<!-- start blog item -->
                                <?php
                                    $Read->fullRead(
                                        "SELECT p.post_title, p.post_subtitle, p.post_content, p.post_name, p.post_cover, p.post_date, p.post_author, u.user_name, u.user_lastname, u.user_genre FROM " . DB_POSTS . " p, " . DB_USERS . " u WHERE post_status = 1 AND post_date <= NOW() AND post_author = user_id ORDER BY post_views DESC, post_date DESC LIMIT :limit",
                                        "limit=3"
                                    );

                                    if (!$Read->getResult()) {
                                        echo Check::erro(
                                            "Ainda não existem posts cadastrados nesta seção. Favor volte mais tarde.",
                                            E_USER_NOTICE
                                        );
                                    } else {
                                        foreach ($Read->getResult() as $Post) {
                                            extract($Post);
                                            $AuthorName = "{$user_name} {$user_lastname}";
                                            $BOX = 3;
                                            require REQUIRE_PATH . '/inc/post.php';
                                        }
                                    }
                                ?>
								<!-- end blog item -->
							</div>
						</div>
					</section><!-- end blog section -->
                    <?php
                } else {
                    foreach ($Read->getResult() as $Post) {
                        extract($Post);
                        $AuthorName = "{$user_name} {$user_lastname}";
                        $BOX = 2;
                        require REQUIRE_PATH . '/inc/post.php';
                    }
                }
            ?>
			<!-- start pagination -->
			<div class="text-center margin-top-40px wow fadeInUp">
				<div class="pagination text-small text-uppercase">
                    <?php
                        $Pager->exePaginator(
                            DB_POSTS,
                            "WHERE post_status = 1 AND post_date <= NOW() AND {$SearchWhere}",
                            http_build_query(['s' => $Search]),
                            $SearchQuery
                        );
                        echo $Pager->getPaginator();
                    ?>
				</div>
			</div>
			<!-- end pagination -->
		</main>
        <?php
            require REQUIRE_PATH . '/inc/sidebar.php'; ?>
	</div>
</section>
