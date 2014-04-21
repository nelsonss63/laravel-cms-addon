<?php

namespace Cms\Models;

use Illuminate\Support\Facades\DB;

class Page extends \Eloquent
{

    protected $table = "cms_pages";
    public $timestamps = true;

    public static function boot() {
        parent::boot();

        self::deleting(function($page) {
            return $page->content()->delete();
        });
    }

    public function content()
    {
        return $this->hasOne('Cms\Models\Content', 'page_id', 'id')->orderBy("updated_at", "desc");
    }

    public function menus()
    {
        return $this->hasOne('Cms\Models\Menu', 'id', 'menu_id');
    }

    public function scopeHome($query)
    {
        $query->where("is_home", "=", "1");
    }


    /**
     * Get where Published and publish end is either above current date or not set (no end of life for page)
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where("published", "=", 1)->where(function ($query) {
            $query->where("publish_end", ">", date("Y-m-d H:i:s"))
                ->orWhereNull("publish_end");
        });
    }

    public function scopeOnlyMainPages($query)
    {
        return $query->where("parent_id", "=", 0);
    }

    public function scopeUnsorted($query)
    {
        return $query->where("menu_id", "=", 0);
    }

    /**
     * @param string $keywords
     * @param bool $extended TRUE = search also in body text, otherwise only in meta (title and such)
     * @return mixed
     */
    public static function search($keywords = "", $extended = false)
    {
        $result = "";
        if(!empty($keywords)) {
            /* Query Builder */
            $result = Page::
                join("content", "content.page_id", "=", "pages.id")
                ->where(function ($query) use ($keywords, $extended) {
                    foreach(explode(" ", $keywords) as $keyword) {
                        $query->where("content.title", "LIKE", "%" . $keyword . "%");
                        if($extended) {
                            $query->where("content.body", "LIKE", "%" . $keyword . "%");
                        }
                    }
                })
                ->published()
                ->groupBy("content.page_id")
                ->orderBy("content.updated_at", "desc")
                ->get();

            return $result;
        }
    }

    /**
     * Create URL with all subpages
     * @param $pageId
     */
    public static function getUrl($pageId)
    {
        $url = "/";
        $subpages = self::subPages($pageId);
        if(count($subpages) > 0) {
            $loop = 0;
            foreach($subpages as $page) {
                if($loop > 0) $url .= "/";
                $url .= $page->slug;
                $loop++;
            }
        } else {
            $url .= self::where('id', '=', $pageId)->pluck('slug');
        }

        return $url;
    }

    /**
     * Get the top parent page ID of the current page
     */
    public function getTopParent()
    {
        $subpages = Page::subPages($this->page_id);
        if($subpages) {
            $topPage = $subpages['0'];

            return $topPage['page_id'];
        }
    }

    /**
     * Subpages of a specific page, ie breadcrumbs etc
     * @param int $pageId
     * @param array $subpages
     * @return array|bool
     */
    public static function subPages($pageId = 0, &$subpages = array())
    {
        if(!$pageId) return false;
        $page = self::with("content")->where("id", "=", $pageId)->first();
        //Dig until no more parent
        $subpages[] = $page;
        if($page->parent_id > 0) {
            self::subPages($page->parent_id, $subpages);
        } else {
            //Reverse order when hit root (parent_id = 0)
            $subpages = array_reverse($subpages);
        }

        return $subpages;
    }

    /**
     * Page History gets all versions of a page
     * @param $pageId
     */
    public static function history($pageId)
    {
        return Content::where("page_id", "=", $pageId)->orderBy("updated_at", "desc")->get();
    }

    /**
     * @param $title
     * @param array $content (array with body, and any custom data)
     * @param int $parent_id
     */
    public static function createPage($data = array(), $parent_id = 0)
    {
        $page = new Page;
        $page->publish_start = date("Y-m-d H:i");
        foreach($data['page'] as $field => $value) {
            $page->$field = $value;
        }
        $page->crawled = 1;
        $page->save();
        $pageId = $page->id;

        $content = new Content;
        $content->page_id = $pageId;
        foreach($data['content'] as $field => $value) {
            $content->$field = $value;
        }
        //$content->custom_content_right = $content['content_right'];
        $content->save();

    }
}