<?php

/**
 * CMS ROUTES
 */

//Admin & Edit
//RESTful-baserat routing (allt sköts i Controllern)
Route::group(array('prefix' => 'cms/admin', 'before' => 'cmsAdmin'), function() {

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

    Route::get('/', array('as' => 'cmsAdmin', 'uses' => '\Cednet\Cms\AdminController@start'));

});

/**
 * Edit
 */
Route::group(array('prefix' => 'cms/edit', 'before' => 'cmsEdit'), function() {

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

    Route::get('/', array('as' => 'cmsEdit', 'uses' => '\Cednet\Cms\PagesController@start'));
});

//CMS Login
Route::get('cms/login', array('as' => 'cmsLogin', 'uses' => '\Cednet\Cms\PageController@login'));

/**
 * All routes for this CMS are predefined URL:s created by the CMS
 * This CMS is not meant to have thousands of pages = this is OK up to XX numbers of pages
 */
foreach(\Cms\Libraries\Helper::getRoutes() as $page) {
    Route::get($page->slug, '\Cednet\Cms\PageController@page');
}


/**
 * Login
 */

Route::post('cms/login', function () {
    $user = array(
        'username' => Input::get('username'),
        'password' => Input::get('password'),
        'edit' => 1, //ensures atleast "edit" permissions is required
    );
    if (Auth::attempt($user)) {
        return Redirect::route('cmsEdit')
            ->with('flash_notice', 'Du är nu inloggad.');
    }

    // authentication failure! lets go back to the login page
    return Redirect::route('cmsLogin')
        ->with('flash_error', 'Felaktigt användarnamn / lösenord.')
        ->withInput();
});
Route::get('cms/logout', array('as' => 'cmsLogout', function () {
    Auth::logout();

    return Redirect::route('cmsLogin')
        ->with('flash_notice', 'Du har nu loggats ut.');
}))->before('auth');


/**
 * FILTERS
 */

Route::filter('cmsAdmin', function()
{
    if (Auth::guest()) return Redirect::route('cmsLogin');
    if (!Auth::user()->admin) return Redirect::route('cmsLogin')->with('flash_notice', 'Du måste vara Admin för att komma åt admin.');
});

Route::filter('cmsEdit', function()
{
    if (Auth::guest()) return Redirect::route('cmsLogin');
    if (!Auth::user()->edit) return Redirect::route('cmsLogin')->with('flash_notice', 'Du måste vara Redaktör för att komma åt edit.');
});
