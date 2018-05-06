<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['body'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function answers()
    {
        return $this->hasMany('App\Answer');
    }

    /*
     * The tags that belong to the question
     */

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }
}
