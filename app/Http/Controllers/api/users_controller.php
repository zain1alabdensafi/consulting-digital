<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class users_controller extends Controller
{

    // REGESTER API
    public function user_regester(Request $request)
    {
        // checking the email if the expert already take the email
        $expert = Expert::query()->where("email", $request->email)->first();
        if (isset($expert)) {
            return response()->json([
                "status" => 0,
                "message" => "The Email Has Already Geen Taken."
            ]);
        }
        // validation
        $request->validate([
            "name" => "required|alpha_dash",
            "email" => "required|unique:users|email",
            "password" => "required|confirmed"
        ]);
        // create data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_wallet = 1000;
        $user->save();
        $token = $user->createToken("token_name")->plainTextToken;
        // send response
        return response()->json([
            "status" => 1,
            "message" => "User Created Successfully",
            "token" => $token,
            "data" => $user
        ]);
    }
}
