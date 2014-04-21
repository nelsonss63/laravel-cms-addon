<?php

namespace Cednet\Cms;

use Cms\Models\Page;
use Cms\Models\Crawl;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.admin';

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Admin");
    }

    public function start()
    {
        return View::make('cms::admin/start');
    }


}