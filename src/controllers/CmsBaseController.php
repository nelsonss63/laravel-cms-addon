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
        //Layout variables
        View::share('extendUrl', Setting::get('extend_url_template_page'));
        View::share('sectionName', Setting::get('content_section_name'));
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