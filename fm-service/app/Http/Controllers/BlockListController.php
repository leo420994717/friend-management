<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;

use App\Http\Controllers\ApiController;
use App\User;

class BlockListController extends ApiController
{
    /**
     * block updates from an email address
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

        // Block updates if not blocked
        if (!$requestor->hasBlocked($target->id)) {
            if (!$requestor->block($target)) {
                return $this->responseError('Failed to block', 500);
            }
        }
        
        return $this->responseSuccess();
    }    
}
