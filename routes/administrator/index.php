<?php

use App\Events\ChatPusherEvent;
use App\Http\Requests\PusherChatRequest;
use App\Models\Chat;
use App\Models\ChatImage;
use App\Models\Notification;
use App\Models\ParticipantChat;
use App\Models\RestfulAPI;
use App\Models\User;
use App\Traits\StorageImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/admin', 'App\Http\Controllers\Admin\AdminController@loginAdmin')->name('login');
Route::post('/admin', 'App\Http\Controllers\Admin\AdminController@postLoginAdmin');

Route::get('/admin/logout', [
    'as' => 'administrator.logout',
    'uses' => '\App\Http\Controllers\Admin\AdminController@logout'
]);

Route::prefix('administrator')->group(function () {

    Route::prefix('password')->group(function () {
        Route::get('/', [
            'as' => 'administrator.password.index',
            'uses' => 'App\Http\Controllers\Admin\Controller@password',
        ]);
        Route::put('/', [
            'as' => 'administrator.password.update',
            'uses' => 'App\Http\Controllers\Admin\Controller@updatePassword',
        ]);

    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [
            'as' => 'administrator.dashboard.index',
            'uses' => 'App\Http\Controllers\Admin\DashboardController@index',
            'middleware' => 'can:dashboard-list',
        ]);

    });

    Route::prefix('history-datas')->group(function () {
        Route::get('/', [
            'as' => 'administrator.history_data.index',
            'uses' => 'App\Http\Controllers\Admin\HistoryDataController@index',
            'middleware' => 'can:history_datas-list',
        ]);

    });

    Route::prefix('logos')->group(function () {
        Route::get('/', [
            'as' => 'administrator.logos.add',
            'uses' => 'App\Http\Controllers\Admin\LogoController@create',
            'middleware' => 'can:logos-list',
        ]);

        Route::post('/store', [
            'as' => 'administrator.logos.store',
            'uses' => 'App\Http\Controllers\Admin\LogoController@store',
            'middleware' => 'can:logos-add',
        ]);

    });

    Route::prefix('users')->group(function () {

        Route::get('/', [
            'as' => 'administrator.users.index',
            'uses' => 'App\Http\Controllers\Admin\UserController@index',
            'middleware' => 'can:users-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.users.create',
            'uses' => 'App\Http\Controllers\Admin\UserController@create',
            'middleware' => 'can:users-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.users.store',
            'uses' => 'App\Http\Controllers\Admin\UserController@store',
            'middleware' => 'can:users-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.users.edit',
            'uses' => 'App\Http\Controllers\Admin\UserController@edit',
            'middleware' => 'can:users-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.users.update',
            'uses' => 'App\Http\Controllers\Admin\UserController@update',
            'middleware' => 'can:users-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.users.delete',
            'uses' => 'App\Http\Controllers\Admin\UserController@delete',
            'middleware' => 'can:users-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.users.delete_many',
            'uses' => 'App\Http\Controllers\Admin\UserController@deleteManyByIds',
            'middleware' => 'can:users-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.users.export',
            'uses' => 'App\Http\Controllers\Admin\UserController@export',
            'middleware' => 'can:users-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.users.get',
            'uses' => 'App\Http\Controllers\Admin\UserController@get',
            'middleware' => 'can:users-list',
        ]);

    });

    Route::prefix('chats')->group(function () {
        Route::get('/', [
            'as' => 'administrator.chats.index',
            'uses' => 'App\Http\Controllers\Admin\ChatController@index',
            'middleware' => 'can:chats-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.chats.create',
            'uses' => 'App\Http\Controllers\Admin\ChatController@create',
            'middleware' => 'can:chats-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.chats.store',
            'uses' => 'App\Http\Controllers\Admin\ChatController@store',
            'middleware' => 'can:chats-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.chats.edit',
            'uses' => 'App\Http\Controllers\Admin\ChatController@edit',
            'middleware' => 'can:chats-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.chats.update',
            'uses' => 'App\Http\Controllers\Admin\ChatController@update',
            'middleware' => 'can:chats-edit',
        ]);

        Route::get('/delete/{id}', [
            'as' => 'administrator.chats.delete',
            'uses' => 'App\Http\Controllers\Admin\ChatController@delete',
            'middleware' => 'can:chats-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.chats.delete_many',
            'uses' => 'App\Http\Controllers\Admin\ChatController@deleteManyByIds',
            'middleware' => 'can:chats-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.chats.export',
            'uses' => 'App\Http\Controllers\Admin\ChatController@export',
            'middleware' => 'can:chats-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.chats.get',
            'uses' => 'App\Http\Controllers\Admin\ChatController@get',
            'middleware' => 'can:chats-list',
        ]);

    });

    Route::prefix('employees')->group(function () {
        Route::get('/', [
            'as' => 'administrator.employees.index',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@index',
            'middleware' => 'can:employees-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.employees.create',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@create',
            'middleware' => 'can:employees-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.employees.store',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@store',
            'middleware' => 'can:employees-add',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.employees.update',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@update',
            'middleware' => 'can:employees-edit',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.employees.edit',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@edit',
            'middleware' => 'can:employees-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.employees.delete',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@delete',
            'middleware' => 'can:employees-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.employees.delete_many',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@deleteManyByIds',
            'middleware' => 'can:employees-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.employees.export',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@export',
            'middleware' => 'can:employees-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.employees.get',
            'uses' => 'App\Http\Controllers\Admin\EmployeeController@get',
            'middleware' => 'can:employees-list',
        ]);

    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [
            'as' => 'administrator.roles.index',
            'uses' => 'App\Http\Controllers\Admin\RoleController@index',
            'middleware' => 'can:roles-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.roles.create',
            'uses' => 'App\Http\Controllers\Admin\RoleController@create',
            'middleware' => 'can:roles-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.roles.edit',
            'uses' => 'App\Http\Controllers\Admin\RoleController@edit',
            'middleware' => 'can:roles-edit',
        ]);

        Route::post('/store', [
            'as' => 'administrator.roles.store',
            'uses' => 'App\Http\Controllers\Admin\RoleController@store',
            'middleware' => 'can:roles-add',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.roles.update',
            'uses' => 'App\Http\Controllers\Admin\RoleController@update',
            'middleware' => 'can:roles-edit',
        ]);

        Route::get('/delete/{id}', [
            'as' => 'administrator.roles.delete',
            'uses' => 'App\Http\Controllers\Admin\RoleController@delete',
            'middleware' => 'can:roles-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.roles.delete_many',
            'uses' => 'App\Http\Controllers\Admin\RoleController@deleteManyByIds',
            'middleware' => 'can:roles-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.roles.export',
            'uses' => 'App\Http\Controllers\Admin\RoleController@export',
            'middleware' => 'can:roles-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.roles.get',
            'uses' => 'App\Http\Controllers\Admin\RoleController@get',
            'middleware' => 'can:roles-list',
        ]);

    });

    Route::prefix('permissions')->group(function () {
        Route::get('/create', [
            'as' => 'administrator.permissions.create',
            'uses' => 'App\Http\Controllers\Admin\PermissionController@create',
            'middleware' => 'can:permissions-list',
        ]);

        Route::post('/store', [
            'as' => 'administrator.permissions.store',
            'uses' => 'App\Http\Controllers\Admin\PermissionController@store',
            'middleware' => 'can:permissions-add',
        ]);

    });

    Route::prefix('sliders')->group(function () {
        Route::get('/', [
            'as' => 'administrator.sliders.index',
            'uses' => 'App\Http\Controllers\Admin\SliderController@index',
            'middleware' => 'can:sliders-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.sliders.create',
            'uses' => 'App\Http\Controllers\Admin\SliderController@create',
            'middleware' => 'can:sliders-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.sliders.store',
            'uses' => 'App\Http\Controllers\Admin\SliderController@store',
            'middleware' => 'can:sliders-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.sliders.edit',
            'uses' => 'App\Http\Controllers\Admin\SliderController@edit',
            'middleware' => 'can:sliders-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.sliders.update',
            'uses' => 'App\Http\Controllers\Admin\SliderController@update',
            'middleware' => 'can:sliders-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.sliders.delete',
            'uses' => 'App\Http\Controllers\Admin\SliderController@delete',
            'middleware' => 'can:sliders-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.sliders.delete_many',
            'uses' => 'App\Http\Controllers\Admin\SliderController@deleteManyByIds',
            'middleware' => 'can:sliders-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.sliders.export',
            'uses' => 'App\Http\Controllers\Admin\SliderController@export',
            'middleware' => 'can:sliders-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.sliders.get',
            'uses' => 'App\Http\Controllers\Admin\SliderController@get',
            'middleware' => 'can:sliders-list',
        ]);

    });

    Route::prefix('news')->group(function () {
        Route::get('/', [
            'as' => 'administrator.news.index',
            'uses' => 'App\Http\Controllers\Admin\NewsController@index',
            'middleware' => 'can:news-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.news.create',
            'uses' => 'App\Http\Controllers\Admin\NewsController@create',
            'middleware' => 'can:news-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.news.store',
            'uses' => 'App\Http\Controllers\Admin\NewsController@store',
            'middleware' => 'can:news-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.news.edit',
            'uses' => 'App\Http\Controllers\Admin\NewsController@edit',
            'middleware' => 'can:news-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.news.update',
            'uses' => 'App\Http\Controllers\Admin\NewsController@update',
            'middleware' => 'can:news-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.news.delete',
            'uses' => 'App\Http\Controllers\Admin\NewsController@delete',
            'middleware' => 'can:news-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.news.delete_many',
            'uses' => 'App\Http\Controllers\Admin\NewsController@deleteManyByIds',
            'middleware' => 'can:news-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.news.export',
            'uses' => 'App\Http\Controllers\Admin\NewsController@export',
            'middleware' => 'can:news-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.news.get',
            'uses' => 'App\Http\Controllers\Admin\NewsController@get',
            'middleware' => 'can:news-list',
        ]);

    });

    Route::prefix('job-emails')->group(function () {
        Route::get('/', [
            'as' => 'administrator.job_emails.index',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@index',
            'middleware' => 'can:job_emails-list',
        ]);

        Route::post('/store', [
            'as' => 'administrator.job_emails.store',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@store',
            'middleware' => 'can:job_emails-add',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.job_emails.delete',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@delete',
            'middleware' => 'can:job_emails-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.job_emails.delete_many',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@deleteManyByIds',
            'middleware' => 'can:job_emails-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.job_emails.export',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@export',
            'middleware' => 'can:job_emails-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.job_emails.get',
            'uses' => 'App\Http\Controllers\Admin\JobEmailController@get',
            'middleware' => 'can:job_emails-list',
        ]);

    });

    Route::prefix('job-notifications')->group(function () {
        Route::get('/', [
            'as' => 'administrator.job_notifications.index',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@index',
            'middleware' => 'can:job_notifications-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.job_notifications.create',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@create',
            'middleware' => 'can:job_notifications-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.job_notifications.store',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@store',
            'middleware' => 'can:job_notifications-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.job_notifications.edit',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@edit',
            'middleware' => 'can:job_notifications-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.job_notifications.update',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@update',
            'middleware' => 'can:job_notifications-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.job_notifications.delete',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@delete',
            'middleware' => 'can:job_notifications-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.job_notifications.delete_many',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@deleteManyByIds',
            'middleware' => 'can:job_notifications-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.job_notifications.export',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@export',
            'middleware' => 'can:job_notifications-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.job_notifications.get',
            'uses' => 'App\Http\Controllers\Admin\JobNotificationController@get',
            'middleware' => 'can:job_notifications-list',
        ]);

    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [
            'as' => 'administrator.categories.index',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@index',
            'middleware' => 'can:categories-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.categories.create',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@create',
            'middleware' => 'can:categories-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.categories.store',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@store',
            'middleware' => 'can:categories-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.categories.edit',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@edit',
            'middleware' => 'can:categories-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.categories.update',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@update',
            'middleware' => 'can:categories-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.categories.delete',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@delete',
            'middleware' => 'can:categories-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.categories.delete_many',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@deleteManyByIds',
            'middleware' => 'can:categories-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.categories.export',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@export',
            'middleware' => 'can:categories-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.categories.get',
            'uses' => 'App\Http\Controllers\Admin\CategoryController@get',
            'middleware' => 'can:categories-list',
        ]);

    });

    Route::prefix('products')->group(function () {
        Route::get('/', [
            'as' => 'administrator.products.index',
            'uses' => 'App\Http\Controllers\Admin\ProductController@index',
            'middleware' => 'can:products-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.products.create',
            'uses' => 'App\Http\Controllers\Admin\ProductController@create',
            'middleware' => 'can:products-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.products.store',
            'uses' => 'App\Http\Controllers\Admin\ProductController@store',
            'middleware' => 'can:products-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.products.edit',
            'uses' => 'App\Http\Controllers\Admin\ProductController@edit',
            'middleware' => 'can:products-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.products.update',
            'uses' => 'App\Http\Controllers\Admin\ProductController@update',
            'middleware' => 'can:products-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.products.delete',
            'uses' => 'App\Http\Controllers\Admin\ProductController@delete',
            'middleware' => 'can:products-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.products.delete_many',
            'uses' => 'App\Http\Controllers\Admin\ProductController@deleteManyByIds',
            'middleware' => 'can:products-delete',
        ]);

        Route::get('/export', [
            'as'=>'administrator.products.export',
            'uses'=>'App\Http\Controllers\Admin\ProductController@export',
            'middleware'=>'can:products-list',
        ]);

        Route::post('/import', [
            'as'=>'administrator.products.import',
            'uses'=>'App\Http\Controllers\Admin\ProductController@import',
            'middleware'=>'can:products-add',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.products.get',
            'uses' => 'App\Http\Controllers\Admin\ProductController@get',
            'middleware' => 'can:products-list',
        ]);

    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [
            'as' => 'administrator.settings.index',
            'uses' => 'App\Http\Controllers\Admin\SettingController@index',
            'middleware' => 'can:settings-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.settings.create',
            'uses' => 'App\Http\Controllers\Admin\SettingController@create',
            'middleware' => 'can:settings-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.settings.store',
            'uses' => 'App\Http\Controllers\Admin\SettingController@store',
            'middleware' => 'can:settings-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.settings.edit',
            'uses' => 'App\Http\Controllers\Admin\SettingController@edit',
            'middleware' => 'can:settings-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.settings.update',
            'uses' => 'App\Http\Controllers\Admin\SettingController@update',
            'middleware' => 'can:settings-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.settings.delete',
            'uses' => 'App\Http\Controllers\Admin\SettingController@delete',
            'middleware' => 'can:settings-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.settings.delete_many',
            'uses' => 'App\Http\Controllers\Admin\SettingController@deleteManyByIds',
            'middleware' => 'can:settings-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.settings.export',
            'uses' => 'App\Http\Controllers\Admin\SettingController@export',
            'middleware' => 'can:settings-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.settings.get',
            'uses' => 'App\Http\Controllers\Admin\SettingController@get',
            'middleware' => 'can:settings-list',
        ]);

    });

    Route::prefix('category-news')->group(function () {
        Route::get('/', [
            'as' => 'administrator.category_news.index',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@index',
            'middleware' => 'can:category_news-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.category_news.create',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@create',
            'middleware' => 'can:category_news-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.category_news.store',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@store',
            'middleware' => 'can:category_news-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.category_news.edit',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@edit',
            'middleware' => 'can:category_news-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.category_news.update',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@update',
            'middleware' => 'can:category_news-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.category_news.delete',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@delete',
            'middleware' => 'can:category_news-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.category_news.delete_many',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@deleteManyByIds',
            'middleware' => 'can:category_news-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.category_news.export',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@export',
            'middleware' => 'can:category_news-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.category_news.get',
            'uses' => 'App\Http\Controllers\Admin\CategoryNewController@get',
            'middleware' => 'can:category_news-list',
        ]);

    });

    Route::prefix('system-branches')->group(function () {
        Route::get('/', [
            'as' => 'administrator.system_branches.index',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@index',
            'middleware' => 'can:system_branches-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.system_branches.create',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@create',
            'middleware' => 'can:system_branches-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.system_branches.store',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@store',
            'middleware' => 'can:system_branches-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.system_branches.edit',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@edit',
            'middleware' => 'can:system_branches-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.system_branches.update',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@update',
            'middleware' => 'can:system_branches-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.system_branches.delete',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@delete',
            'middleware' => 'can:system_branches-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.system_branches.delete_many',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@deleteManyByIds',
            'middleware' => 'can:system_branches-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.system_branches.export',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@export',
            'middleware' => 'can:system_branches-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.system_branches.get',
            'uses' => 'App\Http\Controllers\Admin\SystemBranchController@get',
            'middleware' => 'can:system_branches-list',
        ]);
    });

    Route::prefix('quotations')->group(function () {
        Route::get('/', [
            'as' => 'administrator.quotations.index',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@index',
            'middleware' => 'can:quotations-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.quotations.create',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@create',
            'middleware' => 'can:quotations-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.quotations.store',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@store',
            'middleware' => 'can:quotations-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.quotations.edit',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@edit',
            'middleware' => 'can:quotations-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.quotations.update',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@update',
            'middleware' => 'can:quotations-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.quotations.delete',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@delete',
            'middleware' => 'can:quotations-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.quotations.delete_many',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@deleteManyByIds',
            'middleware' => 'can:quotations-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.quotations.export',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@export',
            'middleware' => 'can:quotations-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.quotations.get',
            'uses' => 'App\Http\Controllers\Admin\QuotationController@get',
            'middleware' => 'can:quotations-list',
        ]);
    });

    Route::prefix('calendars')->group(function () {
        Route::get('/', [
            'as' => 'administrator.calendars.index',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@index',
            'middleware' => 'can:calendars-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.calendars.create',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@create',
            'middleware' => 'can:calendars-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.calendars.store',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@store',
            'middleware' => 'can:calendars-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.calendars.edit',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@edit',
            'middleware' => 'can:calendars-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.calendars.update',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@update',
            'middleware' => 'can:calendars-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.calendars.delete',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@delete',
            'middleware' => 'can:calendars-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.calendars.delete_many',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@deleteManyByIds',
            'middleware' => 'can:calendars-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.calendars.export',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@export',
            'middleware' => 'can:calendars-list',
        ]);

        Route::get('/import', [
            'as' => 'administrator.calendars.import',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@import',
            'middleware' => 'can:calendars-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.calendars.get',
            'uses' => 'App\Http\Controllers\Admin\CalendarsController@get',
            'middleware' => 'can:calendars-list',
        ]);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [
            'as' => 'administrator.orders.index',
            'uses' => 'App\Http\Controllers\Admin\OrderController@index',
            'middleware' => 'can:orders-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.orders.create',
            'uses' => 'App\Http\Controllers\Admin\OrderController@create',
            'middleware' => 'can:orders-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.orders.store',
            'uses' => 'App\Http\Controllers\Admin\OrderController@store',
            'middleware' => 'can:orders-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.orders.edit',
            'uses' => 'App\Http\Controllers\Admin\OrderController@edit',
            'middleware' => 'can:orders-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.orders.update',
            'uses' => 'App\Http\Controllers\Admin\OrderController@update',
            'middleware' => 'can:orders-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.orders.delete',
            'uses' => 'App\Http\Controllers\Admin\OrderController@delete',
            'middleware' => 'can:orders-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.orders.delete_many',
            'uses' => 'App\Http\Controllers\Admin\OrderController@deleteManyByIds',
            'middleware' => 'can:orders-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.orders.export',
            'uses' => 'App\Http\Controllers\Admin\OrderController@export',
            'middleware' => 'can:orders-list',
        ]);

        Route::get('/import', [
            'as' => 'administrator.orders.import',
            'uses' => 'App\Http\Controllers\Admin\OrderController@import',
            'middleware' => 'can:orders-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.orders.get',
            'uses' => 'App\Http\Controllers\Admin\OrderController@get',
            'middleware' => 'can:orders-list',
        ]);
    });

    Route::prefix('vouchers')->group(function () {
        Route::get('/', [
            'as' => 'administrator.vouchers.index',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@index',
            'middleware' => 'can:vouchers-list',
        ]);

        Route::get('/create', [
            'as' => 'administrator.vouchers.create',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@create',
            'middleware' => 'can:vouchers-add',
        ]);

        Route::post('/store', [
            'as' => 'administrator.vouchers.store',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@store',
            'middleware' => 'can:vouchers-add',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'administrator.vouchers.edit',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@edit',
            'middleware' => 'can:vouchers-edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'administrator.vouchers.update',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@update',
            'middleware' => 'can:vouchers-edit',
        ]);

        Route::delete('/delete/{id}', [
            'as' => 'administrator.vouchers.delete',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@delete',
            'middleware' => 'can:vouchers-delete',
        ]);

        Route::delete('/delete-many', [
            'as' => 'administrator.vouchers.delete_many',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@deleteManyByIds',
            'middleware' => 'can:vouchers-delete',
        ]);

        Route::get('/export', [
            'as' => 'administrator.vouchers.export',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@export',
            'middleware' => 'can:vouchers-list',
        ]);

        Route::get('/import', [
            'as' => 'administrator.vouchers.import',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@import',
            'middleware' => 'can:vouchers-list',
        ]);

        Route::get('/{id}', [
            'as' => 'administrator.vouchers.get',
            'uses' => 'App\Http\Controllers\Admin\VoucherController@get',
            'middleware' => 'can:vouchers-list',
        ]);
    });

});

