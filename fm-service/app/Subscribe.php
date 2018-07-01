<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    public $timestamps = false;
    
    protected $table = 'subscribe';
    
    protected $guarded = [];
}
