<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Updates extends Model
{
    public $timestamps = false;
    
    protected $table = 'updates';
    
    protected $guarded = [];
    
    /**
     * Retrieve all email addresses that can receive updates from an email address.
     */
    public function getRecipients()
    {
        $recipients = [];
        
        $sql = "SELECT u.email FROM (
                SELECT friend_user_id AS user_id FROM friend WHERE user_id = $this->user_id
                UNION
                SELECT user_id FROM friend WHERE friend_user_id = $this->user_id
                UNION
                SELECT requestor_user_id AS user_id FROM subscribe WHERE target_user_id = $this->user_id
                UNION
                SELECT u.id AS user_id 
                 FROM updates up 
                 LEFT JOIN user u ON up.text LIKE CONCAT('%', u.email, '%')
                 WHERE up.user_id = $this->user_id
                ) AS a
                LEFT JOIN user u ON u.id = a.user_id
                WHERE a.user_id NOT IN 
                (SELECT requestor_user_id FROM block_list WHERE target_user_id = $this->user_id)";
        
        if ($results = DB::select($sql)) {
            foreach ($results as $r) {
                $recipients[] = $r->email;
            }
        }
        
        return $recipients;
    }
}
