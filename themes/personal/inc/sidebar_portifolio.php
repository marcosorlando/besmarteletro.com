<aside class="sidebar">
    <!-- SEARCH -->
    <div class="search_form">
        <form name="search" action="" method="post" enctype="multipart/form-data">
            <label for="p">
                <input type="text" id="p" name="p" placeholder="DIGITE O QUE PROCURA + ENTER" required>
                <button><i class='bx bx-search-alt-2'></i></button>
            </label>
        </form>
    </div>
   
       <div style="--i:2" class="sidebar-box">
        <h5>Projetos</h5>
        <div class="sidebar-box-content">
            <?php
            $Read->exeRead(DB_CATEGORIES_PORTIFOLIO, "WHERE category_parent IS NULL ORDER BY category_title ASC");
            if (!$Read->getResult()) :
                echo Erro("Ainda não existem sessões cadastradas!", E_USER_NOTICE);
            else :
                echo "<ul class=\"category-list\">";
                foreach ($Read->getResult() as $Ses) :
                    echo "<li class='session bx bxs-tag-alt'><a title='{$Ses['category_title']}' href='" . BASE . "/projetos/{$Ses['category_name']}'>{$Ses['category_title']}</a></li>";

                    $Read->fullRead("SELECT category_title, category_name FROM " . DB_CATEGORIES_PORTIFOLIO . " WHERE category_parent = :pr ORDER BY category_title ASC", "pr={$Ses['category_id']}");

                    if ($Read->getResult()) :
                        foreach ($Read->getResult() as $Cat) :
                            echo "<li class='category'><a title='{$Ses['category_title']}' href='" . BASE . "/projetos/{$Cat['category_name']}'><i class='bx bx-tag-alt' ></i></i> {$Cat['category_title']}</a></li>";
                        endforeach;
                    endif;
                endforeach;
                echo "</ul>";
            endif;
            ?>
        </div>
    </div>

    <div style="--i:3" class="sidebar-box">
        <h5>Projetos Recentes</h5>
        <div class="sidebar-box-content">
            <div class="recent-posts">
                <?php
                $Read->exeRead(DB_PORTIFOLIO, "WHERE porti_status = 1 AND porti_date <= NOW() ORDER BY porti_date DESC LIMIT 5");
                if (!$Read->getResult()) :
                    echo Erro("Ainda Não existe projetos cadastrados. Favor volte mais tarde :)", E_USER_NOTICE);
                else :
                    foreach ($Read->getResult() as $Post) :
                ?>
                        <article class="post-item">
                            <figure class="image">
                                <a title="Ler mais sobre <?= $Post['porti_title']; ?>" href="<?= BASE; ?>/projeto/<?= $Post['porti_name']; ?>">
                                    <img title="<?= $Post['porti_title']; ?>" alt="<?= $Post['porti_title']; ?>" src="<?= BASE; ?>/tim.php?src=uploads/<?= $Post['porti_cover']; ?>&w=<?= IMAGE_W / 2; ?>&h=<?= IMAGE_H / 2; ?>" />
                                </a>
                            </figure>
                            <header>
                                <h5>
                                    <a title="Ler mais sobre <?= $Post['porti_title']; ?>" href="<?= BASE; ?>/projeto/<?= $Post['porti_name']; ?>"><?= $Post['porti_title']; ?></a>
                                </h5>
                                <div class="meta-item">
                                    <i class='bx bx-calendar-event'></i>
                                    <time datetime="<?= date('Y-m-d', strtotime($Post['porti_date'])); ?>" pubdate="pubdate"><?= utf8_encode(strftime(" %d de %B de %Y", strtotime($Post['porti_date']))); ?></time>
                                </div>
                            </header>
                        </article>
                <?php
                    endforeach;
                endif;
                ?>

            </div>
        </div>
    </div>

    <div class="sidebar-box wow fadeInUp">
        <h5>Instagram</h5>
        <div class="instagram-follow-api_"></div>

        <div class="instagram-follow-api" id="instafeed-container">
            <ul id="instaFeed-aside"></ul>
        </div>
    </div>

    <div style="--i:4" class="sidebar-box ">
        <h5>Palavras Chave</h5>
        <ul class="list-tags">
            <?php
            $URL[1] = $URL[1] ? $URL[1] : ' ';

            $Read->exeRead(DB_PORTIFOLIO, "WHERE porti_name = :nm", "nm={$URL[1]}");
            if (!$Read->getResult()) :
                $tags = explode(',', 'PHP, PROGRAMAÇÃO, PERSONAL TEST OF ENGLISH, APRENDER, LINGUAGEM, ');
                foreach ($tags as $key => $value) :
            ?>
                    <li>
                        <a title="Pesquisar por " href="<?= BASE; ?>/pesquisa_portifolio/<?= $value ?>">
                            <h6><?= $value ?></h6>
                        </a>
                    </li>
                <?php
                endforeach;
            else :
                extract($Read->getResult()[0]);
                $tags = explode(',', $porti_tags);

                foreach ($tags as $key => $value) :
                ?>

                    <li>
                        <a title="Pesquisar por " href="<?= BASE; ?>/pesquisa_portifolio/<?= $value ?>">
                            <h6><?= $value ?></h6>
                        </a>
                    </li>

            <?php
                endforeach;
            endif;
            ?>
        </ul>
    </div>
</aside>
