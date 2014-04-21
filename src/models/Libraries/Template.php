<?php

namespace Cms\Libraries;

use Illuminate\Support\Facades\File;

class Template {

    /**
     * Fetches all template files in views/templates
     * Cuts out blade and extension stuff
     * @return array
     */
    public static function getTemplateOptions()
    {
        $templateFiles = File::files(__DIR__.'/../../views/templates');
        $array = [];
        foreach($templateFiles as $template) {
            $file = pathinfo($template);
            list($filename) = explode(".", $file['basename']);
            $array[$filename] = ucfirst($filename);
        }
        return $array;
    }

}