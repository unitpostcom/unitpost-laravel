<?php

declare(strict_types=1);

namespace Unitpost\Laravel;

use Illuminate\Support\ServiceProvider;
use Unitpost\Client;
use Unitpost\OrganizationClient;

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
        // Built when first resolved, so an app without an organization key keeps booting.
        $this->app->singleton(OrganizationClient::class, function () {
            return new OrganizationClient([
                'apiKey' => config('unitpost.org_key'),
                'organizationId' => config('unitpost.organization_id'),
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
