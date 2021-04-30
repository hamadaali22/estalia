<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Patient;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Country;
use App\City;
use App\Doctor;
use App\Speciality;
use App\Service;
use Hash;
use Mail;
use Password;
use Illuminate\Support\Str;
use DB;
class DoctorAuthController extends Controller
{

    use GeneralTrait;
   public function login(Request $request)
    {
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
                
                $credentials = $request->only(['email','password']) ;

                $token =  Auth::guard('doctor-api') -> attempt($credentials);
                if(!$token)
                    if(isset($request->lang)  && $request -> lang == 'en' ){
                        return $this -> returnError('404','The email or password is incorrect');
                    }else{
                        return $this -> returnError('404','البريد الإلكتروني أو كلمة المرور خطأ');
                    } 

                    $UserData = Doctor::where("email" , $request->email)->first();
                    if($UserData->is_activated ==0)
                    {
                        if(isset($request->lang)  && $request -> lang == 'en' ){
                            return $this -> returnError('404','your email is not active');
                        }else{
                            return $this -> returnError('404','البريد الإلكتروني غير مفعل');
                        }   
                    }else{
                        $admin = Auth::guard('doctor-api') -> user();
                        $admin -> api_token = $token;
                        $UserData->device_token=$request->device_token;
                        $UserData->token=$token;
                        $UserData->save();
                        $doctor = Doctor::selection()->where('id',$UserData->id)->get();
                        foreach ($doctor as $item) {
                            $item->specialityName= Speciality::selection()->where('id',$item->specialityId)->first();   
                            $item->serviceName= Service::selection()->where('doctorId',$item->id)->get(); 
                            $item->countries= Country::selection()->where('id',$item->countryId)->first();
                            $item->cities= City::selection()->where('id',$item->cityId)->first();
                        }       
                        return $this -> returnData('doctor' , $doctor);
                    } 
           

        }catch (\Exception $ex){
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }


    }
    public function register(Request $request)
    {
        // dd($request->photo);
        
        $checkemail = Doctor::where("email" , $request->email)->first();
        if($checkemail){
            if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnError('001','Email already exists');
            }else{
                return $this -> returnError('001','البريد الإلكتروني موجود مسبقا');
            }
        }else{
            $add = new Doctor();
            if($file=$request->file('photo'))
            {
                $file_extension = $request -> file('photo') -> getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $file_nameone = $file_name;
                $path = 'assets_admin/img/doctors/photo';
                $request-> file('photo') ->move($path,$file_name);
                $add->photo  = $file_nameone;
            }else{
                $add->photo  = "profile_image.png"; 
            }

            $add->first_name_ar  = $request->first_name_ar; 
            $add->last_name_ar  = $request->last_name_ar; 
            $add->first_name_en  = $request->first_name_en; 
            $add->last_name_en  = $request->last_name_en;  
            $add->email  = $request->email;   
            $add->password  = bcrypt($request->password);  
            $add->mobile  = $request->mobile;
            $add->address_ar  = $request->address_ar;
            $add->address_en  = $request->address_en;
            $add->countryId  = $request->countryId;
            $add->cityId  = $request->cityId;
            $add->longitude  = $request->longitude;
            $add->latitude  = $request->latitude;
            $add->experience  = $request->experience;
            $add->gender  = $request->gender; 
            $add->specialityId  = $request->specialityId; 
            $add-> save();
            
            // $add->link = Str::random(32);
            // $add->link = str_random(30);
            // DB::table('user_activations')->insert(['id_user'=>$add->id,'token'=>$add->link]);
            // Mail::send('emails.activation', $add, function($message) use ($add){
            //     $message->to($add->email);
            //     $message->subject('www.hc-kr.com - Activation Code');
            // });
            
            // $user = $request->all()->toArray();
            // $input = {
            //       "id":$add->id,
            //       "email":$request->email,
            //       "first_name_ar":$request->first_name_ar
            // }
            $user = $add->toArray();
            $user['link'] = Str::random(32);
            DB::table('user_activations')->insert(['id_user'=>$user['id'],'token'=>$user['link']]);
            Mail::send('emails.doctor-activation', $user, function($message) use ($user){
                $message->to($user['email']);
                $message->subject('esptaila - Activation Code');
            });

            $video=[
                'doctorId' => $add->id,
                'services_name_ar'  => "استشارة فيديو",
                'services_name_en'  => "Video consulting",
                'price'  => 3,
                'type'  => "Video",
            ];
            $reavel=[
                'doctorId' => $add->id,
                'services_name_ar'  => "كشف ف العيادة",
                'services_name_en'  => "Examination in the clinic",
                'price'  => 3,
                'type'  => "reavel",
            ];   
            $serv1 = Service::create($video);
            $serv2 = Service::create($reavel);
            
            if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('Please visit your email to activate the account ');
            }else{
                return $this -> returnSuccessMessage('يرجى زيارة بريدك الإلكتروني لتفعيل الحساب');
            }
        } 
        // return $this -> returnData('doctor' , $doctor);
        // return redirect()->back()->with("message",'تمت الإضافة بنجاح'); 
    }
    

    public function addNewServices(Request $request)
    {
        
        $video=[
                'doctorId' => 1,
                'services_name_ar'  => "استشارة فيديو",
                'services_name_en'  => "Video consulting",
                'price'  => 3,
                'type'  => "Video",
            ];
        $reavel=[
                'doctorId' => 1,
                'services_name_ar'  => "كشف ف العيادة",
                'services_name_en'  => "Examination in the clinic",
                'price'  => 3,
                'type'  => "reavel",
            ];   
        $serv1 = Service::create($video);
        $serv2 = Service::create($reavel);
        // if($contents_arr) {
        //     return response()->json([
        //         'status' => 'success',
        //         'data' => $contents_arr
        //     ]);
        // }
        // return response()->json([
        //     'status' => 'fail',
        //     'message' => 'failed to create content_arr record'
        // ]);

        // return $this -> returnSuccessMessage('تم الاضافة بنجاح');
    }
    
    public function getDoctorData(Request $request)
    {
        $doctors = Doctor::selection()->where('id',$request->id)->get();
        // $cities= City::selection()->where('id',"1")->first();
        // dd($cities);
        foreach ($doctors as $item) {
            $item->specialityName= Speciality::selection()->where('id',$item->specialityId)->first();   
            $item->serviceName= Service::selection()->where('doctorId',$item->id)->get();  
            $item->countries= Country::selection()->where('id',$item->countryId)->first();
            $item->cities= City::selection()->where('id',$item->cityId)->first();
        }
        
        
        // if (!$doctors)
        //     return $this->returnError('001', 'هذا القسم غير موجد');
       
        
        return $this->returnData('doctors', $doctors);
    }
    
    
     public function doctorUpdate(Request $request)
    {
        $edit = Doctor::selection()->findOrFail($request->doctorId);
                 // $edit = Doctor::selection()->where("id" , $request->id)->first();
        if($file=$request->file('photo'))
         {
            $file_extension = $request -> file('photo') -> getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $file_nameone = $file_name;
            $path = 'assets_admin/img/doctors/photo';
            $request-> file('photo') ->move($path,$file_name);
            $edit->photo  = $file_nameone;
         }else{
            $edit->photo  = $edit->photo; 
         }

         if($file2=$request->file('university_degree'))
         {  
            $file2 = $request->file('university_degree');
            $file_nameone2 = time() . '.' . $request->file('university_degree')->extension();
            $filePath2 = 'assets_admin/img/doctors/degree';
            $file2->move($filePath2, $file_nameone2);

            $edit->university_degree  = $file_nameone2;  
         }else{
            $edit->university_degree  = $edit->university_degree;
         }

        if($fil3=$request->file('practice_certificate'))
         {  
            $file3 = $request->file('practice_certificate');
            $file_nameone3 = time() . '.' . $request->file('practice_certificate')->extension();
            $filePath3 =  'assets_admin/img/doctors/certificate';
            $file3->move($filePath3, $file_nameone3);

            $edit->practice_certificate  = $file_nameone3;   
         }else{
            $edit->practice_certificate  = $edit->practice_certificate;
        }
        if($file4=$request->file('other_qualification'))
         {
            $file4 = $request->file('other_qualification');
            $file_nameone4 = time() . '.' . $request->file('other_qualification')->extension();
            $filePath4 =  'assets_admin/img/doctors/qualification';
            $file4->move($filePath4, $file_nameone4);

            $edit->other_qualification  = $file_nameone4;
         }else{
            $edit->other_qualification  = $edit->other_qualification;
         }
         
       if($request->lang == 'ar'){
            if($request->first_name){
             $edit->first_name_ar  = $request->first_name; 
             }else{
                $edit->first_name_ar  = $edit->first_name; 
             } 

            if($request->last_name){
             $edit->last_name_ar  = $request->last_name;  
             }else{
                $edit->last_name_ar  = $edit->last_name; 
             } 

             if(isset($request->address)){
             $edit->address_ar  = $request->address;  
             }else{
                $edit->address_ar  = $edit->address; 
             } 

            if(isset($request->specialityDesc)){
             $edit->specialityDesc_ar  = $request->specialityDesc;  
             }else{
                $edit->specialityDesc_ar  = $edit->specialityDesc; 
             } 

            if(isset($request->authority)){
             $edit->authority_ar  = $request->authority;  
            }else{
                $edit->authority_ar  = $edit->authority; 
            } 

            if(isset($request->degree)){
              $edit->degree_ar  = $request->degree;  
            }else{
                $edit->degree_ar  = $edit->degree; 
            } 
        }else{
             if(isset($request->first_name)){
             $edit->first_name_en  = $request->first_name;  
             }else{
                $edit->first_name_en  = $edit->first_name; 
             } 
             if(isset($request->last_name)){
                 $edit->last_name_en  = $request->last_name;  
             }else{
                $edit->last_name_en  = $edit->last_name; 
             } 
            if(isset($request->address)){
                 $edit->address_en  = $request->address;  
             }else{
                $edit->address_en  = $edit->address; 
             } 
             
            if(isset($request->specialityDesc)){
             $edit->specialityDesc_en  = $request->specialityDesc;  
             }else{
                $edit->specialityDesc_en  = $edit->specialityDesc; 
             } 
            
             if(isset($request->authority)){
                 $edit->specialityDesc_en  = $request->authority;  
             }else{
                $edit->authority_en  = $edit->authority; 
             } 

            if(isset($request->degree)){
                 $edit->degree_en  = $request->degree;  
             }else{
                $edit->degree_en  = $edit->degree; 
             }  
        }

           
        // if($request->password){
        //     if ((Hash::check(request('old_password'), $userid->password)) == false) {
        //     $arr = array("status" => 400, "message" => "Check your old password.", "data" => array());
        //     } else if ((Hash::check(request('new_password'), $request->password)) == true) {
        //             $arr = array("status" => 400, "message" => "Please enter a password which is not similar then current password.", "data" => array());
        //     } else {
        //         $userid->password  = Hash::make($input['new_password']);
        //         $userid->save();
        //         // Patient::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
        //         $arr = array("status" => 200, "message" => "Password updated successfully.", "data" => $userid);
        //     }  
        // }else{
        //     $edit->password  = $edit->password; 
        // } 

        if(isset($request->mobile)){
            $edit->mobile  = $request->mobile;  
        }else{
            $edit->mobile  = $edit->mobile; 
        }           
        
        if(isset($request->countryId)){
            $edit->countryId  = $request->countryId;  
        }else{
            $edit->countryId  = $edit->countryId; 
        }  

        if(isset($request->cityId)){
            $edit->cityId  = $request->cityId;  
        }else{
            $edit->cityId  = $edit->cityId; 
        } 

        if(isset($request->longitude)){
            $edit->longitude  = $request->longitude;  
        }else{
            $edit->longitude  = $edit->longitude; 
        }

        if(isset($request->latitude)){
            $edit->latitude  = $request->latitude;  
        }else{
            $edit->latitude  = $edit->latitude; 
        } 
        if(isset($request->experience)){
            $edit->experience  = $request->experience;  
        }else{
            $edit->experience  = $edit->experience; 
        }   

        if(isset($request->gender)){
            $edit->gender  = $request->gender;  
        }else{
            $edit->gender  = $edit->gender; 
        } 

        if(isset($request->specialityId)){
            $edit->specialityId  = $request->specialityId;  
        }else{
            $edit->specialityId  = $edit->specialityId; 
        } 

        if(isset($request->membershipNo)){
            $edit->membershipNo  = $request->membershipNo;  
        }else{
            $edit->membershipNo  = $edit->membershipNo; 
        }  

        if(isset($request->yearOfRegistration)){
            $edit->yearOfRegistration  = $request->yearOfRegistration;  
        }else{
            $edit->yearOfRegistration  = $edit->yearOfRegistration; 
        }
        if(isset($request->bankNumber)){
            $edit->bankNumber  = $request->bankNumber;  
        }else{
            $edit->bankNumber  = $edit->bankNumber; 
        }   
        
        
        // dd($request->all());
        $edit-> save();
        // dd($edit);
        // dd($edit->id);
        $doctor = Doctor::selection()->find($edit->id);

        return $this -> returnData('doctor' , $doctor);

        // return $this -> returnSuccessMessage('تم التعديل بنجاح');
        
    }
    
    
  


     public function change_password(Request $request)
    {

        // $gg= Auth::guard('doctor-api')->user();
        // dd($gg);
        $input = $request->all();
        $userid = Doctor::where("id" , $request->id)->first();
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
    
    // public function forgot_password(Request $request)
    // {
    //         $email = Doctor::where("email" , $request->email)->first();
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
                
                 $doctorss= Doctor::where('email',$request->email)->first();
                if($doctorss==null){
                    return $this -> returnError('البريد الإلكتروني غير موجود');
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
