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

`Unitpost::sms()` is the SMS channel: **beta, behind the launch gate**. It is wired in its GA shape (send, get, list, brands, numbers, contact SMS consent); while your workspace's gate is off every call returns `404`.

Docs: https://www.unitpost.com/docs
