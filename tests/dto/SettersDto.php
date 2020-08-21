<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\dto;


class SettersDto {

    private $_name;

    public $address;


    public function setName(string $name){
        $this->_name = $name;
    }

    public function getName(): ?string  {
        return $this->_name;
    }

    public function __set($name, $value) {
        throw new \Exception('Property "'.$name.'" does not exist');
    }
}