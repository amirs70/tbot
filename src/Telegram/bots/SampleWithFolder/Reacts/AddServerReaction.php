<?php

namespace App\Telegram\bots\SampleWithFolder\Reacts;

use App;

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
