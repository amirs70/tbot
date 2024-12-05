<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder;

use Amirm\TBot\Telegram\bots\SampleWithFolder\Reacts\AddServerReaction;
use Amirm\TBot\Telegram\bots\SampleWithFolder\Reacts\Start;
use Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Servers\Servers;
use Amirm\TBot\Telegram\bots\SampleWithFolder\V2R\Users\Users;
use Amirm\TBot\Telegram\Core\Chat\InlineButton;
use Amirm\TBot\Telegram\Core\Chat\SingleChat;
use Amirm\TBot\Telegram\Core\Storage\MySQLStorage;
use Amirm\TBot\Telegram\Core\Storage\TelegramStorage;
use Amirm\TBot\Telegram\Core\TelegramBot;
use Illuminate\Support\Facades\Route;

class BotSampleWithFolder extends TelegramBot
{

    //use AllServersReaction;
    use AddServerReaction;

    public function getApi(): string
    {
        return "5850717650:AAEhYPlDWnrFW4v8KxE-naake78ev4FGzGk";
    }

    public function getStorage(): TelegramStorage
    {
        return new MySQLStorage(self::class);
    }

    public function react(): void
    {
        $this->subscription();

        $this->use(new Start($this));
        $this->addServerReaction();
    }

    private function subscription(): void
    {
        Route::get("/subs/{token}", function ($token) {
            $user = Users::getInstance()
                ->get($token);
            if (is_null($user) || $user["enable"] !== true) {
                echo "";

                return;
            }
            echo base64_encode(
                base64_encode(
                    base64_encode(
                        Servers::getInstance()
                            ->getUserAllProfile($token)
                    )
                )
            );
        });
    }

    private function getServersAsButtons(SingleChat $chat): SingleChat|bool
    {
        $servers = Servers::getInstance()
            ->getServers();
        if (count($servers) > 0) {
            $i = 0;
            $c = count($servers);
            $btn = [];
            foreach ($servers as $server) {
                $btn[] = $server["nickname"];
                if ($i % 2 === 0 || ++$i === $c) {
                    $chat->addReplyMarkupKeyboardRow($btn);
                    $btn = [];
                }
            }

            return $chat;
        }

        return false;
    }

    private function getUsersAsButtons(SingleChat $chat): SingleChat|bool
    {
        $users = Users::getInstance()
            ->getUsers();
        if (count($users) > 0) {
            $i = 0;
            $c = count($users);
            $btn = [];
            foreach ($users as $user) {
                $btn[] = $user["nickname"];
                if ($i % 2 === 0 || ++$i === $c) {
                    $chat->addReplyMarkupKeyboardRow($btn);
                    $btn = [];
                }
            }

            return $chat;
        }

        return false;
    }

    public function addUserToServer($chat_id, $message): void
    {
        $chat = SingleChat::create($chat_id);
        $servers_btn = $this->getServersAsButtons($chat);

        if ($servers_btn !== false) {
            $chat = $servers_btn;
            $chat->setText("Which server would you prefer to choose:");
            $this->getStorage()
                ->writeUser($chat_id, "user_for_server", sha1($message))
                ->writeUser($chat_id, "point", sha1($message));
        } else {
            $chat = $this->getMainMenuButtons($chat)
                ->setText("No server has been registered yet");
        }
        $this->sendMessage($chat);
    }

    public function getMainMenuButtons(SingleChat $chat): SingleChat
    {
        $this->point($chat->getChatId(), null);

        return $chat->hideKeyBoard()
            ->addReplyMarkupInlineKeyboardRow([
                InlineButton::create($this, "💾 Add server")
                    ->setCallback("callback_add_new_server"),
                /*InlineButton::create($this, "🖱 All servers")
                    ->setCallback("callback_all_server", [$this, "allServers"]),*/
            ])
            ->addReplyMarkupInlineKeyboardRow([
                InlineButton::create($this, "👤 Add user")
                    ->setCallback("callback_add_user"),
                InlineButton::create($this, "🎁 Add user to server")
                    ->setCallback("callback_users_to_server"),
            ]);
    }

    protected function excludeId(): array|null
    {
        return null;
    }

    protected function includeId(): array|null
    {
        return null;
    }

    public function getIsEnabled(): bool
    {
        return false;
    }
}
