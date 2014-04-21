<?php

namespace Cednet\Cms;

use Controller;
use Illuminate\Support\Facades\View;
use Cms\Models\Setting;

class CmsBaseController extends \Controller
{

    public $company_name = "";

    public function __construct()
    {
        View::share("website_home", "/");
        View::share("title", false); //set default zero / false value for title
        View::share("company_logo_url", Setting::get('company_logo_url'));
        View::share("company_name", Setting::get('company_name'));

        //\Auth::loginUsingId(1);
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if(!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

}