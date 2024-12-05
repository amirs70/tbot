<?php

namespace Amirm\T_Bot\Telegram\Core;

use Amirm\T_Bot\Init\Functions;
use Amirm\T_Bot\Init\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class Telegram
{

    const URI = "https://api.telegram.org/bot";

    /**
     * @var TelegramBot[]
     */
    const TelegramBots = [];

    private static self|null $obj = null;

    /**
     * @var TelegramBot[]
     */
    private array $bots = [];

    public function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (Telegram::$obj === null) {
            Telegram::$obj = new self();
        }

        return Telegram::$obj;
    }

    private function sorT_Bots($prepare = false): void
    {
        $bots = (array)scandir(__DIR__ . "/../bots");
        unset($bots[0], $bots[1]);
        foreach ($bots as $bot) {
            if (file_exists(app_path("Telegram/bots/$bot/Bot$bot.php"))) {
                $b = "\\App\\Telegram\\bots\\$bot\\Bot$bot";
                $bo = new $b();
            } elseif (file_exists(app_path("Telegram/bots/$bot"))) {
                $b = "\\App\\Telegram\\bots\\" . str_replace(".php", "", $bot);
                $bo = new $b();
            }
            if (isset($bo) && $bo instanceof TelegramBot) {
                if (!$bo->getIsEnabled()) continue;
                if ($prepare) $bo->prepare();
                $this->bots[] = $bo;
            }
        }
    }

    public function init(): self
    {

        $this->sorT_Bots(true);

        Route::get("/Telegram/Hooks/Restart", [Telegram::class, "resetHooks"]);

        return $this;
    }

    public function resetHooks(): JsonResponse
    {
        $this->sorT_Bots();
        $a = [];
        $b = [];
        if (Functions::is_array_plus($this->bots)) {
            foreach ($this->bots as $bot) {
                $b[] = Request::create(Telegram::URI . $bot->getApi() . "/setwebhook?url=" . urlencode(url("/bot/" . $bot->geT_BotPath())))
                    ->execute(Request::METHOD_GET);
                $a[] = Telegram::URI . $bot->getApi() . "/setwebhook?url=" . url("/bot/" . $bot->geT_BotPath());
            }
            return Response::json([
                "success" => true,
                "a" => $a,
                "b" => $b
            ]);
        }
        return Response::json([
            "success" => false,
            "message" => "No enabled bot was found",
        ]);
    }

    public function bots(): self
    {
        foreach ($this->bots as $bot) {
            $bot->react();
        }

        return $this;
    }

}
