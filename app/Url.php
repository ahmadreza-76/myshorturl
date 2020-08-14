<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = [
        'original_url', 'short_url', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
