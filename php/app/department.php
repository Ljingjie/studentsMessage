<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    public $table = 'department';
    public $timestamps = false;
    protected $guarded = ['id'];
}
