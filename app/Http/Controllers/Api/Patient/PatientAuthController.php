<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Admin;
use App\Patient;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Hash;



use Mail;
use Password;
use Illuminate\Support\Str;
use DB;
class PatientAuthController extends Controller
{

    use GeneralTrait;
    
 
    public function login(Request $request)
    {
// dd('token');
        try {
            $rules = [
                "email" => "required",
                "password" => "required",
                "device_token" => "required"
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

               
                $credentials = $request -> only(['email','password']);

                $token =  Auth::guard('patient-api') -> attempt($credentials);
               // dd($token);
                if(!$token)

                if(isset($request->lang)  && $request -> lang == 'en' ){
                    return $this -> returnError('404','The login information is incorrect ');
                }else{
                    return $this -> returnError('404','بيانات الدخول غير صحيحة');
                }


                $UserData = Patient::where("email" , $request->email)->first();
                if($UserData->is_activated ==0)
                {
                    if(isset($request->lang)  && $request -> lang == 'en' ){
                        return $this -> returnError('404','your email is not active');
                    }else{
                        return $this -> returnError('404','البريد الإلكتروني غير مفعل');
                    }   
                }else{ 

                    $admin = Auth::guard('patient-api') -> user();
                    $admin -> api_token = $token;

                    $UserData->device_token=$request->device_token;
                    $UserData->token=$token;
                    $UserData->save();
                     // $userid= $UserData->id;
                    $patient = Patient::selection()->find($UserData->id);
                    //  $patient = Auth::guard('patient-api')->user();  
                    return $this -> returnData('patient' , $patient);
                }   

            
        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }


    public function register(Request $request)
    {
        // dd($request->photo);
        $checkemail = Patient::where("email" , $request->email)->first();
        if($checkemail){
            if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnError('404','Email already exists');
            }else{
                return $this -> returnError('404','البريد الإلكتروني موجود مسبقا');
            }
        }else{
            $add = new Patient();
            if($file=$request->file('photo'))
            {
                $file_extension = $request -> file('photo') -> getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $file_nameone = $file_name;
                $path = 'assets_admin/img/patients';
                $request-> file('photo') ->move($path,$file_name);
                $add->photo  = $file_nameone;
            }else{
                $add->photo  = $request->url; 
            }
    
            $add->first_name_ar  = $request->first_name_ar; 
            $add->last_name_ar  = $request->last_name_ar; 
            $add->first_name_en  = $request->first_name_en; 
            $add->last_name_en  = $request->last_name_en;    
            $add->email  = $request->email;   
            $add->password  = bcrypt($request->password); 
            $add->mobile  = $request->mobile; 
            $add->gender  = $request->gender; 
            $add->dateOfBirth  = $request->dateOfBirth;  
            $add-> save();

            $user = $add->toArray();
            $user['link'] = Str::random(32);
            DB::table('user_activations')->insert(['id_user'=>$user['id'],'token'=>$user['link']]);
            Mail::send('emails.patient-activation', $user, function($message) use ($user){
                $message->to($user['email']);
                $message->subject('esptaila - Activation Code');
            });
          if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('Please visit your email to activate the account ');
            }else{
                return $this -> returnSuccessMessage('يرجى زيارة بريدك الإلكتروني لتفعيل الحساب');
            }
        }
       
        // return redirect()->back()->with("message",'تمت الإضافة بنجاح'); 
    }
    public function getPatientData(Request $request)
    {
        $patients = Patient::selection()->find($request->id);
        if (!$patients)
            return $this->returnError('001', 'هذا القسم غير موجد');
       
        return $this->returnData('patients', $patients);
    }
    
    public function change_password(Request $request)
    {

        // $gg= Auth::guard('doctor-api')->user();
        // dd($gg);
        $input = $request->all();
        $userid = Patient::where("id" , $request->id)->first();
        // $userid = Auth::guard('doctor-api')->user()->id;
        // dd($userid);
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                // dd('12345');
                // $cc=$userid->password;
                // dd($cc);
                // $ss=bcrypt($request->old_password);
                //  dd($ss);
                if ((Hash::check(request('old_password'), $userid->password)) == false) {
                    $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
                } else if ((Hash::check(request('new_password'), $request->password)) == true) {
                    $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
                } else {
                     $userid->password  = Hash::make($input['new_password']);
                     $userid->save();
                    // Patient::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => $userid);
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                $arr = array("status" => 400, "message" => $msg, "data" => array());
            }
        }
        return \Response::json($arr);
    }
    
    
    // public function forgetPassword(Request $request)
    // {
    //         $email = Patient::where("email" , $request->email)->first();
    //         // $edit->save();
    //         return $this -> returnSuccessMessage('يرجي زيارة بريدك الإلكتروني');
    // }
    
    
     public function forgetPassword(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'email' => "required|email",
        );




        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                 $doctorss= Patient::where('email',$request->email)->first();
                if($doctorss==null){
                    if(isset($request->lang)  && $request -> lang == 'en' ){
                         return $this -> returnError('001','Email not found ');
                    }else{
                        return $this -> returnError('001','البريد الإلكتروني غير موجود');
                    }
                }else{
                    // $user->registartionId = str_rand(6 only digit)->unique;
                    $gene = mt_rand(1000000000, 9999999999);
                    $doctorss->password = bcrypt($gene);
                    // str_rand(8)->make_bcrypt->unique;
                    $doctorss->save();
                    // dd($doctorss);
                    $details = [
                        'title' => 'Password of Esptalia',
                        'body' => 'cope this password to enter Esptalia ' ." " . $gene . " "
                    ];
                   
                    \Mail::to($request->email)->send(new \App\Mail\MyTestMail($details));
                   
                    // dd("Email is Sent.");

                    if(isset($request->lang)  && $request -> lang == 'en' ){
                         return $this -> returnSuccessMessage('Please visit your email ');
                    }else{
                        return $this -> returnSuccessMessage('يرجى زيارة بريدك الإلكتروني');
                    }
                }
                    


                    











            } catch (\Swift_TransportException $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            } catch (Exception $ex) {
                $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
            }
        }
        // return \Response::json('doneeeee');
    }
}
