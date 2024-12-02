<?php

namespace App\Telegram\bots\SampleWithFolder\V2R\Users;

use App\Init\Functions;

class Users
{

    private string $file = __DIR__ . '/users.json';

    private array $storage = [];

    static self|null $instance = null;

    private function __construct()
    {
        if ( ! file_exists($this->file)) {
            fclose(fopen($this->file, "a+"));
            $init_ = [];
            file_put_contents($this->file, Functions::prettyJson($init_));
        }
        $this->storage = json_decode(file_get_contents($this->file), true);
    }

    protected function saveStorage(): void
    {
        file_put_contents($this->file, Functions::prettyJson($this->storage));
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function add($nickname): self
    {
        $this->storage[sha1($nickname)] = ["nickname" => $nickname, "enable" => true];
        $this->saveStorage();

        return $this;
    }

    public function disable($nickname): self
    {
        $this->storage[sha1($nickname)]["enable"] = false;
        $this->saveStorage();

        return $this;
    }

    public function enable($nickname): self
    {
        $this->storage[sha1($nickname)]["enable"] = true;
        $this->saveStorage();

        return $this;
    }

    public function get($nickname): array|null
    {
        return $this->storage[sha1($nickname)] ?? ($this->storage[$nickname] ?? null);
    }

    public function getUsers(): array
    {
        return $this->storage;
    }

}
