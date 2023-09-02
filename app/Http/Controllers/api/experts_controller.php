<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Consult_type;
use App\Models\Expert;
use App\Models\ExpertConsultations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class experts_controller extends Controller
{
    // EXPERT REGESTER
    public function expert_regester(Request $request)
    { {
            // checking the email if the user already take the email
            $user = User::query()->where("email", $request->email)->first();
            if (isset($user)) {
                return response()->json([
                    "status" => 0,
                    "message" => "The email has already been taken."
                ]);
            }
            // validation
            $request->validate([
                "name" => 'required|alpha_dash',
                "email" => 'required|email|unique:Experts',
                "password" => 'required|confirmed',
                "image" => 'required',
                "phone_num" => 'required|unique',
                "address" => 'required',
                "experience" => 'required',
                "session_price" => 'required',
                "consultation" => 'required|integer'
            ]);
            // create expert
            $expert = new Expert();
            $expert->name = $request->name;
            $expert->email = $request->email;
            $expert->password = Hash::make($request->password);
            $expert->img = URL::asset('storage' . $request->file('image')->store('public/images'));
            $expert->phone_num = $request->phone_num;
            $expert->address = $request->address;
            $expert->experience = $request->experience;
            $expert->expert_wallet = 0;
            $expert->session_time = 1;
            $expert->session_price = $request->session_price;
            $expert->save();
            ExpertConsultations::create([
                "consult_id" => $request->consultation,
                "expert_id" => $expert->expert_id
            ]);
            $token = $expert->createToken("token")->plainTextToken;
            return response()->json([
                "status" => 1,
                "messege" => "Expert Created Successfully",
                "token" => $token,
                "data" => $expert
            ]);
        }
    }


    // GET PROFILE API
    public function expert_by_id(Request $request, $expert_id)
    {
        $expert = Expert::query()->where("expert_id", $expert_id)->first();
        if (!isset($expert)) {
            return response()->json([
                "status" => 0,
                "message" => "The id is not valid."
            ], 422);
        } else {
            $consult =  ExpertConsultations::query()->where("expert_id", "=", $expert_id)->get();
            $experts = [];
            foreach ($consult as $item) {
                $rashido = Consult_type::query()->where("consult_id", "=", $item->consult_id)->first();
                array_push($experts, $rashido);
            };
            return response()->json([
                "status" => 1,
                "message" => "user profile",
                "consultations" => $experts,
                "data" => $expert
            ]);
        }
    }


    // GET THE EXPERTS IN SPECIFIC CONSULTATION
    public function ExpertsByConsultation(Request $request, $consult_id)
    {
        $consult = Consult_type::query()->where("consult_id", $request->consult_id)->first();
        if (!isset($consult)) {
            return response()->json([
                "status" => 0,
                "message" => "There Is No Experts In This Consultation"
            ], 422);
        }
        $mix = ExpertConsultations::query()->where("consult_id", $consult_id)->get();
        $experts = [];
        foreach ($mix as $item) {
            $expert = Expert::query()->where("expert_id", $item->expert_id)->get()[0];
            array_push($experts, $expert);
        };
        return $experts;
    }


    // GET ALL THE EXPERTS
    public function get_all_experts()
    {
        $experts = Expert::all();
        $all = [];
        foreach ($experts as $expert) {
            $expertData = $expert->load('ExpertConsultations');
            array_push($all, $expertData);
        }
        return response()->json([
            "status" => 1,
            "data" => $all
        ]);
    }
}
