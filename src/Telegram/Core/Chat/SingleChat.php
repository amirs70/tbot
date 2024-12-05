<?php

namespace Amirm\T_Bot\Telegram\Core\Chat;

use Amirm\T_Bot\Init\Functions;

class SingleChat
{

    private array $message = [];

    public function __construct($chat_id, $message_id = null)
    {
        $this->message["chat_id"] = $chat_id;
        if (!is_null($message_id)) {
            $this->message["message_id"] = $message_id;
        }
    }

    public function getChatId()
    {
        return $this->message["chat_id"];
    }

    public static function create($chat_id, $message_id = null): SingleChat
    {
        return new self($chat_id, $message_id);
    }

    public function setText($text): self
    {
        $this->message["text"] = $text;
        $this->message["parse_mode"] = 'html';

        return $this;
    }

    public function addText($text): self
    {
        if (!isset($this->message["text"])) $this->message["text"] = "";
        $this->message["text"] .= $text;
        $this->message["parse_mode"] = 'html';

        return $this;
    }

    public function hideKeyBoard(): self
    {
        unset($this->message["reply_markup"]);
        $this->message["remove_keyboard"] = true;
        $this->message["selective"] = false;

        return $this;
    }

    public function setReplyTo($message_id): self
    {
        $this->message["reply_to_message_id"] = $message_id;

        return $this;
    }

    public function setMessageId($message_id): self
    {
        $this->message["message_id"] = $message_id;

        return $this;
    }

    public function addReplyMarkupKeyboardRow($row): self
    {
        if (!isset($this->message["reply_markup"])) {
            $this->message["reply_markup"] = [];
        }
        if (!isset($this->message["reply_markup"]["keyboard"])) {
            $this->message["reply_markup"]["keyboard"] = [];
        }

        $this->message["reply_markup"]["keyboard"][] = $row;

        $this->message["reply_markup"]["resize_keyboard"] = true;

        return $this;
    }

    /**
     * @param $row InlineButton[]
     *
     * @return $this
     */
    public function addReplyMarkupInlineKeyboardRow(array $row): self
    {
        $this->message["resize_keyboard"] = true;
        if (!isset($this->message["reply_markup"])) {
            $this->message["reply_markup"] = [];
        }
        if (!isset($this->message["reply_markup"]["inline_keyboard"])) {
            $this->message["reply_markup"]["inline_keyboard"] = [];
        }

        if (Functions::is_array_plus($row)) {
            foreach ($row as $k => $btn) {
                if (is_a($btn, InlineButton::class)) {
                    $row[$k] = $btn->build();
                }
            }
        }
        $this->message["reply_markup"]["inline_keyboard"][] = $row;

        return $this;
    }

    public function setReplyMarkupInlineKeyboardRow(array $all): self
    {
        $this->message["resize_keyboard"] = true;
        if (!isset($this->message["reply_markup"])) {
            $this->message["reply_markup"] = [];
        }
        $this->message["reply_markup"]["inline_keyboard"] = $all;

        return $this;
    }

    public function toQuery(?callable $callback): string|array|null
    {
        if (isset($this->message["reply_markup"])) {
            $this->message["reply_markup"] = json_encode($this->message["reply_markup"]);
        }

        return is_callable($callback) ? $callback($this->message) : $this->message;
    }

    public function __toString(): string
    {
        if (isset($this->message["reply_markup"]) && Functions::is_array_plus($this->message["reply_markup"])) {
            $this->message["reply_markup"] = json_encode($this->message["reply_markup"]);
        }

        return json_encode($this->message);
    }

    public function toArray(): array
    {
        if (isset($this->message["reply_markup"]) && Functions::is_array_plus($this->message["reply_markup"])) {
            foreach ($this->message["reply_markup"] as $k => $b) {
                foreach ($b as $key => $btna) {
                    foreach ($btna as $kk => $btn) {
                        if (is_a($btn, InlineButton::class)) {
                            $this->message["reply_markup"][$k][$key][$kk] = $btn->build();
                        }
                    }
                }
            }
            $this->message["reply_markup"] = json_encode($this->message["reply_markup"]);
        }

        return $this->message;
    }

}
