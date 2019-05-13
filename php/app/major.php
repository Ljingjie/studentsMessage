<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class major extends Model
{
    public $table = 'major';
    public $timestamps = false;
    protected $guarded = ['id'];
}
