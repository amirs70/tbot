<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\TBot\init\Key;

class Reality extends Vless
{

    private $sid = "";

    private $publicKey;

    private function getCert(): object|bool
    {
        if ( ! $this->loginStuff($this->panel)) {
            return json_decode(json_encode(["success" => false, "message" => "Login failed"]));
        }
        $cert = $this->panel->getNewX25519Cert();
        if ($cert && $cert->success) {
            return $cert->obj;
        }

        return false;
    }

    private function getShortId(): string
    {
        if (empty($this->sid)) {
            $this->sid = Key::simpleRandomChar(8);
        }

        return $this->sid;
    }

    protected function getStreamSettings(): array
    {
        $cert = $this->getCert();

        $this->publicKey = $cert->publicKey;

        return [
            "network"         => "tcp",
            "security"        => "reality",
            "externalProxy"   => [],
            "realitySettings" => [
                "show"        => false,
                "xver"        => 0,
                "dest"        => "yahoo.com:443",
                "serverNames" => ["yahoo.com", "www.yahoo.com"],
                "privateKey"  => $cert->privateKey,
                "minClient"   => "",
                "maxClient"   => "",
                "maxTimediff" => 0,
                "shortIds"    => [$this->getShortId()],
                "settings"    => ["publicKey" => $cert->publicKey, "fingerprint" => "random", "serverName" => "", "spiderX" => "/"],
            ],
            "tcpSettings"     => ["acceptProxyProtocol" => false, "header" => ["type" => "none"]],
        ];
    }

    protected function getProfile($profile_id): array|bool
    {
        return [
            "id"      => $profile_id,
            "profile" => "vless://{$this->getClientId()}@$this->server:{$this->getProt()}?type=tcp&security=reality&pbk=$this->publicKey&fp=random&sni=yahoo.com&sid={$this->getShortId()}&spx=/#$this->nickname-reality",
        ];
    }

}
