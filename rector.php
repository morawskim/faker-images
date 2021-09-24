<?php

use Rector\PHPUnit\Set\PHPUnitSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator): void {
     $containerConfigurator->import(PHPUnitSetList::PHPUNIT_80);
};
