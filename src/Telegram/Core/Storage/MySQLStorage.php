<?php

namespace Amirm\TBot\Telegram\Core\Storage;

use Amirm\TBot\Models\Setting;

class MySQLStorage implements TelegramStorage
{

    private string $botName;

    public function __construct($botName)
    {
        $this->botName = str_replace("bot", "", strtolower($botName));
    }

    public function writeBotSetting($key, $value): TelegramStorage
    {
        Setting::updateOrCreate(["bot" => $this->botName, "user_id" => null, "name" => $key], [
            "value" => $value,
            "bot" => $this->botName,
            "user_id" => null,
            "name" => $key,
        ]);

        return $this;
    }

    public function removeBotSetting($key): TelegramStorage
    {
        $s = Setting::where("bot", $this->botName)
            ->where("name", $key)
            ->where("user_id", null)
            ->first();
        if ($s === null) return $this;
        $s->delete();
        return $this;
    }

    public function readBotSetting($key, $default = null): string|int|array|bool|null
    {
        $res = Setting::where("bot", $this->botName)
            ->where("name", $key)
            ->where("user_id", null)
            ->first();
        return $res === null ? $default : $res->toArray()["value"];
    }

    public function writeUser($user_id, $key, $value): TelegramStorage
    {
        Setting::updateOrCreate(["bot" => $this->botName, "user_id" => $user_id, "name" => $key], [
            "value" => $value,
            "bot" => $this->botName,
            "user_id" => $user_id,
            "name" => $key,
        ]);
        return $this;
    }

    public function point($user_id, $value = null): TelegramStorage|string|int|array|bool|null
    {
        return $value === null ? $this->readUser($user_id, "point") : $this->writeUser($user_id, "point", $value);
    }

    public function readUser($user_id, $key, $default = null): string|int|array|bool|null
    {
        $res = Setting::where("bot", $this->botName)
            ->where("name", $key)
            ->where("user_id", $user_id)
            ->first();
        return $res === null ? $default : $res->toArray()["value"];
    }

    public function removeUser($user_id, $key): TelegramStorage
    {
        $s = Setting::where("bot", $this->botName)
            ->where("name", $key)
            ->where("user_id", $user_id)
            ->first();
        if ($s === null) return $this;
        $s->delete();
        return $this;
    }
}
