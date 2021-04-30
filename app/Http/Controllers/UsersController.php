<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $users=User::all();
        
        return view('admin.userss.all',compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }
    

    public function store(Request $request)
    {
        $file_extension = $request -> file('photo')-> getClientOriginalExtension();
        $file_name = time().'.'.$file_extension;
        $file_nameone = $file_name;
        $path = 'assets_admin/img/users';
        $request-> file('photo') ->move($path,$file_name);

        $add = new User();
        $add->name  = $request->name;  
        $add->email  = $request->email;   
        $add->password  = bcrypt($request->password);  
        $add->photo  = $file_nameone;  

        $add-> save();

        return redirect()->back()->with("message",'تمت الإضافة بنجاح'); 
    }

    
    public function edit(User $user)
    {
        return view('admin.users.edit',compact('user'));
    }

    // public function update(AircraftRequest $request, User $user)
    // {
    //     $userId = 1;
    //      if($file=$request->file('logoone'))
    //      {
    //         $file_extension = $request -> file('logoone') -> getClientOriginalExtension();
    //         $file_name = time().'.'.$file_extension;
    //         $file_nameone = $file_name;
    //         $path = 'admin/images/aircraft';
    //         $request-> file('logoone') ->move($path,$file_name);
    //         $request->merge(['logo'=>$file_nameone]);

    //          $request->merge(['updated_by'=>$userId]);
    //          $user->update($request->all());
    //      }else{
    //           $request->merge(['logo'=> $request->url]);
    //           $request->merge(['updated_by'=>$userId]);
    //           $user->update($request->all());
    //      }
       
    //     dd($request->all());
        //return redirect()->route('aircraft.index')->with("message", __('admin.updateSuccess')); 
    // }

    public function destroy(Request $request )
    {
        $delete = User::findOrFail($request->id);
        $delete->delete();
            return redirect()->route('users.index')->with("message",'تم الحذف بنجاح');
    } 

    public function updateStatus(Request $request)
    {
        $user = User::findOrFail($request->userId);
        $user->status = $request->status;
        $user->type = $request->type;
        $user->save();

        return redirect()->route('users.index')->with("message", 'تم التعديل بنجاح');
    }
}
