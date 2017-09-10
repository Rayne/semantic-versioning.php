<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . DIRECTORY_SEPARATOR . 'src',
        __DIR__ . DIRECTORY_SEPARATOR . 'tests',
    ]);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
