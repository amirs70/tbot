<?php

namespace Amirm\T_Bot\Init;

class Key
{

    public static function simpleRandomChar($count): string
    {
        $a = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f"];
        $w = "";
        $c = count($a) - 1;
        for ($i = 0; $i < $count; $i++) {
            $w .= $a[rand(0, $c)];
        }

        return $w;
    }

    public static function randomChar($count): string
    {
        $a = [
            0,
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            "a",
            "b",
            "c",
            "d",
            "e",
            "f",
            "g",
            "h",
            "i",
            "j",
            "k",
            "l",
            "m",
            "n",
            "o",
            "p",
            "q",
            "r",
            "s",
            "t",
            "v",
            "w",
            "x",
            "y",
            "z",
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
            "I",
            "J",
            "K",
            "L",
            "M",
            "N",
            "O",
            "P",
            "Q",
            "R",
            "S",
            "T",
            "V",
            "W",
            "X",
            "Y",
            "Z",
            "+",
            "/",
            "-",
            "_",
            "*",
            "&",
            "^",
            "%",
            "$",
            "#",
            "@",
            "!",
        ];
        $w = "";
        $c = count($a) - 1;
        for ($i = 0; $i < $count; $i++) {
            $w .= $a[rand(0, $c)];
        }

        return $w;
    }

}
