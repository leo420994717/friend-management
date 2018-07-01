<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public $timestamps = false;
    
    protected $table = 'friend';
    
    protected $guarded = [];
    
    public static function getCommonFriendsEmail($friendAId, $friendBId)
    {
        return Friend::where('friend.user_id', $friendAId)
                ->where('f2.user_id', $friendBId)
                ->join('friend AS f2', 'f2.friend_user_id', '=', 'friend.friend_user_id')
                ->join('user AS u', 'u.id', '=', 'friend.friend_user_id')
                ->pluck('email')
                ->all();
    }
}
