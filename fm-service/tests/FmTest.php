<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class FmTest extends TestCase
{
    /**
     * 1. As a user, I need an API to create a friend connection between two email addresses.
     */
    public function testStoryOne()
    {
        $this->json('POST', '/users', ['email' => 'andy@example.com'])
             ->seeJson([
                'success' => true,
             ]);
        
        $this->json('POST', '/users', ['email' => 'john@example.com'])
             ->seeJson([
                'success' => true,
             ]);
        
         $this->json('POST', '/friends', ['friends' => [
             'andy@example.com', 'john@example.com'
         ]])
             ->seeJson([
                'success' => true,
             ]);
    }
    
    /**
     * 2. As a user, I need an API to retrieve the friends list for an email address.
     */
    public function testStoryTwo()
    {        
         $this->json('POST', '/friends/get-list', ['email' => 'andy@example.com'])
             ->seeJson([
                'success' => true,
                'friends' => ['john@example.com'],
                'count' => 1
             ]);
    }  
    
    /**
     * 3. As a user, I need an API to retrieve the common friends list between two email addresses.
     */
    public function testStoryThree()
    {        
        // Add user common@example.com
        $this->json('POST', '/users', ['email' => 'common@example.com'])
             ->seeJson([
                'success' => true,
             ]);
        
        // Add friend connection with andy and john
        $this->json('POST', '/friends', ['friends' => [
             'andy@example.com', 'common@example.com'
         ]])
             ->seeJson([
                'success' => true,
             ]);
        
        $this->json('POST', '/friends', ['friends' => [
             'john@example.com', 'common@example.com'
         ]])
             ->seeJson([
                'success' => true,
             ]);
        
        // Get common friend
         $this->json('POST', '/friends/get-common-list', ['friends' => [
             'andy@example.com', 'john@example.com'
         ]])
             ->seeJson([
                'success' => true,
                'friends' => ['common@example.com'],
                'count' => 1
             ]);
    }    
    
    /**
     * 4. As a user, I need an API to subscribe to updates from an email address.
     */
    public function testStoryFour()
    {        
        // Add user lisa
        $this->json('POST', '/users', ['email' => 'lisa@example.com'])
             ->seeJson([
                'success' => true,
             ]);
        
         $this->json('POST', '/subscribes', ['requestor' => 'lisa@example.com', 'target' => 'john@example.com'])
             ->seeJson([
                'success' => true
             ]);
    }    
    
    /**
     * 5. As a user, I need an API to block updates from an email address.
     */
    public function testStoryFive()
    {        
         $this->json('POST', '/blocks', ['requestor' => 'andy@example.com', 'target' => 'john@example.com'])
             ->seeJson([
                'success' => true
             ]);
    }    
    
    /**
     * 6. As a user, I need an API to retrieve all email addresses that can receive updates from an email address.
     */
    public function testStorySix()
    {        
        // Add user kate
        $this->json('POST', '/users', ['email' => 'kate@example.com'])
             ->seeJson([
                'success' => true,
             ]);
        
         $this->json('POST', '/updates', ['sender' => 'john@example.com', 'text' => "Hello World! kate@example.com"])
             ->seeJson([
                'success' => true,
                "recipients" =>
                    [
                      "lisa@example.com",
                      "common@example.com", // Don't forget common
                      "kate@example.com"
                    ]
             ]);
    }    
}
