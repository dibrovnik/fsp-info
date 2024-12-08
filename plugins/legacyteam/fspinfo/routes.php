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

  // EVENT
  
    Route::get('event/{id}', 'EventsApiController@getEvent');

  // NEWS
  
    Route::get('news', 'NewsApiController@getAllNews');
    Route::get('news/{id}', 'NewsApiController@getNewsByRegion');

  // NEW

    Route::get('new/{id}', 'NewsApiController@getNew');

  // RESULTS

    Route::get('results/{id}', 'ResultsApiController@getResultsByRegion');
    Route::get('result/{id}', 'ResultsApiController@getResultsByEvent');
    Route::post('result/create', 'ResultsApiController@setResults');

  // REGIONS

    Route::get('regions', 'RegionsApiController@getRegions');
    Route::get('regions/raw', 'RegionsApiController@getRegionsRaw');
    Route::get('region/{id}', 'RegionsApiController@getRegionInfo');
    // Route::get('regions/api', 'RegionsApiController@regionsApi');

    // Example
    // Route::get('products', 'Products@index');
    // Route::post('orders', 'Orders@store');
});