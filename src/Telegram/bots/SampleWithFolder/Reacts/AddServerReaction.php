<?php

namespace Amirm\T_Bot\Telegram\bots\SampleWithFolder\Reacts;

use Amirm\T_Bot;
use Illuminate\Support\Facades\App;

trait AddServerReaction
{

    public function addServerReaction(): void
    {
        $this->reactTo("addServerYesTakeCareOfSSL", [$this, "addServerYesTakeCareOfSSL"]);
    }

    public function addServerYesTakeCareOfSSL($chat_id): void
    {
        App::setLocale("fa");
        $this->sendMessage(__("User :user added to the club successfully", ["user" => "Amir"]), $chat_id);
    }

}
