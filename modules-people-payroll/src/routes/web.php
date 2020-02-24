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


//    Route::get('tax-authorities/{id}', 'ModulesFinanceTaxController@singleAuthority')->name('single_authority');
//    Route::post('tax-authorities', 'ModulesFinanceTaxController@createAuthority')->name('create_authority');
//    Route::put('tax-authorities/{id}', 'ModulesFinanceTaxController@updateAuthority')->name('update_authority');
//    Route::delete('tax-authorities/{id}', 'ModulesFinanceTaxController@deleteAuthority')->name('delete_authority');
//
//    Route::get('tax-element-search', 'ModulesFinanceTaxController@searchElement')->name('element-search');
//    Route::post('tax-elements', 'ModulesFinanceTaxController@addElement')->name('create_element');


});