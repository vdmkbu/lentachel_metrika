<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuthorReport extends Model
{
    protected $fillable = [
        'date',
        'url',
        'count'
    ];
}
