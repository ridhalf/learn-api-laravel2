<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function index()
    {
        return view('index');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        $email = trim($request->header('x-email'));
        $password = trim($request->header('x-password'));

        $request->merge([
            'email' => $email,
            'password' => $password
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ], [
            'required' => ':attribute Tidak Boleh Kosong',
        ]);

        $validator->setAttributeNames([
            'email' => 'Email',
            'password' => 'Password'
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator);
        }

        $credentials = $request->only(['email', 'password']);
        // $validatedData = $request->validate([
        //     'email' => 'required',
        //     'password' => 'required'
        // ]);
        if (!$token = Auth::attempt($credentials)) {
            return $this->respondError('Email atau Password Tidak Sesuai', 201);
        }

        return $this->respondWithToken($token);
    }
}
