<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;

Route::get('/', [IndexController::class, 'index'])->name('index');
// Route::redirect('/login', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => true]);
Auth::routes(['login' => true]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Status
    Route::delete('status/destroy', 'StatusController@massDestroy')->name('status.massDestroy');
    Route::resource('status', 'StatusController');

    // Departments
    Route::delete('departments/destroy', 'DepartmentsController@massDestroy')->name('departments.massDestroy');
    Route::resource('departments', 'DepartmentsController');

    // Employees
    Route::delete('employees/destroy', 'EmployeesController@massDestroy')->name('employees.massDestroy');
    Route::post('employees/media', 'EmployeesController@storeMedia')->name('employees.storeMedia');
    Route::get('get-employees/{department_id}', 'EmployeesController@getByDepartment')->name('employees.byDepartment');
    Route::resource('employees', 'EmployeesController');


    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientsController');

    // Appointments
    Route::delete('appointments/destroy', 'AppointmentsController@massDestroy')->name('appointments.massDestroy');
    Route::post('appointments/cancel', 'AppointmentsController@cancel')->name('appointments.cancel');
    Route::get('appointments/check-slots', 'AppointmentsController@checkSlots')->name('appointments.checkSlots');
    Route::post('appointments/completed', 'AppointmentsController@completed')->name('appointments.completed');
    Route::resource('appointments', 'AppointmentsController');

    Route::get('system-calendar', 'SystemCalendarController@index')->name('systemCalendar');
    
});
