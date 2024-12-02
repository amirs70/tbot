<?php

namespace Amirm\TBot\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{

    protected $guarded = [];

    public $timestamps = false;

    public string $bot;
    public string $name;
    public string $value;
    public ?int $user_id = null;

}
