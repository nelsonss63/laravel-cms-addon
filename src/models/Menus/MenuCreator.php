<?php

namespace Cms\Models;

class MenuCreator
{


    /**
     * Navbar functionality (based on a meny / section)
     * @param string $menu_id
     * @param int $parent_id
     * @param string $class TB class is default but can be altered from view file
     * @param int $depth Current depth in loop
     * @return string
     */
    public static function navbar($menu_id, $parent_id = 0, $class = "nav", &$depth = 1)
    {
        //Hämta ID om slug
        if(!is_numeric($menu_id)) {
            $menu_id = self::getMenuIdBySlug($menu_id);
        }

        $pages = Page::published()->where("parent_id", "=", $parent_id)
            ->where("id", "=", $menu_id)
            ->get();

        $html = '<ul class="' . $class . '">
      ';

        foreach($pages as $page) {
            //Link (any URL) or internal URL using slugs
            $link = (!empty($page->link)) ? $page->link : $page->url;
            //Some pages does not allow or need dropdown
            if($page->allow_dropdown) {
                $subpages = Page::published()->where("parent_id", "=", $page->id)
                    ->where("id", "=", $menu_id)
                    ->get();
                if(count($subpages) > 0 AND $depth <= 1) {
                    $depth++;
                    $a = '<a href="' . $link . '" class="dropdown-toggle js-activated" data-toggle="dropdown">' . $page->content->title . '
               <b class="caret"></b>
               </a>
               ';
                    $html .= '<li class="dropdown">' . $a . '
                  ' . self::navbar($menu_id, $page->id, "dropdown-menu", $depth) . '
               </li>
               ';
                } else {
                    $a = '<a href="' . $page->url . '">' . $page->content->title . '</a>
               ';
                    $html .= "<li>" . $a . "</li>
               ";
                }
            } else {
                $a = '<a href="' . $page->url . '">' . $page->content->title . '</a>
            ';
                $html .= "<li>" . $a . "</li>
            ";
            }
        }
        $depth = 1;

        $html .= "</ul>";

        return $html;
    }

    /**
     * Fetches Main Nav
     */
    public static function mainNav(Page $page)
    {
        if(!empty($page->id_as_menu)) {
            $mainNav = self::generateMainNav($page->id_as_menu);
        } elseif(!empty($page->id)) {
            $mainNav = self::generateMainNav($page->getTopParent());
        } else {
            $mainNav = self::generateMainNav();
        }

        return $mainNav;
    }

    /**
     * Menu Tree for Page
     * @param string $menu_id
     * @param int $parentId (minst 1, så ej huvudsida tas med)
     * @param string $class TB class is default but can be altered from view file
     * @return string
     */
    public static function generateMainNav($parentId = 1, $class = "nav nav-list")
    {
        //Hämta sidor
        $pages = Page::with("content")->published()->where("parent_id", "=", $parentId)->get();

        $html = '<ul class="' . $class . '">';

        // Skapa menyträd
        foreach($pages as $page) {
            //Visa endast undersidor till sidor med undersidor
            $a = "<a href=\"" . $page->url . "\">" . $page->content->title . "</a>";
            if(Page::published()->where("parent_id", "=", $page->id)->count() > 0) {
                $html .= "<li>" . $a . "
                  " . self::generateMainNav($page->id) . "
               </li>";
            } else {
                $html .= "<li>" . $a . "</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }

    /**
     * Menu Tree for Edit
     * Uses jQuery TreeView
     * @param $menu_id
     * @param int $parent_id
     * @param string $class TB class is default but can be altered from view file
     * @return string
     */
    public static function treeview($menu_id, $parent_id = 0, $sub = false)
    {
        //Hämta ID om slug
        if(!is_numeric($menu_id)) {
            $menu_id = self::getMenuIdBySlug($menu_id);
        }

        //Hämta sidor
        $pages = Page::with("content")->where("parent_id", "=", $parent_id)
            ->where("id", "=", $menu_id)
            ->get();

        $html = "";

        // Skapa menyträd
        foreach($pages as $page) {
            $a = "<a href=\"".route('editPage', array($page->id)) . "\" data-title=\"" . $page->content->title . "\" class=\"edit_link\" data-menuid=\"" . $page->menu_id . "\" data-pageid=\"" . $page->id . "\" data-parentid=\"" . $page->parent_id . "\">" . $page->content->title . "</a>";
            $subpages = Page::where("parent_id", "=", $page->id)
                ->where("id", "=", $menu_id)
                ->get();
            if(count($subpages) > 0) {
                $html .= "<li class=\"expandable " . ($page->is_home ? ' is_home' : '') . "\"><div class=\"hitarea expandable-hitarea\"></div>" . $a . "
            <ul style=\"display: none;\">
               " . self::treeview($menu_id, $page->id, true) . "
            </ul></li>";
            } else {
                $html .= "<li" . ($page->is_home ? ' class="is_home"' : '') . ">" . $a . "</li>";
            }
        }

        return $html;
    }

    public static function jstree($parent_id = 0, $class = "")
    {
        //Hämta sidor
        $pages = Page::with("content")->where("parent_id", "=", $parent_id)->get();

        $html = '<ul>';

        // Skapa menyträd
        foreach($pages as $page) {
            $a = "<a href=\"" . route('editPage', array($page->id)) . "\">" . $page->content->title . "</a>";
            if(Page::where("parent_id", "=", $page->id)->count() > 0) {
                $html .= "<li id=\"" . $page->id . "\" data-menuid=\"" . $page->menu_id . "\" data-pageid=\"" . $page->id . "\" data-parentid=\"" . $page->parent_id . "\">" . $a . "
               " . self::jstree($page->id) . "
            </li>";
            } else {
                $html .= "<li id=\"" . $page->id . "\" data-menuid=\"" . $page->menu_id . "\" data-pageid=\"" . $page->id . "\" data-parentid=\"" . $page->parent_id . "\">" . $a . "</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }

    public static function jstree_menus($menu_id, $parent_id = 0, $class = "")
    {
        //Hämta ID om slug
        if(!is_numeric($menu_id)) {
            $menu_id = self::getMenuIdBySlug($menu_id);
        }

        //Hämta sidor
        $pages = Page::where("parent_id", "=", $parent_id)->where("id", "=", $menu_id)->get();

        $html = '<ul>';

        // Skapa menyträd
        foreach($pages as $page) {
            $a = "<a href=\"" . route('editPage', array($page->id)) . "\">" . $page->content->title . "</a>";
            if(Page::where("parent_id", "=", $page->id)->where("menu_id", "=", $menu_id)->count() > 0) {
                $html .= "<li id=\"" . $page->id . "\" data-menuid=\"" . $page->menu_id . "\" data-pageid=\"" . $page->id . "\" data-parentid=\"" . $page->parent_id . "\">" . $a . "
               " . self::jstree($menu_id, $page->id) . "
            </li>";
            } else {
                $html .= "<li id=\"" . $page->id . "\" data-menuid=\"" . $page->menu_id . "\" data-pageid=\"" . $page->id . "\" data-parentid=\"" . $page->parent_id . "\">" . $a . "</li>";
            }
        }

        $html .= "</ul>";

        return $html;
    }

}