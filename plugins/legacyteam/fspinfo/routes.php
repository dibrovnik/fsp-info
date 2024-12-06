<?php



Route::group([
    'prefix'     => 'api/v1',
    'namespace'  => 'Legacyteam\FspInfo\ApiControllers',
    'middleware' => 'cors'
], function() {

  // EVENTS
    Route::get('events/{id}', 'EventsApiController@getEventsByRegion');
    Route::get('events/{id}/all', 'EventsApiController@getAllEventsByRegion');
    Route::get('events', 'EventsApiController@getAllEvents');

  // NEWS
    Route::get('news', 'NewsApiController@getAllNews');
    Route::get('news/{id}', 'NewsApiController@getNewsByRegion');

  // RESULTS

    Route::get('results/{id}', 'ResultsApiController@getResultsByRegion');
    Route::post('result/create', 'ResultsApiController@setResults');

  // REGIONS

    Route::get('regions', 'RegionsApiController@getRegions');

    // Example
    // Route::get('products', 'Products@index');
    // Route::post('orders', 'Orders@store');
});