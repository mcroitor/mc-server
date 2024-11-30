<?php

include __DIR__ . '/../src/mc/httpstatus.php';
include __DIR__ . '/../src/mc/httprequest.php';
include __DIR__ . '/../src/mc/httpresponse.php';
include __DIR__ . '/../src/mc/server.php';

use mc\Server;

$server = new Server([
    Server::HOST => 'localhost',
    Server::PORT => 8080,
]);

$server->SetHandler(
    function ($request) {
        $httpRequest = new mc\HttpRequest($request);
        echo "accessed: " .
            $httpRequest->getHeader(mc\HttpRequest::HEADER_HOST) .
            $httpRequest->getPath() . PHP_EOL;
        $path = $httpRequest->getPath();
        if ($path === '/') {
            $path = '/index.html';
        }
        $path = __DIR__ . "/site/{$path}";
        echo "path: $path" . PHP_EOL;
        if (!file_exists($path)) {
            $result = new mc\HttpResponse('Not Found');
            $result->setStatus(mc\HttpStatus::NOT_FOUND);
            return $result->__toString();
        }
        $page = file_get_contents($path);

        $httpResponse = new \mc\HttpResponse($page);
        return $httpResponse->__toString();
    }
);

$server->run();
