<?php

/**
 * Script para gerar árvore de diretórios e arquivos do projeto
 * Ignora o conteúdo de pastas grandes, mas mantém elas listadas.
 * Salve como project-tree.php e rode:
 * php project-tree.php > arvore.txt.
 *
 * @param mixed $dir
 * @param mixed $prefix
 * @param mixed $excluir
 */
function listarDiretorios($dir, $prefix = '', $excluir = [])
{
    $it = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
    $items = \iterator_to_array($it, false);

    // ordena: diretórios antes, depois arquivos
    \usort($items, function ($a, $b) {
        return (int) $a->isFile() - (int) $b->isFile() ?: \strcasecmp($a->getFilename(), $b->getFilename());
    });

    $total = \count($items);
    $count = 0;

    foreach ($items as $item) {
        ++$count;
        $isLast = $count === $total;
        $pointer = $isLast ? '└── ' : '├── ';
        $nome = $item->getFilename();

        echo $prefix.$pointer.$nome.PHP_EOL;

        // se for diretório e não estiver na lista de exclusão, desce nele
        if ($item->isDir() && !\in_array($nome, $excluir, true)) {
            \listarDiretorios($item->getPathname(), $prefix.($isLast ? '    ' : '│   '), $excluir);
        }
    }
}

$baseDir = __DIR__; // raiz do projeto
$pastasExcluidas = [
    'vendor',
    'uploads',
    'cache',
    'var',
    '.git',
    '.idea',
    '_api',
    'themes',
    'tests',
    'country-flags-main',
    'js',
    '_js',
];

echo \basename($baseDir).PHP_EOL;
\listarDiretorios($baseDir, '', $pastasExcluidas);
