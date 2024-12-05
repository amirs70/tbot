<?php

namespace Amirm\TBot\Telegram\bots\Family\reacts;

use Amirm\TBot\Init\Request;
use Amirm\TBot\Telegram\Core\Chat\SingleChat;
use Amirm\TBot\Telegram\Core\Reactable;

class ReactStartButtonSMS extends Reactable
{

    public function use(): void
    {
        $this->getBot()->reactTo("sms_forward_activation", [$this, "startSMS"]);
        $this->getBot()->reactTo("any", "sms_forward_activation", [$this, "startSMSGetNumber"]);
        $this->getBot()->reactTo("yes_to_question", "sms_forward_activation_check_number", [$this, "yesStartSMSGetNumber"]);
        $this->getBot()->reactTo("no_to_question", "sms_forward_activation_check_number", [$this, "noStartSMSGetNumber"]);
    }

    public function startSMS($chat_id): void
    {
        $this->getBot()->point($chat_id, "sms_forward_activation")->sendMessage(
            SingleChat::create($chat_id)
                ->setText(__("Enter the number to forward any sms that this phone receive"))
        );
    }

    public function startSMSGetNumber($chat_id, $message): void
    {
        $this->getBot()->point($chat_id, "sms_forward_activation_check_number")
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setReplyMarkupInlineKeyboardRow(ReactStart::btnYesNo())
                    ->setText(__("The number you've entered is :number\nIs it acceptable to you?", ["number" => $message]))
            );
    }

    public function yesStartSMSGetNumber($chat_id, $message, $point, $message_id): void
    {
        $this->getBot()
            ->deleteMessage($chat_id, $message_id)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("The number you've entered has been saved"))
            );
        ReactStart::sendStartMsg($this->getBot(), $chat_id);
    }

    public function noStartSMSGetNumber($chat_id, $message, $point, $message_id): void
    {
        $this->getBot()
            ->deleteMessage($chat_id, $message_id)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("The number has been cancelled"))
            );
        ReactStart::sendStartMsg($this->getBot(), $chat_id);
    }
}
