<?php

declare(strict_types=1);

namespace App\Config;

use App\Conn\Read;
use Closure;
use Dotenv\Dotenv;

final class ConfigLoader
{
    private const DEFAULT_IMAGE_PATH = '/images/default.jpg';

    private static bool $booted = false;

    public static function boot(): void
    {

        if (self::$booted) {
            return;
        }

        self::$booted = true;

        self::loadEnv();
        self::defineApplicationConstants();
        self::defineAgencyConstants();
        self::defineClientConstants();
        self::defineBasePaths();
        self::defineAdminDefaults();
        self::defineMediaDefaults();
        self::defineApplicationModules();
        self::defineLevelPermissions();
        self::defineSegmentationDefaults();
        self::defineLinkAndAccountConfig();
        self::defineCacheConfig();
        self::defineDatabaseTableConstants();
        self::defineDatabaseCredentials();
        self::defineIntegrationDefaults();
        self::loadConfigFromDatabase();
        self::configureErrorHandling();
    }

    private static function loadEnv(): void
    {

        $envDir = __DIR__;

        if (file_exists($envDir . '/.env')) {
            $dotenv = Dotenv::createImmutable($envDir);
            $dotenv->safeLoad();
        }
    }

    /**
     * Traduz as variáveis de ambiente da aplicação (APP_*) em constantes.
     * Precisa rodar antes de defineBasePaths(), que depende de APP_LOCALHOST,
     * APP_DOMAIN e APP_THEME já existirem como constantes.
     */
    private static function defineApplicationConstants(): void
    {

        $defaults = [
            'APP_NAME' => self::env('APP_NAME', 'CMS'),
            'APP_ENV' => self::env('APP_ENV', 'production'),
            'APP_DEBUG' => filter_var(self::env('APP_DEBUG', false), FILTER_VALIDATE_BOOLEAN),
            'APP_LOCALHOST' => self::env('APP_LOCALHOST', 'http://localhost'),
            'APP_DOMAIN' => self::env('APP_DOMAIN', ''),
            'APP_THEME' => self::env('APP_THEME', 'default'),
            'APP_TIMEZONE' => self::env('APP_TIMEZONE', 'America/Sao_Paulo'),
            'APP_LOCALE' => self::env('APP_LOCALE', 'pt_BR'),
        ];

        self::defineConstants($defaults);
    }

    private static function defineAgencyConstants(): void
    {

        /** @var array<string, \Closure|scalar> $agency */
        $agency = require __DIR__ . '/Agency.inc.php';
        self::defineConstants($agency);
    }

    /**
     * @param array<string, \Closure|scalar> $constants
     */
    private static function defineConstants(array $constants): void
    {

        foreach ($constants as $name => $value) {
            self::defineIfNotDefined($name, $value);
        }
    }

    /**
     * @param null|\Closure|scalar $value
     */
    private static function defineIfNotDefined(string $name, $value): void
    {

        if (defined($name)) {
            return;
        }

        if ($value instanceof Closure) {
            $value = $value();
        }

        define($name, $value);
    }

    private static function defineClientConstants(): void
    {

        /** @var array<string, \Closure|scalar> $client */
        $client = require __DIR__ . '/Client.inc.php';
        self::defineConstants($client);
    }

    private static function defineBasePaths(): void
    {

        $host = $_SERVER['HTTP_HOST'] ?? null;
        $isLocal = is_string($host) && 'localhost' === $host;

        self::defineIfNotDefined(
            'BASE',
            ($isLocal ? APP_LOCALHOST : APP_DOMAIN)
        );

        if (!defined('THEME')) {
            $sessionTheme = $_SESSION['WC_THEME'] ?? null;
            $theme = is_string($sessionTheme) && '' !== $sessionTheme ? $sessionTheme : APP_THEME;

            define('THEME', $theme);
        }

        self::defineIfNotDefined('INCLUDE_PATH', BASE . '/themes/' . THEME);
        self::defineIfNotDefined('REQUIRE_PATH', 'themes/' . THEME);
    }

