# unitpost/laravel

Laravel wrapper around [`unitpost/unitpost`](https://packagist.org/packages/unitpost/unitpost).

```bash
composer require unitpost/laravel
```

```php
use Unitpost\Laravel\Facades\Unitpost;

$result = Unitpost::email()->send([
    'from' => 'Acme <team@acme.com>',
    'to' => 'user@example.com',
    'subject' => 'Hello',
    'html' => '<p>Hi</p>',
]);
```

The service provider binds `Unitpost\Client` from `UNITPOST_API_KEY` / `config/unitpost.php`.

`Unitpost::sms()` is the SMS channel (send, get, list, brands, numbers, contact SMS consent). Available on paid plans: on the free plan `send` returns a `403` with code `limit_reached`, while the reads keep working.

Docs: https://www.unitpost.com/docs
