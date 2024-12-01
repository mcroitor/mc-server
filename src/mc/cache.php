<?php

namespace mc;

class Cache{
    public const TTL = 'ttl';
    public const KEY_SIZE = 'key_size';
    public const VALUE_SIZE = 'value_size';
    public const CACHE_SIZE = 'cache_size';

    private $data = [];
    private $config = [
        self::TTL => 60,
        self::KEY_SIZE => 100,
        self::VALUE_SIZE => 1000,
        self::CACHE_SIZE => 1000,
    ];

    public function __construct(array $config = []){
        foreach($config as $key => $value){
            if(\array_key_exists($key, $this->config)){
                $this->config[$key] = $value;
            }
        }
    }

    public function Set(string $key, string $value){
        if(\strlen($key) > $this->config[self::KEY_SIZE]){
            throw new \Exception('Key size is too large');
        }

        if(\strlen($value) > $this->config[self::VALUE_SIZE]){
            throw new \Exception('Value size is too large');
        }

        if(\count($this->data) >= $this->config[self::CACHE_SIZE]){
            throw new \Exception('Cache is full');
        }

        $this->data[$key] = [
            'value' => $value,
            'time' => \time(),
        ];
    }

    public function Get(string $key){
        if(!isset($this->data[$key])){
            return null;
        }

        if(\time() - $this->data[$key]['time'] > $this->config[self::TTL]){
            unset($this->data[$key]);
            return null;
        }

        return $this->data[$key]['value'];
    }

    public function Delete(string $key){
        unset($this->data[$key]);
    }

    public function Clear(){
        $this->data = [];
    }

    public function Info(){
        return [
            'size' => \count($this->data),
            'config' => $this->config,
        ];
    }

    public function Purge(){
        $this->data = \array_filter($this->data, function($item){
            return \time() - $item['time'] <= $this->config[self::TTL];
        });
    }
}