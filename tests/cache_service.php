<?php

/**
 * @file tests/cache_service.php
 * @brief Simple HTTP cache service
 * @details This script implements a simple HTTP cache service.
 * Usage: HTTP method defines operation:
 *     - `GET /{key}` - get cached value by key
 *     - `POST /{key}` - set cached value by key
 *     - `DELETE /{key}` - delete cached value by key
 *     - `DELETE /` - delete all cached values
 */

include __DIR__ . '/../src/mc/http/status.php';
include __DIR__ . '/../src/mc/http/request.php';
include __DIR__ . '/../src/mc/http/response.php';
include __DIR__ . '/../src/mc/http/server.php';
include __DIR__ . '/../src/mc/cache.php';

use mc\http\Server;
use mc\http\Status;
use mc\http\Request;
use mc\http\Response;

$server = new Server([
    Server::HOST => 'localhost',
    Server::PORT => 8081,
]);

$cache = new mc\Cache();

$server->SetHandle(
    function (string $request) {
        global $cache;
        // invalidate TTL
        $cache->Purge();
        // Parse HTTP request
        $httpRequest = new Request($request);
        $key = $httpRequest->GetPath();
        $key = trim($key, '/');
        $httpResponse = new Response();
        switch($httpRequest->GetMethod()){
            case Request::METHOD_GET:
                $value = $cache->Get($key);
                if ($value === null) {
                    $httpResponse->SetStatus(Status::NOT_FOUND);
                } else {
                    $httpResponse->SetStatus(Status::OK);
                    $httpResponse->SetBody($value);
                }
                break;
            case Request::METHOD_POST:
                $value = $httpRequest->GetBody();
                $cache->Set($key, $value);
                $httpResponse->SetStatus(Status::CREATED);
                break;
            case Request::METHOD_DELETE:
                if ($key === '') {
                    $cache->Clear();
                    $httpResponse->SetStatus(Status::NO_CONTENT);
                }
                else {
                    $cache->Delete($key);
                    $httpResponse->SetStatus(Status::NO_CONTENT);
                    
                }
                break;
        }
        return $httpResponse->ToString();
    }
);

$server->Run();