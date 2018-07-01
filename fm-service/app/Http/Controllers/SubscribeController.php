<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;

use App\Http\Controllers\ApiController;
use App\User;

class SubscribeController extends ApiController
{
    /**
     * subscribe to updates from an email address
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function create(httpRequest $httpRequest)
    {
        // Check if both requestor and target exists
        if (empty($httpRequest->json('requestor')) || !$requestor = User::findByEmail($httpRequest->json('requestor'))) {
            return $this->responseError('Requestor not found', 404);
        }
        
        if (empty($httpRequest->json('target')) || !$target = User::findByEmail($httpRequest->json('target'))) {
            return $this->responseError('Target not found', 404);
        }

        // Subscribe
        if (!$requestor->hasSubscribed($target->id)) {
            // Can't subscribe if either of them block each other
            if ($requestor->hasBlocked($target->id) || $target->hasBlocked($requestor->id)) {
                return $this->responseError('Blocked', 403);
            }
            
            if (!$requestor->subscribe($target->id)) {
                return $this->responseError('Failed to subscribe', 500);
            }
        }
        
        return $this->responseSuccess();
    }    
}
