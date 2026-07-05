<?php

use App\Conn\Read;
use App\Conn\Update;
use App\Helpers\Check;
use App\Helpers\DateHelper;

$Read ??= new Read();
$URL[1] = $URL[1] ?? '';

$Read->exeRead(DB_POSTS, 'WHERE post_name = :nm', 'nm=' . $URL[1]);
if (!$Read->getResult()) {
    require REQUIRE_PATH . '/404.php';

    return;
}
extract($Read->getResult()[0]);
$Update = new Update();
$UpdateView = ['post_views' => $post_views + 1, 'post_lastview' => date('Y-m-d H:i:s')];
$Update->exeUpdate(DB_POSTS, $UpdateView, 'WHERE post_id = :id', 'id=' . $post_id);

$Read->fullRead(
    'SELECT category_title, category_name FROM ' . DB_CATEGORIES . ' WHERE category_id = :id',
    'id=' . $post_category_parent
);
$PostCategory = $Read->getResult()[0];
extract($PostCategory);

$Read->fullRead(
    'SELECT user_name, user_lastname, user_thumb, user_genre,user_twitter, user_youtube, user_google, user_description FROM ' . DB_USERS . ' WHERE user_id = :user',
    'user=' . $post_author
);
$AuthorName = sprintf('%s %s', $Read->getResult()[0]['user_name'], $Read->getResult()[0]['user_lastname']);

extract($Read->getResult()[0]);
?>
<!-- PAGE TITLE -->
<section class="blog-banner">
	<div class="container">
		<div class="row align-content-center">
			<div class="col-sm-12 col-md-8">
				<h1><?php
                    echo $post_title; ?></h1>
			</div>
			<div class="col-sm-12 col-md-4 breadcrumbs">
				<a href="<?php
                echo BASE . ('/artigos/' . $PostCategory['category_name']); ?>"
				   title="Ver mais: <?php
                   echo $PostCategory['category_title']; ?> em <?php
                   echo SITE_NAME; ?>"><?php
                    echo Check::getCapilalize(
                        $PostCategory['category_title']
                    ); ?></a>
			</div>
		</div>
	</div>
</section>
<!-- END PAGE TITLE -->

<!-- POST SECTION -->
<section class="commonSection newsDetailsSection">
	<div class="container ">
		<div class="row">
			<div class="col-xl-8 col-md-12 col-lg-8">
				<div class="newsDetailsArea">
                    <?php
                    if ($post_video) {
                        echo "<div class='embed-container htmlchars'>";
                        echo sprintf(
                                "<iframe id='mediaview' src='https://www.youtube.com/embed/%s?rel=0&amp;showinfo=0&autoplay=0&origin=",
                                $post_video
                            ) . BASE . "' allowfullscreen></iframe>";
                        echo '</div>';
                    } else {
                        $post_cover = $post_cover ? 'uploads/' . $post_cover : 'admin/_img/no_image.jpg';
                        echo "<div class='newsThumb newsGall owl-carousel'>";
                        echo "<div class='ntItem'>";
                        echo sprintf("<img title='%s' alt='%s' src='", $post_title, $post_title) . BASE . sprintf(
                                '/tim.php?src=%s&w=',
                                $post_cover
                            ) . IMAGE_W . '&h=' . IMAGE_H . "'>";
                        echo '</div></div>';
                    }
                    ?>
					<div class="newsDetails">
						<div class="ndMeta">
                            <span><i class="fa fa-calendar-check"></i>
                               <time datetime="<?php
                               echo DateHelper::iso($Post['post_date'] ?? null); ?>"
                                     pubdate="pubdate"><?php
                                   echo DateHelper::human($Post['post_date'] ?? null); ?></time>
                            </span>
							<span><i class="fa fa-tag"></i><?php
                                echo $PostCategory['category_title']; ?></span>
							<span><i class="fa fa-user"></i><?php
                                echo $AuthorName; ?></span>
							<span><i class="fa fa-laptop"></i><?php
                                echo $post_views; ?> views</span>
						</div>
                        <?php
                        $WC_TITLE_LINK = $post_title;
                        $WC_SHARE_HASH = '#hashtags';
                        $WC_SHARE_LINK = BASE . ('/artigo/' . $post_name);

                        require __DIR__ . '/../../_cdn/widgets/share/share.wc.php';
                        ?>
						<h2 class="ndTitle"><?php
                            echo $post_subtitle; ?></h2>
						<div class="nd_content">
							<div class="htmlchars"><?php
                                echo $post_content; ?></div>
						</div>
						<div class="row mb50">
							<div class="col-xl-12 col-md-12 col-sm-12">
								<div class="ndTags text-left clearfix">
									<h5>Tags:</h5>
                                    <?php
                                    $tags = explode(',', (string)$post_tags);
                                    foreach ($tags as $tag) {
                                        echo sprintf("<a title='%s' href='", $tag) . BASE . '/pesquisa/' . urlencode(
                                                trim($tag)
                                            ) . sprintf("'>%s</a>", $tag);
                                    }
                                    ?>
								</div>
							</div>
                            <?php
                            require __DIR__ . '/../../_cdn/widgets/share/share.wc.php';
                            ?>
						</div>
						<div class="clearfix mh1"></div>
						<div class="row mb49">
							<div class="col-lg-12">
								<div class="ndAuthor">
									<div class="ndAuthorInner text-center">
										<img src="<?php
                                        echo BASE . ('/uploads/' . $user_thumb); ?>"
										     alt="<?php
                                             echo $AuthorName; ?>"
										     title="<?php
                                             echo $AuthorName; ?>"/>
										<h3><?php
                                            echo $AuthorName; ?></h3>
										<p><?php
                                            echo $user_description; ?></p>
									</div>
								</div>
							</div>
						</div>
						<!-- COMMENTS -->
						<div class="entry-comments mt-20">
							<h3 class="heading relative heading-small uppercase bottom-line style-2 left-align mb-40"></h3>

                            <?php
                            if (APP_COMMENTS && COMMENT_ON_POSTS) {
                                $CommentKey = $post_id;
                                $CommentType = 'post';

                                require __DIR__ . '/../../_cdn/widgets/comments/comments.php';
                            }
                            ?>
						</div>
						<!--  END COMMENTS -->

						<!-- RELATED POSTS -->
                        <?php
                        $Read->exeRead(
                            DB_POSTS,
                            'WHERE post_status = 1 AND post_date <= NOW() AND post_category_parent != :ct AND post_id != :id ORDER BY post_date DESC LIMIT 3',
                            sprintf('ct=%s&id=%s', $post_category_parent, $post_id)
                        );

                        if ($Read->getResult()) {
                            echo "<div class='related-posts mt-40'>";
                            echo "<h3 class='heading relative heading-small uppercase bottom-line style-2 left-align mb-30'>
                                    Posts Relacionados</h3>";
                            echo "<div class='row'>";
                            foreach ($Read->getResult() as $Post) {
                                extract($Post);

                                require REQUIRE_PATH . '/inc/post.php';
                            }

                            echo '</div></div>';
                        }
                        ?>
						<!-- END RELATED POSTS -->
					</div>
				</div>
			</div>
            <?php
            require REQUIRE_PATH . '/inc/sidebar.php'; ?>
		</div>
	</div>
</section>
