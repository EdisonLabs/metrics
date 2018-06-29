<?php

use Symfony\Component\DependencyInjection\Definition;

$definition = new Definition();

$definition
    ->setAutowired(true)
    ->setAutoconfigured(true)
    ->setPublic(true);

// $this is a reference to the current loader
$this->registerClasses(
    $definition,
    'EdisonLabs\\Metrics\\',
    '/home/jribeiro/Projects/git/composer/edison_metrics-8/src/EdisonLabs/Metrics/*'
);