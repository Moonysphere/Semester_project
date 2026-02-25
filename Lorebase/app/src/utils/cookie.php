<?php

class Cookie
{
    private string $name;
    private string $value;
    private int $expire;
    private string $path;
    private string $domain;
    private bool $secure;
    private bool $httponly;

    public function __construct(
        string $name,
        string $value,
        int $duration = 3600, 
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httponly = true
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->expire = time() + $duration;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }

    public function create(): bool
{
    return setcookie($this->name, $this->value, [
        'expires' => $this->expire,
        'path' => $this->path,
        'domain' => $this->domain,
        'secure' => $this->secure,
        'httponly' => $this->httponly,
        'samesite' => 'Strict'
    ]);
}

    public function delete(): bool
    {
        return setcookie($this->name, '', time() - 3600, $this->path);
    }
}