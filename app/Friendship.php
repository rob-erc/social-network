<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_user', 'second_user', 'acted_user', 'status'
    ];
}
