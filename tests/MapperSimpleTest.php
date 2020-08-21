<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\dto;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\Mapper;

class SimpleDto
{

    public $firstname;
    public $middle;
    public $lastname;

}

class SimpleModel
{

    public $firstname = 'firstname';
    public $middle = 'middle';
    public $lastname = 'lastname';
}

class MapperSimpleTest extends TestCase
{

    public function testSimpleMap(){
        $model = new SimpleModel();
        $mapper = new Mapper();
        $dto = $mapper->extract($model,SimpleDto::class);

        $this->assertEquals('firstname',$dto->firstname);
        $this->assertEquals('middle',$dto->middle);
        $this->assertEquals('lastname',$dto->lastname);
    }

    public function testSimpleMapToArray(){
        $model = new SimpleModel();
        $mapper = new Mapper();

        $dto = $mapper->extract($model);

        $this->assertEquals('firstname',$dto['firstname']);
        $this->assertEquals('middle',$dto['middle']);
        $this->assertEquals('lastname',$dto['lastname']);
    }

    public function testIgnoredFields(){
        $model = new SimpleModel();
        $mapper = (new Mapper())
            ->setIgnoredFields(['middle']);

        $dto = $mapper->extract($model);

        $this->assertEquals('firstname',$dto['firstname']);
        $this->assertFalse(isset($dto['middle']));
        $this->assertEquals('lastname',$dto['lastname']);
    }


}