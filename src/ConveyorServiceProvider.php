<?php

namespace Tanzar\Conveyor;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tanzar\Conveyor\Console\InitConveyors;
use Tanzar\Conveyor\Console\UpdateConveyors;

class ConveyorServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('conveyor')
            ->hasConfigFile()
            ->discoversMigrations()
            ->hasCommands([
                InitConveyors::class,
                UpdateConveyors::class,
            ])
            ->hasRoutes([ 'channels', 'web' ])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }
}
