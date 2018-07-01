<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use Illuminate\Support\Facades\DB;

use App\Friend;
use App\Subscribe;
use App\BlockList;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    public $timestamps = false;
    
    protected $table = 'user';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
    ];
    
    /**
     * Find user by email
     * 
     * @param string $email
     * @return User
     */
    public static function findByEmail($email) 
    {
        return self::where('email', $email)->first();
    }
    
    /**
     * Check if user has friend connection with this friend
     * @param int $friendUserId
     * @return boolean
     */
    public function hasFriend($friendUserId)
    {
        return Friend::where('user_id', $this->id)->where('friend_user_id', $friendUserId)
                ->exists();
    }
    
    /**
     * Add new friend connection
     * @param int $friendUserId
     * @return Friend
     */
    public function addFriend($friendUserId)
    {
        return Friend::create([
            'user_id' => $this->id,
            'friend_user_id' => $friendUserId
        ]);
    }
    
    /**
     * Get user's friends email list
     * 
     * @return array
     */
    public function getFriendsEmail()
    {
        $emails = Friend::where('user_id', $this->id)
                ->leftJoin('user', 'user.id', '=', 'friend.friend_user_id')
                ->pluck('email')
                ->all();
        
        return $emails;
    }
    
    /**
     * Check if user has subscribed updates from target user
     * @param int $targetUserId
     * @return boolean
     */
    public function hasSubscribed($targetUserId)
    {
        return Subscribe::where('requestor_user_id', $this->id)->where('target_user_id', $targetUserId)
                ->exists();
    }
    
    /**
     * Subscribe updates from target user
     * @param int $targetUserId
     * @return Subscribe
     */
    public function subscribe($targetUserId)
    {
        return Subscribe::create([
            'requestor_user_id' => $this->id,
            'target_user_id' => $targetUserId
        ]);
    }

    /**
     * Unsubscribe updates from target user
     * @param int $targetUserId
     * @return Subscribe
     */
    public function unsubscribe($targetUserId)
    {
        return Subscribe::where('requestor_user_id', $this->id)->where('target_user_id', $targetUserId)->delete();
    }
    
    /**
     * Check if user has blocked updates from target user
     * @param int $targetUserId
     * @return boolean
     */
    public function hasBlocked($targetUserId)
    {
        return BlockList::where('requestor_user_id', $this->id)->where('target_user_id', $targetUserId)
                ->exists();
    }
    
    /**
     * Block updates from target user
     * @param User $targetUser
     * @return Subscribe
     */
    public function block($targetUser)
    {
        DB::beginTransaction();
        
        try {
            if (!BlockList::create([
                'requestor_user_id' => $this->id,
                'target_user_id' => $targetUser->id
            ])) {
                throw new \Exception('Failed to create block list');
            }
            
            // Unsubscribe each other
            $this->unsubscribe($targetUser->id);
            $targetUser->unsubscribe($this->id);
            
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollback();
            return false;
        }
    }    
}
