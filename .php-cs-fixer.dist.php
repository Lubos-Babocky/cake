<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__.'/src')
    ->exclude('vendor');

return (new Config())
    ->setRules([
        '@Symfony' => true,
        // ďalšie pravidlá
    ])
    ->setFinder($finder);
