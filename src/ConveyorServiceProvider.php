<?php

namespace Tanzar\Conveyor;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ConveyorServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->name('Conveyor')
            ->discoversMigrations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
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
