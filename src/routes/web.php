<?php

Route::group(['namespace' => 'Dorcas\ModulesPeoplePayroll\Http\Controllers', 'middleware' => ['web', 'auth'], 'prefix' => 'mpe'], function () {
    Route::get('payroll-main', 'ModulesPeoplePayrollController@index')->name('payroll-main');

    Route::get('payroll-authorities', 'ModulesPeoplePayrollController@authorityIndex')->name('payroll-authorities');
    Route::get('payroll-authorities-search', 'ModulesPeoplePayrollController@searchAuthority')->name('payroll-authority-search');
    Route::get('payroll-authorities/{id}', 'ModulesPeoplePayrollController@singleAuthority')->name('payroll-single-authority');
    Route::post('payroll-authorities', 'ModulesPeoplePayrollController@createAuthority')->name('payroll-create_authority');
    Route::put('payroll-authorities/{id}', 'ModulesPeoplePayrollController@updateAuthority')->name('payroll-update_authority');
    Route::delete('payroll-authorities/{id}', 'ModulesPeoplePayrollController@deleteAuthority')->name('payroll-delete_authority');


    Route::get('payroll-allowances', 'ModulesPeoplePayrollController@allowanceIndex')->name('payroll-allowances');
    Route::get('payroll-allowances-search', 'ModulesPeoplePayrollController@searchAllowance')->name('payroll-allowance-search');
    Route::post('payroll-allowance', 'ModulesPeoplePayrollController@createAllowance')->name('payroll-create_allowance');
    Route::get('payroll-allowance/{id}', 'ModulesPeoplePayrollController@singleAllowance')->name('payroll-single-allowance');
    Route::put('payroll-allowance/{id}', 'ModulesPeoplePayrollController@updateAllowance')->name('payroll-update_allowance');
    Route::delete('payroll-allowance/{id}', 'ModulesPeoplePayrollController@deleteAllowance')->name('payroll-delete_allowance');



    Route::get('payroll-paygroup', 'ModulesPeoplePayrollController@paygroupIndex')->name('payroll-paygroup');
    Route::get('payroll-paygroups-search', 'ModulesPeoplePayrollController@searchPaygroup')->name('payroll-paygroup-search');
    Route::post('payroll-paygroup', 'ModulesPeoplePayrollController@createPaygroup')->name('payroll-create_paygroup');
    Route::get('payroll-paygroup/{id}', 'ModulesPeoplePayrollController@singlePaygroup')->name('payroll-single-paygroup');
    Route::put('payroll-paygroup/{id}', 'ModulesPeoplePayrollController@updatePaygroup')->name('payroll-update_paygroup');
    Route::delete('payroll-paygroup/{id}', 'ModulesPeoplePayrollController@deletePaygroup')->name('payroll-delete_paygroup');
    Route::get('payroll-employee-search','ModulesPeoplePayrollController@searchEmployee')->name('payroll-employee-search');
    Route::post('payroll-employee-add/{id}','ModulesPeoplePayrollController@addEmployees')->name('payroll-employee-add');
    Route::post('payroll-employee-delete/{id}','ModulesPeoplePayrollController@deleteEmployees')->name('payroll-employee-delete');
    Route::post('payroll-allowances-add/{id}','ModulesPeoplePayrollController@addAllowances')->name('payroll-allowance-add');
    Route::post('payroll-allowance-delete/{id}','ModulesPeoplePayrollController@deleteAllowances')->name('payroll-allowances-delete');

    Route::get('payroll-transactions','ModulesPeoplePayrollController@transactionIndex')->name('payroll-transactions');
    Route::post('payroll-transaction','ModulesPeoplePayrollController@createTransaction')->name('payroll-transaction');
    Route::get('payroll-transaction-search','ModulesPeoplePayrollController@searchTransaction')->name('payroll-transaction-search');
    Route::get('payroll-transaction/{id}', 'ModulesPeoplePayrollController@singleTransaction')->name('payroll-single-transaction');
    Route::put('payroll-transaction/{id}','ModulesPeoplePayrollController@updateTransaction')->name('payroll-transaction');
    Route::delete('payroll-transaction/{id}','ModulesPeoplePayrollController@deleteTransaction')->name('payroll-transaction');


    Route::get('payroll-runs','ModulesPeoplePayrollController@runIndex')->name('payroll-runs');
    Route::get('payroll-run-search','ModulesPeoplePayrollController@searchRun')->name('payroll-run-search');
    Route::post('payroll-run','ModulesPeoplePayrollController@createRun')->name('payroll-run');
    Route::get('payroll-run/{id}','ModulesPeoplePayrollController@singleRun')->name('payroll-run');
    Route::get('payroll-run/employees/{id}','ModulesPeoplePayrollController@getPayrollProcessedEmployeesInvoice')->name('payroll-run-employees');
    Route::get('payroll-run/total/employees/{id}','ModulesPeoplePayrollController@getTotalPayrollAmount')->name('payroll-run-employee-total');
    Route::get('payroll-run/total/authorities/{id}','ModulesPeoplePayrollController@getTotalPayrollAuthorityAmount')->name('payroll-run-authorities-total');
    Route::post('payroll-employee-payslip','ModulesPeoplePayrollController@viewPaySlip')->name('view-payslip');
    Route::put('payroll-run/{id}','ModulesPeoplePayrollController@updateRun')->name('payroll-run');
    Route::delete('payroll-run/{id}','ModulesPeoplePayrollController@deleteRun')->name('payroll-run');





});