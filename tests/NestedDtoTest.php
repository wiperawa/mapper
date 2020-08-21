<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\Mapper;

class AddressDto {

    public $address;
    public $city;

}

class NestedDtoModel {

    public $firstname = 'firstname';

    public $lastname = 'lastname';

    public $address = 'lenina';

    public $city = 'moscow';

}

class NestedDtoTest extends TestCase {

    private $mapper;
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->mapper = (new Mapper())
            ->setMap([
             'firstname',
             'lastname',
             'address' => [
                 AddressDto::class,
                 'address' => 'address',
                 'city' => 'city'
             ],
         ]);
        parent::__construct($name, $data, $dataName);
    }

    public function testNestedDtoAsFieldConfiguration(){
        $model = new NestedDtoModel();

        $ret = $this->mapper->extract($model);

        $this->assertInstanceOf(AddressDto::class, $ret['address']);
        $this->assertEquals('lenina', $ret['address']->address);
        $this->assertEquals('moscow', $ret['address']->city);
    }
}