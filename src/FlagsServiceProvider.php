<?php

declare(strict_types=1);

namespace Larabit\Flags;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class FlagsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('flags', []);
            $factory->add('flag', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/flags'),
            ], 'flags');

            $this->publishes([
                __DIR__ . '/../config/flags.php' => $this->app->configPath('flags.php'),
            ], 'flags-config');
        }
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/flags.php', 'flags');
    }
}
