<?php

namespace Cednet\Cms;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;

class AccountController extends \Cednet\Cms\CmsBaseController
{

    public function __construct()
    {
        parent::__construct();
        View::share('title', Lang::get('cms::m.my-account'));
    }


    public function index()
    {
        return View::make('cms::account.index');
    }

}