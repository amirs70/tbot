<?php

namespace Amirm\T_Bot\Telegram\Core\Storage;

use Amirm\T_Bot\Init\Functions;

abstract class FileStorage implements TelegramStorage
{

    private string $file;

    public function __construct()
    {
        $this->file = "{$this->getStoragePath()}/storage.json";
        if ( ! file_exists("{$this->getStoragePath()}/storage.json")) {
            fclose(fopen($this->file, "a+"));
            $init_ = [
                "users" => [],
            ];
            file_put_contents($this->file, Functions::prettyJson($init_));
        }
    }

    protected abstract function getStoragePath(): string;

    public function writeBotSetting($key, $value): TelegramStorage
    {
        $storage       = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"), true);
        $storage[$key] = $value;
        file_put_contents($this->file, Functions::prettyJson($storage));

        return $this;
    }

    public function removeBotSetting($key): TelegramStorage
    {
        if ($key === "users") {
            return $this;
        }
        $storage = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"), true);
        unset($storage[$key]);
        file_put_contents($this->file, Functions::prettyJson($storage));

        return $this;
    }

    public function readBotSetting($key, $default): string|int|array|bool|null
    {
        $storage = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"));

        return $storage->$key ?? $default;
    }

    public function writeUser($user_id, $key, $value): TelegramStorage
    {
        $storage                          = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"), true);
        $storage["users"][$user_id][$key] = $value;
        file_put_contents($this->file, Functions::prettyJson($storage));

        return $this;
    }

    public function point($user_id, $value): self
    {
        $this->writeUser($user_id, "point", $value);

        return $this;
    }

    public function readUser($user_id, $key, $default): string|int|array|bool|null
    {
        $storage = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"));

        return $storage->users->$user_id->$key ?? $default;
    }

    public function removeUser($user_id, $key): TelegramStorage
    {
        $storage = json_decode(file_get_contents("{$this->getStoragePath()}/storage.json"), true);
        unset($storage["users"][$user_id][$key]);
        file_put_contents($this->file, Functions::prettyJson($storage));

        return $this;
    }

}
