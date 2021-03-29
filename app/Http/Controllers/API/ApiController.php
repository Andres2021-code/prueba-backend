<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            "success" => true,
            "data" => $result,
            "message" => $message
        ];

        return response()->json($response, 200);
    }

    public function sendErrors($error, $errorMessage = [], $status = 404)
    {
        $response = [
            "success" => false,
            "data" => $error,
            "errorMessage" => $errorMessage
        ];

        return response()->json($response, $status);    }
    }
