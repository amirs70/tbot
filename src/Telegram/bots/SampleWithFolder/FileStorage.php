<?php

namespace Amirm\TBot\Telegram\bots\SampleWithFolder;

use Amirm\TBot\Telegram\Core\Storage\FileStorage as FS;

class FileStorage extends FS
{

    protected function getStoragePath(): string
    {
        return __DIR__;
    }

}
