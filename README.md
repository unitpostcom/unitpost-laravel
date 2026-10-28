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

## Retries

The client is the PHP SDK's, with its defaults: `429`, `5xx` and transport errors (timeouts, network failures) are retried up to 2 times (3 attempts in all). When the error response carries a `Retry-After` header (a `429` always does), the client waits that long, read as seconds or an HTTP date and capped at 20 seconds. Otherwise it waits with exponential backoff and full jitter: a random time up to 500 ms × 2^attempt, also capped at 20 seconds. Client errors (`401`/`403`/`404`/`409`/`422`) are never retried. Only `GET`s and writes that carry an idempotency key (`email()->send`, `email()->batch` and `sms()->send` always do) are retried; any other write without a key is sent once.

`config/unitpost.php` has no retry settings. To change them, bind your own client in a service provider's `register()` method; the facade resolves `Unitpost\Client` from the container. The options are the PHP SDK's `maxRetries`, `retryBaseDelay` and `maxRetryDelay` (delays in milliseconds):

```php
use Unitpost\Client;

$this->app->singleton(Client::class, fn () => new Client([
    'apiKey' => config('unitpost.api_key'),
    'baseUrl' => config('unitpost.base_url'),
    'maxRetries' => 0, // no retries at all
]));
```

## Receive a text and answer it

US toll-free, 10DLC and Canadian numbers take replies (`two_way` is true on `Unitpost::sms()->numbers()`). Subscribe a webhook to `sms.received`, skip STOP and texts Unitpost already answered, read the conversation with `participant`, and answer without `from` so the reply leaves from the number they texted.

```php
use Illuminate\Http\Request;
use Unitpost\Laravel\Facades\Unitpost;

// routes/web.php (exclude it from CSRF), subscribed to sms.received.
Route::post('/webhooks/unitpost', function (Request $request) {
    $event = Unitpost::webhooks()->verify(
        $request->getContent(), // the RAW body
        config('services.unitpost.webhook_secret'), // whsec_…; set from env('UNITPOST_WEBHOOK_SECRET') in config/services.php
        $request->headers->all(),
    );
    $reply = $event['data'];
    if ($event['type'] !== 'sms.received' || $reply['keyword'] === 'OPT_OUT' || $reply['auto_replied']) {
        return response()->noContent(); // not a reply, STOP, or already answered
    }

    // The whole conversation with this person, both directions, newest first.
    $history = Unitpost::sms()->list(['participant' => $reply['from'], 'limit' => 20]);
    $answer = yourAgent($history->data, $reply['body'] ?? '');

    // No 'from': the answer goes out from the number they texted.
    Unitpost::sms()->send(['to' => $reply['from'], 'body' => $answer]);
    return response()->noContent();
});
```

Docs: https://www.unitpost.com/docs
