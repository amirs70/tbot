<?php

namespace App\Telegram\bots\Family;

use App\Telegram\bots\Family\reacts\ReactStart;
use App\Telegram\bots\Family\reacts\ReactStartButtonLanguage;
use App\Telegram\bots\Family\reacts\ReactStartButtonReminder;
use App\Telegram\bots\Family\reacts\ReactStartButtonSMS;
use App\Telegram\Core\Storage\MySQLStorage;
use App\Telegram\Core\Storage\TelegramStorage;
use App\Telegram\Core\TelegramBot;
use Illuminate\Support\Facades\App;

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
