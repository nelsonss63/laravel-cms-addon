<?php

namespace Cms\Models;

use Illuminate\Support\Facades\DB;
use Cms\Models\Page;

class Menu extends \Eloquent
{

    protected $table = 'cms_menus';
    protected $primaryKey = "id";
    protected $fillable = array('slug', 'title', 'edit_order');
    public $timestamps = true;

    public static function getMenuIdBySlug($menu_slug)
    {
        return self::where("slug", "=", $menu_slug)->pluck('id');
    }

}