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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('company', 'CompanyController');

    Route::resource('eps', 'EpsController');
    Route::get('eps-services/{id}', 'EpsController@services')->name('eps.services.index');
    Route::get('eps-services/{id}/create', 'EpsController@servicesCreate')->name('eps.services.create');
    Route::get('eps-services/{id}/create-from-authorization', 'EpsController@servicesCreateAuthorization')->name('eps.services.create.authorization');
    Route::post('eps-services/store', 'EpsController@servicesStore')->name('eps.services.store');
    Route::get('eps-services/{id}/edit', 'EpsController@servicesEdit')->name('eps.services.edit');
    Route::patch('eps-services/{id}/update', 'EpsController@servicesUpdate')->name('eps.services.update');
    Route::delete('eps-services/{id}/destroy', 'EpsController@servicesDestroy')->name('eps.services.destroy');

    Route::resource('invoice', 'InvoiceController');

    Route::get('accounting/eps', 'AccountingController@eps')->name('accounting.eps');
    Route::get('accounting/accounts-receivable', 'AccountingController@accountsReceivable')->name('accounting.accounts.receivable');
    Route::get('accounting/rips', 'AccountingController@rips')->name('accounting.rips');

    Route::resource('accounting-note', 'AccountingNoteController');
});

Route::middleware(['auth', 'both'])->group(function () {
    Route::resource('authorization', 'AuthorizationController');
    Route::post('authorization/confirm', 'AuthorizationController@confirm')->name('authorization.confirm');
    Route::post('authorization/create-back', 'AuthorizationController@createBack')->name('authorization.create.back');

    Route::resource('patient', 'PatientController');
    Route::get('patient-create-from-authorization', 'PatientController@createAuthorization')->name('patient.create.authorization');
    Route::get('patient-import', 'PatientController@import')->name('patient.import');
    Route::post('patient-import-process', 'PatientController@importProcess')->name('patient.import.process');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/get-eps-patients/{id}', 'AjaxController@getEpsPatients');
Route::get('/get-day-range/{date}', 'AjaxController@getDayRange');
Route::get('/get-services/{id}', 'AjaxController@getServices');
Route::get('/get-cities/{id}', 'AjaxController@getCities');
