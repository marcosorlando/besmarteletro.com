<?php

declare(strict_types=1);

/*
 * Junta várias folhas CSS em um arquivo só, gerado uma vez e reaproveitado.
 *
 * - O nome leva um hash das datas/tamanhos das folhas: o arquivo é imutável (cache de 1 ano no
 *   .htaccess via ?v=) e qualquer deploy que altere uma folha gera um bundle novo sozinho.
 * - url() relativos são reescritos a partir da pasta de cada folha (fontes e imagens quebrariam
 *   ao mudar de pasta), normalizando os "../".
 * - Retorna o caminho relativo do bundle (com ?v=) ou null se não der para gravar.
 */
return static function (string $root, array $files, string $outDir): ?string {
    $signature = '';
    foreach ($files as $file) {
        $path = $root . '/' . $file;
        if (!is_file($path)) {
            return null;
        }
        $signature .= $file . ':' . filemtime($path) . ':' . filesize($path) . '|';
    }

    $hash = substr(md5($signature), 0, 12);
    $bundle = "{$outDir}/css-{$hash}.css";
    $target = $root . '/' . $bundle;

    if (is_file($target)) {
        return "{$bundle}?v={$hash}";
    }

    if (!is_dir($root . '/' . $outDir) && !@mkdir($root . '/' . $outDir, 0755, true)) {
        return null;
    }

    $basePath = rtrim((string) parse_url(BASE, PHP_URL_PATH), '/');

    $normalize = static function (string $path): string {
        $parts = [];
        foreach (explode('/', $path) as $segment) {
            if ('..' === $segment) {
                array_pop($parts);
            } elseif ('' !== $segment && '.' !== $segment) {
                $parts[] = $segment;
            }
        }

        return implode('/', $parts);
    };

    $css = '@charset "UTF-8";' . "\n";
    foreach ($files as $file) {
        $dir = dirname($file);
        $content = (string) file_get_contents($root . '/' . $file);
        // @charset só vale no início do arquivo; no meio do bundle seria descartado com aviso
        $content = preg_replace('~@charset\s+"[^"]*"\s*;~i', '', $content);
        $content = preg_replace_callback(
            '~url\(\s*([\'"]?)(?!data:|https?:|//|/|#)([^\'")]+)\1\s*\)~i',
            static fn (array $m): string => 'url(' . $m[1] . $basePath . '/' . $normalize($dir . '/' . $m[2]) . $m[1] . ')',
            $content
        );
        $css .= "/* {$file} */\n" . $content . "\n";
    }

    // grava em arquivo temporário e renomeia: uma requisição simultânea nunca lê o bundle pela metade
    $tmp = $target . '.' . getmypid() . '.tmp';
    if (false === @file_put_contents($tmp, $css) || !@rename($tmp, $target)) {
        @unlink($tmp);

        return null;
    }

    foreach (glob($root . '/' . $outDir . '/css-*.css') ?: [] as $old) {
        if ($old !== $target) {
            @unlink($old);
        }
    }

    return "{$bundle}?v={$hash}";
};
