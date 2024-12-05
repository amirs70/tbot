<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\TBot\Init\Key;

class Vless extends V2RayProfilable
{

    protected function getProtocol(): string
    {
        return "vless";
    }

    protected function getClientId(): string
    {
        if (empty($this->id)) {
            $this->id = Key::simpleRandomChar(8);
            $this->id .= "-";
            $this->id .= Key::simpleRandomChar(4);
            $this->id .= "-";
            $this->id .= Key::simpleRandomChar(4);
            $this->id .= "-";
            $this->id .= Key::simpleRandomChar(4);
            $this->id .= "-";
            $this->id .= Key::simpleRandomChar(12);
        }

        return $this->id;
    }

    protected function getSettings(): array
    {
        $this->id = $this->getClientId();

        return [
            "clients"    => [
                [
                    "id"         => $this->getClientId(),
                    "flow"       => "",
                    "email"      => $this->getProtocol()."@".Key::simpleRandomChar(5).".com",
                    "limitIp"    => "0",
                    "totalGB"    => 0,
                    "expiryTime" => 0,
                    "enable"     => true,
                    "tgId"       => "",
                    "subId"      => $this->getSubId(),
                    "reset"      => 0,
                ],
            ],
            "decryption" => "none",
            "fallbacks"  => [],
        ];
    }

    protected function getProfile($profile_id): array|bool
    {
        return [
            "id"      => $profile_id,
            "profile" => "vless://{$this->getClientId()}@$this->server:{$this->getProt()}?type=tcp&security=none#$this->nickname-vless",
        ];
    }

}
