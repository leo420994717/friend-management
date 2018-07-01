<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;

use App\Http\Controllers\ApiController;
use App\User;

class UserController extends ApiController
{
    /**
     * Create a new user
     * 
     * @param httpRequest $httpRequest
     * @return JSON
     */
    public function create(httpRequest $httpRequest)
    {
        // Check if user exist
        if (User::findByEmail($httpRequest->json('email'))) {
            return $this->responseSuccess();
        }
        
        if (User::create([
            'email' => $httpRequest->json('email')
        ])) {
            return $this->responseSuccess();
        } else {
            return $this->responseError('Failed to create user', 500);
        } 
    }   
}