    private static function defineAdminDefaults(): void
    {

        $defaults = [
            'ADMIN_NAME' => 'Nome do CMS - Painel de Controle',
            'ADMIN_DESC' => "Descrição do Painel de Controle",
            'ADMIN_MODE' => 1,
            'ADMIN_WC_CUSTOM' => 1,
            'ADMIN_MAINTENANCE' => 0,
            'ADMIN_VERSION' => '3.5.0',
            'MAIL_HOST' => self::env('MAIL_HOST', 'mail.seu-dominio.com.br'),
            'MAIL_PORT' => self::env('MAIL_PORT', '465'), // 587 sem autenticação ou 465 autenticado
            'MAIL_USER' => self::env('MAIL_USER', 'noreply@seu-dominio.com.br'),
            'MAIL_SMTP' => self::env('MAIL_SMTP', 'noreply@seu-dominio.com.br'),
            'MAIL_PASS' => self::env('MAIL_PASS', 'senha-do-email'),
            'MAIL_SENDER' => self::env('MAIL_SENDER', 'Industing'),
            'MAIL_MODE' => self::env('MAIL_MODE', 'tls'),
            'MAIL_TESTER' => self::env('MAIL_TESTER', 'e-mail-para-testes@xmail.com'),
        ];

        self::defineConstants($defaults);
    }

    private static function defineMediaDefaults(): void
    {

        $defaults = [
            'IMAGE_W' => 1200,
            'IMAGE_H' => 628,
            'THUMB_W' => 800,
            'THUMB_H' => 800,
            'AVATAR_W' => 500,
            'AVATAR_H' => 500,
            'SLIDE_W' => 1920,
            'SLIDE_H' => 900,
            'VIDEO_W' => 1280,
            'VIDEO_H' => 720,
        ];

        self::defineConstants($defaults);
    }

    private static function defineApplicationModules(): void
    {

        $defaults = [
            'APP_POSTS' => 1,
            'APP_POSTS_AMP' => 0,
            'APP_POSTS_INSTANT_ARTICLE' => 0,
            'APP_SEARCH' => 1,
            'APP_PAGES' => 1,
            'APP_COMMENTS' => 1,
            'APP_SLIDE' => 1,
            'APP_USERS' => 1,
            'APP_VIDEOS' => 0,
            'APP_MATERIALS' => 0,
            'APP_DEPOSITIONS' => 0,
            'APP_ALBUMS' => 0,
            'APP_PDT' => 1,
            'APP_SERVICES' => 0,
            'APP_SEGMENTS' => 0,
            'APP_CURIOSITIES' => 0,
            'APP_CV' => 0,
            'APP_OUVIDORIA' => 0,
            'APP_CERTIFICATIONS' => 0,
            'APP_HELLO' => 0,
            'APP_LANDING_PAGES' => 0,
            'APP_THANKYOU_PAGES' => 0,
            'APP_LEADS' => 0,
            'APP_PARTNERS' => 0,
            'APP_REPRESENTATIVES' => 0,
            'APP_LINKTREE' => 0,
            /*MIGRAÇÃO DE MODULOS*/
            'APP_HOMEPAGE' => 0,
            'APP_ABOUTPAGE' => 0,
            'APP_SKILLSPAGE' => 0,
            'APP_PROJETOS' => 0
        ];

        self::defineConstants($defaults);
    }

    private static function defineLevelPermissions(): void
    {

        $levels = [
            /*MIGRAÇÃO DE MODULOS*/
            'LEVEL_WC_HOMEPAGE' => 6,
            'LEVEL_WC_ABOUTPAGE' => 6,
            'LEVEL_WC_SKILLSPAGE' => 6,
            'LEVEL_WC_PROJETOS' => 6,

            'LEVEL_WC_POSTS' => 6,
            'LEVEL_WC_COMMENTS' => 6,
            'LEVEL_WC_LINKTREE' => 6,
            'LEVEL_WC_PAGES' => 9,
            'LEVEL_WC_SLIDES' => 9,
            'LEVEL_WC_REPORTS' => 9,
            'LEVEL_WC_USERS' => 9,
            'LEVEL_WC_VIDEOS' => 9,
            'LEVEL_WC_DEPOSITIONS' => 9,
            'LEVEL_WC_PARTNERS' => 9,
            'LEVEL_WC_ALBUMS' => 9,
            'LEVEL_WC_PRODUCTS' => 9,
            'LEVEL_WC_SERVICES' => 9,
            'LEVEL_WC_SEGMENTS' => 9,
            'LEVEL_WC_CURIOSITIES' => 9,
            'LEVEL_WC_CV' => 9,
            'LEVEL_WC_OUVIDORIA' => 9,
            'LEVEL_WC_CERTIFICATIONS' => 9,
            'LEVEL_WC_REPRESENTATIVES' => 9,
            'LEVEL_WC_HELLO' => 9,
            'LEVEL_WC_LANDING_PAGES' => 9,
            'LEVEL_WC_THANKYOU_PAGES' => 9,
            'LEVEL_WC_LEADS' => 9,
            'LEVEL_WC_CONFIG_MASTER' => 10,
            'LEVEL_WC_CONFIG_API' => 10,
            'LEVEL_WC_CONFIG_CODES' => 10,
        ];

        self::defineConstants($levels);
    }

