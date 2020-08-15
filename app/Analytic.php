<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    //
    protected $fillable = ['short_url','type'];
    public function url()
    {
        return $this->belongsTo('App\Url','short_url','short_url');
    }
}
