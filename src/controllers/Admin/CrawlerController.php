<?php

namespace Cednet\Cms;

use Cms\Models\Page;
use Cms\Models\Crawler;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class CrawlerController extends \Cednet\Cms\CmsAdminBaseController
{

    protected $layout = 'cms::layouts.admin';
    protected $available_maintenances = array();
    protected $nav = array();

    public function __construct()
    {
        parent::__construct();
        View::share("title", "Admin");
    }

    public function crawler($action = "")
    {
        $action = Input::get('action') ? Input::get('action') : $action;

        //Available Maintenance Functions
        $this->available_maintenances = array(
            'recreateurls' => Lang::get('cms::m.recreate-urls'),
            'convertToPages' => Lang::get('cms::m.crawl-reate-pages'),
            //'crawl' => Lang::get('cms::m.crawl-a-url'), //on page form instead
        );

        Return View::make('cms::admin/maintenance', array(
            "available_maintenances" => $this->available_maintenances
        ));

    }

    public function runCrawler()
    {
        switch (Input::get('action')) {
            case 'recreateurls':
                foreach(Page::all() as $page) {
                    $page->url = Page::getUrl($page->id);
                    $page->save();
                }
                die("Recreated URL:s");
                break;
            case 'crawl':
                Crawler::url(Input::get('crawl_url'), Input::get('crawl_found_links') ? true : false);
                if(Input::get('crawl_convert')) Crawler::createPages();
                break;
            case 'convertToPages':
                Crawler::convertToPages();
                break;
            default:
                return Response::json('Invalid action', 400);
                break;
        }
        if(Request::ajax()) {
            return Response::json(Lang::get('cms::m.crawler-done'), 200);
        } else {
            return Redirect::route('crawler')->with('flash_notice', Lang::get('cms::m.crawler-done'));
        }
    }

}