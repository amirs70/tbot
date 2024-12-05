<?php

namespace Amirm\T_Bot\Telegram\Core;

abstract class Reactable
{

    protected TelegramBot $bot;

    public function __construct(TelegramBot $bot)
    {
        $this->bot = $bot;
    }

    public function geT_Bot(): TelegramBot
    {
        return $this->bot;
    }

    abstract public function use(): void;

}
