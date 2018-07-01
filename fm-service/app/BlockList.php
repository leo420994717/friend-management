<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlockList extends Model
{
    public $timestamps = false;
    
    protected $table = 'block_list';
    
    protected $guarded = [];
}
