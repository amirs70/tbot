<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\TBot\Init\Key;

class Trojan extends V2RayProfilable
{

    private string $password = "";

    protected function getProtocol(): string
    {
        return "trojan";
    }

    private function getPassword(): string
    {
        if (empty($this->password)) {
            $this->password = Key::simpleRandomChar(10);
        }

        return $this->password;
    }

    protected function getSettings(): array
    {
        return [
            "clients"   => [
                [
                    "password"   => $this->getPassword(),
                    "flow"       => "",
                    "email"      => $this->getProtocol()."@".Key::simpleRandomChar(5).".com",
                    "limitIp"    => 0,
                    "totalGB"    => 0,
                    "expiryTime" => 0,
                    "enable"     => true,
                    "tgId"       => "",
                    "subId"      => $this->getSubId(),
                    "reset"      => 0,
                ],
            ],
            "fallbacks" => [],
        ];
    }

    protected function getProfile($profile_id): array|bool
    {
        return [
            "id"      => $profile_id,
            "profile" => "trojan://{$this->getPassword()}@$this->server:{$this->getProt()}?type=tcp&security=none#$this->nickname-trojan",
        ];
    }

}
