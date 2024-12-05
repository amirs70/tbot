<?php

namespace Amirm\T_Bot\Telegram\bots\Family\reacts;

use Amirm\T_Bot\Telegram\Core\Chat\InlineButton;
use Amirm\T_Bot\Telegram\Core\Chat\SingleChat;
use Amirm\T_Bot\Telegram\Core\Reactable;
use Illuminate\Support\Facades\App;

class ReactStartButtonLanguage extends Reactable
{

    public function use(): void
    {
        $this->geT_Bot()->reactTo("select_language", [$this, "startLang"]);
        $this->geT_Bot()->reactTo(["select_language_en", "select_language_fa", "select_language_sp"], [$this, "selectLang"]);
    }

    public static function btnLangs(): array
    {
        return [
            [
                InlineButton::create("English")->setCallback("select_language_en"),
                InlineButton::create("فارسی")->setCallback("select_language_fa"),
                InlineButton::create("spanish")->setCallback("select_language_sp"),
            ], [
                InlineButton::create("🏠 " . __("Back"))->setCallback("start_button"),
            ]
        ];
    }

    public function startLang($chat_id, $message, $point, $message_id): void
    {
        $this->geT_Bot()->editMessageText(
            SingleChat::create($chat_id, $message_id)
                ->setReplyMarkupInlineKeyboardRow(ReactStartButtonLanguage::btnLangs())
                ->addText(__("Select your preferred language"))
        );
    }

    public function selectLang($chat_id, $message, $point, $message_id): void
    {
        $lng = str_replace("select_language_", "", $message);
        if (!in_array($lng, ["en", "fa"])) {
            $this->geT_Bot()->editMessageText(
                SingleChat::create($chat_id, $message_id)
                    ->setReplyMarkupInlineKeyboardRow(ReactStartButtonLanguage::btnLangs())
                    ->addText(__("Right now, this language is not supported"))
                    ->addText("\n")
                    ->addText(__("Select your preferred language"))
            );
            return;
        }
        $this->geT_Bot()->getStorage()->writeUser($chat_id, "lang", $lng);
        App::setLocale($lng);
        ReactStart::sendStartMsg($this->geT_Bot(), $chat_id, false, $message_id);
    }

}
