<?php

declare(strict_types=1);

namespace wiperawa\mapper\tests\dto;


class NestedDto {
    public $field ;
}

class NestedModelDto {

    public $firstname;
    public $middle;
    public $lastname;

    public $nestedField;

    public $nestedDto;

    public function __construct()
    {
        $this->nestedDto = new NestedDto();
    }
}