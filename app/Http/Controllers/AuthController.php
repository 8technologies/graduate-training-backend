<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function Login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $data['token'] = $user->createToken('API Token')->accessToken;
        $data['user'] = $user;
        $user->getAllPermissions();



        return response()->json($data, 200);
    }

    //function to register new user
    public function Register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'university_id' => 'required|max:255',
            'role_id' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'telephone' => $request->telephone,
            'university_id' => $request->university_id,
            'role_id' => $request->role_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('Admin');

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    //get authenticated user
    public function User()
    {
        $getusers = User::all();
        return response()->json($getusers, 200);
    }


}
