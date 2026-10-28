<?php

declare(strict_types=1);

namespace Unitpost\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Unitpost\Client;

/**
 * @method static mixed email()
 * @method static mixed sms()
 * @method static mixed contacts()
 * @method static mixed contactFields()
 * @method static mixed segments()
 * @method static mixed brandKits()
 * @method static mixed libraryImages()
 * @method static mixed branding()
 * @method static mixed workspace()
 * @method static mixed webhooks()
 * @method static mixed apiKeys()
 * @method static mixed logs()
 * @method static mixed suppressions()
 * @method static mixed jobs()
 * @method static mixed usage()
 *
 * @see Client
 */
final class Unitpost extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
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
