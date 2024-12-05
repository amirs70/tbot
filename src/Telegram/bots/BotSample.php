<?php

namespace Amirm\T_Bot\Telegram\bots;

use Amirm\T_Bot\Telegram\Core\Storage\MySQLStorage;
use Amirm\T_Bot\Telegram\Core\Storage\TelegramStorage;
use Amirm\T_Bot\Telegram\Core\TelegramBot;

class BotSample extends TelegramBot
{

    public function getApi(): string
    {
        return "12qewd23:asdfasdfjnasmdfk";
    }

    protected function excludeId(): array|null
    {
        return null;
    }

    protected function includeId(): array|null
    {
        return null;
    }

    public function getStorage(): TelegramStorage
    {
        return new MySQLStorage(self::class);
    }

    public function react(): void
    {

    }

    public function getIsEnabled(): bool
    {
        return false;
    }
}
