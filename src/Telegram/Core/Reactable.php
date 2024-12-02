<?php

namespace Amirm\TBot\Telegram\Core;

abstract class Reactable
{

    protected TelegramBot $bot;

    public function __construct(TelegramBot $bot)
    {
        $this->bot = $bot;
    }

    public function getBot(): TelegramBot
    {
        return $this->bot;
    }

    abstract public function use(): void;

}
