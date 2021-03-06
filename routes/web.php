<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneAuthController;
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

Route::get('//', function () {
    return view('auth.login'); //home page -> index
});

Auth::routes();
//Auth::routes(['register' => false]);
Route::get('//home', 'HomeController@index')->name('home');
Route::middleware('auth')->group(function () {
    Route::resource('/invoices', 'InvoicesController');
    Route::resource('/archive', 'InvoiceAchiveController');

    Route::get('/invoices_paid', 'InvoicesController@invoices_paid');
    Route::get('/invoices_unpaid', 'InvoicesController@invoices_unpaid');
    Route::get('/invoices_partial', 'InvoicesController@invoices_partial');
    Route::get('/invoices_export', 'InvoicesController@export');
    Route::get('/invoice_print/{id}','InvoicesController@invoice_print');

    Route::get('/section/{id}', 'InvoicesController@getproducts');
    Route::get('/edit_invoice/{id}', 'InvoicesController@edit');
    Route::get('/show_status/{id}', 'InvoicesController@show')->name('show_status');

    Route::post('/update_status/{id}', 'InvoicesController@update_status')->name('update_status');

    Route::get('/invoices_details/{id}', 'InvoicesDetailsController@edit');
    Route::get('/view_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
    Route::get('/download_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');
    Route::post('/delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');
    //InvoiceAttachments
    Route::resource('/sections', 'SectionsController');
    Route::resource('/products', 'ProductsController');
    Route::resource('/InvoiceAttachments', 'InvoicesAttachmentsController');
    Route::get('/phone-auth', 'PhoneAuthController@index');

    Route::resource('/roles','RoleController');

    Route::resource('/users','UserController');
    //reports start
    Route::get('/invoices_report', 'InvoicesReportController@index');
    Route::post('/invoices_search', 'InvoicesReportController@invoices_search');
    Route::get('/customers_report', 'CustomersReporController@index')->name("customers_report");
    Route::post('/customers_search', 'CustomersReporController@customers_search');
    //notifications start
    Route::get('/MarkAsRead_all','InvoicesController@MarkAsRead_all')->name('MarkAsRead_all');
    Route::get('/unreadNotifications_count', 'InvoicesController@unreadNotifications_count')->name('unreadNotifications_count');
    Route::get('/unreadNotifications', 'InvoicesController@unreadNotifications')->name('unreadNotifications');
});
// Route::resource('/invoices', 'InvoicesController')->middleware('auth');
// Route::resource('/sections', 'SectionsController')->middleware('auth');
// Route::resource('/products', 'ProductsController')->middleware('auth');
Route::get('//{page}', 'AdminController@index');
