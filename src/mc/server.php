<?php

namespace mc;

class Server {
    public const HOST = 'host';
    public const PORT = 'port';
    public const HANDLER = 'handler';
    private $config = [
        self::HOST => 'localhost',
        self::PORT => 8080,
        self::HANDLER => null
    ];
    private $socket;

    public function __construct($config) {
        $this->checkSocketExtension();
        foreach ($config as $key => $value) {
            if (\array_key_exists($key, $this->config)) {
                $this->config[$key] = $value;
            }
        }
        $this->socket = \socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function getConfig() {
        return $this->config;
    }

    public function SetHandler($handler) {
        $this->config[self::HANDLER] = $handler;
    }

    public function run() {
        \socket_bind($this->socket, $this->config[self::HOST], $this->config[self::PORT]);
        \socket_listen($this->socket);
        while (true) {
            $client = \socket_accept($this->socket);
            $request = \socket_read($client, 1024);
            $response = $this->config[self::HANDLER]($request);
            \socket_write($client, $response);
            \socket_close($client);
        }
    }

    private function checkSocketExtension() {
        if (!\extension_loaded('sockets')) {
            exit('The sockets extension is not loaded, please enable it in your php.ini');
        }
    }
}