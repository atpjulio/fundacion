<?php

use App\Utilities;

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

Route::view('/', 'auth.login');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('company', 'CompanyController');

    Route::resource('eps', 'EpsController');
    Route::get('eps-services/{id}', 'EpsController@services')->name('eps.services.index');
    Route::delete('eps-services/{id}/destroy', 'EpsController@servicesDestroy')->name('eps.services.destroy');

    Route::resource('invoice', 'InvoiceController');
    Route::get('invoice/pdf/{id}', 'InvoiceController@pdf')->name('invoice.pdf');
    Route::get('invoice-relation', 'InvoiceController@relation')->name('invoice.relation');
    Route::get('invoice-relation-pdf', 'InvoiceController@relationPDF')->name('invoice.relation.pdf');
    Route::get('invoice-volume', 'InvoiceController@volume')->name('invoice.volume');
    Route::get('invoice-volume-pdf', 'InvoiceController@volumePDF')->name('invoice.volume.pdf');

    Route::get('accounting/eps', 'AccountingController@eps')->name('accounting.eps');
    Route::get('accounting/eps/{id}', 'AccountingController@epsDetail')->name('accounting.eps.detail');
    Route::get('accounting/accounts-receivable', 'AccountingController@accountsReceivable')->name('accounting.accounts.receivable');

    Route::resource('accounting-note', 'AccountingNoteController');
    Route::get('accounting-note-delete/{id}', 'AccountingNoteController@delete')->name('accounting.note.delete');

    Route::resource('egress', 'EgressController');
    Route::get('egress/pdf/{id}', 'EgressController@pdf')->name('egress.pdf');
    Route::get('egress-delete/{id}', 'EgressController@delete')->name('egress.delete');

    Route::resource('rip', 'RipController');
    Route::get('rip/download/{id}', 'RipController@download')->name('rip.download');

    Route::get('test', 'RipController@test');

    Route::resource('receipt', 'ReceiptController');
    Route::get('receipt/generals', 'ReceiptController@generals')->name('receipt.generals');
    Route::get('receipt/pdf/{id}', 'ReceiptController@pdf')->name('receipt.pdf');
    Route::view('receipt-import', 'accounting.receipt.import')->name('receipt.import');
    Route::post('receipt-import-process', 'ReceiptController@importProcess')->name('receipt.import.process');
});

Route::middleware(['auth', 'both'])->group(function () {
    Route::get('eps-services/{id}/create-from-authorization', 'EpsController@servicesCreateAuthorization')->name('eps.services.create.authorization');
    Route::get('eps-services/{id}/create', 'EpsController@servicesCreate')->name('eps.services.create');
    Route::post('eps-services/store', 'EpsController@servicesStore')->name('eps.services.store');
    Route::post('eps-services/new', 'EpsController@servicesNew')->name('eps.services.new');
    Route::get('eps-services/{id}/edit', 'EpsController@servicesEdit')->name('eps.services.edit');
    Route::patch('eps-services/{id}/update', 'EpsController@servicesUpdate')->name('eps.services.update');

    Route::resource('authorization', 'AuthorizationController');
    Route::post('authorization/confirm', 'AuthorizationController@confirm')->name('authorization.confirm');
    Route::get('authorization-incomplete', 'AuthorizationController@incomplete')->name('authorization.incomplete');
    Route::get('authorization-open', 'AuthorizationController@open')->name('authorization.open');
    Route::get('authorization-close', 'AuthorizationController@close')->name('authorization.close');
    Route::post('authorization-global', 'AuthorizationController@global')->name('authorization.global');
    Route::post('authorization/create-back', 'AuthorizationController@createBack')->name('authorization.create.back');

    Route::resource('patient', 'PatientController');
    Route::get('patient-create-from-authorization', 'PatientController@createAuthorization')->name('patient.create.authorization');
    Route::post('patient-global', 'PatientController@global')->name('patient.global');
    Route::get('patient-import', 'PatientController@import')->name('patient.import');
    Route::post('patient-import-process', 'PatientController@importProcess')->name('patient.import.process');
    Route::post('patient-import-process-txt', 'PatientController@importProcessTxt')->name('patient.import.process.txt');

    Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
    Route::put('user/profile/{id}', 'UserController@profileUpdate')->name('user.profile.update');

    Route::get('authorization/excel/{id}', 'AuthorizationController@excel')->name('authorization.excel');

    Route::get('/get-eps-patients-filtered/{search}', 'AjaxController@getEpsPatientsFiltered');
    Route::get('/get-global-authorizations/{search}', 'AjaxController@getGlobalAuthorizations');
    Route::get('/get-invoices-amount-number/{data}', 'AjaxController@getInvoicesAmountNumber');
    Route::get('/get-full-authorizations/{search}', 'AjaxController@getFullAuthorizations');
    Route::get('/get-companion-services/{id}', 'AjaxController@getCompanionServices');
    Route::get('/get-multiple-services/{id}', 'AjaxController@getMultipleServices');
    Route::get('/get-invoices-amount/{data}', 'AjaxController@getInvoicesAmount');
    Route::get('/get-daily-prices/{id}', 'AjaxController@getDailyPrices');
    Route::get('/get-eps-patients/{id}', 'AjaxController@getEpsPatients');
    Route::get('/get-invoices/{search}', 'AjaxController@getInvoices');
    Route::get('/get-patients/{search}', 'AjaxController@getPatients');
    Route::get('/get-day-range/{date}', 'AjaxController@getDayRange');
    Route::get('/get-services/{id}', 'AjaxController@getServices');
    Route::get('/get-cities/{id}', 'AjaxController@getCities');
    Route::get('/get-entity/{id}', 'AjaxController@getEntity');
    Route::get('/new-service/{id}', 'AjaxController@newService');
    Route::get('/check-patient/{dni}', 'AjaxController@checkPatient');
    Route::get('/check-authorization/{code}', 'AjaxController@checkAuthorization');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
