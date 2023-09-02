<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\DayAvailable;
use App\Models\Expert;
use App\Models\TimeAvailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class time_controller extends Controller
{
    // ADDING THE TIME AVAILABLE
    public function time_available(Request $request)
    {
        // Validation
        $request->validate([
            'day' => "required|numeric|min:1|max:7",
            "from" => "required|numeric|min:1|max:24",
            "to" => 'required|numeric|min:1|max:24'
        ]);
        if ($request->from >= $request->to) {
            return response()->json([
                "status" => 0,
                "message" => "Please Enter Valid Time"
            ], 422);
        }
        $expert = Auth::user();
        $dayWorkTime = $expert->time_available;
        if (isset($dayWorkTime)) {
            foreach ($dayWorkTime as $workTimeItem) {
                if ($workTimeItem->day == $request->day) {
                    if ($request->from < $workTimeItem->to && $request->to > $workTimeItem->from) {
                        return response()->json([
                            "status" => 0,
                            "message" => "The Times Collided, Please Enter Valid Times"
                        ]);
                    }
                }
            }
        }
        // Adding The Time
        $time = new TimeAvailable();
        $time->day = $request->day;
        $time->from = $request->from;
        $time->to = $request->to;
        $time->expert_id = $expert->expert_id;
        $time->save();
        return  response()->json([
            "status" => 1,
            "message" => "Time Added Successfully",
            "data" => $time
        ]);
    }
    public function booking(Request $request)
    {
        // sut= [1,2,4,9,10]
        // [1,1,4]
        // [1,9,10]
        // [1,1]=>  1->2 is booked
        $sut = [];
        $sun = [];
        $mon = [];
        $tus = [];
        $wed = [];
        $ths = [];
        $fri = [];
        $expert = auth()->user();
        $expertWorkTime = $expert->time_available;
        foreach ($expertWorkTime as $workTimeItem) {
            switch ($workTimeItem->day) {
                case 1:
                    array_push($sut, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 2:
                    array_push($sun, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 3:
                    array_push($mon, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 4:
                    array_push($tus, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 5:
                    array_push($wed, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 6:
                    array_push($ths, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
                case 7:
                    array_push($fri, time_controller::time_cutter($workTimeItem->from, $workTimeItem->to));
                    break;
            }
        }
        switch ($request->day) {
            case 1:
                foreach($sut as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 2:
                foreach($sun as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 3:
                foreach($mon as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 4:
                foreach($tus as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 5:
                foreach($wed as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 6:
                foreach($ths as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
            case 7:
                foreach($fri as $item){
                    if($request->from = $item){
                        unset($sut[$item]);
                    }else{
                        return response()->json([
                            "message" => "the time is not available"
                        ]);
                    }
                }
                break;
        }
    }
    private function time_cutter($from, $to)
    {
        $result = [];
        while ($to > $from) {
            $from = $from + 1;
            array_push($result, $from);
        }
        return $result;
    }

    public function free_time(Request $request, $expert_id){
        $expert = Expert::query()->where('expert_id', $expert_id)->first();
        
    }

    // private function time_cutter($from, $to, $duration)
    // {
    //     $result = [];
    //     while ($to > $from) {
    //         $from = $from + $duration;
    //         array_push($result, $from);
    //     }
    //     return $result;
    // }
}
