<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\Reacts;

use Amirm\TBot\Telegram\Core\Chat\SingleChat;
use Amirm\TBot\Telegram\Core\Reactable;

class Start extends Reactable
{

    public function start($chat_id, $message, $point, $message_id, $rawMessage): void
    {
        $this->bot->sendMessage(
            $this->bot->getMainMenuButtons(
                SingleChat::create($chat_id)
                    ->setText("Welcome to V2Ray bot:")
            )
        );
    }

    public function use(): void
    {
        /*$this->bot->getStorage()
            ->writeBotSetting("last", (json_decode(file_get_contents('php://input'), true)));*/
        $this->bot->reactTo("/start", [$this, "start"]);
    }

}
