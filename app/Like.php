<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
    use SoftDeletes;

    protected $table = 'likeables';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];


    public function pictures()
    {
        return $this->morphedByMany('App\Picture', 'likeable');
    }

    public function posts()
    {
        return $this->morphedByMany('App\Post', 'likeable');
    }
}
