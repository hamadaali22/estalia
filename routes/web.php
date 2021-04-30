<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*****************ADMIN ROUTES*******************/
      
       
use App\Events\MessageSent;
use App\User;
use App\Post;
use App\Patient;
use App\Notification;

Route::get('/user/activation/{token}', 'UserController@userActivation');
Route::get('/doctor/activation/{token}', 'UserController@doctorActivation');
Route::get('/patient/activation/{token}', 'UserController@patientActivation');
Route::get('/activated', function () {
    return view('emails.activated');
});
// Route::get('/welcom', function () {
//     return view('admin.welc');
// });

// Route::get('/testt', function () {
//     event(new App\Events\MyEvent('Welcomeeeeeeee '));
//     return 'event has been sent';
// });
Route::get('/postnot', function () {
    // $user = Post::find(1);
    // $user->notify(new \App\Notifications\PostNewNotification());
    $user = Post::get();
    Notification::send($user, new \App\Notifications\PostNewNotification());
});

Route::get('/usernot', function () {
    
    $user = User::find(1);
    Notification::send($user, new \App\Notifications\UserNewNotification($user));

});


Route::get('/getusernot', function () {
    $user = Patient::find(15);
    $notf=[];
    foreach ($user->notifications as $not) {
        // $not->notifications;
        // foreach ($not->notifications as $nott) {
        //     $not->notf=$not->data;
        // }
        $notf=$not->notifications;

    }
    // return Response::json($notf);

    // $user = Notification::get();

    // foreach ($user as $not) {
    //     $not->notf=$not->data;
    // }
    // return Response::json($user);


    // foreach ($user->readnotifications as $not) {
    //     var_dump($not->id);
    // }

    // foreach ($user->unreadnotifications as $not) {
    //     var_dump($not->id);
    // }
     // $notifications= Patient::with(array('unreadnotifications'=>function($query){
     //                            $query->markAsRead();
     //                        }))->get();
    foreach ($user->unreadnotifications as $not) {
        $not->markAsRead();
    }
    return redirect()->back(); 


    // $user->notifications()->delete();
    
});



// Route::resource('category','CategoryController');

 // Route::resource('specialities','SpecialityController');
// Route::get('admin/specialities', 'SpecialityController@index');
// Route::post('admin/specialities/store', 'SpecialityController@store');  // create Category
// Route::get('admin/specialities/{id}/delete', 'SpecialityController@destroy');  // create Category
// // Route::get('admin/specialities/{id}/edit', 'ServicesController@edit'); // edit page view
// Route::post('admin/specialities/update','SpecialityController@update');



// Route::get('/chats/{doctorId}', 'ChatsController@index');
// Route::get('/chats/{doctorId}/{chatID}', 'ChatsController@GetMessages');


Route::get('/chats', 'ChatsController@index');
Route::get('/messages', 'ChatsController@fetchAllMessages');
Route::post('/messages', 'ChatsController@sendMessage');


Route::get('send-mail', function () {
   
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
   
    \Mail::to('hamadaali221133@gmail.com')->send(new \App\Mail\MyTestMail($details));
   
    dd("Email is Sent.");
});


Route::get('dropdownlistt/{id}','DropdownController@index');
Route::get('get-state-list','DropdownController@getStateList');
Route::get('get-city-list','DropdownController@getCityList');

 
Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
    Route::resource('products','ProductController');
});


Route::get('/welll', function () {
    //broadcast(new MessageSent('some data'),3);

    return view('welll');
});
// Route::get('/welll', function ($data) {
//     broadcast(new WebsocketDemoEvent($data));

//     return view('welll');
// });

Route::get('causes_cat/{id}', 'CauseController@index');
    Route::get('getsub/{id}', 'CauseController@GetSub');




      
        Route::resource('/','DashBoardController');
        Route::resource('appointments','AppointmentController');
        Route::resource('doctors','DoctorController');
        Route::resource('reviews','ReviewsController');
        
        Route::resource('articles','ArticleController');
        Route::resource('pannars','BannerController');

        Route::resource('specialities','SpecialityController');
        Route::resource('countries','CountryController');
        Route::resource('cities','CityController');

        Route::resource('specialities','SpecialityController');

        Route::resource('userss','UsersController');
        // Route::resource('reviews','CommentController');
        Route::resource('contactinfo','ContactInfoController');
        Route::resource('days','DayController');
        
        Route::resource('offers','OfferController');
        Route::resource('patients','PatientController');
        Route::resource('sections','SectionController');
        Route::resource('sliders','SliderController');

        
