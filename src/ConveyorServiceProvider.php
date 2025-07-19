<?php

namespace Tanzar\Conveyor;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConveyorServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('conveyor')
            ->hasConfigFile()
            ->hasAssets()
            ->discoversMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile()
                    ->publishAssets()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }

    public function register()
    {

    }

    public function boot()
    {
        
    }
}
