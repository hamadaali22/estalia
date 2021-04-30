<?php

namespace App\Http\Controllers\Api\Doctor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Banner;
use App\Article;
use App\Doctor;
use App\Patient;
use App\Slider;
use App\Speciality;
use App\Service;
use App\Offer;
use App\Payment;
use App\Appointment;
use App\Diagnostic;
use Carbon\Carbon;
use App\WorkingDays;
use App\Traits\GeneralTrait;
use DateTime;
use DB;
class HomeController extends Controller
{
    use GeneralTrait;
      public function index(Request $request)
    {
        
        $workday= WorkingDays::where('doctorId',$request->doctorId)->first();
         $todayDate = date("Y-m-d");
         
        $appoint_morning=Appointment::where('doctorId',$request->doctorId)
                                     ->where('permanent_type','AM')
                                     ->where('date',$todayDate)
                                     ->where('status','confirmed')
                                     ->where('payment_status',1)
                                     ->where('doctorId',$request->doctorId)
                                     ->orderBy('id', 'DESC')->get();
        foreach ($appoint_morning as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
        $count_morning= count($appoint_morning);
        
        $appoint_after=Appointment::where('doctorId',$request->doctorId)
                                    ->where('permanent_type','AF')
                                    ->where('date',$todayDate)
                                    ->where('status','confirmed')
                                    ->where('payment_status',1)
                                    ->orderBy('id', 'DESC')->get();
        foreach ($appoint_after as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
                // dd($appoint_after);

        $count_after= count($appoint_after);
        $appoint_evening=Appointment::where('doctorId',$request->doctorId)
                                        ->where('permanent_type','PM')
                                        ->where('date',$todayDate)
                                        ->where('status','confirmed')
                                        ->where('payment_status',1)
                                        ->orderBy('id', 'DESC')->get();
        foreach ($appoint_evening as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
        $count_evening= count($appoint_after);
        $offers = Offer::selection()->where('doctorId',$request->doctorId)->orderBy('id', 'DESC')->get();        
        $articles = Article::selection()->where('doctorId',$request->doctorId)->get();  
        foreach ($articles as $item) {
            $item->specialityName= Speciality::selection()->where('id',$item->specialityId)->first();     
        }  
        $home  =[  
                    'appoint_morning'=>$appoint_morning,
                    'appoint_after'=>$appoint_after,
                    'appoint_evening'=>$appoint_evening,
                    'workday'=> $workday,
                    'count_morning'=> $count_morning,
                    'count_after'=> $count_after,
                    'count_evening'=> $count_evening,
                    'offers'=>$offers,
                    'articles'=>$articles,
                ];
        return $this -> returnData('home',$home);
    }
    public function getDoctorOffer(Request $request)
    {
        $offers = Offer::where('id',$request->offerId)->first();  
         return $this -> returnData('offer',$offers);
    }
    public function getAppointmentById(Request $request)
    {
        
        $appoint_morning=Appointment::where('doctorId',$request->doctorId)
                                     ->where('date',$request->date)
                                     ->where('status','!=',"combledted")
                                      ->where('status','!=',"absent")
                                      ->where('payment_status',1)
                                     ->where('permanent_type','AM')->get();
        foreach ($appoint_morning as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
        
        
       $appoint_after=Appointment::where('doctorId',$request->doctorId)
                                     ->where('date',$request->date)
                                     ->where('status','!=',"combledted")
                                      ->where('status','!=',"absent")
                                      ->where('payment_status',1)
                                     ->where('permanent_type','AF')->get();
        foreach ($appoint_after as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
      
        $appoint_evening=Appointment::where('doctorId',$request->doctorId)
                                     ->where('date',$request->date)
                                     ->where('status','!=',"combledted")
                                     ->where('status','!=',"absent")
                                     ->where('payment_status',1)
                                     ->where('permanent_type','PM')->get();
        foreach ($appoint_evening as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        }
        $workday= WorkingDays::where('doctorId',$request->doctorId)->first();
      
        $appointment  =[  
                'appoint_morning'=>$appoint_morning,
                'appoint_after'=>$appoint_after,
                'workday'=>$workday,
                'appoint_evening'=>$appoint_evening,
        ];
        return $this -> returnData(
            'appointment',$appointment
        );
    }

    public function addNewOffer(Request $request)
    {

         
        $percent = (($request->old_price - $request->new_price) / $request->old_price) * 100;
        // dd(intval($percent));
            $add = new Offer;
            if($file=$request->file('image'))
             {
                $file_extension = $request -> file('image') -> getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $file_nameone = $file_name;
                $path = 'assets_admin/img/offers';
                $request-> file('image') ->move($path,$file_name);
                $add->image  = $file_nameone;
             }else{
                $add->image  = $request->url; 
             }
            $add->doctorId    = $request->doctorId;
            $add->offer_name_ar  = $request->offer_name_ar;
            $add->offer_name_en  = $request->offer_name_en;
            $add->description_ar  = $request->description_ar;
            $add->description_en  = $request->description_en;
            $add->old_price  = $request->old_price;
            $add->new_price  = $request->new_price;
            $add->percent = intval($percent);
            $add->type  = $request->type;
            $add->save();
            // dd($add);

            if(isset($request -> lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('added Successfully ');
            }else{
                return $this -> returnSuccessMessage('تم الاضافة بنجاح');
            }

    }
    
    public function sendfile(Request $request)
    {
            $add = new Offer;
            if($file=$request->file('image'))
             {
                $file_extension = $request -> file('image') -> getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $file_nameone = $file_name;
                $path = 'assets_admin/img/fils';
                $request-> file('image') ->move($path,$file_name);
                $add->image  = $file_nameone;
             }else{
                $add->image  = $request->url; 
             }
        
            $add->save();
            return $this -> returnSuccessMessage('تم الاضافة بنجاح');
    }
    
    public function deleteOffer(Request $request){
        $cats = Offer::find($request->id);
        $cats->delete();
        if(isset($request -> lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('Deleted Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم الحذف بنجاح');
        }
    }
    public function UpdateOffer(Request $request)
    {
            $percent = (($request->old_price - $request->new_price) / $request->old_price) * 100;
            // dd($percent);
            $edit = Offer::findOrFail($request->id);
            if($file=$request->file('image'))
             {
                $file_extension = $request -> file('image') -> getClientOriginalExtension();
                $file_name = time().'.'.$file_extension;
                $file_nameone = $file_name;
                $path = 'assets_admin/img/offers';
                $request-> file('image') ->move($path,$file_name);
                $edit->image  = $file_nameone;
             }else{
                $edit->image  = $edit->image; 
             }
            $edit->doctorId    = $edit->doctorId;
            $edit->offer_name_ar  = $request->offer_name_ar;
            $edit->offer_name_en  = $request->offer_name_en;
            $edit->description_ar  = $request->description_ar;
            $edit->description_en  = $request->description_en;
            $edit->old_price  = $request->old_price;
            $edit->new_price  = $request->new_price;
            $edit->percent =  intval($percent);;
            $edit->type  = $request->type;
            $edit->save();
            if(isset($request -> lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('Updated Successfully ');
            }else{
                return $this -> returnSuccessMessage('تم التعديل بنجاح');
            }
    }
    
    public function updateServices(Request $request)
    {
            
            $edit = Service::findOrFail($request->id);
            $edit->doctorId  = $request->doctorId;
            $edit->price  = $request->price;
            $edit->status  = $request->status;
            $edit->update();
            if(isset($request -> lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('Updated Successfully ');
            }else{
                return $this -> returnSuccessMessage('تم التعديل بنجاح');
            }
            // dd($edit);
            // if($file=$request->file('icon'))
            // {
            //     $file_extension = $request -> file('icon') -> getClientOriginalExtension();
            //     $file_name = time().'.'.$file_extension;
            //     $file_nameone = $file_name;
            //     $path = 'assets_admin/img/services';
            //     $request-> file('icon') ->move($path,$file_name);
            //     $edit->icon  = $file_nameone;
            // }else{
            //     // $edit->icon  = $request->url; 
            // }
            // $edit->doctorId    = $edit->doctorId;
            // $edit->services_name_ar  = $request->services_name_ar;
            // $edit->services_name_en  = $request->services_name_en;
            
    }

    public function getServices(Request $request)
    {
        $services=Service::selection()->where('doctorId',$request->doctorId)->get();
        return $this -> returnData('services',$services);
    }
    public function doctorSpecialities()
    {
        $speciality=Speciality::selection()->get();
        return $this -> returnData(
            'speciality',$speciality
        );
    }
    public function addArticle(Request $request)
    {
        $file_extension = $request -> file('image') -> getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $file_nameone = $file_name;
        $path = 'assets_admin/img/article';
        $request-> file('image') ->move($path,$file_name);
        
        $ghgth = new Article;
        $ghgth->specialityId    = $request->specialityId;
        $ghgth->doctorId    = $request->doctorId;
        $ghgth->title_ar    = $request->title_ar;
        $ghgth->title_en  = $request->title_ar;
        $ghgth->description_ar  = $request->description_ar;
        $ghgth->description_en  = $request->description_ar;
        $ghgth->image    = $file_nameone;
        $ghgth->save();   
        if(isset($request -> lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('added Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم الاضافة بنجاح');
        }
    }
      public function articleDelete(Request $request){
        $cats = Article::find($request->id);
        $cats->delete();
        if(isset($request->lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('Deleted Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم الحذف بنجاح');
        }
    }
    public function updateArticle(Request $request)
    {
        $edit = Article::findOrFail($request->id);
        if($file=$request->file('image'))
        {
            $file_extension = $request -> file('image') -> getClientOriginalExtension();
            $file_name = time().'.'.$file_extension;
            $file_nameone = $file_name;
            $path = 'assets_admin/img/offers';
            $request-> file('image') ->move($path,$file_name);
            $edit->image  = $file_nameone;
        }else{
            $edit->image  = $edit->image; 
        }
        // dd($file_nameone);
       if($request->specialityId){
            $edit->specialityId  = $request->specialityId;  
        }else{
            $edit->specialityId  = $edit->specialityId; 
        } 
        if($request->doctorId){
            $edit->doctorId  = $request->doctorId;  
        }else{
            $edit->doctorId  = $edit->doctorId; 
        } 
        if($request->title_ar){
            $edit->title_ar  = $request->title_ar;  
        }else{
            $edit->title_ar  = $edit->title_ar; 
        } 
        if($request->title_en){
            $edit->title_en  = $request->title_ar;  
        }else{
            $edit->title_en  = $edit->title_en; 
        }
        if($request->description_ar){
            $edit->description_ar  = $request->description_ar;  
        }else{
            $edit->description_ar  = $edit->description_ar; 
        }
        if($request->description_ar){
            $edit->description_en  = $request->description_ar;  
        }else{
            $edit->description_en  = $edit->description_en; 
        }

        $edit->save();   
         if(isset($request -> lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('Updated Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم التعديل بنجاح');
        }
    }

    public function getDiagnosis(Request $request)
    {
        $diagnostics=Diagnostic::where('doctorId',$request->doctorId)->get();
        // foreach ($diagnostics as $item) {
        //     $item->patient= Patient::selection()->where('id',$item->patientId)->first();
        // }
        
         foreach ($diagnostics as $item) {
            $item->patient= Patient::selection()->where('id',$item->patientId)->first();
            $item->sum=Payment::where('doctorId',$request->doctorId)
                                ->where('patientId',$request->patientId)
                                ->sum('amount');
        }
        return $this -> returnData('diagnostics',$diagnostics);
    }
    public function addDiagnosis(Request $request)
    {
        // $todayDate = date("Y-m-d");
        // $time = date("H:i");
        if($request->id==null){
            $todayDate = date("Y-m-d");
            $time = new DateTime();
            $time->modify('+2 hours');
            $ghgth = new Diagnostic;
            $ghgth->doctorId      = $request->doctorId;
            $ghgth->patientId     = $request->patientId;
            $ghgth->appointmentId = $request->appointmentId;
            $ghgth->weight    = $request->weight;
            $ghgth->hight  = $request->hight;
            $ghgth->blood  = $request->blood;
            $ghgth->temp  = $request->temp;
            $ghgth->complaint  = $request->complaint;
            $ghgth->symptoms  = $request->symptoms;
            $ghgth->diagnosis  = $request->diagnosis;
            $ghgth->medicine  = $request->medicine;
            $ghgth->date  = $todayDate;
            $ghgth->time  = $time->format("H:i");
            $ghgth->save();
            
            $edit = Appointment::findOrFail($request->appointmentId);
            $edit->status  = "combledted";//Completed
            $edit->save();    
               
            if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('added Successfully ');
            }else{
                return $this -> returnSuccessMessage('تم الاضافة بنجاح');
            }
        }else{
            $todayDate = date("Y-m-d");
            $time = new DateTime();
            $time->modify('+2 hours');
            // $ghgth = new Diagnostic;
            $ghgth = Diagnostic::findOrFail($request->id);
            $ghgth->doctorId    = $request->doctorId;
            $ghgth->patientId    = $request->patientId;
            $ghgth->weight    = $request->weight;
            $ghgth->hight  = $request->hight;
            $ghgth->blood  = $request->blood;
            $ghgth->temp  = $request->temp;
            $ghgth->complaint  = $request->complaint;
            $ghgth->symptoms  = $request->symptoms;
            $ghgth->diagnosis  = $request->diagnosis;
            $ghgth->medicine  = $request->medicine;
            $ghgth->date  = $todayDate;
            $ghgth->time  = $time->format("H:i");
            $ghgth->save();
            // dd($ghgth);    
                
            if(isset($request->lang)  && $request -> lang == 'en' ){
                return $this -> returnSuccessMessage('Updated Successfully ');
            }else{
                return $this -> returnSuccessMessage('تم التعديل بنجاح');
            }
        }
        
    }
    public function appointmentStatus(Request $request)
    {
        $edit = Appointment::findOrFail($request->id);
        $edit->status  = $request->status;
        $edit->save();
        if(isset($request->lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('Updated Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم التعديل بنجاح');
        }
    }

     public function AddApointment(Request $request)
    {
        // dd($request->day);
        // $length = count($request->day);
        // return response()->json($length);
        // $this->validate( $request,[          
        //         'from_date'=>'required',
        //         'to_date'=>'required',
        //         'day'=>'required',
        //         'day_number'=>'required',
        //         'from_morning'=>'required',
        //         'to_morning'=>'required',
        //         'from_afternoon'=>'required',
        //         'to_afternoon'=>'required',
        //         'from_evening'=>'required',
        //         'to_evening'=>'required',
        //         'duration'=>'required',
        //     ],
        //     [
        //         'from_date.required'=>'تاريخ بداية الفتره مطلوب',
        //         'to_date.required'=>' تاريخ نهاية الفترة مطلوب ',
        //         'day.required'=>' ادخل ايام العمل',
        //         'from_morning.required'=>' ادخل موعد الفتره الصباحيه  ',
        //         'to_morning.required'=>'  ادخل موعد الفتره الصباحيه  ',
        //         'from_afternoon.required'=>'  ادخل موعد الفتره بعد الظهر ',
        //         'to_afternoon.required'=>'  ادخل موعد الفتره بعد الظهر  ',
        //         'from_evening.required'=>'  ادخل موعد الفتره المسائية ',
        //         'to_evening.required'=>'  ادخل موعد الفتره المسائية ',
                
        //         'duration.required'=>' ادخل مدة الكشف ',   
        //     ]
        // );

        // return response()->json($request->all());
        $from_date = $request->input("from_date");
        $to_date = $request->input("to_date"); 

        
           $workingdays=WorkingDays::where('doctorId',$request->doctorId)
                                   ->where('from_date',$request->from_date)
                                   ->where('to_date',$request->to_date)
                                   ->first();
        if($workingdays){            
            if(isset($request->lang)  && $request -> lang == 'en' ){
            return $this -> returnError('There is a period of work on this date');
            }else{
                return $this -> returnError('يوجد فترة عمل في هذا التاريخ');
            }
        }else{    
            $length = count($request->day);
            if($length > 0)
            {
                for($i=0; $i<$length; $i++)
                {
                    $person= new WorkingDays;
                    $person->doctorId  = $request->doctorId;
                    $person->from_date  = $request->from_date;
                    $person->to_date  = $request->to_date;
                    $person->day  = $request->day[$i];
                    $person->day_number  = $request->day_number[$i];
                    $person->from_morning  = $request->from_morning;
                    $person->to_morning  = $request->to_morning;
                    $person->from_afternoon  = $request->from_afternoon;
                    $person->to_afternoon  = $request->to_afternoon;
                    $person->from_evening  = $request->from_evening;
                    $person->to_evening  = $request->to_evening;
                    $person->duration  = $request->duration;
                    $person->save();
                }
                
                if(isset($request->lang)  && $request -> lang == 'en' ){
                     return $this -> returnSuccessMessage('added Successfully ');
                }else{
                    return $this -> returnSuccessMessage('تم الإضافة بنجاح');
                }
            }
        }    
        

    }
    

    public function getWorkDay(Request $request)
    {
        $workday= WorkingDays::where('doctorId',$request->doctorId)->get();
        return $this->returnData('workday', $workday);
    }
    
    
    public function removeWorkingDays(Request $request){
        $cats = WorkingDays::find($request->id);
        $cats->delete();
        if(isset($request->lang)  && $request -> lang == 'en' ){
            return $this -> returnSuccessMessage('Deleted Successfully ');
        }else{
            return $this -> returnSuccessMessage('تم الحذف بنجاح');
        }
    }
    public function updateApointment(Request $request)
    {
                $edit = WorkingDays::findOrFail($request->id);
                $edit->doctorId  = $request->doctorId;
                $edit->from_date  = $request->from_date;
                $edit->to_date  = $request->to_date;
                $edit->day  = $request->day;
                $edit->day_number  = $request->day_number;
                $edit->from_morning  = $request->from_morning;
                $edit->to_morning  = $request->to_morning;
                $edit->from_afternoon  = $request->from_afternoon;
                $edit->to_afternoon  = $request->to_afternoon;
                $edit->from_evening  = $request->from_evening;
                $edit->to_evening  = $request->to_evening;
                $edit->duration  = $request->duration;
                $edit->save();
                if(isset($request->lang)  && $request -> lang == 'en' ){
                    return $this -> returnSuccessMessage('Updated Successfully ');
                }else{
                    return $this -> returnSuccessMessage('تم التعديل بنجاح');
                }
    }   
    
    public function getPaymentById(Request $request)
    {

        $payment=Payment::where('doctorId',$request->doctorId)->get();
        $sum=Payment::where('doctorId',$request->doctorId)->sum('amount');
        
        // dd($sum);
        foreach ($payment as $item) {
            $item->doctor= Doctor::selection()->where('id',$item->doctorId)->first(); 
            $item->patient= Patient::selection()->where('id',$item->patientId)->first(); 
            $item->apointment= Appointment::where('id',$item->appointmentId)->first();   
        }
        $home  =[  
            'payment'=>$payment,
            
            'sum'=>$sum,
        ];
        return $this->returnData('payment', $home);
    }
    
    
   public function getPaymentByIdfilter(Request $request)
    {
        $payment=Payment::where('doctorId',$request->doctorId)
                          ->whereBetween('date', array($request->from_date, $request->to_date))->get();
        
        $sum=Payment::where('doctorId',$request->doctorId)
                     ->whereBetween('date', array($request->from_date, $request->to_date))->sum('amount');
        
        // dd($sum);
        foreach ($payment as $item) {
            $item->doctor= Doctor::selection()->where('id',$item->doctorId)->first(); 
            $item->patient= Patient::selection()->where('id',$item->patientId)->first(); 
            $item->apointment= Appointment::where('id',$item->appointmentId)->first();   
        }
        $home  =[  
            'payment'=>$payment,
            
            'sum'=>$sum,
        ];
        return $this->returnData('payment', $home);
    }

}
