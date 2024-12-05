<?php

namespace Amirm\T_Bot\Init;


class  Functions
{

    public static function getFileDocBlock($filename)
    {
        $source = file_get_contents($filename);

        $tokens  = token_get_all($source);
        $comment = [
            T_COMMENT,      // All comments since PHP5
            T_DOC_COMMENT,   // PHPDoc comments
        ];
        $res     = [];
        foreach ($tokens as $token) {
            if ( ! in_array($token[0], $comment)) {
                continue;
            }
            $res[] = $token[1];
        }

        return $res;
    }

    public static function objectToArray($object)
    {
        if ( ! is_object($object) && ! is_array($object)) {
            return $object;
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }

        return array_map([Functions::class, 'objectToArray'], $object);
    }

    public static function prettyJson($arr): string
    {
        return json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public static function is_array_plus($arr)
    {
        return ! empty($arr) && is_array($arr) && count($arr) > 0;
    }

    public static function hold_string($fn)
    {
        ob_start();
        $fn();

        return ob_get_clean();
    }

    public static function get_string_between($string, $start, $end)
    {
        $explode = explode($start, $string);
        if ( ! Functions::is_array_plus($explode)) {
            return [];
        }
        $betweens = [];
        foreach ($explode as $value) {
            if (strpos($value, $end) === false) {
                continue;
            }
            $b          = explode($end, $value);
            $betweens[] = $b[0];
        }

        return $betweens;
    }

    public static function equals($string1, $string2)
    {
        return $string1 === $string2;
    }

    public static function endsWith($string, $end_with)
    {
        return strpos($string."!@#$@", $end_with."!@#$@") !== false;
    }

    public static function startsWith($string, $start_with)
    {
        return strpos("!@#$@".$string, "!@#$@".$start_with) !== false;
    }

    public static function htmlescape($value)
    {
        if ( ! is_array($value)) {
            return htmlentities($value, ENT_NOQUOTES, "utf-8");
        }
        $newValue = [];
        foreach ($value as $key => $Value) {
            $newValue[$key] = Functions::htmlescape($Value);
        }

        return $newValue;
    }

    public static function jdate_things($ts = null, $lng = "en")
    {
        require_once "./jdf.php";
        $j = jdate("\Y:Y,\m:m,\d:d,\H:H,\h:h,\i:i,\s:s", $ts, null, null, $lng);
        $r = [];

        $jArray = explode(",", $j);
        foreach ($jArray as $v) {
            $va        = explode(":", $v);
            $r[$va[0]] = $va[1];
        }

        return $r;
    }

    public static function array_remove_value($array, $f)
    {
        if ( ! Functions::is_array_plus($array)) {
            return $array;
        }
        foreach ($array as $key => $val) {
            $b = false;
            if (is_callable($f)) {
                $b = $f($val, $key) === true;
            } else {
                $b = Functions::equals($f, $val) === true;
            }
            if ($b) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    public static function DTS($days)
    {
        return $days * 24 * 60 * 60;
    }

    public static function array_merge_key_value($keys, $value)
    {
        $arr = [];
        if ( ! Functions::is_array_plus($keys) || ! Functions::is_array_plus($value)) {
            return $arr;
        }
        if (count($keys) !== count($value)) {
            return $arr;
        }

        foreach ($keys as $i => $key) {
            $arr[$key] = $value[$i];
        }

        return $arr;
    }

    public static function template_replace_args($tpl, $args)
    {
        foreach ($args as $k => $arg) {
            $tpl = str_replace("%$k%", $arg, $tpl);
        }

        return preg_replace("#%+[a-z]+%#", "", $tpl);
    }

}
