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
        echo "<div class='trigger trigger_error'>";
        echo sprintf('<b>Erro na Linha: #%s ::</b> %s<br>', $errLine, $errMsg);
        echo sprintf('<small>%s</small>', $errFile);
        echo "<span class='ajax_close'></span></div>";

        if (E_USER_ERROR === $errNo) {
            exit;
        }

        return true;
    }
}
