<?php

class CmsPassword {

    private $value;

    public function __construct($value) {

        //Just require max length 30 for now...
        if(strlen($value) > 30) {
            throw new InvalidArgumentException('Invalid Username');
        }

        $this->value = $value;

    }

    public function __toString() {
        return $this->value;
    }
}