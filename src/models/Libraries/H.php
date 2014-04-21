<?php

namespace Cms\Libraries;

use Cms\Models\Menu;
use Illuminate\Support\Facades\Input;

class H {
   public static function createPageSlug($string, $pageId = 0, $loop = 1) {
      $slug = trim($string);
      $slug = strtolower($slug);
      $slug = str_replace(array('å', 'ä', 'ö', ' '), array('a', 'a', 'o', '-'), $slug);
      $slug = preg_replace("/[^a-z0-9-]/", "", $slug);
      $slug = preg_replace("/[-]+/", "-", $slug);
      if(\Cms\Models\Page::published()->where('slug', '=', $slug)->where("id", "!=", $pageId)->count()) {
         $loop++;
         $slug = self::createPageSlug($loop."-".$string, $pageId, $loop); //add page_id to ensure is unique
      }
      return $slug;
   }

   public static function createMenuSlug($string, $menu_id = 0, $loop = 1) {
      $slug = trim($string);
      $slug = strtolower($slug);
      $slug = str_replace(array('å', 'ä', 'ö', ' '), array('a', 'a', 'o', '-'), $slug);
      $slug = preg_replace("/[^a-z0-9-]/", "", $slug);
      $slug = preg_replace("/[-]+/", "-", $slug);
      if(Menu::where('slug', '=', $slug)->where("id", "!=", $menu_id)->count()) {
         $loop++;
         $slug = self::createMenuSlug($loop."-".$string, $menu_id, $loop); //add page_id to ensure is unique
      }
      return $slug;
   }

   public static function createLink($link = "") {
      if(!empty($link) AND strlen($link) > 0) {
         if(!preg_match("/http/is", $link)) {
            $link = "http://".$link;
         }
      }
      return $link;
   }

   public static function strip_tags_content($text, $tags = '', $invert = FALSE) {

      preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
      $tags = array_unique($tags[1]);

      if(is_array($tags) AND count($tags) > 0) {
         if($invert == FALSE) {
            return preg_replace('@<(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
         }
         else {
            return preg_replace('@<('. implode('|', $tags) .')\b.*?>.*?</\1>@si', '', $text);
         }
      }
      elseif($invert == FALSE) {
         return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
      }
      return $text;
   }

   /*
 * Method to strip tags globally.
 */
   public static function globalXssClean()
   {
      // Recursive cleaning for array [] inputs, not just strings.
      $sanitized = static::arrayStripTags(Input::get());
      Input::merge($sanitized);
   }

   public static function arrayStripTags($array)
   {
      $result = array();

      foreach ($array as $key => $value) {
         // Don't allow tags on key either, maybe useful for dynamic forms.
         $key = strip_tags($key);

         // If the value is an array, we will just recurse back into the
         // function to keep stripping the tags out of the array,
         // otherwise we will set the stripped value.
         if (is_array($value)) {
            $result[$key] = static::arrayStripTags($value);
         } else {
            // I am using strip_tags(), you may use htmlentities(),
            // also I am doing trim() here, you may remove it, if you wish.
            $result[$key] = trim(strip_tags($value));
         }
      }

      return $result;
   }
}