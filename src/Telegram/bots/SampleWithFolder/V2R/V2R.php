<?php

namespace App\Telegram\bots\SampleWithFolder\V2R;

use App\Init\Request;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\Reality;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\Shadowsocks;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\Trojan;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\V2RayProfilable;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\Vless;
use App\Telegram\bots\SampleWithFolder\V2R\Profile\Vmess;
use App\Telegram\bots\SampleWithFolder\V2R\Servers\Servers;

class V2R
{

    private array $server;

    public const PREFIX = "/pan/";

    public const PORT = 2106;

    public const USER = "amirmn";

    public const UBER = "Aa5646sdsd@";

    public const V2RAY_PATH_ONLINES = V2R::PREFIX."panel/inbound/onlines";

    public const V2RAY_PATH_CHECK_LOGIN = V2R::PREFIX."panel/setting/getUserSecret";

    public const V2RAY_PATH_UPDATE_SETTINGS = V2R::PREFIX."panel/setting/update";

    public const V2RAY_PATH_RESET_PANEL = V2R::PREFIX."panel/setting/restartPanel";

    public const V2RAY_PATH_UPDATE_XRAY = V2R::PREFIX."panel/xray/update";

    public const V2RAY_PATH_RESET_XRAY = V2R::PREFIX."server/restartXrayService";

    public const V2RAY_PATH_LOGIN = V2R::PREFIX."login";

    public const V2RAY_PATH_ADD_INBOUND = V2R::PREFIX."panel/inbound/add";

    public const V2RAY_PATH_DELETE_INBOUND = V2R::PREFIX."panel/inbound/del";

    public const V2RAY_PATH_GET_NEW_CERT_X25519 = V2R::PREFIX."server/getNewX25519Cert";

    private static self|null $instance = null;

    private function __construct($server)
    {
        $this->server = Servers::getInstance()
            ->getServer($server);
    }

    public static function getInstance($server): self
    {
        if (self::$instance == null) {
            self::$instance = new self($server);
        }

        return self::$instance;
    }

    public function isLogin(): bool
    {
        $res = Request::create($this->server["address"], V2R::V2RAY_PATH_CHECK_LOGIN)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->execute(500, "json_decode");
        if ( ! $res) {
            return false;
        }

        return isset($res->success) && $res->success===true;
    }

    public function login(): string|bool
    {
        $res = Request::create($this->server["address"], V2R::V2RAY_PATH_LOGIN)
            ->setQuery(["username" => V2R::USER, "password" => V2R::UBER])
            ->execute(10000, "json_decode");
        if (isset($res->success) && $res->success === true) {
            $access = str_replace("3x-ui=", "", end(Request::$COOKIES[0]));
            Servers::getInstance()
                ->setAccess($this->server["nickname"], $access);

            return $access;
        }

        return false;
    }

    public function setSSL(): null|bool|string|object
    {
        $res = Request::create($this->server["address"], V2R::V2RAY_PATH_UPDATE_SETTINGS)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->setQuery([
                "webListen"          => "",
                "webDomain"          => "",
                "webPort"            => "443",
                "webCertFile"        => "/etc/letsencrypt/live/at.ng-itm.info/fullchain.pem",
                "webKeyFile"         => "/etc/letsencrypt/live/at.ng-itm.info/privkey.pem",
                "webBasePath"        => "/pan/",
                "sessionMaxAge"      => "0",
                "pageSize"           => "50",
                "expireDiff"         => "0",
                "trafficDiff"        => "0",
                "remarkModel"        => "-ieo",
                "datepicker"         => "gregorian",
                "tgBotEnable"        => "false",
                "tgBotToken"         => "",
                "tgBotProxy"         => "",
                "tgBotChatId"        => "",
                "tgRunTime"          => "@daily",
                "tgBotBackup"        => "false",
                "tgBotLoginNotify"   => "true",
                "tgCpu"              => "0",
                "tgLang"             => "en-US",
                "xrayTemplateConfig" => "",
                "secretEnable"       => "false",
                "subEnable"          => "false",
                "subListen"          => "",
                "subPort"            => "2096",
                "subPath"            => "/sub/",
                "subJsonPath"        => "/json/",
                "subDomain"          => "",
                "subCertFile"        => "",
                "subKeyFile"         => "",
                "subUpdates"         => "12",
                "subEncrypt"         => "true",
                "subShowInfo"        => "true",
                "subURI"             => "",
                "subJsonURI"         => "",
                "subJsonFragment"    => "",
                "subJsonMux"         => "",
                "subJsonRules"       => "",
                "timeLocation"       => "Asia/Tehran",
            ])
            ->execute(1000, "json_decode");

        return isset($res->success) && $res->success === true;
    }

