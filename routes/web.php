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
    Route::get('eps-services/{id}/edit', 'EpsController@servicesEdit')->name('eps.services.edit');
    Route::patch('eps-services/{id}/update', 'EpsController@servicesUpdate')->name('eps.services.update');

    Route::resource('authorization', 'AuthorizationController');
    Route::post('authorization/confirm', 'AuthorizationController@confirm')->name('authorization.confirm');
    Route::get('authorization-incomplete', 'AuthorizationController@incomplete')->name('authorization.incomplete');
    Route::get('authorization-open', 'AuthorizationController@open')->name('authorization.open');
    Route::get('authorization-close', 'AuthorizationController@close')->name('authorization.close');
    Route::post('authorization/create-back', 'AuthorizationController@createBack')->name('authorization.create.back');

    Route::resource('patient', 'PatientController');
    Route::get('patient-create-from-authorization', 'PatientController@createAuthorization')->name('patient.create.authorization');
    Route::get('patient-import', 'PatientController@import')->name('patient.import');
    Route::post('patient-import-process', 'PatientController@importProcess')->name('patient.import.process');

    Route::get('user/profile/{id}', 'UserController@profile')->name('user.profile');
    Route::put('user/profile/{id}', 'UserController@profileUpdate')->name('user.profile.update');

    Route::get('authorization/excel/{id}', 'AuthorizationController@excel')->name('authorization.excel');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/get-companion-services/{id}', 'AjaxController@getCompanionServices');
Route::get('/get-invoices-amount/{data}', 'AjaxController@getInvoicesAmount');
Route::get('/get-eps-patients/{id}', 'AjaxController@getEpsPatients');
Route::get('/get-day-range/{date}', 'AjaxController@getDayRange');
Route::get('/get-services/{id}', 'AjaxController@getServices');
Route::get('/get-cities/{id}', 'AjaxController@getCities');
Route::get('/get-entity/{id}', 'AjaxController@getEntity');
