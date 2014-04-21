<?php

namespace Cednet\Cms;

use Cms\Models\Setting;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use App;
use Cms\Models\Page;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Cms\Libraries\H;

class PageController extends CmsBaseController
{

    public function __construct()
    {
        parent::__construct();

        //Breadcrumbs
        View::share('breadcrumbs', array());

    }

    public function homepage()
    {
        $page = Page::home()->first();

        $this->setViewVariables($page);

        return View::make("cms::templates." . $page->template);
    }

    /**
     * Page Controller
     * Fetches URL segments, and uses last segment as page slug
     * @return mixed
     */
    public function page()
    {
        $page = $this->getPageBySlug();

        //Missing? Send to 404 page
        if(is_null($page))
            App::abort(404);

        //Is Link?
        if($page->link) {
            return Redirect::to($page->link);
        }

        //Is redirect to a Controller?
        //NOTE: This will only display if there is no route defined
        //Because if Route is defined, the script will never be here anyways...
        if($page->controller) {
            return Redirect::route(strtolower(trim($page->controller)));
        }

        $this->setViewVariables($page);

        return View::make("cms::templates." . $page->template);
    }

    public function login()
    {
        View::share('title', Lang::get('cms::m.login'));

        return View::make('cms::login');
    }

    public function search()
    {
        $q = H::strip_tags_content(Input::get('q'));
        View::share('title', Lang::get('cms::m.search-result-for') . " " . $q);

        return View::make('cms::search', array(
            "q" => $q,
            "result" => Page::search($q)
        ));
    }

    /**
     * @return mixed
     */
    private function getPageBySlug()
    {
        $path = Request::path(); //ie whatever/subpage/and-so-on
        $slugs = explode("/", $path);
        $first_slug = $slugs['0'];
        $last_slug = $slugs[count($slugs) - 1];
        $slug = $last_slug;

        //Page by Slug
        $page = Page::with("content")->published()->where('slug', '=', $slug)->first();

        return $page;
    }

    /**
     * @param $page
     */
    private function setViewVariables($page)
    {
        //Which URL to extend
        View::share('extendUrl', Setting::get('extend_url_template_page'));

        View::share('page', $page);
        View::share('title', $page->content->title);

        //only allow page edit in a page with a page_id
        View::share('allow_edit_page', $page->id ? true : false);

        //Breadcrumbs
        View::share('breadcrumbs', \Cms\Models\Page::subPages($page->id));

    }

}