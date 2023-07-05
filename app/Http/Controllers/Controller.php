<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function respondWithToken($token)
    {
        return response()->json([
            'response' => [
                'token' => $token
            ],
            'metadata' => [
                'message' => 'OK',
                'code' => 200
            ]

        ], 200);
    }

    protected function validationErrors($validator)
    {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $key => $value) {
            array_push($errors, $value[0]);
        }
        $message = implode(', ', $errors);

        return response()->json(['metadata' => ['message' => $message, 'code' => 201]], 201);
    }

    protected function respondError($message, $code)
    {
        return response()->json(['metadata' => ['message' => $message, 'code' => $code]], $code);
    }

    protected function respondSuccess($response = null)
    {
        if ($response != null) {
            $result['response'] = $response;
        }

        $result['metadata'] = [
            'message' => 'OK',
            'code' => 200
        ];

        return response()->json($result, 200);
    }
}
