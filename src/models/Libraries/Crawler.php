<?php

namespace Cms\Models;

use Cms\Libraries\H;
use Cms\Models\Crawl;
use Cms\Models\Page;
use Illuminate\Support\Facades\Input;
use Whoops\Example\Exception;

/**
 * Custom Crawler for SMC
 * Crawls a complete domain and creates a table of content in a database
 */

class Crawler {

   public static $base_url = "http://www.svmc.se";
   public static $crawled_urls = array();
   public static $found_links = array();

   /**
    * Crawls a URL
    * @param $url
    * @param bool $crawl_found_links (TRUE or FALSE)
    */
   public static function url($url, $crawl_found_links, $digging = FALSE) {
      //Allow to run forever
      ini_set('max_execution_time', 0);
      ini_set('set_time_limit', 0);

      //Start URL
      $start_url = self::prepUrl(Input::get('crawl_url'));

      //Current URL to crawl
      $url = self::prepUrl($url);

      //Only do subpages of current URL?
      if(Input::get('crawl_only_subpages')) {
         if(substr($url,0,strlen($start_url)) !== $start_url) {
            return;
         }
      }


      if(in_array($url, self::$crawled_urls)) return; //don't process already processed URL:s
      self::$crawled_urls[] = $url; //mark URL as processed

      try {
         //Get HTML content from URL
         if(substr($url,0,1) =="/") {
            $html = file_get_contents(self::$base_url.$url); //Add http://XXXXXX
         } else {
            $html = file_get_contents($url);
         }
      } catch(Exception $e) {
         return; //only stop, so it can continue the loop
         Throw new Exception('Invalid URL: '.$url);
      }

      //Title
      preg_match('/<title>(.*?)<\/title>/is', $html, $titles);
      $title = trim($titles['1']);

      //Content (main)
      $content = "";
      preg_match('/<div class="content_left_page_wrapper">(.*?)<div class="content_right_page_container">/is', $html, $found_content);
      if(!empty($found_content['0'])) {
         //strip unwanted stuff
         $content = str_replace("  ", "", $found_content['0']);
         $content = str_replace('<div class="content_left_page_wrapper">', "", $content);
         $content = str_replace("\n", "", $content);
         $content = str_replace('</div></div>					 <div class="content_right_page_container">', "", $content);
         $content = preg_replace('/id="ctl00(.+?)"/is', "", $content);
         $content = trim($content);
      }

      //Content (right column)
      //$content_right = $html->find('div.content_right_page_wrapper')->plaintext;
      $content_right = "";
      preg_match('/<div class="content_right_page_wrapper">(.*?)<\!-- content end -->/is', $html, $found_content);
      if(!empty($found_content['0'])) {
         //strip unwanted stuff
         $content_right = str_replace("  ", "", $found_content['0']);
         $content_right = str_replace('<div class="content_right_page_wrapper">', "", $content_right);
         $content_right = str_replace("\r", "", $content_right);
         $content_right = str_replace("\n", "", $content_right);
         $content_right = str_replace('</div></div> </div> <!-- content end -->', "", $content_right);
         $content_right = preg_replace('/id="ctl00(.+?)"/is', "", $content_right);
         $content_right = trim($content_right);
      }

      //Has form? (class=xform)
      $has_form = preg_match("/xform/is", $content.$content_right) ? TRUE : FALSE;

      //Store crawl
      $crawl = Crawl::where("url", "=", $url)->first();
      if(!$crawl) {
         $crawl = new Crawl;
         $crawl->url = strtolower($url);
         $slugs = explode("/", strtolower($url));
         $crawl->slug = $slugs[count($slugs)-1];
      } else {
         //Changed / Updated content? Increase updated_count
         if($content !== $crawl->content OR $content_right !== $crawl->content_right) {
            $crawl->updated_count = $crawl->updated_count+1;
         }
      }
      $crawl->published = Input::get('crawl_published') ? 1 : 0;
      $crawl->menu_id = Input::get('crawl_menu_id') ? Input::get('crawl_menu_id') : 0;
      $crawl->parent_id = Input::get('crawl_parent_id') ? Input::get('crawl_parent_id') : 0;
      $crawl->title = $title;
      $crawl->content = $content;
      $crawl->content_right = $content_right;
      $crawl->has_form = $has_form;
      $crawl->save();
      $crawl_id = $crawl->id;

      //Get all links and save to database
      $links = array();
      //remove any current URLs for current crawl (so we don't get duplicates)
      DB::table('crawl_found_links')->where("crawl_id", "=", $crawl->id)->delete();
      //Get links from current URL (the whole page)
      preg_match_all('~a href=("|\')(.*?)\1~', $html, $all_links);
      $used_count_index = array_count_values($all_links['2']);
      $found_links = array();
      //Only store INTERNAL links and NOT media, etc
      if($crawl_id) {
         foreach($all_links['2'] as $link) {
            if(!$link) return;
            $used_count = $used_count_index[$link];
            $pathinfo = pathinfo($link);
            if(
               !preg_match("/\#|javascript|mailto/is", $link)
               AND
               !preg_match("/jpg|png|pdf|xls|gif|zip|rar|php|css|js|txt|inc|jar|xss|xml|xls|bmp|csv/is", substr(preg_replace("/[^A-Z]/", "", $link),-3,3))
               AND @is_null($pathinfo['extension'])
            ) {
               $skip = FALSE;
               //Check full URL:s to ensure they are "internal"
               if(preg_match("/http|:\/\//is", $link)) {
                  if(!preg_match("/www.svmc.se|\/\/svmc.se/is", $link)) {
                     $skip = TRUE;
                  }
               }
               //Save for storage if not invalid (skip = true) and is not in current loop, and not run before in previous crawled url
               if(!$skip AND !in_array($link, $found_links) AND !in_array($link, self::$found_links)) {
                  $links[] = array(
                     "crawl_id" => $crawl_id,
                     "link" => trim($link),
                     "used_count" => $used_count
                  );
                  $found_links[] = $link;
                  self::$found_links[] = $link;
               }
            }
         }
         //Insert links to relations table
         if(count($found_links) > 0) DB::table('crawl_found_links')->insert($links);
      }

      //Crawl all stored links
      if($crawl_found_links AND count($links) > 0) {
         foreach($links as $link) {
            self::url($link['link'], $crawl_found_links, TRUE);
         }
      } else {
         return;
      }
   }

   private static function prepUrl($url) {
      if(strpos($url, "?")) { $url = explode("?", $url); $url = $url['0']; } //No Query strings
      $url = str_replace(self::$base_url, "", $url);
      if(substr($url,-1) == "/") $url = substr($url,0,-1); //Remove ending slash
      return $url;
   }

   /**
    * Creates Pages from Crawl Data
    * Only not already converted
    */
   public static function convertToPages() {
      $crawlData = Crawl::whereNull("converted")->get();
      foreach($crawlData as $crawl) {
         Page::createPage(array(
            "page" => array(
               "published" => $crawl->published,
               "menu_id" => $crawl->menu_id,
               "parent_id" => $crawl->parent_id,
               "slug" => $crawl->slug ? $crawl->slug : H::createPageSlug($crawl->title)
            ),
            "content" => array(
               "title" => $crawl->title,
               "body" => $crawl->content
            )
         ));
         $crawl->converted = date("Y-m-d H:i:s");
         $crawl->save();
      }
   }

   /** Puts all crawled and converted (unsorted pages) into their own menu hiearchy */
   public static function autoSort() {

   }

}