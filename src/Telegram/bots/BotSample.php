<?php

namespace Amirm\TBot\Telegram\bots;

use Amirm\TBot\Telegram\Core\Storage\MySQLStorage;
use Amirm\TBot\Telegram\Core\Storage\TelegramStorage;
use Amirm\TBot\Telegram\Core\TelegramBot;

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
