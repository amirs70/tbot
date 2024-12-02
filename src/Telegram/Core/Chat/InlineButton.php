<?php

namespace Amirm\TBot\Telegram\Core\Chat;

class InlineButton
{

    private array $btn = [];

    /*private TelegramBot $bot;*/

    public function __construct(string $text)
    {
        /*$this->bot         = $bot;*/
        $this->btn["text"] = $text;
    }

    public static function create(/*TelegramBot $bot, */ $text): self
    {
        return new InlineButton(/*$bot, */ $text);
    }

    public function setCallback($callbackName): self
    {
        $this->btn["callback_data"] = $callbackName;

        return $this;
    }

    public function setUrl($url): self
    {
        $this->btn["url"] = $url;

        return $this;
    }

    public function build(): array
    {
        return $this->btn;
    }

}
