<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apps\UserManagement;
use App\Http\Controllers\apps\CompanyManagement;
use App\Http\Controllers\apps\CampaignManagement;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SiteSettingsController;

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

$controller_path = 'App\Http\Controllers';

// Main Page Route
Route::get('/', $controller_path . '\pages\HomePage@index')->name('pages-home');
Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');


Route::get('/setcurrentcampaign', $controller_path . '\SiteSettingsController@setcampaign')->name('setcurrentcampaign-page');
Route::post('/subcamp', $controller_path . '\SiteSettingsController@subcamp')->name('subcamp');
Route::post('/storecampaign', $controller_path . '\SiteSettingsController@storecampaign')->name('storecampaign');



Route::get('/capturenew', $controller_path . '\pages\Capture@capturenew')->name('capture-page-new');
Route::get('/captureexisting', $controller_path . '\pages\Capture@captureexisting')->name('capture-page-existing');

// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::controller(LoginBasic::class)->group(function() {
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/logout', 'logout')->name('logout');
});

Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::post('auth/register-store', $controller_path . '\authentications\RegisterBasic@store')->name('auth-register-store');

Route::get('/app/user-management', [UserManagement::class, 'UserManagement'])->name('app-user')->middleware('auth');
Route::resource('/user-list', UserManagement::class)->middleware('auth');

Route::get('/all-companies', $controller_path . '\CompanyController@AllCompanies')->name('all-companies')->middleware('auth');


Route::get('/app/company-management', [CompanyManagement::class, 'CompanyManagement'])->name('app-company')->middleware('auth');
Route::resource('/company-list', CompanyManagement::class)->middleware('auth');


Route::get('/app/campaign-management', [CampaignManagement::class, 'CampaignManagement'])->name('app-campaign')->middleware('auth');
Route::resource('/campaign-list', CampaignManagement::class)->middleware('auth');


// apps
/*
Route::get('/app/email', $controller_path . '\apps\Email@index')->name('app-email');
Route::get('/app/chat', $controller_path . '\apps\Chat@index')->name('app-chat');
Route::get('/app/calendar', $controller_path . '\apps\Calendar@index')->name('app-calendar');
Route::get('/app/kanban', $controller_path . '\apps\Kanban@index')->name('app-kanban');
Route::get('/app/invoice/list', $controller_path . '\apps\InvoiceList@index')->name('app-invoice-list');
Route::get('/app/invoice/preview', $controller_path . '\apps\InvoicePreview@index')->name('app-invoice-preview');
Route::get('/app/invoice/print', $controller_path . '\apps\InvoicePrint@index')->name('app-invoice-print');
Route::get('/app/invoice/edit', $controller_path . '\apps\InvoiceEdit@index')->name('app-invoice-edit');
Route::get('/app/invoice/add', $controller_path . '\apps\InvoiceAdd@index')->name('app-invoice-add');
Route::get('/app/user/list', $controller_path . '\apps\UserList@index')->name('app-user-list');
Route::get('/app/user/view/account', $controller_path . '\apps\UserViewAccount@index')->name('app-user-view-account');
Route::get('/app/user/view/security', $controller_path . '\apps\UserViewSecurity@index')->name('app-user-view-security');
Route::get('/app/user/view/billing', $controller_path . '\apps\UserViewBilling@index')->name('app-user-view-billing');
Route::get('/app/user/view/notifications', $controller_path . '\apps\UserViewNotifications@index')->name('app-user-view-notifications');
Route::get('/app/user/view/connections', $controller_path . '\apps\UserViewConnections@index')->name('app-user-view-connections');
Route::get('/app/access-roles', $controller_path . '\apps\AccessRoles@index')->name('app-access-roles');
Route::get('/app/access-permission', $controller_path . '\apps\AccessPermission@index')->name('app-access-permission');
*/