    private static function defineSegmentationDefaults(): void
    {

        $defaults = [
            'SEGMENT_FB_PAGE_ID' => '',
            'SEGMENT_FB_PIXEL_ID' => '',
            'SEGMENT_WC_USER' => 1,
            'SEGMENT_WC_BLOG' => 1,
            'SEGMENT_GL_ANALYTICS' => '',
            'SEGMENT_GL_TAGMANAGER' => 'GTM-MBQ2G8X',
            'SEGMENT_GL_ADWORDS_ID' => '',
            'SEGMENT_GL_ADWORDS_LABEL' => ''
        ];

        self::defineConstants($defaults);
    }

    private static function defineLinkAndAccountConfig(): void
    {

        $defaults = [
            'APP_LINK_POSTS' => 1,
            'APP_LINK_PAGES' => 1,
            'APP_LINK_PRODUCTS' => 1,
            'APP_LINK_PROPERTIES' => 1,
            'ACC_MANAGER' => 1,
            'ACC_TAG' => 'Minha Conta',
            'COMMENT_MODERATE' => 1,
            'COMMENT_ON_POSTS' => 1,
            'COMMENT_ON_PAGES' => 0,
            'COMMENT_ON_PRODUCTS' => 0,
            'COMMENT_SEND_EMAIL' => 1,
            'COMMENT_ORDER' => 'DESC',
            'COMMENT_RESPONSE_ORDER' => 'ASC',
            'SAC_URL' => 'https://seu-dominio.com.br',
        ];

        self::defineConstants($defaults);
    }

    private static function defineCacheConfig(): void
    {

        self::defineIfNotDefined('SIS_CACHE_TIME', 10);
        self::defineIfNotDefined('SIS_CONFIG_WC', 1);
        self::defineIfNotDefined('DB_AUTO_TRASH', 1);
        self::defineIfNotDefined('DB_AUTO_PING', 1);
    }

