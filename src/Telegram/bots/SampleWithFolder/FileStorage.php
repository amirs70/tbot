<?php

namespace Amirm\T_Bot\Telegram\bots\SampleWithFolder;

use Amirm\T_Bot\Telegram\Core\Storage\FileStorage as FS;

class FileStorage extends FS
{

    protected function getStoragePath(): string
    {
        return __DIR__;
    }

}
