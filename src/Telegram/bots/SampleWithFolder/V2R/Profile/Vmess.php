<?php

namespace Amirm\T_Bot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\T_Bot\Init\Key;

class Vmess extends V2RayProfilable
{

    protected function getProtocol(): string
    {
        return "vmess";
    }

    private function getClientId(): string
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
            "clients" => [
                [
                    "id"         => $this->getClientId(),
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
        ];
    }

    protected function getProfile($profile_id): array|bool
    {
        $arr = [
            "v"    => "2",
            "ps"   => "$this->nickname-vmess",
            "add"  => $this->server,
            "port" => $this->getProt(),
            "id"   => $this->getClientId(),
            "net"  => "tcp",
            "type" => "none",
            "tls"  => "none",
        ];

        return [
            "id"      => $profile_id,
            "profile" => "vmess://".base64_encode(json_encode($arr)),
        ];
    }

}
