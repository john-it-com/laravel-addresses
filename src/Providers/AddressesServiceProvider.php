<?php

declare(strict_types=1);

namespace Rinvex\Addresses\Providers;

use Rinvex\Addresses\Models\Address;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Addresses\Console\Commands\MigrateCommand;
use Rinvex\Addresses\Console\Commands\PublishCommand;
use Rinvex\Addresses\Console\Commands\RollbackCommand;

class AddressesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class,
        PublishCommand::class,
        RollbackCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.addresses');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'rinvex.addresses.address' => Address::class,
        ]);

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'rinvex.addresses');

        // publish config files
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('rinvex.addresses.php'),
        ], 'config');

//        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
    }
}
