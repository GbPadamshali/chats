<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    /**
      * The attributes that should be mutated to dates.
      *
      * @var array
      */

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'sent_at',
        'read_at',
    ];
}
