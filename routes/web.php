<?php

Route::group(['prefix' => '/auth'], function () {
    Route::get('/steam', 'Auth\SteamController@redirectToSteam');
    Route::get('/steam/handle', 'Auth\SteamController@handle');

    Route::get('/vk', 'Auth\VkController@redirectToVK');
    Route::get('/vk/handle', 'Auth\VkController@handle');

    Route::get('/fb', 'Auth\FbController@redirectToFb');
    Route::get('/fb/handle', 'Auth\FbController@handle');

    Route::get('/logout', 'Auth\SteamController@logout');
});

Route::group(['prefix' => '/api'], function () {
    Route::group(['prefix' => 'cases'], function () {
        Route::post('/get', 'CasesController@get');
        Route::post('/one', 'CasesController@one');
        Route::post('/open', 'CasesController@open');
        Route::post('/liveDrop', 'CasesController@liveDrop');
    });
    Route::group(['prefix' => 'users'], function () {
        Route::post('/getBalance', 'UsersController@getBalance');
        Route::post('/sell', 'UsersController@sell');
        Route::post('/get', 'UsersController@get');
        Route::post('/saveLink', 'UsersController@saveLink');
        Route::post('/items', 'UsersController@items');
        Route::post('/buy', 'UsersController@buy');
        Route::post('/sellAll', 'UsersController@sellAll');
    });
    Route::group(['prefix' => '/bot', 'middleware' => 'bot'], function () {
        Route::post('/checkItems', 'BotController@checkItems');
        Route::post('/getSettings', 'BotController@getSettings');
        Route::post('/setStatus', 'BotController@setStatus');
        Route::post('/getOrders', 'BotController@getOrders');
        Route::post('/getOrder', 'BotController@getOrder');
        Route::post('/getUserFromOrder', 'BotController@getUserFromOrder');
        Route::post('/setBattleStatus', 'BattlesController@setBattleStatus');
        Route::post('/fakeOpen', 'CasesController@fakeOpen');
    });
    Route::group(['prefix' => '/payments'], function () {
        Route::post('/create', 'PaymentController@create');
        Route::post('/check', 'PaymentController@check');
    });
    Route::group(['prefix' => '/contracts'], function () {
        Route::post('/loadItems', 'ContractsController@loadItems');
        Route::post('/create', 'ContractsController@create');
    });
    Route::group(['prefix' => '/battles'], function () {
        Route::post('/loadCases', 'BattlesController@loadCases');
        Route::post('/create', 'BattlesController@create');
        Route::post('/getBattle', 'BattlesController@getBattle');
        Route::post('/join', 'BattlesController@join');
        Route::post('/decline', 'BattlesController@decline');
        Route::post('/history', 'BattlesController@history');
    });
    Route::group(['prefix' => 'upgrades'], function () {
        Route::post('/getMyItems', 'UpgradeController@getMyItems');
        Route::post('/getSiteItems', 'UpgradeController@getSiteItems');
        Route::post('/getOneItem', 'UpgradeController@getOneItem');
        Route::post('/upgrade', 'UpgradeController@upgrade');
    });
    Route::post('/top', 'PagesController@top');
    Route::post('/loadSeo', 'Admin\SeoController@getterSeo');
    Route::post('/checkPromocode', 'Admin\PromocodesController@checkPromocode');
});

Route::group(['prefix' => '/api/admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::post('/load', 'SettingsController@load');
        Route::post('/save', 'SettingsController@save');
        Route::post('/socketRun', 'SettingsController@socketRun');
        Route::post('/socketOff', 'SettingsController@socketOff');
        Route::post('/statistic', 'SettingsController@statistic');
        Route::post('/getBalance', 'SettingsController@getBalance');
    });
    Route::group(['prefix' => 'items'], function () {
        Route::post('/load', 'ItemsController@load');
        Route::post('/get', 'ItemsController@get');
        Route::post('/edit', 'ItemsController@edit');
        Route::post('/del', 'ItemsController@del');
        Route::post('/create', 'ItemsController@create');
        Route::post('/updatePrices', 'ItemsController@updatePrices');
    });
    Route::group(['prefix' => 'styles'], function () {
        Route::post('/load', 'StylesController@load');
        Route::post('/create', 'StylesController@create');
        Route::post('/get', 'StylesController@get');
        Route::post('/edit', 'StylesController@edit');
        Route::post('/del', 'StylesController@del');
    });
    Route::group(['prefix' => 'categories'], function () {
        Route::post('/load', 'CategoriesController@load');
        Route::post('/create', 'CategoriesController@create');
        Route::post('/get', 'CategoriesController@get');
        Route::post('/edit', 'CategoriesController@edit');
        Route::post('/del', 'CategoriesController@del');
    });
    Route::group(['prefix' => 'promocodes'], function () {
        Route::post('/load', 'PromocodesController@load');
        Route::post('/create', 'PromocodesController@create');
        Route::post('/get', 'PromocodesController@get');
        Route::post('/edit', 'PromocodesController@edit');
        Route::post('/del', 'PromocodesController@del');
    });
    Route::group(['prefix' => 'seo'], function () {
        Route::post('/load', 'SeoController@load');
        Route::post('/get', 'SeoController@get');
        Route::post('/edit', 'SeoController@edit');
    });
    Route::group(['prefix' => 'cases'], function () {
        Route::post('/load', 'CasesController@load');
        Route::post('/create', 'CasesController@create');
        Route::post('/get', 'CasesController@get');
        Route::post('/edit', 'CasesController@edit');
        Route::post('/del', 'CasesController@del');

        Route::group(['prefix' => 'items'], function () {
            Route::post('/load', 'CasesItemsController@load');
            Route::post('/create', 'CasesItemsController@create');
            Route::post('/get', 'CasesItemsController@get');
            Route::post('/edit', 'CasesItemsController@edit');
            Route::post('/del', 'CasesItemsController@del');
            Route::post('/all', 'CasesItemsController@all');
        });
    });
    Route::group(['prefix' => 'users'], function () {
        Route::post('/load', 'UsersController@load');
        Route::post('/get', 'UsersController@get');
        Route::post('/save', 'UsersController@save');
        Route::post('/getSteamid', 'UsersController@getSteamid');
        Route::post('/create', 'UsersController@create');
    });
});

Route::get('/admin{any}', 'PagesController@admin')->where('any', '.*')->middleware('admin');

Route::get('/{any}', 'PagesController@index')->where('any', '.*');
