<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;

// Set composer vendor path.
$composer_vendor = str_replace('/autoload.php', '', EDISONLABS_COMPOSER_INSTALL);

// Find packages using the Metrics classes.
$finder = new Finder();
$finder->directories();
$finder->followLinks();
$finder->in("$composer_vendor/..");
$finder->name('EdisonLabs');

// Register services.
if ($finder->count() !== 0) {
    $definition = new Definition();
    $definition
        ->setAutowired(true)
        ->setAutoconfigured(true)
        ->setPublic(true);

    foreach ($finder as $folder) {
        // $this is a reference to the current loader
        $this->registerClasses(
            $definition,
            'EdisonLabs\\Metrics\\',
            $folder->getRealPath() . '/Metrics'
        );
    }
}