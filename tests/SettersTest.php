<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\Mapper;

class AddressSetterDto
{

    private $_city;

    public function setCity(string $city)
    {
        $this->_city = $city;
    }

    public function getCity(): ?string
    {
        return $this->_city;
    }

    public function __set($name, $value)
    {
        throw new \Exception('Property "' . $name . '" does not exist');
    }
}

class SetterDto {

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

class SettersModel
{

    public $name = 'firstname';
    public $city = 'moscow';

}

class SettersTest extends TestCase
{

    private $mapper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->mapper = (new Mapper())
            ->setMap([
                'name',
                'address' => [
                    AddressSetterDto::class,
                    'city' => 'city',
                ]
            ]);
        parent::__construct($name, $data, $dataName);
    }

    public function testSetters()
    {

        $model = new SettersModel();

        $dto = $this->mapper->extract($model,SetterDto::class);

        $this->assertEquals('firstname', $dto->getName());
        $this->assertEquals('moscow', $dto->address->getCity());
    }
}