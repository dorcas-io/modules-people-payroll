<?php
Route::group(['namespace' => 'Dorcas\ModulesFinanceTax\Http\Controllers', 'middleware' => ['web','auth'], 'prefix' => 'mfn'], function() {
    Route::get('tax-main', 'ModulesFinanceTaxController@index')->name('tax-main');

    Route::get('tax-authorities','ModulesFinanceTaxController@Authorities')->name('tax-authorities');

    Route::get('tax-authorities-search','ModulesFinanceTaxController@searchAuthority')->name('authority-search');
    Route::get('tax-authorities/{id}','ModulesFinanceTaxController@singleAuthority')->name('single_authority');
    Route::post('tax-authorities' ,'ModulesFinanceTaxController@createAuthority')->name('create_authority');
    Route::put('tax-authorities/{id}' ,'ModulesFinanceTaxController@updateAuthority')->name('update_authority');
    Route::delete('tax-authorities/{id}' ,'ModulesFinanceTaxController@deleteAuthority')->name('delete_authority');

    Route::get('tax-element-search','ModulesFinanceTaxController@searchElement')->name('element-search');
    Route::post('tax-elements' ,'ModulesFinanceTaxController@addElement')->name('create_element');
    Route::get('tax-element/{id}' ,'ModulesFinanceTaxController@singleElement')->name('update_element');



});