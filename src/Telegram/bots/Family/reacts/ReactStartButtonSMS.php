<?php

namespace Amirm\T_Bot\Telegram\bots\Family\reacts;

use Amirm\T_Bot\Init\Request;
use Amirm\T_Bot\Telegram\Core\Chat\SingleChat;
use Amirm\T_Bot\Telegram\Core\Reactable;

class ReactStartButtonSMS extends Reactable
{

    public function use(): void
    {
        $this->geT_Bot()->reactTo("sms_forward_activation", [$this, "startSMS"]);
        $this->geT_Bot()->reactTo("any", "sms_forward_activation", [$this, "startSMSGetNumber"]);
        $this->geT_Bot()->reactTo("yes_to_question", "sms_forward_activation_check_number", [$this, "yesStartSMSGetNumber"]);
        $this->geT_Bot()->reactTo("no_to_question", "sms_forward_activation_check_number", [$this, "noStartSMSGetNumber"]);
    }

    public function startSMS($chat_id): void
    {
        $this->geT_Bot()->point($chat_id, "sms_forward_activation")->sendMessage(
            SingleChat::create($chat_id)
                ->setText(__("Enter the number to forward any sms that this phone receive"))
        );
    }

    public function startSMSGetNumber($chat_id, $message): void
    {
        $this->geT_Bot()->point($chat_id, "sms_forward_activation_check_number")
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setReplyMarkupInlineKeyboardRow(ReactStart::btnYesNo())
                    ->setText(__("The number you've entered is :number\nIs it acceptable to you?", ["number" => $message]))
            );
    }

    public function yesStartSMSGetNumber($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()
            ->deleteMessage($chat_id, $message_id)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("The number you've entered has been saved"))
            );
        ReactStart::sendStartMsg($this->geT_Bot(), $chat_id);
    }

    public function noStartSMSGetNumber($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()
            ->deleteMessage($chat_id, $message_id)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("The number has been cancelled"))
            );
        ReactStart::sendStartMsg($this->geT_Bot(), $chat_id);
    }
}
