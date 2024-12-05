<?php

namespace Amirm\T_Bot\Telegram\bots\Family\reacts;

use Amirm\T_Bot\Telegram\Core\Chat\InlineButton;
use Amirm\T_Bot\Telegram\Core\Chat\SingleChat;
use Amirm\T_Bot\Telegram\Core\Reactable;

class ReactStartButtonReminder extends Reactable
{

    public function use(): void
    {
        $this->geT_Bot()->reactTo("set_reminder", [$this, "startReminder"]);
        $this->geT_Bot()->reactTo(["set_reminder_periodic", "set_reminder_timer", "set_reminder_schedule"], [$this, "selectReminder"]);
        $this->geT_Bot()->reactTo("any", ["set_reminder_timer", "set_reminder_periodic"], [$this, "selectReminderTitle"]);
        $this->geT_Bot()->reactTo("any", "set_reminder_timer_title", [$this, "selectReminderTitle"]);
        $this->geT_Bot()->reactTo("any", "set_reminder_periodic_title", [$this, "selectReminderTitle"]);
        $this->geT_Bot()->reactTo("any", "selectReminderTitle_title", [$this, "selectReminderTitle"]);
    }

    public static function btnReminder($back_fn = "start_button", $jusBack = false): array
    {
        $back = InlineButton::create("🏠 " . __("Back"))->setCallback($back_fn);
        if ($jusBack) {
            return [[$back]];
        }
        return [
            [
                InlineButton::create("⏳ " . __("Periodic"))->setCallback("set_reminder_periodic"),
                InlineButton::create("⏰ " . __("Timer"))->setCallback("set_reminder_timer"),
                InlineButton::create("⏲ " . __("Schedule"))->setCallback("set_reminder_schedule"),
            ], [$back]
        ];
    }

    public function startReminder($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()->editMessageText(
            SingleChat::create($chat_id, $message_id)
                ->setReplyMarkupInlineKeyboardRow(ReactStartButtonReminder::btnReminder())
                ->setText(__("Select the reminder type\nThe periodic reminder will be reminding you at the proper circular times but the timer reminder will remind you once, at the time you pick"))
        );
    }

    public function selectReminder($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()
            ->point($chat_id, $message)
            ->editMessageText(
                SingleChat::create($chat_id, $message_id)
                    ->setText(__("Enter the title of this reminder"))
                    ->setReplyMarkupInlineKeyboardRow(ReactStartButtonReminder::btnReminder("set_reminder", true))
            );
    }

    public function selectReminderTitle($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()
            ->point($chat_id, $point . "_title")
            ->userMeta($chat_id, "reminder_title", $message)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("Enter the title of this reminder"))
            );
        //ReactStart::sendStartMsg($this->geT_Bot(), $chat_id, true);
    }

    /*public function selectReminderTitle($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()
            ->point($chat_id, $point . "_title")
            ->userMeta($chat_id, "reminder_title", $message)
            ->sendMessage(
                SingleChat::create($chat_id)
                    ->setText(__("Enter the title of this reminder"))
            );
        //ReactStart::sendStartMsg($this->geT_Bot(), $chat_id, true);
    }*/

}
