<?php

namespace Cms\Models;

class Content extends \Eloquent {

   protected $table = 'content';
   protected $primaryKey = "content_id";
   public $timestamps = true;
   protected $touches = array('page');

   public function page()
   {
      return $this->belongsTo('Cms\Models\Page');
   }

}