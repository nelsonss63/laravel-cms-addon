<?php

namespace Cednet\Cms;

use Cms\Models\Content;
use Cms\Models\Menu;
use Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Lang;

use Cms\Models\Page;
use Cms\Libraries\H;

class PagesController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.edit';

    protected $rules = array(
        'page' => array(
            'menu_id' => 'required|integer',
            'title' => 'max:255',
            'body' => 'trim',
            'order' => 'numeric'
        )
    );

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Edit");
    }

    public function start() {
        return View::make('cms::edit.start');
    }

    /** Create Page */
    public function createPageStart()
    {
        View::share('title', Lang::get('cms::m.create-page'));

        return View::make('cms::edit.pages.create-page-start', array());
    }

    public function createPage($menu_id = 0, $parent_id = 0)
    {
        View::share("title", Lang::get('cms::m.create-page'));
        if(!ctype_digit(strval($menu_id)) OR !ctype_digit(strval($parent_id))
            OR $menu_id <= 0
        ) throw new Exception('Invalid direct access to Create Page');

        //TODO: check parent_id belongs to menu_id
        return View::make('cms::edit.pages.create-page', array(
            "rules" => $this->rules['page'],
            "new" => true, //enabled if yes / no else in form view file
            "page" => (object) array(
                    "controller" => "",
                    "template" => Input::get('template'), //default template is page
                    "page_id" => false,
                    "published" => 1, //Default = published
                    "publish_start" => date("Y-m-d H:i:s"),
                    "publish_end" => "",
                    "menu_id" => $menu_id,
                    "allow_dropdown" => 1, //If Dropdown navbar is allowed to show subpages or not
                    "title" => "",
                    "body" => "",
                    "link" => "",
                    "order" => "",
                ),
            "parent_id" => $parent_id
        ));
    }

    public function saveNewPage($menu_id, $parent_id)
    {
        //Create page
        $page = new Page();
        $page->controller = Input::get('controller');
        $page->template = Input::get('template');
        $page->menu_id = $menu_id;
        $page->allow_dropdown = Input::get('allow_dropdown');
        $page->parent_id = $parent_id;
        $page->link = H::createLink(Input::get('link'));
        $page->order = Input::get('order');
        $page->published = Input::get('published') ? 1 : 0;
        $page->publish_start = Input::get('publish_start');
        $page->publish_end = Input::get('publish_end');
        $page->slug = H::createPageSlug(Input::get('title'));
        $page->save();

        //Create URL
        $page->url = Page::getUrl($page->id);
        $page->save();

        ///Create New Content Version
        $content = new Content();
        $content->page_id = $page->id;
        $content->title = Input::get('title');
        $content->body = Input::get('body');
        $content->save();

        return Redirect::route('editPage', array($page->id))->with('flash_notice', Lang::get('cms::m.page-created'));
    }

    /** Edit Page */
    public function editPage($pageId = 0, $contentId = false)
    {
        $page = Page::find($pageId);
        if(!$page) return Redirect::route('edit');
        if(!ctype_digit(strval($pageId))) throw new Exception('Invalid page-ID! Must be an integer value');
        if($pageId <= 0) throw new Exception($pageId . ' is not a valid page-ID');
        if(is_numeric($contentId) AND $contentId > 0) {
            $page = Page::
                join("content", "content.page_id", "=", "pages.id")
                ->where(function ($query) use ($contentId) {
                    $query->where('content.content_id', '=', $contentId);
                })->first();
            if(empty($page->id)) Throw new Exception('Invalid Content-ID');
        } else {
            $page = Page::find($pageId);
            if(empty($page->id)) Throw new Exception('Invalid page-ID');
            $page->title = $page->content->title;
            $page->body = $page->content->body;
            $page->content_id = $page->content->content_id;
        }
        View::share("title", Lang::get('cms::m.edit-page') . " - " . $page->content->title);

        return View::make('cms::edit.pages.edit-page', array(
            "rules" => $this->rules['page'],
            "page" => $page,
            "history" => Page::history($pageId),
            "current_content_id" => $page->content_id
        ));
    }

    public function savePage($pageId)
    {
        //Update Page
        $page = Page::find($pageId);
        //Alter menu only if menu_id is in Input
        if(Input::get('menu_id') > 0) {
            //Set parent_id to 0 if new menu_is not same as old
            if($page->menu_id != Input::get('menu_id')) {
                $page->parent_id = 0;
            }
            $page->menu_id = Input::get('menu_id');
        }
        $page->allow_dropdown = Input::get('allow_dropdown');
        //$page->parent_id = $parent_id;
        $page->controller = Input::get('controller');
        $page->template = Input::get('template');
        $page->link = H::createLink(Input::get('link'));
        $page->order = Input::get('order');
        $page->published = Input::get('published');
        $page->publish_start = Input::get('publish_start');
        $page->publish_end = Input::get('publish_end');
        $page->slug = H::createPageSlug(Input::get('title'), $pageId);
        $page->save();

        //Modify URL after save according to new info
        $page->url = Page::getUrl($page->id);
        $page->save();

        //Create New Content Version
        $content = new Content;
        $content->page_id = $page->id;
        $content->title = Input::get('title');
        $content->body = Input::get('body');
        $content->save();

        return Redirect::route('editPage', array($pageId))->with('flash_notice', Lang::get('cms::m.saved'));
    }

    /** Delete Page */
    public function removePage($pageId)
    {
        Page::find($pageId)->delete();

        return Redirect::back()->with('flash_notice', Lang::get('cms::m.deleted'));
    }

    /** Mark as Home */
    public function MarkAsHome($pageId)
    {
        $page = Page::find($pageId);
        if(!empty($page->id)) {
            //remove any other page as home
            Page::where("is_home", "=", 1)->update(array('is_home' => 0));
            //Set new page as home
            $page->is_home = 1;
            $page->save();
        }
    }



    /**
     * Unsorted Pages = pages without meny assignment
     * @return mixed
     */
    public function unsortedPages()
    {
        View::share('title', Lang::get('cms::m.unsorted-pages'));

        return View::make('cms::edit.unsorted-pages', array(
            "actions" => array(
                "" => "",
                "remove" => Lang::get('cms::m.remove-permanently'),
            ),
            "unsorted" => Page::unsorted()->get()
        ));
    }

    /**
     * Removes Pages and its Content
     * @return mixed
     */
    public function saveUnsortedPages()
    {
        switch (Input::get('action')) {
            case 'remove':
                if(count(Input::get('page')) > 0) {
                    Content::whereIn("page_id", Input::get('page'))->delete();
                    Page::whereIn("id", Input::get('page'))->delete();
                }
                break;
        }
        return Redirect::route('unsortedPages');
    }

}