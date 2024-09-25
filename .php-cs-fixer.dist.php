<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__.'/src')
    ->exclude('vendor');

return (new Config())
    ->setRules((new Rshop\CS\Config\Rshop())->getRules())
    ->setFinder($finder);