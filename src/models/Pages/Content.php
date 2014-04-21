<?php

namespace Cms\Models;

class Content extends \Eloquent {

   protected $table = 'cms_page_content';
   public $timestamps = true;
   protected $touches = array('page');

   public function page()
   {
      return $this->belongsTo('Cms\Models\Page');
   }

}