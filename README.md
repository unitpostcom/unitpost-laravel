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

Docs: https://www.unitpost.com/docs
