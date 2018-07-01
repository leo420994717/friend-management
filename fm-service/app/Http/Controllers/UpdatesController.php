<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;

use App\Http\Controllers\ApiController;
use App\User;
use App\Updates;

class UpdatesController extends ApiController
{
    /**
     * Create a updates and return recipients
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function create(httpRequest $httpRequest)
    {
        // Check if both sender exists
        if (empty($httpRequest->json('sender')) || !$sender = User::findByEmail($httpRequest->json('sender'))) {
            return $this->responseError('Sender not found', 404);
        }
        
        if (empty($httpRequest->json('text'))) {
            return $this->responseError('Empty text', 400);
        }
        
        if (!$update = Updates::create([
            'user_id' => $sender->id,
            'text' => $httpRequest->json('text')
        ])) {
            return $this->responseError('Failed to create updates', 500);
        }
        
        return $this->responseSuccess([
            'recipients' => $update->getRecipients()
        ]);
    }    
}
