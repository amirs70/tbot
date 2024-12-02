<?php

namespace Amirm\TBot\Telegram\Core\Storage;

interface TelegramStorage
{

    public function writeBotSetting($key, $value): self;

    public function removeBotSetting($key): self;

    public function readBotSetting($key, $default): string|int|array|bool|null;

    public function writeUser($user_id, $key, $value): self;
    public function point($user_id, $value): self|string|int|array|bool|null;

    public function readUser($user_id, $key, $default): string|int|array|bool|null;

    public function removeUser($user_id, $key): self;

}
