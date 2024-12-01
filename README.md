# mc-server

Simple HTTP Server, written in PHP.

Please check usage in the `tests` directory.

## Dependencies

- PHP 7.4 or higher
- sockets extension

## Tests

To run simple HTTP server:

```bash
php tests/test_mcserver.php
```

It will start a server on `localhost:8080`, that will serve files from the `tests/site` directory.

To run HTTP cache server:

```bash
php tests/cache_service.php
```

The Cache service starts on `localhost:8081` and can be used to cache smth with HTTP methods:

- `GET /{key}` - get cached value by key
- `POST /{key}` - set cached value by key, with body as value
- `DELETE /{key}` - delete cached value by key
- `DELETE /` - delete all cached values
