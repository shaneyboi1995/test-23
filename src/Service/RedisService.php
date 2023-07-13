<?php

namespace App\Service;

use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class RedisService
{
    /**
     * @param ClientInterface $redis
     */
    public function __construct(
        private readonly ClientInterface $redis
    ){}

    /**
     * @param string $key
     * @param array $value
     * @return void
     */
    public function set(string $key, array $value): void
    {
        $this->redis->set($key, $value);
        $this->redis->expire($key, 3600);
    }

    /**
     * @param string $key
     * @param array $value
     * @return void
     */
    public function setArray(string $key, array $value): void
    {
        $this->redis->set($key, serialize($value));
        $this->redis->expire($key, 3600);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->redis->get($key);
    }

    /**
     * @param string $key
     * @return array|null
     */
    public function getArray(string $key): ?array
    {
        $data = $this->redis->get($key);
        return unserialize($data);
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key): bool
    {
        return (bool) $this->redis->exists($key);
    }
}
