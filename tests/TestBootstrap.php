<?php

declare(strict_types=1);

use Shopware\Core\TestBootstrapper;

$loader = (new TestBootstrapper())
    ->addCallingPlugin()
    ->addActivePlugins('KommandhubFoundationSW')
    ->setForceInstallPlugins(false)
    ->bootstrap()
    ->getClassLoader();

$loader->addPsr4('Kommandhub\\Foundation\\Tests\\', __DIR__);
