<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'level';
    protected $keyType = 'int';

    protected $fillable = [
        'level',
        'xp_required',
    ];
}
