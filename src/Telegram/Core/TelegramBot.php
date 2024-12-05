<?php

namespace Amirm\T_Bot\Telegram\Core;

use Amirm\T_Bot\Init\Functions;
use Amirm\T_Bot\Telegram\Core\Storage\TelegramStorage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class TelegramBot
{

    use TelegramMessenger;

    protected ?int $chat_id;

    public function __construct()
    {

    }

    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    public function prepare(): void
    {
        Route::post("/bot/{$this->geT_BotPath()}", [$this::class, "sortProperReact"]);
    }

    public function sortProperReact(): JsonResponse
    {
        $parsed = TelegramParser::parse(Functions::objectToArray(json_decode(file_get_contents('php://input'))));
        if ($parsed === false) {
            return Response::json([
                "success" => false,
                "message" => __("The message you requested was not found.")
            ])/*->setStatusCode(404)*/ ;
        }
        $this->chat_id = $parsed["sender"];
        App::setLocale($this->getStorage()->readUser($this->getChatId(), "lang", "en"));
        if (Functions::is_array_plus($this->includeId()) || Functions::is_array_plus($this->excludeId())) {
            $excluded = Functions::is_array_plus($this->excludeId()) && in_array($parsed["sender"], $this->excludeId());
            $notIncluded = Functions::is_array_plus($this->includeId()) && !in_array($parsed["sender"], $this->includeId());
            if (!$excluded || !$notIncluded) {
                return Response::json([
                    "success" => false,
                    "message" => __("You're not allowed to use this robot.")
                ])/*->setStatusCode(403)*/ ;
            }
        }
        $point = $this->getStorage()
            ->readUser($parsed["sender"], "point");
        $properReacts = $this->fineProperReact($parsed["text"], $point);
        return $this->runReacts(
            $properReacts,
            $parsed["sender"],
            $parsed["text"],
            $point,
            $parsed["message_id"],
            $parsed["raw_message"]
        );
    }

    private function runReacts($reacts, $chat_id, $message, $point, $message_id, $rawMessage): JsonResponse
    {
        return Event::trigger($reacts, $chat_id, $message, $point, $message_id, $rawMessage);
    }

    /**
     * @param $message
     * @param $point
     *
     * @return array|bool [callable]|bool
     */
    private function fineProperReact($message, $point): array|bool
    {
        return Event::find($message, $point);
    }

    public function geT_BotPath(): string
    {
        return explode(":", $this->getApi())[1];
    }

    abstract public function getApi(): string;

    abstract public function getIsEnabled(): bool;

    abstract protected function excludeId(): array|null;

    abstract protected function includeId(): array|null;

    abstract public function getStorage(): TelegramStorage;

    public function reactTo($message, $pointOrCallback, $callback = null): self
    {
        //var_dump("message", $message, "pointOrCallback", $pointOrCallback, "------");
        Event::on($message, $pointOrCallback, $callback);

        return $this;
    }

    public function point(int $user_id, ?string $point = null): self|string|int|array|bool|null
    {
        if ($point === null) return $this->getStorage()->point($user_id);
        $this->getStorage()->writeUser($user_id, "point", $point);

        return $this;
    }

    public function setting(string $name, ?string $value = null): self|string|int|array|bool|null
    {
        if ($value === null) return $this->getStorage()->readBotSetting($name);
        $this->getStorage()->writeBotSetting($name, $value);

        return $this;
    }

    public function userMeta(int $user_id, string $name, ?string $value = null): self|string|int|array|bool|null
    {
        if ($value === null) return $this->getStorage()->readUser($user_id, $name);
        $this->getStorage()->writeUser($user_id, $name, $value);

        return $this;
    }

    /*public function reactToOnce($message, $pointOrCallback, $callback = null): self
    {
        Event::once($message, $pointOrCallback, $callback);

        return $this;
    }*/

    public function use(Reactable $react): void
    {
        $react->use();
    }

    abstract public function react(): void;

}
