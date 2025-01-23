<?php

declare(strict_types=1);

namespace App\Cache;

use Predis\Client;

class TaxCache
{
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => '6379',
        ]);
    }

    public function set(string $key, string $value): void
    {
        $this->client->set($key, $value);
    }

    public function get(string $key): ?string
    {
        return $this->client->get($key);
    }

    public function delete(string $key): void
    {
        $this->client->del($key);
    }
}
