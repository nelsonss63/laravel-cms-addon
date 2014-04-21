<?php

namespace Cednet\Cms;

use Cms\Models\Setting;
use Exception;
use Former\Facades\Former;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Lang;

class SettingsController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.edit';
    protected $nav = array();

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Settings");
    }

    /** Create Setting */
    public function settings()
    {
        return View::make('cms::edit.settings.settings', array(
            "settings" => Setting::orderBy("name", "asc")->get()
        ));
    }

    public function saveSettings()
    {
        foreach(Input::all() as $key => $value) {
            Setting::where("name", "=", $key)->update(array(
                "value" => $value
            ));
        }

        //New settings
        if(Input::get('new_setting')) {
            Setting::create(array(
                "name" => Input::get('new_setting'),
                "value" => Input::get('new_setting_value')
            ));
        }

        return Redirect::route('settings')->with('flash_notice', Lang::get('cms::m.saved'));
    }

    /**
     * Removing a Setting must also reset all pages to setting_id = 0 (put in Unsorted Pages)
     * @param $settingId
     * @return mixed
     */
    public function removeSetting($settingId)
    {
        //Remove
        $setting = Setting::find($settingId);
        if($setting) $setting->delete();

        return Redirect::route('settings')->with('flash_notice', 'Setting removed');
    }

}