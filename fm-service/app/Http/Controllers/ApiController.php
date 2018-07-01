<?php

namespace App\Http\Controllers;

/**
 * Parent controller for the API service
 */
class ApiController extends Controller
{
    /**
     * Return success response with JSON format
     * 
     * @param array $response
     * @return JSON string
     */
    public function responseSuccess($response = [])
    {
        if (!is_array($response)) {
            return response()->json([
                'success' => false,
                'msg' => 'Response is not array'
            ], 500);
        }
        
        $data = array_merge(['success' => true], $response);
        
        return response()->json($data);
    }
    
    /**
     * Return error response with JSON format
     * 
     * @param string $msg error message
     * @param int $code HTTP status code
     * @return JSON string
     */
    public function responseError($msg = 'Internal Server Error', $code = 500)
    {
        return response()->json([
                'success' => false,
                'msg' => $msg
            ], $code);
    }    
}