    private static function defineDatabaseTableConstants(): void
    {

        $tables = [
            'DB_CONF' => 'ws_config',
            'DB_USERS' => 'ws_users',
            'DB_USERS_ADDR' => 'ws_users_address',
            'DB_USERS_NOTES' => 'ws_users_notes',
            'DB_POSTS' => 'ws_posts',
            'DB_POSTS_IMAGE' => 'ws_posts_images',
            'DB_CATEGORIES' => 'ws_categories',
            'DB_SEARCH' => 'ws_search',
            'DB_PAGES' => 'ws_pages',
            'DB_PAGES_IMAGE' => 'ws_pages_images',
            'DB_COMMENTS' => 'ws_comments',
            'DB_COMMENTS_LIKES' => 'ws_comments_likes',
            'DB_HELLO' => 'ws_hellobar',
            'DB_SLIDES' => 'ws_slides',
            'DB_VIEWS_VIEWS' => 'ws_siteviews_views',
            'DB_VIEWS_ONLINE' => 'ws_siteviews_online',
            'DB_WC_API' => 'workcontrol_api',
            'DB_WC_CODE' => 'workcontrol_code',
            'DB_YOUTUBE' => 'ws_youtube',
            'DB_MATERIAIS' => 'ws_materiais',
            'DB_MATCATEGORIES' => 'ws_matcategories',
            'DB_LEADS' => 'ws_leads',
            'DB_ALBUMS' => 'ws_albums',
            'DB_ALBUMS_IMAGE' => 'ws_albums_images',
            'DB_DEPOSITIONS' => 'ws_depositions',
            'DB_PARTNERS' => 'ws_partners',
            'DB_LANDING_PAGES' => 'ws_landingpages',
            'DB_LANDING_PAGES_IMAGES' => 'ws_landingpages_images',
            'DB_THANKYOU_PAGES' => 'ws_thankyoupages',
            'DB_BANNERS' => 'ws_banners',
            'DB_PDT' => 'ws_products',
            'DB_PDT_CATS' => 'ws_products_categories',
            'DB_PDT_LINES' => 'ws_products_lines',
            'DB_PDT_IMAGE' => 'ws_products_images',
            'DB_PDT_GALLERY' => 'ws_products_gallery',
            'DB_PDT_BRANDS' => 'ws_products_brands',
            'DB_PDT_COMBO' => 'ws_products_combo',
            'DB_PDT_COUPONS' => 'ws_products_coupons',
            'DB_PDT_STOCK' => 'ws_products_stock',
            'DB_PDT_WISHLIST' => 'ws_products_wishlist',
            'DB_PDT_ATTR_COLORS' => 'ws_products_attributes_colors',
            'DB_PDT_ATTR_PRINTS' => 'ws_products_attributes_prints',
            'DB_PDT_ATTR_SIZES' => 'ws_products_attributes_sizes',
            'DB_PDT_GROUPS_ATTR' => 'ws_products_groups_attributes',
            'DB_PDT_TRAVI' => 'ws_products_travi',
            'DB_PDT_IMAGE_TRAVI' => 'ws_products_images_travi',
            'DB_PDT_IMAGE_CAT_TRAVI' => 'ws_products_images_cat_travi',
            'DB_PDT_GALLERY_TRAVI' => 'ws_products_gallery_travi',
            'DB_PDT_CATS_TRAVI' => 'ws_products_categories_travi',
            'DB_PDT_PROCESS_TRAVI' => 'ws_products_processes_travi',
            'DB_PDT_FORMAT_TRAVI' => 'ws_products_formats_travi',
            'DB_SVC' => 'ws_services',
            'DB_SVC_IMAGE' => 'ws_services_images',
            'DB_SVC_GALLERY' => 'ws_services_gallery',
            'DB_SEG' => 'ws_segments',
            'DB_SEG_IMAGE' => 'ws_segments_images',
            'DB_SEG_GALLERY' => 'ws_segments_gallery',
            'DB_CERT' => 'ws_certifications',
            'DB_CERT_IMAGE' => 'ws_certifications_images',
            'DB_REPRESENTATIVES' => 'ws_representatives',
            'DB_STATES' => 'states',
            'DB_CITIES' => 'cities',
            'DB_CURIOSITIES' => 'ws_curiosities',
            'DB_CV' => 'ws_job_candidates',
            'DB_OUVIDORIA' => 'ws_ouvidoria',
            'DB_CARD_USER' => 'trv_card_user',
            'DB_HOMEPAGE' => 'ws_home',
            'DB_PORTIFOLIO' => 'ws_portifolio',
            'DB_PORTIFOLIO_IMAGES' => 'ws_portifolio_images',
            'DB_SKILLSPAGE' => 'ws_skills',
            'DB_ABOUTPAGE' => 'ws_about',
            'DB_CATEGORIES_PORTIFOLIO' => 'ws_categories_portifolio',
            'DB_TEMPLATE_COLORS' => 'ws_template_colors'
        ];

        self::defineConstants($tables);
    }

    private static function defineDatabaseCredentials(): void
    {

        $isLocal = isset($_SERVER['HTTP_HOST']) && 'localhost' === $_SERVER['HTTP_HOST'];

        if ($isLocal) {
            self::defineIfNotDefined('SIS_DB_HOST', self::env('DB_HOST_DEV', 'localhost'));
            self::defineIfNotDefined('SIS_DB_USER', self::env('DB_USER_DEV', 'root'));
            self::defineIfNotDefined('SIS_DB_PASS', self::env('DB_PASSWORD_DEV', ''));
            self::defineIfNotDefined('SIS_DB_NAME', self::env('DB_NAME_DEV', ''));
        } else {
            self::defineIfNotDefined('SIS_DB_HOST', self::env('DB_HOST_PRODUCTION', 'localhost'));
            self::defineIfNotDefined('SIS_DB_USER', self::env('DB_USER_PRODUCTION', ''));
            self::defineIfNotDefined('SIS_DB_PASS', self::env('DB_PASSWORD_PRODUCTION', ''));
            self::defineIfNotDefined('SIS_DB_NAME', self::env('DB_NAME_PRODUCTION', ''));
        }
    }

