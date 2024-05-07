<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\FlightType;

class Chat extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const TYPE_MESSAGE = 1;
    const TYPE_FILE = 2;
    protected $table = "chats";
    public $timestamps = false;
    protected $fillable = [
        "from_id",
        "to_id",
        "type",
        "message"
    ];

}