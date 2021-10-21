<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);
// Admin

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
	
	// Payroll
    Route::delete('payrolls/destroy', 'PayrollController@massDestroy')->name('payrolls.massDestroy');
    Route::post('payrolls/destroys/{id}', 'PayrollController@destroy')->name('payrolls.destroy');
    Route::resource('payrolls', 'PayrollController');
    Route::post('payrolls/add', 'PayrollController@store')->name('payrolls.add');
    Route::post('payrolls/update', 'PayrollController@update')->name('payrolls.update');
    Route::post('payrolls/retrieve', 'PayrollController@retrieve')->name('payrolls.retrieve');
    Route::post('payrolls/details/destroy', 'PayrollController@destroy_details')->name('payrolls.destroy.details');
    Route::post('payrolls/details/update', 'PayrollController@update_details')->name('payrolls.details.update');
    Route::get('payrolls/print_payroll/{id}', 'PayrollController@print_payroll')->name('payrolls.print');
    Route::post('payrolls/daterange', 'PayrollController@filter_daterange')->name('payrolls.daterange');


    // Students
    Route::delete('students/destroy', 'StudentsController@massDestroy')->name('students.massDestroy');
    Route::resource('students', 'StudentsController');

    // Attendances
    Route::get('attendances/{year}/{month}', 'AttendanceController@index')
        ->where('year', '20(19|20)')
        ->where('month', '(1[0-2]|0?[1-9])')
        ->name('attendances.index');

    //default redirection to current month and redirect if fail above route rules
    Route::get('attendances/{year?}/{month?}', function () {
        return redirect()->route('admin.attendances.index', ['year' => now()->year, 'month' => now()->format('m')]);
    })->name('attendances.redirect');

    Route::post('attendances/{year}/{month}', 'AttendanceController@store')
        ->where('year', '20(19|20)')
        ->where('month', '(1[0-2]|0?[1-9])')
        ->name('attendances.store');

    // Invoices
    Route::delete('invoices/destroy', 'InvoicesController@massDestroy')->name('invoices.massDestroy');
    Route::resource('invoices', 'InvoicesController');
    Route::post('invoices/add', 'InvoicesController@store')->name('invoices.add');
    Route::post('invoices/update', 'InvoicesController@update')->name('invoices.update');
    Route::put('invoices/{invoice}/toggle-paid', 'InvoicesController@togglePaid')->name('invoices.togglePaid');
    Route::post('invoices/{invoice}/resend', 'InvoicesController@resend')->name('invoices.resend');
    Route::post('invoices/retrieve', 'InvoicesController@retrieve')->name('invoices.retrieve');
    Route::post('invoices/details/destroy', 'InvoicesController@destroy_details')->name('invoices.destroy.details');
    Route::post('invoices/details/update', 'InvoicesController@update_details')->name('invoices.details.update');
    Route::get('invoices/print_invoice/{id}', 'InvoicesController@print_invoice')->name('invoices.print');
	
	//Company
    Route::delete('companies/destroy', 'CompanyController@massDestroy')->name('companies.massDestroy');
    Route::resource('companies', 'CompanyController');

    //Farm Company
    Route::delete('farm_companies/destroy', 'FarmCompanyController@massDestroy')->name('farm_companies.massDestroy');
    Route::resource('farm_companies', 'FarmCompanyController');
    Route::post('farm_companies/get_super', 'FarmCompanyController@getSuper')->name('farm_companies.getSuper');

    //Employee
    Route::delete('employees/destroy', 'EmployeeController@massDestroy')->name('employees.massDestroy');
    Route::resource('employees', 'EmployeeController');

    //Job
    Route::delete('jobs/destroy', 'JobController@massDestroy')->name('jobs.massDestroy');
    Route::resource('jobs', 'JobController');
    Route::post('jobs/get-job', 'JobController@get_job_info')->name('jobs.info');

    

});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
// Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
    }

});
