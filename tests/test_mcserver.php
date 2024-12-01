<?php

include __DIR__ . '/../src/mc/http/status.php';
include __DIR__ . '/../src/mc/http/request.php';
include __DIR__ . '/../src/mc/http/response.php';
include __DIR__ . '/../src/mc/http/server.php';

use mc\http\Server;
use mc\http\Status;
use mc\http\Request;
use mc\http\Response;

$server = new Server([
    Server::HOST => 'localhost',
    Server::PORT => 8080,
]);

$server->SetHandle(
    function ($request) {
        $httpRequest = new Request($request);
        echo "accessed: " .
            $httpRequest->GetHeader(Request::HEADER_HOST) .
            $httpRequest->GetPath() . PHP_EOL;
        $path = $httpRequest->GetPath();
        if ($path === '/') {
            $path = '/index.html';
        }
        $path = __DIR__ . "/site/{$path}";
        echo "path: $path" . PHP_EOL;
        if (!file_exists($path)) {
            $result = new Response('Not Found');
            $result->SetStatus(Status::NOT_FOUND);
            return $result->ToString();
        }
        $page = file_get_contents($path);

        $httpResponse = new Response($page);
        return $httpResponse->ToString();
    }
);

$server->run();
