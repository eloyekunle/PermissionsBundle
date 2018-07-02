<?php

$header = <<<EOF
This file is part of the FOSUserBundle package.

(c) FriendsOfSymfony <http://friendsofsymfony.github.com/>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'long'],
        'combine_consecutive_unsets' => true,
        'header_comment' => ['header' => $header],
        'linebreak_after_opening_tag' => true,
        'no_php4_constructor' => true,
        'no_useless_else' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'php_unit_construct' => true,
        'php_unit_strict' => true,
        'phpdoc_no_empty_return' => false,
    ])
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;
