<?php
use Rector\PHPUnit\Set\PHPUnitSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Core\Configuration\Option;

return function (ContainerConfigurator $containerConfigurator): void {
     $containerConfigurator->import(PHPUnitSetList::PHPUNIT_60);
};
