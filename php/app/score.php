<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class score extends Model
{
    public $table = 'score';
    public $timestamps = false;
    protected $guarded = ['id'];
}
