<?php

namespace Cednet\Cms;

use Cms\Models\Content;
use Cms\Models\Menu;
use Exception;
use Former\Facades\Former;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Lang;

use Cms\Models\Page;
use Cms\Libraries\H;

class MenusController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.edit';
    protected $nav = array();

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Menus");
    }

    /** Create Menu */
    public function menu($menu_id = 0)
    {
        if($menu_id) {

            $menu = Menu::find($menu_id);
            if(!$menu) return Redirect::route('edit');
            View::share("title", Lang::get('cms::m.update-menu') . ": " . $menu->title);

            Former::populate($menu);

        } else {

            $menu = new Menu();
            View::share("title", Lang::get('cms::m.create-menu'));

        }

        return View::make('cms::edit.menus.menu', array(
            "menu" => $menu
        ));
    }

    public function saveMenu($menu_id = 0)
    {
        if($menu_id) {
            $menu = Menu::find($menu_id);
        } else {
            $menu = new Menu();
        }
        $menu->title = Input::get('title');
        $menu->edit_order = Input::get('edit_order');
        $menu->slug = H::createMenuSlug(Input::get('title'), $menu_id);
        $menu->save();

        return Redirect::route('editMenu', array($menu->id))->with('flash_notice', Lang::get('cms::m.saved'));
    }

    /**
     * Removing a Menu must also reset all pages to menu_id = 0 (put in Unsorted Pages)
     * @param $menu_id
     * @return mixed
     */
    public function removeMenu($menu_id)
    {
        //Remove
        $menu = Menu::find($menu_id);
        $menu->delete();
        //Set any pages with current menu_id to menu_id = 0 = Unsorted, also set new parent_id to 0
        $pages = Page::where("id", "=", $menu_id)->update(array(
            'menu_id' => 0,
            'parent_id' => 0
        ));

        return Redirect::route('edit')->with('flash_notice', Lang::get('cms::m.menu-removed'));
    }

    /** AJAX */
    public function getTreeviewData($menu_slug)
    {
        return Menu::jqtree($menu_slug);
        exit;
        if(Request::ajax()) {
            return Menu::getEditTree($menu_slug);
        } else {
            Throw new exception('Invalid request - Must be an Ajax call!');
        }
    }

}