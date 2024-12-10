<?php

namespace Caiquebispo\Project\Core\Service;

class Cache
{
    private $cacheDir;

    public function __construct(string $cacheDir = 'cache')
    {
        $this->cacheDir = '../storage/' . $cacheDir;
        // if (!is_dir($this->cacheDir)) {
        //     mkdir($this->cacheDir, 0775, true);
        // }
    }

    private function getFilePath(string $key): string
    {
        return $this->cacheDir . '/' . md5($key) . '.cache';
    }

    public function put(string $key, mixed $value, int $minutes = 60): bool
    {
        $filePath = $this->getFilePath($key);
        
        $data = [
            'value' => serialize($value),
            'expires_at' => time() + $minutes * 60,
        ];
        return file_put_contents($filePath, json_encode($data)) !== false;
    }

    public function get(string $key): mixed
    {
        $filePath = $this->getFilePath($key);
        if (!file_exists($filePath)) {
            return null;
        }

        $data = json_decode(file_get_contents($filePath), true);
        if ($data['expires_at'] < time()) {
            $this->forget($key);
            return null;
        }

        return unserialize($data['value']);
    }

    public function forget(string $key): bool
    {
        $filePath = $this->getFilePath($key);
        return unlink($filePath);
    }

    public function has(string $key): bool
    {
        $filePath = $this->getFilePath($key);
        return file_exists($filePath) && $this->get($key) !== null;
    }

    public function flush(): bool
    {
        foreach (glob($this->cacheDir . '/*') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        return true;
    }
    public function remember(string $key, \Closure $callback, int $minutes = 60): mixed
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $value = $callback();
        $this->put($key, $value, $minutes);
        return $value;
    }
}
