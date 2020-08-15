<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedirectLog extends Model
{
    //

    public function url()
    {
        return $this->belongsTo('App\Url','short_url','short_url');
    }
}
