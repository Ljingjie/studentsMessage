<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class subject extends Model
{
    public $table = 'subject';
    public $timestamps = false;
    protected $guarded = ['id'];
}
