<?php

\header('Content-Type: application/xml');

require_once __DIR__.'/vendor/autoload.php';

use App\Conn\Read;

$Read = $Read ?? new Read();

$getFeed = \filter_input(INPUT_GET, 'app', FILTER_DEFAULT);
$Feed = \strip_tags(\trim((string) $getFeed));

echo '<?xml version="1.0" encoding="UTF-8" ?>'."\r\n";

// Escapa texto para XML
$xml = static fn (mixed $value): string => \htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

if ('instant-articles' == $Feed) {
    echo '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">'."\r\n";
} elseif ('products' == $Feed) {
    // Namespace g: (Google Merchant / catálogo Meta)
    echo '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'."\r\n";
} else {
    echo '<rss version="2.0">'."\r\n";
}

echo '<channel>'."\r\n";

echo '<title>'.$xml(SITE_NAME.' - '.SITE_SUBNAME).'</title>'."\r\n";
echo '<link>'.BASE.('products' == $Feed ? '/produtos/produtos' : '').'</link>'."\r\n";
echo '<description>'.$xml(SITE_DESC).'</description>'."\r\n";
echo '<language>pt-br</language>'."\r\n";

switch ($Feed) {
    case 'products':
        if (!\defined('APP_PDT') || !APP_PDT) {
            break;
        }

        // Categorias: id => [título, árvore] para montar o product_type (Produtos > Eletrodomésticos > Linha Smart Home)
        $PdtCats = [];
        $Read->fullRead('SELECT cat_id, cat_title, cat_tree FROM '.DB_PDT_CATS);
        foreach ($Read->getResult() ?: [] as $Cat) {
            $PdtCats[(int) $Cat['cat_id']] = $Cat;
        }

        $PdtCatPath = static function (int $catId) use ($PdtCats): string {
            if (!isset($PdtCats[$catId])) {
                return '';
            }

            $Ids = \array_filter(\array_map('intval', \explode(',', (string) $PdtCats[$catId]['cat_tree'])));
            $Ids[] = $catId;
            $Titles = [];
            foreach (\array_unique($Ids) as $Id) {
                if (isset($PdtCats[$Id])) {
                    $Titles[] = $PdtCats[$Id]['cat_title'];
                }
            }

            return \implode(' > ', $Titles);
        };

        // Galeria: product_id => [imagens]
        $PdtGallery = [];
        $Read->fullRead('SELECT product_id, image FROM '.DB_PDT_GALLERY.' ORDER BY id ASC');
        foreach ($Read->getResult() ?: [] as $Image) {
            $PdtGallery[(int) $Image['product_id']][] = $Image['image'];
        }

        // Mesma regra da página do produto: ativo e com linha cadastrada
        $Read->fullRead(
            'SELECT p.pdt_id, p.pdt_code, p.pdt_title, p.pdt_subtitle, p.pdt_name, p.pdt_cover, p.pdt_content, '
            .'p.pdt_created, p.pdt_price, p.pdt_offer_price, p.pdt_offer_start, p.pdt_offer_end, p.pdt_category, '
            .'p.pdt_subcategory, p.pdt_model, l.line_title, b.brand_title '
            .'FROM '.DB_PDT.' p '
            .'INNER JOIN '.DB_PDT_LINES.' l ON l.line_id = p.pdt_line '
            .'LEFT JOIN '.DB_PDT_BRANDS.' b ON b.brand_id = p.pdt_brand '
            .'WHERE p.pdt_status = :status ORDER BY p.pdt_created DESC',
            'status=1'
        );

        foreach ($Read->getResult() ?: [] as $Pdt) {
            $PdtLink = BASE.'/produto/'.$Pdt['pdt_name'];
            $PdtImage = BASE.'/uploads/'.$Pdt['pdt_cover'];
            $PdtId = '' !== \trim((string) $Pdt['pdt_code']) ? \trim((string) $Pdt['pdt_code']) : 'product_'.$Pdt['pdt_id'];

            // Descrição em texto puro (ficha técnica); sem conteúdo, usa o subtítulo
            $PdtDesc = \trim((string) \preg_replace('/\s+/u', ' ', \html_entity_decode(\strip_tags(\str_replace(['</p>', '<br>', '<br/>', '<br />'], ' ', (string) $Pdt['pdt_content'])), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            $PdtDesc = '' !== $PdtDesc ? \mb_substr($PdtDesc, 0, 5000) : (string) ($Pdt['pdt_subtitle'] ?: $Pdt['pdt_title']);

            // product_type pela 1ª subcategoria (caminho completo); sem subcategoria, pela 1ª categoria
            $PdtSub = (int) \strtok((string) $Pdt['pdt_subcategory'], ',');
            $PdtType = $PdtCatPath($PdtSub) ?: $PdtCatPath((int) \strtok((string) $Pdt['pdt_category'], ','));

            echo '<item>'."\r\n";
            echo '<title>'.$xml($Pdt['pdt_title']).'</title>'."\r\n";
            echo '<link>'.$xml($PdtLink).'</link>'."\r\n";
            echo '<guid isPermaLink="true">'.$xml($PdtLink).'</guid>'."\r\n";
            echo '<pubDate>'.\date(DATE_RSS, \strtotime((string) $Pdt['pdt_created']) ?: \time()).'</pubDate>'."\r\n";
            echo '<description>'.$xml($PdtDesc).'</description>'."\r\n";
            if ('' !== $PdtType) {
                echo '<category>'.$xml($PdtType).'</category>'."\r\n";
            }
            echo '<enclosure type="image/'.\strtolower(\pathinfo((string) $Pdt['pdt_cover'], PATHINFO_EXTENSION) ?: 'jpeg').'" url="'.$xml($PdtImage).'" length="0"/>'."\r\n";

            // Catálogo (Google Merchant / Meta)
            echo '<g:id>'.$xml($PdtId).'</g:id>'."\r\n";
            echo '<g:title>'.$xml($Pdt['pdt_title']).'</g:title>'."\r\n";
            echo '<g:description>'.$xml($PdtDesc).'</g:description>'."\r\n";
            echo '<g:link>'.$xml($PdtLink).'</g:link>'."\r\n";
            echo '<g:image_link>'.$xml($PdtImage).'</g:image_link>'."\r\n";
            foreach (\array_slice($PdtGallery[(int) $Pdt['pdt_id']] ?? [], 0, 10) as $Image) {
                echo '<g:additional_image_link>'.$xml(BASE.'/uploads/'.$Image).'</g:additional_image_link>'."\r\n";
            }
            echo '<g:brand>'.$xml($Pdt['brand_title'] ?: SITE_NAME).'</g:brand>'."\r\n";
            echo '<g:condition>new</g:condition>'."\r\n";
            echo '<g:availability>in stock</g:availability>'."\r\n";
            echo '<g:mpn>'.$xml($Pdt['pdt_model'] ?: $PdtId).'</g:mpn>'."\r\n";
            echo '<g:identifier_exists>no</g:identifier_exists>'."\r\n";
            if ('' !== $PdtType) {
                echo '<g:product_type>'.$xml($PdtType).'</g:product_type>'."\r\n";
            }
            echo '<g:custom_label_0>'.$xml($Pdt['line_title']).'</g:custom_label_0>'."\r\n";

            // Preço só quando cadastrado (o site hoje é catálogo, sem preço)
            if ((float) $Pdt['pdt_price'] > 0) {
                echo '<g:price>'.\number_format((float) $Pdt['pdt_price'], 2, '.', '').' BRL</g:price>'."\r\n";

                $OfferStart = \strtotime((string) $Pdt['pdt_offer_start']);
                $OfferEnd = \strtotime((string) $Pdt['pdt_offer_end']);
                if ((float) $Pdt['pdt_offer_price'] > 0 && (float) $Pdt['pdt_offer_price'] < (float) $Pdt['pdt_price'] && $OfferStart && $OfferEnd && $OfferEnd > \time()) {
                    echo '<g:sale_price>'.\number_format((float) $Pdt['pdt_offer_price'], 2, '.', '').' BRL</g:sale_price>'."\r\n";
                    echo '<g:sale_price_effective_date>'.\date('c', $OfferStart).'/'.\date('c', $OfferEnd).'</g:sale_price_effective_date>'."\r\n";
                }
            }
            echo '</item>'."\r\n";
        }

        break;

    /* case('products'):
         $Read->exeRead(DB_PDT_TRAVI, "WHERE pdt_inventory >= 1 AND pdt_status = 1 ORDER BY pdt_created DESC");
         if ($Read->getResult()):
             foreach ($Read->getResult() as $ReadPdt):
                 echo '<item>' . "\r\n";
                 echo '<title>' . $ReadPdt['pdt_title'] . '</title>' . "\r\n";
                 echo '<link>' . BASE . '/produto/' . $ReadPdt['pdt_name'] . '</link>' . "\r\n";
                 echo '<pubDate>' . date('D, d M Y H:i:s O', strtotime($ReadPdt['pdt_created'])) . '</pubDate>' . "\r\n";
                 echo '<description>' . str_replace('&', 'e', $ReadPdt['pdt_subtitle']) . '</description>' . "\r\n";
                 echo '<enclosure type="image/*" url="' . BASE . '/uploads/' . $ReadPdt['pdt_cover'] . '"/>' . "\r\n";

                 //FB ADS PRODUCTS
                 echo '<id>product_' . $ReadPdt['pdt_id'] . '</id>' . "\r\n";
                 echo '<image_link>' . BASE . "/uploads/" . $ReadPdt['pdt_cover'] . '</image_link>' . "\r\n";
                 echo '<condition>new</condition>' . "\r\n";
                 echo '<price>' . $ReadPdt['pdt_price'] . '</price>' . "\r\n";

                 $Read->fullRead(
                     "SELECT SUM(stock_inventory) As PdtTotalStock FROM " . DB_PDT_STOCK_ . " WHERE pdt_id = :pdt",
                     "pdt={$ReadPdt['pdt_id']}"
                 );
                 if ($Read->getResult()['0']['PdtTotalStock'] >= 1):
                     echo '<availability>in stock</availability>' . "\r\n";
                 else:
                     echo '<availability>out of stock</availability>' . "\r\n";
                 endif;

                 $Read->linkResult(DB_PDT_BRANDS, "brand_id", $ReadPdt['pdt_brand'], 'brand_title');
                 $PdtGetBrand = ($Read->getResult() ? $Read->getResult()[0]['brand_title'] : SITE_NAME);
                 echo '<brand>' . $PdtGetBrand . '</brand>' . "\r\n";

                 $Read->linkResult(DB_PDT_CATS_TRAVI, "cat_id", $ReadPdt['pdt_subcategory'], 'cat_title');
                 $PdtGetSubCategory = $Read->getResult()[0]['cat_title'];
                 echo '<google_product_category>' . "{$PdtGetSubCategory}" . '</google_product_category>' . "\r\n";
                 echo '</item>' . "\r\n";
             endforeach;
         endif;
         break;
     case('realty'):
         $Read->exeRead(DB_IMOBI, "WHERE realty_status = 1 ORDER BY realty_date DESC");
         if ($Read->getResult()):
             foreach ($Read->getResult() as $ReadRealty):
                 echo '<item>' . "\r\n";
                 echo '<title>' . $ReadRealty['realty_title'] . '</title>' . "\r\n";
                 echo '<link>' . BASE . '/imovel/' . $ReadRealty['realty_name'] . '</link>' . "\r\n";
                 echo '<pubDate>' . date(
                         'D, d M Y H:i:s O',
                         strtotime($ReadRealty['realty_date'])
                     ) . '</pubDate>' . "\r\n";
                 echo '<description>' . str_replace(
                         '&',
                         'e',
                         Check::Words($ReadRealty['realty_desc'], 10)
                     ) . '</description>' . "\r\n";
                 echo '<enclosure type="image/*" url="' . BASE . '/uploads/' . $ReadRealty['realty_cover'] . '"/>' . "\r\n";

                 //FB ADS PRODUCTS
                 echo '<id>realty_' . $ReadRealty['realty_id'] . '</id>' . "\r\n";
                 echo '<image_link>' . BASE . "/uploads/" . $ReadRealty['realty_cover'] . '</image_link>' . "\r\n";
                 echo '<condition>new</condition>' . "\r\n";
                 echo '<price>' . ($ReadRealty['realty_price'] ? $ReadRealty['realty_price'] : '0.00') . '</price>' . "\r\n";
                 echo '<availability>in stock</availability>' . "\r\n";
                 echo '<brand>' . SITE_NAME . '</brand>' . "\r\n";
                 echo '<google_product_category>Imóvel</google_product_category>' . "\r\n";
                 echo '</item>' . "\r\n";
             endforeach;
         endif;
         break;
     case('courses'):
         $Read->exeRead(DB_EAD_COURSES, "WHERE course_status = 1 ORDER BY course_title ASC");
         if ($Read->getResult()):
             foreach ($Read->getResult() as $ReadCourse):
                 echo '<item>' . "\r\n";
                 echo '<title>' . $ReadCourse['course_title'] . '</title>' . "\r\n";
                 echo '<link>' . BASE . "/curso/{$ReadCourse['course_name']}" . '</link>' . "\r\n";
                 echo '<pubDate>' . date(
                         'D, d M Y H:i:s O',
                         strtotime($ReadCourse['course_created'])
                     ) . '</pubDate>' . "\r\n";
                 echo '<description>' . str_replace(
                         '&',
                         'e',
                         Check::Words($ReadCourse['course_headline'], 10)
                     ) . '</description>' . "\r\n";
                 echo '<enclosure type="image/*" url="' . BASE . '/uploads/' . $ReadCourse['course_cover'] . '"/>' . "\r\n";

                 //FB ADS PRODUCTS
                 echo '<id>course_' . $ReadCourse['course_id'] . '</id>' . "\r\n";
                 echo '<image_link>' . BASE . "/uploads/" . $ReadCourse['course_cover'] . '</image_link>' . "\r\n";
                 echo '<condition>new</condition>' . "\r\n";
                 echo '<price>' . $ReadCourse['course_vendor_price'] . '</price>' . "\r\n";
                 echo '<availability>in stock</availability>' . "\r\n";
                 echo '<brand>' . SITE_NAME . '</brand>' . "\r\n";
                 $Read->fullRead(
                     "SELECT segment_title FROM " . DB_EAD_COURSES_SEGMENTS . " WHERE segment_id = :seg",
                     "seg={$ReadCourse['course_segment']}"
                 );
                 $Segment = ($Read->getResult() ? $Read->getResult()[0]['segment_title'] : 'Curso Online');
                 echo '<google_product_category>' . $Segment . '</google_product_category>' . "\r\n";
                 echo '</item>' . "\r\n";
             endforeach;
         endif;
         break;*/

    case 'instant-articles':
        $Read->fullRead(
            'SELECT '
            .'u.user_id, '
            .'u.user_name, '
            .'p.* '
            .'FROM '.DB_POSTS.' p '
            .'INNER JOIN '.DB_USERS.' u ON u.user_id = p.post_author '
            .'WHERE p.post_status = 1 '
            .'AND p.post_instant_article = 1 '
            .'AND p.post_date <= NOW() ORDER BY p.post_date DESC'
        );

        if ($Read->getResult()) {
            $Content = [];
            $InstantArt = new InstantArticles();

            foreach ($Read->getResult() as $ReadPosts) {
                // Slides
                $Slides = null;
                $Read->exeRead(DB_POSTS_IMAGE, ' WHERE post_id = :pid', "pid={$ReadPosts['post_id']}");
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $slide) {
                        $Slides[] = BASE.'/uploads/'.$slide['image'];
                    }
                }

                // Atigos Relacionados
                $ArtRel = null;
                $Read->fullRead(
                    'SELECT * FROM '.DB_POSTS." WHERE post_status = 1 AND post_date <= NOW() AND post_category = {$ReadPosts['post_category']} AND post_id != {$ReadPosts['post_id']} ORDER BY post_date DESC LIMIT 5"
                );
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $rel) {
                        $ArtRel[] = BASE.'/artigo/'.$rel['post_name'];
                    }
                }

                $AnalyticsCode = '';
                $Read->exeRead(DB_WC_CODE);
                if ($Read->getResult()) {
                    foreach ($Read->getResult() as $code) {
                        $AnalyticsCode = $AnalyticsCode.$code['code_script'];
                    }
                }

                $Content = [
                    'canonical' => BASE.'/artigo/'.$ReadPosts['post_name'],
                    // Obrigatório
                    'title' => $ReadPosts['post_title'],
                    // Obrigatório
                    'desc' => \str_replace('&', 'e', $ReadPosts['post_subtitle']),
                    // Obrigatório
                    'capa' => BASE.'/uploads/'.$ReadPosts['post_cover'],
                    // Obrigatório
                    'data_pub' => $ReadPosts['post_date'],
                    // Obrigatório
                    'data_mod' => $ReadPosts['post_date'],
                    // Obrigatório
                    'conteudo' => $ReadPosts['post_content'],
                    // Obrigatório
                    'site_name' => SITE_NAME,
                    // Obrigatório
                    'author' => $ReadPosts['user_name'],
                    // Opcional
                    'slides' => $Slides,
                    // Opcional - é um array('slide01', 'slide02', 'slide03')
                    'artigos_rel' => $ArtRel,
                    // Opcional - é um array('aticle01', 'aticle02', 'aticle03') com max de 3 links - links - http://example.com/article.html
                    'analytics' => $AnalyticsCode,
                    // Opcional - código analytics
                ];

                echo '<item>'."\r\n";
                echo '<title>'.$ReadPosts['post_title'].'</title>'."\r\n";
                echo '<link>'.BASE.'/artigo/'.$ReadPosts['post_name'].'</link>'."\r\n";
                echo '<pubDate>'.\date('D, d M Y H:i:s O', \strtotime($ReadPosts['post_date'])).'</pubDate>'."\r\n";
                echo '<author>'.$ReadPosts['user_name'].'</author>'."\r\n";
                echo '<description>'.\str_replace('&', 'e', $ReadPosts['post_subtitle']).'</description>'."\r\n";
                echo '<enclosure type="image/*" url="'.BASE.'/uploads/'.$ReadPosts['post_cover'].'"/>'."\r\n";
                echo '<content:encoded>'.\str_replace(
                    '</figure></p>',
                    '</figure>',
                    \str_replace('<p><figure>', '<figure>', $InstantArt->ArtCreate($Content))
                ).'</content:encoded>'."\r\n";
                echo '</item>'."\r\n";

                $Content = null;
            }
        }

        break;

    default:
        $Read->exeRead(DB_POSTS, 'WHERE post_status = 1 AND post_date <= NOW() ORDER BY post_date DESC');
        if ($Read->getResult()) {
            foreach ($Read->getResult() as $ReadPosts) {
                echo '<item>'."\r\n";
                echo '<title>'.$ReadPosts['post_title'].'</title>'."\r\n";
                echo '<link>'.BASE.'/artigo/'.$ReadPosts['post_name'].'</link>'."\r\n";
                echo '<pubDate>'.\date('D, d M Y H:i:s O', \strtotime($ReadPosts['post_date'])).'</pubDate>'."\r\n";
                echo '<description>'.\str_replace('&', 'e', $ReadPosts['post_subtitle']).'</description>'."\r\n";
                echo '<enclosure type="image/*" url="'.BASE.'/uploads/'.$ReadPosts['post_cover'].'"/>'."\r\n";
                echo '</item>'."\r\n";
            }
        }

        break;
}

echo '</channel>'."\r\n";
echo '</rss>'."\r\n";
