<?php

namespace App\Support;

readonly class RfidScanResult
{
    private function __construct(
        public string $status,
        public string $message,
    ) {}

    public static function recorded(string $message): self
    {
        return new self('recorded', $message);
    }

    public static function unregistered(string $message): self
    {
        return new self('unregistered', $message);
    }

    public static function failed(string $message): self
    {
        return new self('failed', $message);
    }
}
