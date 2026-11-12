<?php

declare(strict_types=1);

namespace App\Config;

final class LegacyErrorHandler
{
    public static function register(): void
    {
        set_error_handler([self::class, 'handle']);
    }

    /**
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public static function handle(int $errNo, string $errMsg, string $errFile, int $errLine): bool
    {
        if (\defined('APP_DEBUG') && APP_DEBUG) {
            echo "<div class='trigger trigger_error'>";
            echo sprintf('<b>Erro na Linha: #%s ::</b> %s<br>', $errLine, $errMsg);
            echo sprintf('<small>%s</small>', $errFile);
            echo "<span class='ajax_close'></span></div>";
        } else {
            // Produção (APP_DEBUG=false): não expõe o erro na página, só registra no log do PHP
            error_log(sprintf('[%d] %s em %s:%d', $errNo, $errMsg, $errFile, $errLine));
        }

        if (E_USER_ERROR === $errNo) {
            exit;
        }

        return true;
    }
}
