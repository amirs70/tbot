<?php

namespace Amirm\T_Bot\Telegram\Core;

use Amirm\T_Bot\Init\Request;
use Amirm\T_Bot\Telegram\Core\Chat\SingleChat;

trait TelegramMessenger
{

    private function getURI(): string
    {
        return Telegram::URI . $this->getApi();
    }

    public function sendMessage(SingleChat|string $message, int $chat_id = null): self
    {
        if (is_string($message)) {
            $message = SingleChat::create($chat_id)
                ->setText($message);
        }

        $this->curl("sendMessage", $message);
        return $this;
    }

    public function editMessageText(SingleChat $message): self
    {
        $this->curl("editMessageText", $message);
        return $this;
    }

    public function editMessageReplyMarkup(SingleChat $message): self
    {
        $this->curl("editMessageReplyMarkup", $message);
        return $this;
    }

    public function deleteMessage($chat_id, $message_id): self
    {
        $this->curl("deleteMessage", SingleChat::create($chat_id, $message_id));
        return $this;
    }

    private function curl($method, SingleChat $query): bool|string
    {
        print_r($query->toArray());
        return Request::create($this->getURI(), $method)
            ->setQuery($query->toArray())
            ->debug()
            ->execute(Request::METHOD_POST, $method !== "sendMessage" ? .1 : 5);
    }

}
