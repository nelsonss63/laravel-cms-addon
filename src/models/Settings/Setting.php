<?php

namespace Cms\Models;

/** Settings
 * Editable = 0 = only set by system
 * Editable = 1 = can be edited on admin settings page
 * */

class Setting extends \Eloquent
{

    protected $table = "cms_settings";
    public $timestamps = true;
    protected $fillable = array('name', 'value');

    public static function get($name)
    {
        return Setting::where('name', '=', $name)->pluck('value');
    }

}