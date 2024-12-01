<?php

namespace mc\http;

class Response {
    public const HEADER_CONTENT_TYPE = 'Content-Type';
    public const HEADER_CONTENT_LENGTH = 'Content-Length';
    public const HEADER_CONNECTION = 'Connection';
    public const HEADER_CACHE_CONTROL = 'Cache-Control';

    private $header = [
        self::HEADER_CONTENT_TYPE => 'text/html',
        self::HEADER_CONNECTION => 'close',
        self::HEADER_CACHE_CONTROL => 'no-cache',
        self::HEADER_CONTENT_LENGTH => 0,
    ];
    private $body = '';
    private $status = \mc\Http\Status::OK;

    public function __construct($body = '') {
        $this->body = $body;
        $this->setHeader(self::HEADER_CONTENT_LENGTH, \strlen($body));
    }

    public function getHeader($key) {
        return $this->header[$key] ?? null;
    }

    public function setHeader($key, $value) {
        $this->header[$key] = $value;
    }

    public function getBody() {
        return $this->body;
    }

    public function setBody($body) {
        $this->body = $body;
        $this->setHeader(self::HEADER_CONTENT_LENGTH, \strlen($body));
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function __toString() {
        $header = "HTTP/1.1 {$this->status} " .
            \mc\Http\Status::getMessage($this->status) . "\r\n";
        foreach ($this->header as $key => $value) {
            $header .= "$key: $value\r\n";
        }
        return "$header\r\n$this->body";
    }

    public function ToString() {
        return $this->__toString();
    }
}