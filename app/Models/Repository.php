<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    protected $table = 'repositories';

    protected $fillable = [
        'user_id', 'repos_name', 'repos_owner', 'url'
    ];
}
