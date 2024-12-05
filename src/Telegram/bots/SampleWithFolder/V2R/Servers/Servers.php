<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Servers;

use Amirm\TBot\Init\Functions;

class Servers
{

    private string $file = __DIR__ . '/servers.json';

    private array $storage = [];

    private static self|null $instance = null;

    private function __construct()
    {
        if ( ! file_exists($this->file)) {
            fclose(fopen($this->file, "a+"));
            $init_ = [];
            file_put_contents($this->file, Functions::prettyJson($init_));
        }
        $this->storage = json_decode(file_get_contents($this->file), true);
    }

    private function saveStorage(): void
    {
        file_put_contents($this->file, Functions::prettyJson($this->storage));
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function add($address, $nickname): self
    {
        $this->storage[$nickname] = [
            "nickname" => $nickname,
            "address"  => $address,
            "access"   => "",
            "users"    => [/*
                "asdkjfnasfjkd" => [
                    "13" => "vless://asdfjknmikmewsdmkkvis"
                ]
            */
            ],
        ];
        $this->saveStorage();

        return $this;
    }

    public function getServer($nickname): array|null
    {
        if ( ! isset($this->storage[$nickname])) {
            return null;
        }

        $a = $this->storage[$nickname];
        unset($a["users"]);

        return $a;
    }

    public function getServers(): array
    {
        return count($this->storage) > 0 ? $this->storage : [];
    }

    public function updateAddress(string $nickname, string $address): self
    {
        if ( ! $this->storage[$nickname]) {
            return $this;
        }
        $this->storage[$nickname]["address"] = $address;

        $this->saveStorage();

        return $this;
    }

    public function setAccess(string $nickname, string $access): void
    {
        $this->storage[$nickname]["access"] = $access;
        $this->saveStorage();
    }

    public function addProfileToUser($server, $user, $profile): self
    {
        if ( ! isset($this->storage[$server])) {
            return $this;
        }
        $this->storage[$server]["users"][$user][$profile->id] = base64_encode(base64_encode(base64_encode($profile->profile)));

        $this->saveStorage();

        return $this;
    }

    public function removeProfileFromUser($server, $user, $profile_id): self
    {
        if ( ! isset($this->storage[$server])) {
            return $this;
        }
        unset($this->storage[$server]["users"][$user][$profile_id]);

        $this->saveStorage();

        return $this;
    }

    public function getUserAllProfile($user): string
    {
        $profiles = "";
        foreach ($this->storage as $server) {
            if (isset($server["users"][$user])) {
                foreach ($server["users"][$user] as $profile) {
                    $profiles .= $profile.PHP_EOL;
                }
            }
        }

        return $profiles;
    }

}
