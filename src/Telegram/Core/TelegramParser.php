<?php

namespace Amirm\TBot\Telegram\Core;

class TelegramParser
{

    public static function parse($entry): bool|array
    {
        if (isset($entry["callback_query"])) {
            return [
                "raw_message" => $entry,
                "message_id"  => $entry["callback_query"]["message"]["message_id"],
                "text"        => $entry["callback_query"]["data"],
                "sender"      => intval($entry["callback_query"]["from"]["id"]),
            ];
        } elseif (isset($entry["message"], $entry["message"]["from"]["id"])) {
            return [
                "raw_message" => $entry["message"],
                "message_id"  => $entry["message"]["message_id"],
                "text"        => $entry["message"]["text"],
                "sender"      => intval($entry["message"]["from"]["id"]),
            ];
        }

        return false;
    }

}
