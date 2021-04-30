<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;

// use Illuminate\Support\Facades\Auth;
// use App\Events\MessageSent;
use App\Events\MessageSent;
use Validator;
use App\User;
use Auth;
use App\Patient;
use App\Doctor;
class ChatsController extends Controller
{

        public function index()
        {
            return view('chat');
        }

        public function fetchMessages()
        {
            return Message::with('user')->get();
        }

        

        public function sendMessage(Request $request)
        {
              // $user = Auth::user();
                // doctor-api
                // patient-api
                $user = Auth::guard('patient-api')->user();
               // dd($user);
              $message = $user->messages()->create([
                'message' => $request->input('message')
              ]);
             
              broadcast(new MessageSent($user,$message,$request->chatId))->toOthers();

              return ['status' => 'Message Sent!'];
        }












    public function login(Request $request)
    {
// dd('token');
        try {
            // $rules = [
            //     "email" => "required",
            //     "password" => "required",
            //     // "device_token" => "required"


            // ];

            // $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     $code = $this->returnCodeAccordingToInput($validator);
            //     return $this->returnValidationError($code, $validator);
            // }

            //login

             $credentials = $request -> only(['email','password']) ;

           $token =  Auth::guard('api') -> attempt($credentials);
           // dd($token);
           // if(!$token)
           //     return $this->returnError('E001','بيانات الدخول غير صحيحة');

             $admin = Auth::guard('api') -> user();
             $admin -> api_token = $token;

             $UserData = User::where("email" , $request->email)->first();
             // $UserData->device_token=$request->device_token;
             $UserData->token=$token;
            
             $UserData->save();


        //   $userid= $UserData->id;
              // $patient = User::find($UserData->id);
       
             return response()->json($UserData);

        }catch (\Exception $ex){
            // return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

    public function patientlogin(Request $request)
    {
// dd('token');
        try {
            // $rules = [
            //     "email" => "required",
            //     "password" => "required",
            //     // "device_token" => "required"


            // ];

            // $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     $code = $this->returnCodeAccordingToInput($validator);
            //     return $this->returnValidationError($code, $validator);
            // }

            //login

             $credentials = $request -> only(['email','password']) ;

           $token =  Auth::guard('patient-api') -> attempt($credentials);
           // dd($token);
           // if(!$token)
           //     return $this->returnError('E001','بيانات الدخول غير صحيحة');

             $admin = Auth::guard('patient-api') -> user();
             $admin -> api_token = $token;

             $UserData = Patient::where("email" , $request->email)->first();
             // $UserData->device_token=$request->device_token;
             $UserData->token=$token;
            
             $UserData->save();


        //   $userid= $UserData->id;
              // $patient = User::find($UserData->id);
       
             return response()->json($UserData);

        }catch (\Exception $ex){
            // return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function doctorlogin(Request $request)
    {
// dd('token');
        try {
            // $rules = [
            //     "email" => "required",
            //     "password" => "required",
            //     // "device_token" => "required"


            // ];

            // $validator = Validator::make($request->all(), $rules);

            // if ($validator->fails()) {
            //     $code = $this->returnCodeAccordingToInput($validator);
            //     return $this->returnValidationError($code, $validator);
            // }

            //login

           $credentials = $request -> only(['email','password']) ;

           $token =  Auth::guard('doctor-api') -> attempt($credentials);
           // dd($token);
           // if(!$token)
           //     return $this->returnError('E001','بيانات الدخول غير صحيحة');

             $admin = Auth::guard('doctor-api') -> user();
             $admin -> api_token = $token;

             $UserData = Doctor::where("email" , $request->email)->first();
             // $UserData->device_token=$request->device_token;
             $UserData->token=$token;
            
             $UserData->save();


        //   $userid= $UserData->id;
              // $patient = User::find($UserData->id);
       
             return response()->json($UserData);

        }catch (\Exception $ex){
            // return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }

}
