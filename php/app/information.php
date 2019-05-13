<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class information extends Model
{
    public $table = 'information';
    public $timestamps = false;
    protected $guarded = ['id'];
}
