<?php

/*
 * vim: tabstop=8 expandtab shiftwidth=4 softtabstop=4
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'vendor',
        'app/storage',
    ]);


return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR1'        => true,
        '@PSR2'        => true,
        '@Symfony'     => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'no_unused_imports'                 => true,
        'ordered_imports'                   => true,
        'ordered_class_elements'            => true,
        'trailing_comma_in_multiline_array' => true,
        'binary_operator_spaces'            => [
            'operators' => [
                '=>' => 'align',
            ]
        ],
        'declare_strict_types' => true,
    ]);
