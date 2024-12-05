<?php

namespace Amirm\T_Bot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\T_Bot\Init\Key;
use Amirm\T_Bot\Telegram\bots\SampleWithFolder\V2R\V2R;

abstract class V2RayProfilable
{

    protected string $server;

    protected string $domain;

    protected string $nickname;

    protected string $id = "";

    protected int $port = 0;

    protected V2R $panel;

    protected array $config = [];

    public function __construct($server, $nickname)
    {
        $domains        = explode("//", $server);
        $domains        = end($domains);
        $realDomain     = explode(":", $domains);
        $this->server   = $realDomain[0];
        $this->domain   = $server;
        $this->nickname = $nickname;

        $this->panel = V2R::getInstance($this->nickname);
    }

    public static function getInstance($server, $nickname): self
    {
        return new static($server, $nickname);
    }

    protected function getProt(): int
    {
        if ($this->port < 1) {
            $this->port = rand(400, 65535);
        }

        return $this->port;
    }

    abstract protected function getProtocol(): string;

    abstract protected function getSettings(): array;

    protected function getStreamSettings(): array
    {
        return [
            "network"       => "tcp",
            "security"      => "none",
            "externalProxy" => [],
            "tcpSettings"   => ["acceptProxyProtocol" => false, "header" => ["type" => "none"]],
        ];
    }

    protected function getSubId(): string
    {
        return Key::simpleRandomChar(16);
    }

    protected function build(): array
    {
        $this->config["up"]             = "0";
        $this->config["down"]           = "0";
        $this->config["total"]          = "0";
        $this->config["remark"]         = $this->nickname;
        $this->config["enable"]         = "true";
        $this->config["expiryTime"]     = "0";
        $this->config["listen"]         = "";
        $this->config["port"]           = $this->getProt();
        $this->config["protocol"]       = $this->getProtocol();
        $this->config["settings"]       = json_encode($this->getSettings());
        $this->config["streamSettings"] = json_encode($this->getStreamSettings());
        $this->config["sniffing"]       = '{"enabled":true,"destOverride":["http","tls","quic","fakedns"],"metadataOnly":false,"routeOnly":false}';

        return $this->config;
    }

    abstract protected function getProfile($profile_id): array|bool;

    protected function loginStuff(V2R $panel): bool
    {
        if ( ! $panel->isLogin()) {
            if ($panel->login() === false) {
                return false;
            }
        }

        return true;
    }

    public function add(): object
    {
        if ( ! $this->loginStuff($this->panel)) {
            return json_decode(json_encode(["success" => false, "message" => "Login failed"]));
        }

        $addRes = $this->panel->addInbound($this->build());

        if (empty($addRes) || $addRes === false || $addRes->success === false) {
            return json_decode(json_encode(["success" => false, "message" => $addRes->msg ?: "Inbound is not created"]));
        }

        return json_decode(json_encode(["success" => true, "profile" => $this->getProfile($addRes->obj->id)]));
    }

    public function delete($profile_id): object
    {
        if ( ! $this->loginStuff($this->panel)) {
            return json_decode(json_encode(["success" => false, "message" => "Login failed"]));
        }

        $addRes = $this->panel->deleteInbound($profile_id);

        if (empty($addRes) || $addRes === false || $addRes->success === false) {
            return json_decode(json_encode(["success" => false, "message" => $addRes->msg ?: "Inbound is not created"]));
        }

        return json_decode(json_encode(["success" => true]));
    }

}
