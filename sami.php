<?php

$iterator = \Symfony\Component\Finder\Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('bin')
    ->exclude('build')
    ->exclude('docs')
    ->exclude('Tests')
    ->exclude('vendor')
    ->notName('RoboFile.php')
    ->notName('simple-performance.php')
    ->in(__DIR__)
;

$options = [
    'theme' => 'default',
    'title' => 'Php-Types API Docs',
    'build_dir' => __DIR__ . '/build/api',
    'cache_dir' => __DIR__ . '/build/cache'
];

$sami = new \Sami\Sami($iterator, $options);

return $sami;