###### start doctor ########
        //  get profile and change status
        Route::get('doctor-profile/{doctorId}', 'DoctorController@profile');
        Route::post('doctors/changepassword', 'DoctorController@changePassword')->name('doctors.changepassword');

        Route::get('doctors/update/status', 'DoctorController@updateStatus')->name('doctors.update.status');

        //  add apointment 
        Route::post('doctors/addapointment', 'DoctorController@AddApointment')->name('doctors/addapointment');
        Route::post('doctors/update/apointment', 'DoctorController@updateApointment')->name('doctors.update.apointment');
        Route::post('doctors/delete/apointment', 'DoctorController@deleteApointment')->name('doctors.delete.apointment');

        // add services
        Route::post('doctors/addservice', 'DoctorController@addService')->name('doctors/addservice');
        Route::get('service/{id}/edit', 'DoctorController@editServic')->name('service.{id}.edit');
        Route::post('doctors/update/service', 'DoctorController@updateService')->name('doctors.update.service');
        Route::post('doctors/delete/service', 'DoctorController@deleteService')->name('doctors.delete.service');

        Route::post('doctors/addoffers', 'DoctorController@addOffer')->name('doctors/addoffers');
        Route::get('offer/{id}/edit', 'DoctorController@editOffer')->name('offer.{id}.edit');
        Route::post('doctors/update/offers', 'DoctorController@updateOffer')->name('doctors.update.offers');
        Route::post('doctors/delete/offers', 'DoctorController@deleteOffer')->name('doctors.delete.offers');

        Route::post('doctors/delete/reviews', 'DoctorController@deleteReviews')->name('doctors.delete.reviews');
        Route::post('doctors/delete/payment', 'DoctorController@deletePayment')->name('doctors.delete.payment');

        ####### doctors-report ######
        Route::get('doctors-appointments', 'ReportController@doctorsApointments');
        Route::post('doctorsearch', 'ReportController@doctorSearch')->name('doctorsearch');
        Route::get('doctorpdf/{doctorId}','ReportController@doctorPdf');

        Route::get('documents/{id}', 'DoctorController@getDocument');

#### end doctor ######

###### start  patient #########
        Route::get('patient-profile/{patientId}/', 'PatientController@profile');
        Route::get('patients/update/status', 'PatientController@updateStatus')->name('patients.update.status');
        Route::get('getdoctor/{id}', 'PatientController@getDoctor');
        Route::get('getoffer/{id}', 'PatientController@getOffer');
        Route::get('getservice/{id}', 'PatientController@getService');

        
        Route::get('gettime/{date}/{doctorid}', 'PatientController@getTime');
        Route::post('patients/addbooking', 'PatientController@addBooking')->name('patients.addbooking');
        Route::post('patients/changepassword', 'PatientController@changePassword')->name('patients.changepassword');
        
        
         ####### patients-report ########
        Route::get('patients-appointments', 'ReportController@patientsppointments');
        Route::post('/patientsearch', 'ReportController@patientsSearch')->name('patientsearch');
        Route::get('patientpdf/{doctorId}','ReportController@patientPdf');
        
#### end patient #####

        Route::post('appointments/update/status', 'AppointmentController@updateStatus')->name('appointments.update.status');
        Route::get('reports', 'ReportController@allreport');
       
       
       
        ###################### user-status ##############################
        Route::post('users/status/{id}', 'UsersController@updateStatus')->name('users/status/{id}');
        Route::get('settings', 'ProfileController@settings');
        Route::post('settings/update','ProfileController@updateSettings');

        Route::get('settings', 'ProfileController@settings');
        Route::get('about', 'ProfileController@about');
        Route::get('contact', 'ProfileController@contact');
        Route::get('privacy', 'ProfileController@privacy');


        ###################### admin-profile ##############################
        Route::get('admin-login', 'Auth\LoginController@LoginUser')->name('admin-login');
        Route::get('profile', 'ProfileController@index');
        Route::post('profile/update','ProfileController@updateProfile');
        Route::post('user/changepassword', 'ProfileController@changePassword')->name('user.changepassword');

                

