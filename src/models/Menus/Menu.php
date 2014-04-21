<?php

namespace Cms\Models;

use Illuminate\Support\Facades\DB;
use Cms\Models\Page;

class Menu extends \Eloquent {

   protected $table = 'menus';
   protected $primaryKey = "id";
   public $timestamps = TRUE;

   public static function getMenuIdBySlug($menu_slug) {
      return DB::table("menus")
            ->where("slug", "=", $menu_slug)
            ->pluck('id');
   }

}