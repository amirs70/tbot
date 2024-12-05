<?php

namespace Amirm\TBot\Telegram\bots\Family;

use Amirm\TBot\Telegram\bots\Family\reacts\ReactStart;
use Amirm\TBot\Telegram\bots\Family\reacts\ReactStartButtonLanguage;
use Amirm\TBot\Telegram\bots\Family\reacts\ReactStartButtonReminder;
use Amirm\TBot\Telegram\Core\Storage\MySQLStorage;
use Amirm\TBot\Telegram\Core\Storage\TelegramStorage;
use Amirm\TBot\Telegram\Core\TelegramBot;

class BotFamily extends TelegramBot
{

    public function getApi(): string
    {
        return "7923618754:AAGs7qxBNgRCAb6ylBXqy4kB99tWYab6cck";
    }

    public function getIsEnabled(): bool
    {
        return true;
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
        return new MySQLStorage("family");
    }

    public function react(): void
    {
        $this->use(new ReactStart($this));
        $this->use(new ReactStartButtonLanguage($this));
        $this->use(new ReactStartButtonReminder($this));
    }
}
