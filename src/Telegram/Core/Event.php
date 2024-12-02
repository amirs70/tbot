<?php

namespace Amirm\TBot\Telegram\Core;

use Amirm\TBot\Init\Functions;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Event
{

    protected static array $reacts = [];

    public static function on($message, $pointOrCallback, $callback = null, $once = false): void
    {
        $arr = [];
        if (is_callable($pointOrCallback) || (Functions::is_array_plus($pointOrCallback) && method_exists($pointOrCallback[0], $pointOrCallback[1]))) {
            $arr["callback"] = $pointOrCallback;
        } else {
            $arr["callback"] = $callback;
            if ($pointOrCallback !== null && $pointOrCallback !== "any") {
                $arr["point"] = is_array($pointOrCallback) ? $pointOrCallback : [$pointOrCallback];
            }
        }
        if (!isset($arr["callback"])) {
            return;
        }
        $arr["once"] = $once;
        if ($message !== null && $message !== "any") {
            $arr["message"] = is_array($message) ? $message : [$message];
        }
        Event::$reacts[sha1(uniqid(json_encode($message)))] = $arr;
    }

    public static function once($message, $pointOrCallback, $callback = null): void
    {
        Event::on($message, $pointOrCallback, $callback, true);
    }

    public static function find($message, $point): array|bool
    {
        if (count(Event::$reacts) < 1) {
            return false;
        }
        $exacts = array_filter(Event::$reacts, function ($react) use ($message, $point) {
            return isset($react["message"]) && in_array($message, $react["message"]) &&
                isset($react["point"]) && in_array($point, $react["point"]);
        });
        if (count($exacts) > 0) {
            return $exacts;
        }

        /*$either = array_filter(Event::$reacts, function ($react) use ($message, $point) {
            return (isset($react["message"]) && in_array($message, $react["message"])) || (isset($react["point"]) && in_array($point, $react["point"]));
        });
        if (count($either) > 0) {
            return $either;
        }*/

        $m = array_filter(Event::$reacts, function ($react) use ($message) {
            return isset($react["message"]) && in_array($message, $react["message"]) && !isset($react["point"]);
        });
        if (count($m) > 0) {
            return $m;
        }

        $p = array_filter(Event::$reacts, function ($react) use ($point) {
            return isset($react["point"]) && in_array($point, $react["point"]) && !isset($react["message"]);
        });
        if (count($p) > 0) {
            return $p;
        }

        return false;
    }

    public static function trigger($reacts, $chat_id, $message, $point, $message_id, $rawMessage): JsonResponse
    {
        if (Functions::is_array_plus($reacts)) {
            $a = [];
            foreach ($reacts as $key => $react) {
                if ($react["once"]) {
                    unset(Event::$reacts[$key]);
                }
                call_user_func($react["callback"], $chat_id, $message, $point, $message_id, $rawMessage);
            }
            return Response::json(["success" => true]);
        }
        return Response::json([
            "success" => false,
            "message" => __("Right now we don't have any answer to your request."),
        ]);
    }

}
