<?php

namespace App\Services\Telegram;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\HttpClients\HttpClientInterface;
use Throwable;

class LaravelHttpClientHandler implements HttpClientInterface
{
    protected int $timeOut = 30;

    protected int $connectTimeOut = 10;

    /**
     * Send HTTP request using Laravel's Http client.
     */
    public function send(
        string $url,
        string $method,
        array $headers = [],
        array $options = [],
        bool $isAsyncRequest = false
    ): ResponseInterface|PromiseInterface|null {
        try {
            $pendingRequest = Http::withHeaders($headers)
                ->timeout($this->timeOut)
                ->connectTimeout($this->connectTimeOut);

            if (isset($options['json'])) {
                $pendingRequest->asJson();
                $body = $options['json'];
            } elseif (isset($options['form_params'])) {
                $pendingRequest->asForm();
                $body = $options['form_params'];
            } elseif (isset($options['body'])) {
                $body = $options['body'];
            } elseif (isset($options['query'])) {
                $body = $options['query'];
            } else {
                $body = [];
            }

            if (strtoupper($method) === 'POST') {
                if (isset($options['multipart']) && is_array($options['multipart'])) {
                    foreach ($options['multipart'] as $part) {
                        $pendingRequest->attach($part['name'], $part['contents'], $part['filename'] ?? null, $part['headers'] ?? []);
                    }
                    $response = $pendingRequest->post($url);
                } else {
                    $response = $pendingRequest->send('POST', $url, [
                        'body' => is_string($body) ? $body : null,
                        'json' => is_array($body) && (isset($options['json']) || ! isset($options['form_params'])) ? $body : null,
                        'form_params' => is_array($body) && isset($options['form_params']) ? $body : null,
                    ]);
                }
            } else {
                $response = $pendingRequest->send($method, $url, ['query' => $body]);
            }

            return $response->toPsrResponse();
        } catch (Throwable $e) {
            throw new TelegramSDKException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    public function setTimeOut(int $timeOut): static
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    public function getConnectTimeOut(): int
    {
        return $this->connectTimeOut;
    }

    public function setConnectTimeOut(int $connectTimeOut): static
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }
}