// use App\ContactInfo;
//        View::composer(['*'], function($view){
//              $cont = ContactInfo::first();
//             $view->with('contact',$cont);
//             // dd($cont);
//         });

        // Route::get('/index_admin', function () {
        // return view('admin.index_admin');
        // })->name('pagee');

        Route::get('/appointment-list', function () {
        return view('admin.appointment-list');
        })->name('appointment-list');
        // Route::get('/specialities', function () {
        // return view('admin.specialities');
        // })->name('specialities');
        Route::get('/doctor-list', function () {
        return view('admin.doctor-list');
        })->name('doctor-list');
        Route::get('/patient-list', function () {
        return view('admin.patient-list');
        })->name('patient-list');
        // Route::get('/reviews', function () {
        // return view('admin.reviews');
        // })->name('reviews');
        Route::get('/transactions-list', function () {
        return view('admin.transactions-list');
        })->name('transactions-list');
        // Route::get('/settings', function () {
        // return view('admin.settings');
        // })->name('settings');
        Route::get('/invoice-report', function () {
        return view('admin.invoice-report');
        })->name('invoice-report');
        // Route::get('/profile', function () {
        // return view('admin.profile');
        // })->name('profile');
        // Route::get('/admin-login', function () {
        // return view('admin.login');
        // })->name('login');
        Route::get('/register', function () {
        return view('admin.register');
        })->name('register');
        Route::get('/forgot-password', function () {
        return view('admin.forgot-password');
        })->name('forgot-password');
        Route::get('/lock-screen', function () {
        return view('admin.lock-screen');
        })->name('lock-screen');
        Route::get('/error-404', function () {
        return view('admin.error-404');
        })->name('error-404');
        Route::get('/error-500', function () {
        return view('admin.error-500');
        })->name('error-500');
        Route::get('/blank-page', function () {
        return view('admin.blank-page');
        })->name('blank-page');

        Route::get('/components', function () {
        return view('admin.components');
        })->name('components');
        Route::get('/form-basic-inputs', function () {
        return view('admin.form-basic-inputs');
        })->name('form-basic');
        Route::get('/form-input-groups', function () {
        return view('admin.form-input-groups');
        })->name('form-inputs');
        Route::get('/form-horizontal', function () {
        return view('admin.form-horizontal');
        })->name('form-horizontal');
        Route::get('/form-vertical', function () {
        return view('admin.form-vertical');
        })->name('form-vertical');
        Route::get('/form-mask', function () {
        return view('admin.form-mask');
        })->name('form-mask');
        Route::get('/form-validation', function () {
        return view('admin.form-validation');
        })->name('form-validation');

        Route::get('/tables-basic', function () {
        return view('admin.tables-basic');
        })->name('tables-basic');

        Route::get('/data-tables', function () {
        return view('admin.data-tables');
        })->name('data-tables');
        Route::get('/invoice', function () {
        return view('admin.invoice');
        })->name('invoice');
        Route::get('/calendar', function () {
        return view('admin.calendar');
        })->name('calendar');

       
// });


/********************ADMIN ROUTES END******************************/




// Route::get('/', function () {
//         return view('index');
//     })->name('page');
    
    Route::get('/home', function () {
        return view('index');
    })->name('page');

Route::get('/index', function () {
    return view('index');
})->name('page');

Route::get('/doctor-dashboard', function () {
    return view('doctor-dashboard');
});
// Route::get('/appointments', function () {
//     return view('appointments');
// });
Route::get('/schedule-timings', function () {
    return view('schedule-timings');
});
Route::get('/my-patients', function () {
    return view('my-patients');
});
Route::get('/patient-profile', function () {
    return view('patient-profile');
});
Route::get('/chat-doctor', function () {
    return view('chat-doctor');
})->name('chat-doctor');
Route::get('/invoices', function () {
    return view('invoices');
});
Route::get('/doctor-profile-settings', function () {
    return view('doctor-profile-settings');
});
// Route::get('/reviews', function () {
//     return view('reviews');
// });
Route::get('/doctor-register', function () {
    return view('doctor-register');
})->name('doctor-register');
Route::get('/map-grid', function () {
    return view('map-grid');
})->name('map-grid');
Route::get('/map-list', function () {
    return view('map-list');
})->name('map-list');
Route::get('/search', function () {
    return view('search');
})->name('search');
Route::get('/doctor-profile', function () {
    return view('doctor-profile');
})->name('doctor-profile');
Route::get('/booking', function () {
    return view('booking');
})->name('booking');
Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');
Route::get('/booking-success', function () {
    return view('booking-success');
})->name('booking-success');
Route::get('/patient-dashboard', function () {
    return view('patient-dashboard');
})->name('patient-dashboard');
Route::get('/favourites', function () {
    return view('favourites');
})->name('favourites');
Route::get('/change-password', function () {
    return view('change-password');
})->name('change-password');
Route::get('/profile-settings', function () {
    return view('profile-settings');
})->name('profile-settings');
Route::get('/chat', function () {
    return view('chat');
})->name('chat');
Route::get('/voice-call', function () {
    return view('voice-call');
})->name('voice-call');
Route::get('/video-call', function () {
    return view('video-call');
})->name('video-call');
Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');
// Route::get('/components', function () {
//     return view('components');
// })->name('components');
Route::get('/invoice-view', function () {
    return view('invoice-view');
})->name('invoice-view');
Route::get('/blank-page', function () {
    return view('blank-page');
})->name('blank-page');
Route::get('/loginn', function () {
    return view('login');
})->name('loginn');
Route::get('/register', function () {
    return view('register');
})->name('register');
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('forgot-password');
Route::get('/blog-list', function () {
    return view('blog-list');
})->name('blog-list');
Route::get('/blog-grid', function () {
    return view('blog-grid');
})->name('blog-grid');
Route::get('/blog-details', function () {
    return view('blog-details');
})->name('blog-details');
Route::get('/add-billing', function () {
    return view('add-billing');
});
Route::get('/add-prescription', function () {
    return view('add-prescription');
});
Route::get('/edit-billing', function () {
    return view('edit-billing');
});
Route::get('/edit-prescription', function () {
    return view('edit-prescription');
});
Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');
Route::get('/social-media', function () {
    return view('social-media');
})->name('social-media');
Route::get('/term-condition', function () {
    return view('term-condition');
})->name('term-condition');
Route::get('/doctor-change-password', function () {
    return view('doctor-change-password');
});





Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');








