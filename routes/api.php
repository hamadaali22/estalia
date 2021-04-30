<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
//Chat 

Route::post('patient/loginadmin', 'Api\Patient\PatientAuthController@loginAdmin');
 


 
Route::post('patient/register', 'Api\Patient\PatientAuthController@register');

Route::post('patientchat', 'ChatsController@patientChat');
    Route::post('getmessages', 'ChatsController@patientFetchMessages');
    Route::post('sendmessage', 'ChatsController@patientSendMessage');
    Route::post('creatorgetmessages', 'ChatsController@creatOrGetMessages');

Route::group(['middleware' => ['api','changeLanguage'], 'namespace' => 'Api'], function () {
//   Route::group(['middleware' => ['api','changeLanguage','checkDoctor:patient-api'], 'namespace' => 'Api'], function () {
   
    // Route::group(['middleware' => ['auth:patient-api','changeLanguage'], 'namespace' => 'Api'], function () {

    
  
    Route::post('patientchat', 'Patient\ChatsController@patientChat');
    Route::post('getmessages', 'Patient\ChatsController@patientFetchMessages');
    Route::post('sendmessage', 'Patient\ChatsController@patientSendMessage');
    Route::post('creatorgetmessages', 'Patient\ChatsController@creatOrGetMessages');

    Route::post('doctorchat', 'Doctor\ChatsController@doctorChat');
    Route::post('doctorgetmessages', 'Doctor\ChatsController@doctorFetchMessages');
    Route::post('doctorsendmessage', 'Doctor\ChatsController@doctorSendMessage');
    Route::post('doctorcreatorgetmessages', 'Doctor\ChatsController@creatOrGetMessages');
   
    Route::group(['prefix' => 'patient','namespace'=>'Patient'],function (){
        
        Route::post('change_password', 'PatientAuthController@change_password');
        Route::post('forgetpassword', 'PatientAuthController@forgetPassword');
        Route::post('home', 'HomeController@index');
        Route::post('sliders', 'HomeController@sliders');
        Route::post('addrate', 'HomeController@addRate');
        
        Route::post('doctors_speciality_byid', 'HomeController@getDoctorsSpecialityById');
        Route::post('login', 'PatientAuthController@login');
        // Route::post('register', 'PatientAuthController@register');
        Route::post('patientdata', 'PatientAuthController@getPatientData');
        Route::post('getpatient', 'PatientAuthController@getPatient');
        Route::post('gettime', 'HomeController@getTime');
        Route::post('addbooking', 'HomeController@addBooking');
        Route::post('addpayment', 'HomeController@addPayment');
        Route::post('paymentstatus', 'HomeController@paymentStatus');
        Route::post('getpayment', 'HomeController@getPaymentById');
        Route::post('searchondoctor', 'HomeController@searchOnDoctor');
        Route::post('getdiagnosis', 'HomeController@getDiagnosis');
        Route::post('appointmentbyid', 'HomeController@getAppointmentById');
        Route::post('patientupdate', 'HomeController@patientUpdate');
        Route::post('contactinfo', 'HomeController@contactInfo');
        Route::post('reviewsdoctorid', 'HomeController@getReviewsOfDoctorId');
        Route::post('getfavoritebyid', 'HomeController@getfavoriteById');
        Route::post('addfavorite', 'HomeController@addToFavorite');
        Route::post('removefavorite', 'HomeController@removeFavorite');
        Route::post('countries', 'HomeController@Countries'); 
        Route::post('cities', 'HomeController@Cities'); 
        Route::post('appointmentstatus', 'HomeController@appointmentStatus');




        // Route::post('specialities', 'SpecialityController@index');
        // Route::post('sliders', 'SliderController@index');
        // Route::post('doctors', 'DoctorController@index');
        // Route::post('articles', 'ArticleController@index');
        // Route::post('pannars', 'BannerController@index');
    });
    Route::group(['prefix' => 'doctor','namespace'=>'Doctor'],function (){
        Route::post('change_password', 'DoctorAuthController@change_password');
        Route::post('addServices', 'DoctorAuthController@addNewServices');
        Route::post('doctorofferbyid', 'HomeController@getDoctorOffer');

        Route::post('appointmentstatus', 'HomeController@appointmentStatus');
        Route::post('forgetpassword', 'DoctorAuthController@forgetPassword');
        Route::post('getpayment', 'HomeController@getPaymentById');
        Route::post('deleteoffer', 'HomeController@deleteOffer');
        Route::post('sendfile', 'HomeController@sendfile');


        Route::post('login', 'DoctorAuthController@login');
        Route::post('register', 'DoctorAuthController@register');
        Route::post('doctordata', 'DoctorAuthController@getDoctorData');
        Route::post('doctorupdate', 'DoctorAuthController@doctorUpdate');
        
        Route::post('forgot-password', 'DoctorAuthController@forgot_password');
        Route::post('change-password', 'DoctorAuthController@change_password');
        
        Route::post('home', 'HomeController@index');
        Route::post('doctor/specialities', 'HomeController@doctorSpecialities');
        
        Route::post('doctorappointment', 'HomeController@getAppointmentById');
        Route::post('addnewoffer', 'HomeController@addNewOffer');
        Route::post('updateoffer', 'HomeController@UpdateOffer');
        // Route::post('addnewservices', 'HomeController@addNewServices');
        Route::post('updateservices', 'HomeController@updateServices');
        Route::post('getservices', 'HomeController@getServices');
        Route::post('addarticle', 'HomeController@addArticle');
        Route::post('articledelete', 'HomeController@articleDelete');
        
        Route::post('updatearticle', 'HomeController@updateArticle');
        Route::post('getdiagnosis', 'HomeController@getDiagnosis');
        Route::post('adddiagnosis', 'HomeController@addDiagnosis');
        Route::post('addapointment', 'HomeController@addApointment');
        Route::post('workday', 'HomeController@getWorkDay');
        Route::post('removeworkingways', 'HomeController@removeWorkingDays');
         Route::post('updateapointment', 'HomeController@updateApointment');

    });

});







// Route::group(['middleware' => 'api', 'namespace' => 'Api'], function () {
//  Route::post('get-main-categoriess', 'CategoriesController@index');
//  });


Route::group(['middleware' => ['api','checkPassword','changeLanguage'], 'namespace' => 'Api'], function () {
    Route::post('get-main-categories', 'CategoriesController@index');
    Route::post('get-category-byId', 'CategoriesController@getCategoryById');
    Route::post('change-category-status', 'CategoriesController@changeStatus');

    Route::group(['prefix' => 'admin','namespace'=>'Admin'],function (){
        Route::post('login', 'AuthController@login');
    });

});



Route::group(['middleware' => ['api','checkPassword','changeLanguage','CheckPatient:patient-api'], 'namespace' => 'Api'], function () {
    Route::get('offers', 'CategoriesController@index');
});