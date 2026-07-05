<?php
    setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
    date_default_timezone_set('America/Sao_Paulo');

   $parseUrl = parse_url($_SERVER["REQUEST_URI"]);

    $Search = urldecode($URL[1]);
    $SearchPage = urlencode($Search);

    if (empty($_SESSION['search']) || !in_array($Search, $_SESSION['search'])):
        $Read->FullRead("SELECT search_id, search_count FROM " . DB_SEARCH . " WHERE search_key = :key", "key={$Search}");
        if ($Read->getResult()):
            $Update = new Update;
            $DataSearch = ['search_count' => $Read->getResult()[0]['search_count'] + 1];
            $Update->ExeUpdate(DB_SEARCH, $DataSearch, "WHERE search_id = :id", "id={$Read->getResult()[0]['search_id']}");
        else:
            $Create = new Create;
            $DataSearch = [
                'search_key' => $Search,
                'search_count' => 1,
                'search_date' => date('Y-m-d H:i:s'),
                'search_commit' => date('Y-m-d H:i:s')
            ];
            $Create->ExeCreate(DB_SEARCH, $DataSearch);
        endif;
        $_SESSION['search'][] = $Search;
    endif;

    $SearchText = $Search;

    if(isset($parseUrl['query'])){
        if($parseUrl['query'] == 'author'){
            $Read->LinkResult(DB_USERS, 'user_id', $Search, 'user_name, user_lastname');

            if($Read->getResult()){
                $SearchText = $Read->getResult()[0]['user_name'] . ' ' . $Read->getResult()[0]['user_lastname'] ;
            }
        }

        if($parseUrl['query'] == 'month'){
            $SearchText = getWcMonths($Search);
        }



    }

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
                $Page = (!empty($URL[2]) ? $URL[2] : 1);
                $Pager = new Pager(BASE . "/pesquisa-blog/{$SearchPage}/", "<<", ">>", 5);
                $Pager->ExePager($Page, 10);

                $Read->FullRead("SELECT p.post_title, p.post_subtitle, p.post_content, p.post_name, p.post_cover, p.post_date, p.post_author, u.user_name, u.user_lastname, u.user_genre FROM " . DB_POSTS . " p, " . DB_USERS . " u WHERE post_status = 1 AND post_date <= NOW() AND post_author = user_id AND ((post_title LIKE '%' :s '%') OR (post_subtitle LIKE '%' :s '%') OR (post_tags LIKE '%' :s '%') OR (MONTH(post_date) = :s) OR (post_author = :s)  ) ORDER BY post_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}&s={$Search}");

                if (!$Read->getResult()):
                    $Pager->ReturnPage();
                    echo Erro("Não encontramos conteúdo para a palavra-chave <b class='text-blue text-uppercase'>( {$SearchPage} )</b>.", E_USER_NOTICE);
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
                                    $Read->FullRead("SELECT p.post_title, p.post_subtitle, p.post_content, p.post_name, p.post_cover, p.post_date, p.post_author, u.user_name, u.user_lastname, u.user_genre FROM " . DB_POSTS . " p, " . DB_USERS . " u WHERE post_status = 1 AND post_date <= NOW() AND post_author = user_id ORDER BY post_date DESC LIMIT :limit", "limit=3");

                                    if (!$Read->getResult()):
                                        $Pager->ReturnPage();
                                        echo Erro("Ainda não existem posts cadastrados nesta secão. Favor volte mais tarde.", E_USER_NOTICE);
                                    else:
                                        foreach ($Read->getResult() as $Post):
                                            extract($Post);
                                            $AuthorName = "{$user_name} {$user_lastname}";
                                            $BOX = 3;
                                            require REQUIRE_PATH . '/inc/post.php';
                                        endforeach;
                                    endif;
                                ?>
                                <!-- end blog item -->
                            </div>
                        </div>
                    </section><!-- end blog section -->
                <?php
                else:
                    foreach ($Read->getResult() as $Post):
                        extract($Post);
                        $Read->FullRead("SELECT user_name, user_lastname, user_thumb, user_genre,user_twitter, user_youtube, user_google, user_description FROM " . DB_USERS . " WHERE user_id = :user", "user={$post_author}");
                        $AuthorName = "{$user_name} {$user_lastname}";
                        $BOX = 2;
                        require REQUIRE_PATH . '/inc/post.php';
                    endforeach;
                endif;
            ?>
            <!-- start pagination -->
            <div class="text-center margin-top-40px wow fadeInUp">
                <div class="pagination text-small text-uppercase">
                    <?php
                        $Pager->ExePaginator(DB_POSTS, "WHERE post_status = 1 AND post_date <= NOW() AND (post_title LIKE '%' :s '%' OR post_subtitle LIKE '%' :s '%')", "s={$Search}");
                        echo $Pager->getPaginator();
                    ?>
                </div>
            </div>
            <!-- end pagination -->
        </main>
        <?php require REQUIRE_PATH . '/inc/sidebar.php'; ?>
    </div>
</section>
