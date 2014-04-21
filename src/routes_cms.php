<?php

/**
 * CMS ROUTES
 */

//Admin & Edit
//RESTful-baserat routing (allt sköts i Controllern)
Route::group(array('prefix' => 'cms/admin', 'before' => 'admin'), function() {

    //Crawler feature
    Route::get('crawler', array('as' => 'crawler', 'uses' => '\Cednet\Cms\CrawlerController@crawler'));
    Route::post('crawler', '\Cednet\Cms\CrawlerController@runCrawler');

    //Settings
    Route::get('settings', array('as' => 'settings', 'uses' => '\Cednet\Cms\SettingsController@settings'));
    Route::post('settings', '\Cednet\Cms\SettingsController@saveSettings');
    Route::get('remove-setting/{settingId}', array('as' => 'removeSetting', 'uses' => '\Cednet\Cms\SettingsController@removeSetting'));

    //Users
    Route::get('users', array('as' => 'users', 'uses' => '\Cednet\Cms\UsersController@users'));
    Route::get('create-user', array('as' => 'createUser', 'uses' => '\Cednet\Cms\UsersController@createUser'));
    Route::post('create-user', '\Cednet\Cms\UsersController@saveUser');
    Route::get('user/{userId}', array('as' => 'editUser', 'uses' => '\Cednet\Cms\UsersController@editUser'));
    Route::post('user/{userId}', '\Cednet\Cms\UsersController@saveUser');
    Route::get('remove-user/{userId}', array('as' => 'removeUser', 'uses' => '\Cednet\Cms\UsersController@removeUser'));

    Route::get('/', array('as' => 'admin', 'uses' => '\Cednet\Cms\AdminController@start'));

});

/**
 * Edit
 */
Route::group(array('prefix' => 'cms/edit', 'before' => 'edit'), function() {

    //New page
    Route::get('create-page-start', array('as' => 'createPageStart', 'uses' => '\Cednet\Cms\PagesController@createPageStart'));
    Route::get('create-page/{menuId?}/{parentPageId?}', array('as' => 'createPage', 'uses' => '\Cednet\Cms\PagesController@createPage'));
    Route::post('create-page/{menuId?}/{parentPageId?}', '\Cednet\Cms\PagesController@saveNewPage');

    //Edit page
    Route::get('edit-page/{pageId?}/{contentId?}', array('as' => 'editPage', 'uses' => '\Cednet\Cms\PagesController@editPage'));
    Route::post('edit-page/{pageId}/{contentId?}', '\Cednet\Cms\PagesController@savePage');

    //Remove page
    Route::get('remove-page/{pageId?}', array('as' => 'removePage', 'uses' => '\Cednet\Cms\PagesController@removePage'));

    //Unsorted pages
    Route::get('unsorted-pages', array('as' => 'unsortedPages', 'uses' => '\Cednet\Cms\PagesController@unsortedPages'));
    Route::post('unsorted-pages', '\Cednet\Cms\PagesController@saveUnsortedPages');

    //Mark a page as "Home" (for startpage)
    Route::get('mark-as-home/{pageId?}', array('as' => 'markAsHome', 'uses' => '\Cednet\Cms\PagesController@markAsHome'));

    //Menus
    Route::get('create-menu', array('as' => 'createMenu', 'uses' => '\Cednet\Cms\MenusController@menu'));
    Route::post('create-menu', '\Cednet\Cms\MenusController@saveMenu');
    Route::get('edit-menu/{menuId?}', array('as' => 'editMenu', 'uses' => '\Cednet\Cms\MenusController@menu'));
    Route::post('edit-menu/{menuId?}', '\Cednet\Cms\MenusController@saveMenu');
    Route::get('remove-menu/{menuId}', array('as' => 'removeMenu', 'uses' => '\Cednet\Cms\MenusController@removeMenu'));

    Route::get('/', array('as' => 'edit', 'uses' => '\Cednet\Cms\PagesController@start'));
});

//CMS Account Pages
Route::group(array('before' => 'auth'), function() {
    Route::get('account', array('as' => 'account', 'uses' => '\Cednet\Cms\AccountController@index'));
});

//CMS slugs
Route::any('search', array('as' => 'search', 'uses' => '\Cednet\Cms\PageController@search'));
Route::get('login', array('as' => 'login', 'uses' => '\Cednet\Cms\PageController@login'));
Route::get('{slug}/{args}', '\Cednet\Cms\PageController@page')->where('slug', '(.*)'); //last route tries to catch anything (sends to 404 if not found, see PageController@page function)
Route::get('/', '\Cednet\Cms\PageController@homepage');


/**
 * Login
 */

Route::post('login', function () {
    $user = array(
        'username' => Input::get('username'),
        'password' => Input::get('password')
    );

    if (Auth::attempt($user)) {
        return Redirect::route('edit')
            ->with('flash_notice', 'Du är nu inloggad.');
    }

    // authentication failure! lets go back to the login page
    return Redirect::route('login')
        ->with('flash_error', 'Felaktigt användarnamn / lösenord.')
        ->withInput();
});
Route::get('logout', array('as' => 'logout', function () {
    Auth::logout();

    return Redirect::route('login')
        ->with('flash_notice', 'Du har nu loggats ut.');
}))->before('auth');


/**
 * FILTERS
 */

Route::filter('auth', function()
{
    if (Auth::guest()) return Redirect::route('login');
});

Route::filter('admin', function()
{
    if (Auth::guest()) return Redirect::route('login');
    if (!Auth::user()->admin) return Redirect::route('login')->with('flash_notice', 'Du måste vara Admin för att komma åt admin.');
});

Route::filter('edit', function()
{
    if (Auth::guest()) return Redirect::route('login');
    if (!Auth::user()->edit) return Redirect::route('login')->with('flash_notice', 'Du måste vara Redaktör för att komma åt edit.');
});

Route::filter('guest', function()
{
    if (Auth::check()) return Redirect::to('/');
});