<?php

declare(strict_types=1);

namespace Unitpost\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Unitpost\OrganizationClient;

/**
 * The organization client (ADR-0171), for an organization key.
 *
 * @method static mixed workspaces()
 * @method static mixed workspaceKeys()
 * @method static mixed webhooks()
 * @method static mixed usage()
 * @method static mixed planQuotes()
 * @method static mixed sharedPlan()
 *
 * @see OrganizationClient
 */
final class UnitpostOrganization extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OrganizationClient::class;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::getFacadeRoot();
        if ($args === [] && isset($instance->$method)) {
            return $instance->$method;
        }
        return $instance->$method(...$args);
    }
}
