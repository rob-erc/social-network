<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = [
        'gallery_id', 'path'
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
