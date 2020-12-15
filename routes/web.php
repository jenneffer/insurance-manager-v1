<?php

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::redirect('/', '/admin/insurances');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');


    //Insurance - 20200803 by Jenn
    Route::delete('insurances/destroy', 'InsuranceController@massDestroy')->name('insurances.massDestroy');
    Route::resource('insurances', 'InsuranceController');
    //insurance renew
    Route::post('/insurance/add', 'InsuranceController@store')->name('insurances.store');
    Route::post('/insurance/update', 'InsuranceController@update')->name('insurances.update');
    // Route::post('/insurance/renew', 'InsuranceController@renew')->name('insurances.renew');
    Route::post('/interest_insured/retrieve', 'InsuranceController@showInterestInsured')->name('insurances.showInterestInsured');
    Route::post('/interest_insured/update', 'InsuranceController@updateInterestInsured')->name('insurances.updateInterestInsured');    
    Route::post('/perils/retrieve', 'InsuranceController@showPerils')->name('insurances.showPerils');
    Route::post('/perils/update', 'InsuranceController@updatePerils')->name('insurances.updatePerils'); 



    Route::resource('insurance_details', 'InsuranceDetailsController');
    Route::get('/insurance/renew/{id}','InsuranceDetailsController@index')->name('insurances.renew');
    Route::get('/insurance/renew_without_addition/{id}', 'InsuranceDetailsController@renew_without_addition')->name('insurances.renew_without_addition');
    Route::get('/insurance/renew_with_addition/{id}', 'InsuranceDetailsController@renew_with_addition')->name('insurances.renew_with_addition');
    Route::post('/insurance/update_renewal', 'InsuranceDetailsController@update_renewal')->name('insurances.update_renewal'); //update renewal without addition
    Route::post('/insurance/update_renewal_add', 'InsuranceDetailsController@update_renewal_add')->name('insurances.update_renewal_add'); //update renewal with addition
    //Company
    Route::delete('companies/destroy', 'CompanyController@massDestroy')->name('companies.massDestroy');
    Route::resource('companies', 'CompanyController');

    //interest insured
    Route::post('insurance/interest_insured/destroy', 'InterestInsuredController@destroy')->name('interest_insured.destroy');
    Route::resource('insurance/interest_insured', 'InterestInsuredController');
    Route::post('/interest_insured/retrieve_ii', 'InterestInsuredController@show')->name('interest_insured.show');
    Route::post('/perils/retrieve_perils', 'PerilsController@show')->name('perils.show');

    //perils
    Route::post('insurance/perils/destroy', 'PerilsController@destroy')->name('perils.destroy');

    //attachment
     Route::delete('attachments/destroy', 'AttachmentController@massDestroy')->name('attachments.massDestroy');     
     Route::resource('attachments', 'AttachmentController');

     //agent
    Route::delete('agents/destroy', 'AgentController@massDestroy')->name('agents.massDestroy');
    Route::resource('agents', 'AgentController');

});
