<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $fillable = ['tname'];

    /*
     * The questions that marked with the tag
     */

    public function questions()
    {
        return $this->belongsToMany('App\Question');
    }
}
