<?php

namespace Amirm\TBot\Telegram\bots\Family\reacts;

use Amirm\TBot\Telegram\Core\Chat\InlineButton;
use Amirm\TBot\Telegram\Core\Chat\SingleChat;
use Amirm\TBot\Telegram\Core\Reactable;
use Amirm\TBot\Telegram\Core\TelegramBot;

class ReactStart extends Reactable
{

    public static function btnStart(): array
    {
        return [
            [
                InlineButton::create("🌍 " . __("Select language"))->setCallback("select_language"),
            ],
            [
                InlineButton::create("⏳ " . __("Set reminder"))->setCallback("set_reminder"),
                InlineButton::create("📨 " . __("Forward SMS"))->setCallback("sms_forward_activation")
            ]
        ];
    }

    public static function btnYesNo(): array
    {
        return [
            [
                InlineButton::create("✅ " . __("Yes"))->setCallback("yes_to_question")
            ], [
                InlineButton::create("❌ " . __("No"))->setCallback("no_to_question")
            ]
        ];
    }

    public static function startMsg($withGreeting = false, ?string $point = null): string
    {
        if (!$withGreeting) {
            return __("On this robot you can carry out and manage your daily tasks at once\nTo continue click on one of the buttons down below");
        }
        if ($point === null) {
            return __("Greetings\nOn this robot you can carry out and manage your daily tasks at once\nTo continue click on one of the buttons down below");
        }
        return __("Welcome back\nOn this robot you can carry out and manage your daily tasks at once\nTo continue click on one of the buttons down below");
    }

    public static function sendStartMsg(TelegramBot $bot, $chat_id, $withGreeting = false, ?int $edit = null): void
    {
        $point = $bot->point($chat_id);
        $a = $bot->point($chat_id, "start");
        if ($edit !== null) {
            $a->editMessageText(SingleChat::create($chat_id, $edit)
                ->setText(ReactStart::startMsg($withGreeting, $point))
                ->setReplyMarkupInlineKeyboardRow(ReactStart::btnStart()));
        } else {
            $a->sendMessage(SingleChat::create($chat_id)
                ->setText(ReactStart::startMsg($withGreeting, $point))
                ->setReplyMarkupInlineKeyboardRow(ReactStart::btnStart()));
        }
    }

    public function use(): void
    {
        $this->getBot()->reactTo(["/start", "start_button", "منوی اصلی"], [$this, "start"]);
    }

    public function start($chat_id, $message, $point, $message_id, $rawMessage): void
    {
        /*$this->bot->getStorage()
            ->writeBotSetting("last", (json_encode(file_get_contents('php://input'), true)));*/
        ReactStart::sendStartMsg($this->getBot(), $chat_id, true, $message === "start_button" ? $message_id : null);
    }
}
