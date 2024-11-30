<?php

namespace mc;

class HttpRequest {
    public const HEADER_HOST = 'Host';
    public const HEADER_CONTENT_TYPE = 'Content-Type';
    public const HEADER_CONTENT_LENGTH = 'Content-Length';
    public const HEADER_USER_AGENT = 'User-Agent';
    public const HEADER_ACCEPT = 'Accept';
    public const HEADER_ACCEPT_LANGUAGE = 'Accept-Language';
    public const HEADER_ACCEPT_ENCODING = 'Accept-Encoding';
    public const HEADER_CONNECTION = 'Connection';
    public const HEADER_UPGRADE_INSECURE_REQUESTS = 'Upgrade-Insecure-Requests';
    public const HEADER_CACHE_CONTROL = 'Cache-Control';

    private $method = '';
    private $path = '';
    private $header = [];
    private $body = '';

    public function __construct($request) {
        $this->parse($request);
    }

    private function parse($request) {
        // first line contains method and path
        $lines = \explode("\r\n", $request);
        $firstLine = $lines[0];
        $parts = \explode(' ', $firstLine);
        $this->method = $parts[0];
        $this->path = $parts[1];
        // header and body are separated by two newlines
        $parts = \explode("\r\n\r\n", $request);
        $header = $parts[0];
        $this->body = $parts[1] ?? '';
        $lines = \explode("\r\n", $header);
        $this->parseHeader($lines);
    }

    private function parseHeader($lines) {
        $this->header = [];
        foreach ($lines as $line) {
            $parts = \explode(': ', $line);
            if (\count($parts) === 2) {
                $this->header[$parts[0]] = $parts[1];
            }
        }
    }

    public function getHeader($key) {
        return $this->header[$key] ?? null;
    }

    public function getBody() {
        return $this->body;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getPath() {
        return $this->path;
    }
}