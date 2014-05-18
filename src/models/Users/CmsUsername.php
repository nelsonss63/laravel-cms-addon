<?php

class CmsUsername {

    private $value;

    public function __construct($value) {

        if(!ctype_alnum($value)) {
            throw new InvalidArgumentException('Invalid Username');
        }

        $this->value = $value;

    }

    public function __toString() {
        return $this->value;
    }
}