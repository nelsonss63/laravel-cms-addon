<?php

namespace Cednet\Cms;

use Controller;
use Cms\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;


class CmsAdminBaseController extends \Controller
{

    public $company_name = "";

    public function __construct()
    {
        //Create nav
        $this->nav = array(
            Lang::get('cms::m.site-settings') => route('settings'),
            Lang::get('cms::m.manage-users') => route('users'),
            Lang::get('cms::m.crawler') => route('crawler'),
            'Unsorted pages' => route('unsortedPages'),
        );
        View::share("nav", $this->nav);

        View::share("website_home", "/");
        View::share("title", false); //set default zero / false value for title

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