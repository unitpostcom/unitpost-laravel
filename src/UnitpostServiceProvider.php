<?php

declare(strict_types=1);

namespace Unitpost\Laravel;

use Illuminate\Support\ServiceProvider;
use Unitpost\Client;

final class UnitpostServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/unitpost.php', 'unitpost');
        $this->app->singleton(Client::class, function () {
            return new Client([
                'apiKey' => config('unitpost.api_key'),
                'baseUrl' => config('unitpost.base_url'),
            ]);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/unitpost.php' => config_path('unitpost.php'),
        ], 'unitpost-config');
    }
}
