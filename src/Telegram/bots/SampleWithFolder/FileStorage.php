<?php

namespace App\Telegram\bots\SampleWithFolder;

use App\Telegram\Core\Storage\FileStorage as FS;

class FileStorage extends FS
{

    protected function getStoragePath(): string
    {
        return __DIR__;
    }

}
