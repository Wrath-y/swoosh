<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$finder = new Finder();
$finder->in(__DIR__);
