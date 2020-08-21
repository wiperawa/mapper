<?php

declare(strict_types=1);

namespace wiperawa\mapper\tests\dto;

class AddressSetterDto {

    private $_city ;

    public function setCity(string $city) {
        $this->_city = $city;
    }

    public function getCity(): ?string{
        return $this->_city;
    }
    public function __set($name, $value) {
        throw new \Exception('Property "'.$name.'" does not exist');
    }
}