    private static function env(string $key, mixed $default = null): mixed
    {

        return $_ENV[$key] ?? getenv($key) ?? $default;
    }

    /**
     * Constantes de integrações externas (pagamento, mapas, recaptcha, storage)
     * declaradas no .env mas ainda sem consumidor no código-fonte.
     */
    private static function defineIntegrationDefaults(): void
    {

        $defaults = [
            'PAYPAL_CLIENT_ID' => self::env('PAYPAL_CLIENT_ID', ''),
            'PAYPAL_SECRET' => self::env('PAYPAL_SECRET', ''),
            'MERCADOPAGO_PUBLIC_KEY' => self::env('MERCADOPAGO_PUBLIC_KEY', ''),
            'MERCADOPAGO_ACCESS_TOKEN' => self::env('MERCADOPAGO_ACCESS_TOKEN', ''),
            'PAGSEGURO_EMAIL' => self::env('PAGSEGURO_EMAIL', ''),
            'PAGSEGURO_TOKEN' => self::env('PAGSEGURO_TOKEN', ''),
            'GOOGLE_MAPS_API_KEY' => self::env('GOOGLE_MAPS_API_KEY', ''),
            'RECAPTCHA_SITE_KEY' => self::env('RECAPTCHA_SITE_KEY', ''),
            'RECAPTCHA_SECRET_KEY' => self::env('RECAPTCHA_SECRET_KEY', ''),
            'STORAGE_DRIVER' => self::env('STORAGE_DRIVER', 'local'),
            'STORAGE_PATH' => self::env('STORAGE_PATH', '/uploads'),
        ];

        self::defineConstants($defaults);
    }

    private static function loadConfigFromDatabase(): void
    {

        $sisConfig = (int)(defined('SIS_CONFIG_WC') ? constant('SIS_CONFIG_WC') : 0);
        $isCli = PHP_SAPI === 'cli' || PHP_SAPI === 'cli-server';
        $disableDb = '1' === getenv('DISABLE_DB_ON_BOOT');

        if (0 === $sisConfig || $isCli || $disableDb) {
            return;
        }

        $read = new Read();
        $read->fullRead('SELECT conf_key, conf_value FROM ' . DB_CONF);
        $configs = $read->getResult();
        if (!is_array($configs) || [] === $configs) {
            return;
        }

        foreach ($configs as $configRow) {
            if (!is_array($configRow)) {
                continue;
            }

            $constantName = self::stringValue($configRow['conf_key'] ?? null);
            if (null === $constantName || defined($constantName)) {
                continue;
            }

            if (
                'THEME' === $constantName
                && isset($_SESSION['WC_THEME'])
                && '' !== $_SESSION['WC_THEME']
            ) {
                continue;
            }

            $value = $configRow['conf_value'] ?? null;
            if (null === $value) {
                define($constantName, '');

                continue;
            }

            if (is_scalar($value)) {
                define($constantName, (string)$value);
            }
        }
    }

    private static function stringValue(mixed $value): ?string
    {

        if (null === $value) {
            return null;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            return '' === $trimmed ? null : $trimmed;
        }

        if (is_numeric($value)) {
            return (string)$value;
        }

        return null;
    }

    private static function configureErrorHandling(): void
    {

        if (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') {
            LegacyErrorHandler::register();
        } else {
            $currentReporting = error_reporting();
            error_reporting($currentReporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        }
    }

    private static function defaultImagePath(): string
    {

        $basePath = defined('INCLUDE_PATH') ? INCLUDE_PATH : (defined('BASE') ? BASE . '/themes/' . THEME : '');

        return $basePath . self::DEFAULT_IMAGE_PATH;
    }
}
