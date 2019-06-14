<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TitleReport extends Model
{
    protected $fillable = [
        'date',
        'url',
        'count'
    ];
}
