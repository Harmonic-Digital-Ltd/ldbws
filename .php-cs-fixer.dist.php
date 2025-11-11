<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        'phpdoc_to_comment' =>  ['allow_before_return_statement' => true],
        'single_line_comment_style' => ['comment_types' => ['hash']]
    ])
    ->setFinder($finder)
;
