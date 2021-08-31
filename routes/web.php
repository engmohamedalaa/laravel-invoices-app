<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login'); //home page -> index
});

Auth::routes();
//Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');
Route::middleware('auth')->group(function () {
    Route::resource('invoices', 'InvoicesController');
    Route::get('section/{id}', 'InvoicesController@getproducts');
    Route::get('edit_invoice/{id}', 'InvoicesController@edit');

    Route::get('invoices_details/{id}', 'InvoicesDetailsController@edit');
    Route::get('view_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
    Route::get('download_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');
    Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');
    //InvoiceAttachments
    Route::resource('sections', 'SectionsController');
    Route::resource('products', 'ProductsController');
    Route::resource('InvoiceAttachments', 'InvoicesAttachmentsController');
});
// Route::resource('invoices', 'InvoicesController')->middleware('auth');
// Route::resource('sections', 'SectionsController')->middleware('auth');
// Route::resource('products', 'ProductsController')->middleware('auth');
Route::get('/{page}', 'AdminController@index');
