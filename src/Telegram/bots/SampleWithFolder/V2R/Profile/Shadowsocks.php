<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Profile;

use Amirm\TBot\Init\Functions;
use Amirm\TBot\Init\Key;

class Shadowsocks extends V2RayProfilable
{

    private $password = "";

    protected function getProtocol(): string
    {
        return "shadowsocks";
    }

    private function getMethod(): string
    {
        return "2022-blake3-aes-256-gcm";
    }

    private function getPasswrods(): string
    {
        $arr = [
            "MjkOxpncCG+sZdl3Go85Z+oMUYUdvPhCpWfTeohUexU=",
            "6K69Fkd5Z5Tpm93DvFcGAKAw78ThOXCkggZhyjSQJgE=",
            "4desgVgDXIJYvf6OnBP7y/8my/d0jewNJ4OBSsudU+k=",
            "iZfm2U27M7fymaD59s9JnZyoSiOCNfdkLm68IdVMTW4=",
            "Mx24zYLe8xBBLVesj8L3kHfKBkof+F1PRgRu7ODfuvg=",
            "d2KajFCV1OZjLOH/jwzVrrgfWTdDX2G7A3RbpIMCpdg=",
            "KXXIZkF2EpE1vdfhTTJBSIQ9BUS4EYUauKAehtrnduY=",
            "Um2QJunUjMN1hY1I4GfEBp+1fSDp+5y+SPzA9Sq4OkY=",
            "sGe2LDtSqA/O+yNmvSr49NshxSZ6FuIt9hn12PmLOO8=",
            "7MdOxKCaU3ZsgK0lmtIheJibsLGohcdz6YS5HFk8vhE=",
            "isKq6b4QWSVpr/toijLxp1rXR2b9HqXz9PtdT4925vw=",
            "jpktyZjTqPFvKNZ0mY5botnnAcwHgj1s0ez6nBBWdyI=",
            "8ISSLFrNCY6+pgcM6dNcjgFUadMH7YIX2f6N2vRXI4w=",
            "3sUKRjQVL4R5OLReFoc94I5UTxjMEFzmad22t4RgiW8=",
            "LBXri9hoNtk+zXkLIELh6Zy65zD67fuKHk9kvJA/8VE=",
            "+A0HBkjlUm1fH8fHy7jgIR4zBdwhFaJT3LIh8xfqURs=",
            "liEJie6rS4EIv/jVF3vv8GiOIfHr0E3hn3WpVYJNPtU=",
            "ymjt3Avfeoa3hZ4tPSQZ+06mWiFuOK1XwVDr0veM45M=",
            "MGVZ0q+NMrZJ1BCmid4HzYx9XaaOESwZ3HMmIrL2GqI=",
            "VspgNxwCpjLL7OCzZngrNe16IiTANDLzW9UKsp17nKE=",
            "FOzfPeXrqIh45u29THxtjXmX2MJpsuZPP/MVvIaW79k=",
            "QkEn4LQ+XZNEecSBDfoFKb9xzwqrorG3i3eCfUKhXgo=",
            "Khc4fu49Hj3asz6kgdfdin6Ayc4/y9eDF++dIdiEPOY=",
            "R6qRxyqVwYyjQp0QQGjRF7CVloo/hyIx0wdVSfytMmI=",
            "cT140X5pKGnPYrLBp8Y0e9CsN29PICiBWHtVye2xIqU=",
            "ADhxp0FnaViS3Jcbnj4V8cacDvzk/2NWms02XIgZZi4=",
            "fv8rYGV6hjk/eThhkVXemxkIYfb42GiTixr4EMkvB1k=",
            "Rrlp7O+hK02MqntJpC/ZU1Ix3icTUsp+8e8MsHi/TG4=",
            "xqhEvC/DHef27Kg8h8lPQJXrO99TtDrOe4V0aSoRfxY=",
            "uKLO1VEMfhAd81SiCrQYax4DjGwyhvtxpUyCpbDvxwc=",
            "tEnmdA8rhlNybKrkhgPaUv8K+zv0KIhZOzTahQwfUfo=",
            "eiO6n6h/d8BAgWwR92ipWVwgHkkeuvg5/9oKNqDM0N0=",
            "iRLRN4yAXNJGQEaX7cTZCweL9L0LFMVVwXgxYSgAvxk=",
            "PDKYWT1SaYnwamt9HpqJseEHb/6R6yyoWbQJivUvI28=",
            "EscRYlpdGY09n9KY77li0H12vr6AzM+Cvci2fjBitqQ=",
            "RM1Dv14js8OvH9bviyeTTJMojD8qIHDQ4AZvyxdBRIU=",
            "ngdpeZVq0N99pOYpWx6gpj/C+90hOUHwycdJsGU9nsA=",
            "oiU7nXsUNbzr+z2bO09a/nuar4KZ3OLp33d/2OLSpPk=",
            "J77uKHvGCuCmX/Pf1pTMX1x3cpUxM15vUDpxv8jtMqk=",
            "XmHlDT6p4qhRsni8n2FA9hnCEqa7HfMJntkeAw+cFcU=",
            "EZ+MYri2ivGkqHApL7x0NTN+BXE5PFLJnNgourIkpRE=",
            "5Rbi7fYt/hd/hIiOkwYGoyuq6J1FVFDGvYhYg56wTC4=",
            "kyYBXjTNbXNkcxvHsafHHR4j8zxk1740wo+f/ENCjZU=",
            "oF/xfiSVcEoK1cSR8TcoO7QjN5Kp3otepohXSTC76jw=",
            "St5k9Ehf+8DhEW70v9ayb73WQsbsJMtD/xdglyhikTU=",
            "27WMQKRm4/bhag8Ale4iFNAethY1xKOqYp213xWUn0w=",
            "bUGVbvB4x0ge0xx1qISQs2uMOpna2eZ0aXlU+7ssMdM=",
            "TnQjbTQhNyoEclF0KssxHxxM2kMyI4mHLJ1Rwzqsi/k=",
            "M53Kq7TpXCc5r5LgF4gsVcjbpUQt5d8ZsT5uuf7EsqE=",
            "obvCehNQjH52v7JrWMbU8VA9zKPfha9JH/ZOeC2bNjg=",
            "TUtL8LDNlNoXm/TG1qjHTH/bReABAF7jRqLT4hBQ+ms=",
            "u8LBk8FJdEz+hMmJXf2suoN7xQxBUx06bMsCxQJUO1g=",
            "DQ1MzcX1dge6bFq+zqxyAWWQJC0UwLK7zHx8HdPoIzc=",
            "mmTyTsN7AR72zz0aPKyFi++gqik4+PyjPyyhzFWNOR8=",
            "RmCTVBoYmcgEBTr6KeyG5O8y3zTojAjlwYiF3G0E1A4=",
            "LxB1jp4lg1t751qUcxchz+FVgvRmUSCS1X/Tc6heGuA=",
            "8ajDk+uuzmHcsrElFLqSaJV82Y8J6o9umsq3E9Xs4Kk=",
            "l5sWzQMyb7WPUuqXzLvif+5fCpno/lSUAQAmukBpIa4=",
            "qP+iGLv2R6dVSGwMxxRR+ndScZtn48CnjryTHeXfW/A=",
        ];

        return $arr[rand(0, count($arr) - 1)];
    }

    private function getPassword(): string
    {
        if (empty($this->password)) {
            $this->password = $this->getPasswrods();
        }

        return $this->password;
    }

    private function getClientPassword(): string
    {
        if (empty($this->password)) {
            $this->password = $this->getPasswrods();
        }

        return $this->password;
    }

    protected function getSettings(): array
    {
        return [
            "method"   => $this->getMethod(),
            "password" => $this->getPassword(),
            "network"  => "tcp,udp",
            "clients"  => [
                [
                    "method"     => "",
                    "password"   => $this->getClientPassword(),
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
        $b64 = base64_encode("2022-blake3-aes-256-gcm:{$this->getPassword()}:{$this->getClientPassword()}");
        if (Functions::endsWith($b64, "=")) {
            $b64 = substr($b64, 0, strlen($b64) - 1);
        }

        return [
            "id"      => $profile_id,
            "profile" => "ss://$b64@$this->server:{$this->getProt()}?type=tcp#$this->nickname-shadowsocks",
        ];
    }

}
