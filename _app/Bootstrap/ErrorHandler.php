<?php

declare(strict_types=1);

namespace App\Bootstrap;

use ErrorException;
use ReflectionClass;
use Throwable;

use function error_get_last;
use function error_reporting;
use function header;
use function headers_sent;
use function htmlspecialchars;
use function http_response_code;
use function in_array;
use function json_encode;
use function register_shutdown_function;
use function set_exception_handler;
use function sprintf;

final class ErrorHandler
{
    public static function register(bool $asJson = false): void
    {

        set_error_handler(
            static function (int $severity, string $message, ?string $file = null, ?int $line = null): bool {

                if ((error_reporting() & $severity) === 0) {
                    return false;
                }

                throw new ErrorException($message, 0, $severity, $file ?? 'unknown', $line ?? 0);
            }
        );

        set_exception_handler(static function (Throwable $e) use ($asJson): void {

            http_response_code(500);
            if ($asJson) {
                if (!headers_sent()) {
                    header('Content-Type: application/json; charset=UTF-8');
                }

                echo json_encode([
                    'error' => true,
                    'message' => 'Erro interno',
                    'type' => (new ReflectionClass($e))->getShortName(),
                    'code' => $e->getCode(),
                ], JSON_UNESCAPED_UNICODE);
            } else {
                if (!headers_sent()) {
                    header('Content-Type: text/html; charset=UTF-8');
                }
                $message = sprintf(
                    "%s: %s\n\n%s",
                    (new ReflectionClass($e))->getShortName(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                );
                echo '<h1>Erro interno</h1><pre>' . htmlspecialchars(
                        $message,
                        ENT_QUOTES | ENT_SUBSTITUTE,
                        'UTF-8'
                    ) . '</pre>';
            }
        });

        register_shutdown_function(static function (): void {

            $err = error_get_last();
            if (
                null !== $err && in_array(
                    $err['type'],
                    [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR],
                    true
                )
            ) {
                http_response_code(500);
                // Em produção: logue; em dev: mostre
            }
        });
    }
}
