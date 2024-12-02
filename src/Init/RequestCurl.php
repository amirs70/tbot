<?php

namespace Amirm\TBot\Init;


use CurlHandle;

class RequestCurl
{

    public static array $COOKIES = [];

    private CurlHandle|false $ch;

    private array $header = [];

    const HEADER_CONTENT_TYPE_JSON = 'Content-Type: application/json';

    const HEADER_CONTENT_TYPE_FORM_URLENCODED = 'Content-Type: application/x-www-form-urlencoded';

    public function __construct($uri, ...$path)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $uri . implode("/", $path));
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 500);
        $fn = function ($ch, $headerLine): string {
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1) {
                RequestCurl::$COOKIES[] = $cookie;
            }

            return strlen($headerLine); // Needed by curl
        };
        curl_setopt($this->ch, CURLOPT_HEADERFUNCTION, $fn);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    public static function create($uri, ...$path): self
    {
        return new self($uri, ...$path);
    }

    public function isGet($is = false): self
    {
        curl_setopt($this->ch, CURLOPT_POST, !$is);

        return $this;
    }

    public function hasReturn($has = true): self
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $has);

        return $this;
    }

    public function setQuery(array|string $query): self
    {
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, is_array($query) ? http_build_query($query) : $query);

        return $this;
    }

    public function addHeader($key, $value = null): self
    {
        $this->header[] = $value === null ? $key : "$key: $value";

        return $this;
    }

    private function prepare($timeout = 3000): void
    {
        if (count($this->header) > 0) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);
        }
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
    }

    public function getInfo($timeout = 3000)
    {
        $this->prepare($timeout);

        return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    public function execute($timeout = 3000, callable $callback = null): bool|string|object
    {
        $this->prepare($timeout);
        $check = curl_exec($this->ch);
        if (!$check) {
            $r = curl_error($this->ch);
        } else {
            $r = $check;
        }
        curl_close($this->ch);

        return is_callable($callback) && !is_null($callback($r)) ? $callback($r) : $r;
    }

}
