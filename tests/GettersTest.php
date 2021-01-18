<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\Mapper;

class User {
    private $address;

    public function __construct($address) {
        $this->address = $address;
    }

    public function getAddress() {
        return $this->address;
    }
}

class Address
{
    private $city = null;
    private $street = null;

    public function __construct($city, $street){
        $this->city = $city;
        $this->street = $street;
    }
    public function getCity() {
        return $this->city;
    }

    public function getStreet(){
        return $this->street;
    }
}

class GettersTest extends TestCase
{

    private $mapper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->mapper = (new Mapper())
            ->setMap(['city' => 'address.city', 'street' => 'address.street']);
        parent::__construct($name, $data, $dataName);
    }

    public function testGetters()
    {

        $model = new User(new Address('Kurgan', 'Lenina'));

        $array = $this->mapper->extract($model,[]);
        var_dump($array);
        $this->assertEquals('Kurgan', $array['city']);
        $this->assertEquals('Lenina', $array['street']);
    }
}