// Route::Group(['prefix' => 'admin'], function () { 
//         Route::resource('index_admin','DashBoardController');
//         Route::resource('appointments','AppointmentController');
//         Route::resource('doctors','DoctorController');
//         Route::resource('articles','ArticleController');
//         Route::resource('pannars','BannerController');
//         Route::resource('specialities','SpecialityController');
//         Route::resource('users','UsersController');
//         Route::resource('reviews','CommentController');
//         Route::resource('contactinfo','ContactInfoController');
//         Route::resource('days','DayController');
        
//         Route::resource('offers','OfferController');
//         Route::resource('patients','PatientController');
//         Route::resource('sections','SectionController');
//         Route::resource('sliders','SliderController');



//         Route::get('/index_admin', function () {
//         return view('admin.index_admin');
//         })->name('pagee');

//         Route::get('/appointment-list', function () {
//         return view('admin.appointment-list');
//         })->name('appointment-list');
//         // Route::get('/specialities', function () {
//         // return view('admin.specialities');
//         // })->name('specialities');
//         Route::get('/doctor-list', function () {
//         return view('admin.doctor-list');
//         })->name('doctor-list');
//         Route::get('/patient-list', function () {
//         return view('admin.patient-list');
//         })->name('patient-list');
//         // Route::get('/reviews', function () {
//         // return view('admin.reviews');
//         // })->name('reviews');
//         Route::get('/transactions-list', function () {
//         return view('admin.transactions-list');
//         })->name('transactions-list');
//         Route::get('/settings', function () {
//         return view('admin.settings');
//         })->name('settings');
//         Route::get('/invoice-report', function () {
//         return view('admin.invoice-report');
//         })->name('invoice-report');
//         Route::get('/profile', function () {
//         return view('admin.profile');
//         })->name('profile');
//         Route::get('/login', function () {
//         return view('admin.login');
//         })->name('login');
//         Route::get('/register', function () {
//         return view('admin.register');
//         })->name('register');
//         Route::get('/forgot-password', function () {
//         return view('admin.forgot-password');
//         })->name('forgot-password');
//         Route::get('/lock-screen', function () {
//         return view('admin.lock-screen');
//         })->name('lock-screen');
//         Route::get('/error-404', function () {
//         return view('admin.error-404');
//         })->name('error-404');
//         Route::get('/error-500', function () {
//         return view('admin.error-500');
//         })->name('error-500');
//         Route::get('/blank-page', function () {
//         return view('admin.blank-page');
//         })->name('blank-page');

//         Route::get('/components', function () {
//         return view('admin.components');
//         })->name('components');
//         Route::get('/form-basic-inputs', function () {
//         return view('admin.form-basic-inputs');
//         })->name('form-basic');
//         Route::get('/form-input-groups', function () {
//         return view('admin.form-input-groups');
//         })->name('form-inputs');
//         Route::get('/form-horizontal', function () {
//         return view('admin.form-horizontal');
//         })->name('form-horizontal');
//         Route::get('/form-vertical', function () {
//         return view('admin.form-vertical');
//         })->name('form-vertical');
//         Route::get('/form-mask', function () {
//         return view('admin.form-mask');
//         })->name('form-mask');
//         Route::get('/form-validation', function () {
//         return view('admin.form-validation');
//         })->name('form-validation');

//         Route::get('/tables-basic', function () {
//         return view('admin.tables-basic');
//         })->name('tables-basic');

//         Route::get('/data-tables', function () {
//         return view('admin.data-tables');
//         })->name('data-tables');
//         Route::get('/invoice', function () {
//         return view('invoice');
//         })->name('invoice');
//         Route::get('/calendar', function () {
//         return view('admin.calendar');
//         })->name('calendar');
//    });






Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
