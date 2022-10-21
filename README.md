# Guzzle-jsonrpc

This library implements [JSON-RPC 2.0][jsonrpc] for the Guzzle HTTP client

# Install

```
composer require barechain/guzzle-jsonrpc
```
## Documentation

```php
<?php
use Barechain\GuzzleHttp\JsonRpc\Client;

// Create the client
$client = Client::factory('http://localhost:8000');

// Send a notification
$client->send($client->notification('method', ['key'=>'value']));

// Send a request that expects a response
$client->send($client->request(123, 'method', ['key'=>'value']));

// Send a batch of requests
$client->sendAll([
    $client->request(123, 'method', ['key'=>'value']),
    $client->request(456, 'method', ['key'=>'value']),
    $client->notification('method', ['key'=>'value'])
]);
```

### Async requests

Asynchronous requests are supported by making use of the
[Guzzle Promises][guzzle-promise] library; an implementation of
[Promises/A+][promise].

```php
<?php
use Barechain\GuzzleHttp\JsonRpc\Client;

// Create the client
$client = Client::factory('http://localhost:8000');

// Send an async notification
$promise = $client->sendAsync($client->notification('method', ['key'=>'value']));
$promise->then(function () {
    // Do something
});

// Send an async request that expects a response
$promise = $client->sendAsync($client->request(123, 'method', ['key'=>'value']));
$promise->then(function ($response) {
    // Do something with the response
});

// Send a batch of requests
$client->sendAllAsync([
    $client->request(123, 'method', ['key'=>'value']),
    $client->request(456, 'method', ['key'=>'value']),
    $client->notification('method', ['key'=>'value'])
])->then(function ($responses) {
    // Do something with the list of responses
});
```

### Throw exception on RPC error

You can throw an exception if you receive an RPC error response by adding the
option `[rpc_error => true]` in the client constructor.

```php
<?php
use Barechain\GuzzleHttp\JsonRpc\Client;
use Barechain\GuzzleHttp\JsonRpc\Exception\RequestException;

// Create the client with the `rpc_error`
$client = Client::factory('http://localhost:8000', ['rpc_error'=>true]);

// Create a request
$request = $client->request(123, 'method', ['key'=>'value']);

// Send the request
try {
    $client->send($request);
} catch (RequestException $e) {
    die($e->getResponse()->getRpcErrorMessage());
}
```





