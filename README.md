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