    public function directIranianIpDomain(): bool
    {
        $a   = [
            "log"       => ["access" => "none", "dnsLog" => false, "error" => "./error.log", "loglevel" => "warning"],
            "api"       => ["tag" => "api", "services" => ["HandlerService", "LoggerService", "StatsService"]],
            "inbounds"  => [["tag" => "api", "listen" => "127.0.0.1", "port" => 62789, "protocol" => "dokodemo-door", "settings" => ["address" => "127.0.0.1"]]],
            "outbounds" => [["tag" => "direct", "protocol" => "freedom", "settings" => ["domainStrategy" => "UseIP"]], ["tag" => "blocked", "protocol" => "blackhole", "settings" => []]],
            "policy"    => [
                "levels" => ["0" => ["statsUserDownlink" => true, "statsUserUplink" => true]],
                "system" => ["statsInboundDownlink" => true, "statsInboundUplink" => true, "statsOutboundDownlink" => true, "statsOutboundUplink" => true],
            ],
            "routing"   => [
                "domainStrategy" => "AsIs",
                "rules"          => [
                    ["type" => "field", "inboundTag" => ["api"], "outboundTag" => "api"],
                    ["type" => "field", "outboundTag" => "blocked", "ip" => ["geoip:private"]],
                    ["type" => "field", "outboundTag" => "blocked", "protocol" => ["bittorrent"]],
                    ["type" => "field", "outboundTag" => "direct", "ip" => ["ext:geoip_IR.dat:ir"]],
                    ["type" => "field", "outboundTag" => "direct", "domain" => ["regexp:.*\\.ir$", "regexp:.*\\.xn--mgba3a4f16a$", "ext:geosite_IR.dat:ir"]],
                ],
            ],
            "stats"     => [],
        ];
        $res = Request::create($this->server["address"], V2R::V2RAY_PATH_UPDATE_XRAY)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->setQuery(["xraySetting" => json_encode($a)])
            ->execute(1000, "json_decode");

        return isset($res->success) && $res->success === true;
    }

    public function resetPanel(): null|bool|string|object
    {
        $res = Request::create($this->server["address"], V2R::V2RAY_PATH_RESET_PANEL)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->execute(1000, "json_decode");

        return isset($res->success) && $res->success === true;
    }

    public function addInbound($data): object|bool|null
    {
        return Request::create($this->server["address"], V2R::V2RAY_PATH_ADD_INBOUND)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->setQuery($data)
            ->execute(1000, "json_decode");
    }

    public function deleteInbound($id): object|bool|null
    {
        return Request::create($this->server["address"], V2R::V2RAY_PATH_DELETE_INBOUND, $id)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->execute(1000, "json_decode");
    }

    public function getNewX25519Cert(): object|bool|null
    {
        return Request::create($this->server["address"], V2R::V2RAY_PATH_GET_NEW_CERT_X25519)
            ->addHeader("Cookie", "3x-ui={$this->server["access"]}")
            ->execute(1000, "json_decode");
    }

    public function vless(): V2RayProfilable
    {
        return Vless::getInstance($this->server["address"], $this->server["nickname"]);
    }

    public function vmess(): V2RayProfilable
    {
        return Vmess::getInstance($this->server["address"], $this->server["nickname"]);
    }

    public function trojan(): V2RayProfilable
    {
        return Trojan::getInstance($this->server["address"], $this->server["nickname"]);
    }

    public function shadowsocks(): V2RayProfilable
    {
        return Shadowsocks::getInstance($this->server["address"], $this->server["nickname"]);
    }

    public function reality(): V2RayProfilable
    {
        return Reality::getInstance($this->server["address"], $this->server["nickname"]);
    }

}
