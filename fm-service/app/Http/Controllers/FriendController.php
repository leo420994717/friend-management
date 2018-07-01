<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;

use App\Http\Controllers\ApiController;
use App\User;
use App\Friend;

class FriendController extends ApiController
{
    /**
     * Add a new friend connection
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function create(httpRequest $httpRequest)
    {
        if (!$friends = $httpRequest->json('friends')) {
            return $this->responseError('Missing required parameter', 400);
        }
        
        // Check if both user and friend exists
        if (empty($friends[0]) || !$user = User::findByEmail($friends[0])) {
            return $this->responseError('User not found', 404);
        }
        
        if (empty($friends[1]) || !$friend = User::findByEmail($friends[1])) {
            return $this->responseError('Friend not found', 404);
        }
        
        // Add friend connection if not friend yet
        if (!$user->hasFriend($friend->id)) {
            // Can't add new friend if either of them block each other
            if ($user->hasBlocked($friend->id) || $friend->hasBlocked($user->id)) {
                return $this->responseError('Blocked', 403);
            }
            
            if (!$user->addFriend($friend->id)) {
                return $this->responseError('Failed to add friend', 500);
            }
        }
        
        return $this->responseSuccess();
    }    
    
    /**
     * Retrieve the friends list for an email address.
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function getList(httpRequest $httpRequest)
    {
        // Check user exists
        if (empty($httpRequest->json('email')) || !$user = User::findByEmail($httpRequest->json('email'))) {
            return $this->responseError('User not found', 404);
        }
        
        $friends = $user->getFriendsEmail();
        
        return $this->responseSuccess([
            'friends' => $friends,
            'count' => count($friends)
        ]);
    } 
    
    /**
     * Retrieve the common friends list between two email addresses.
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function getCommonList(httpRequest $httpRequest)
    {
        if (!$friends = $httpRequest->json('friends')) {
            return $this->responseError('Missing required parameter', 400);
        }
        
        // Check if both user and friend exists
        if (empty($friends[0]) || !$friendA = User::findByEmail($friends[0])) {
            return $this->responseError('User not found', 404);
        }
        
        if (empty($friends[1]) || !$friendB = User::findByEmail($friends[1])) {
            return $this->responseError('User not found', 404);
        }
        
        $friends = Friend::getCommonFriendsEmail($friendA->id, $friendB->id);
        
        return $this->responseSuccess([
            'friends' => $friends,
            'count' => count($friends)
        ]);
    }     
}
