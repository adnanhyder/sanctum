<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;

class Users extends Controller
{
    //
    public function store(Request $request)
    {

        $idinstance = User::Create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'device' => $request->device,

            ]
        );

        $user = User::where("id", $idinstance->id)->first();

        $tokenResult = $user->createToken($request->device)->plainTextToken;
        return response()->json(
            [
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
    }


    public function logins(Request $request)
    {

        try {
            $request->validate([
                    'email' => 'email|required',
                    'password' => 'required']
            );
            $credentials = request([
                'email', 'password'
            ]);
            if (!Auth::attempt($credentials)) {
                return response()->json(
                    [
                        'status_code' => 500,
                        'message' => 'Unauthorized'
                    ]);
            }
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json(
                [
                    'status_code' => 200,
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',]
            );
        } catch (Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }

    }

    public function all(Request $request)
    {
        $user = User::all();

        return response()->json(
            [
                'status_code' => 200,
                'login' => 1,
                'user' => $user,

            ]);
    }

    public function logout(Request $request)
    {
        $user = Auth()->user();

       $delete = $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return response()->json(
            [
                'status_code' => 200,
                'login' => 1,
                'user_token_deleted' => $delete,

            ]);
    }

}
