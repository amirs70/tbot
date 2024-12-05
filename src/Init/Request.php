<?php

namespace Amirm\TBot\Init;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;

class  Request
{

    private string $url = "";
    private array|string $query = [];
    private array $header = [];
    private bool $debug = false;
    private int $timeout = 5000;

    /**
     * @var callable
     */
    private $asyncOnSuccess = null;

    /**
     * @var callable
     */
    private $asyncOnError = null;

    public const METHOD_GET = "GET";
    public const METHOD_POST = "POST";
    private bool $async = false;

    public function __construct($uri, ...$path)
    {
        $this->url = $this->adjustUrl($uri . "/" . implode("/", $path));
    }

    private function adjustUrl(string $url): string
    {
        $url = str_replace("//", "", $url);
        $url = str_replace("https:", "https://", $url);
        $url = str_replace("http:", "http://", $url);
        return Functions::endsWith($url, "/") ? str_replace("//@#", "", $url . "/@#") : $url;
    }

    public static function create($uri, ...$path): self
    {
        return new self($uri, ...$path);
    }

    public function setQuery(array|string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function debug(bool $debug = true): self
    {
        $this->debug = $debug;
        return $this;
    }

    public function addHeader($key, $value = null): self
    {
        $this->header[] = $value === null ? $key : "$key: $value";

        return $this;
    }

    public function async($is = true, $onSuccess = null, $onError = null): self
    {
        $this->asyncOnSuccess = $onSuccess;
        $this->asyncOnError = $onError;
        $this->async = $is;
        return $this;
    }

    public function execute($method, $timeout = 5): bool|string|PromiseInterface
    {
        $client = new Client();
        try {
            $options = ["connect_timeout" => $timeout];
            if (Functions::is_array_plus($this->query)) {
                $options[Functions::equals($method, Request::METHOD_GET) ? "query" : "form_params"] = $this->query;
                $options["headers"] = $this->header;
            }
            /*if ($this->async) {
                $promise = $client->requestAsync($method, $this->url, $options);
                $promise->then($this->asyncOnSuccess, $this->asyncOnError);
                return $promise;
            }*/
            $res = $client->request($method, $this->url, $options);
            if ($res->getStatusCode() > 0) {
                return $res->getBody()->getContents();
            }
            return false;
        } catch (GuzzleException $e) {
            if ($this->debug) {
                echo $this->url;
                echo PHP_EOL;
                echo $e->getMessage();
            }
            return false;
        }
    }

}
