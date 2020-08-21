<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\tests\dto\SimpleDto;
use wiperawa\mapper\tests\models\SimpleModel;

class FlatMapingTest extends TestCase {

    public function testFlatMap(){
        $model = new SimpleModel();

        $dto = $model->extract(SimpleDto::class);

        $this->assertEquals('firstname',$dto->firstname);
        $this->assertEquals('middle',$dto->middle);
        $this->assertEquals('lastname',$dto->lastname);
    }

    public function testFlatMapToArray(){
        $model = new SimpleModel();

        $dto = $model->extract();

        $this->assertEquals('firstname',$dto['firstname']);
        $this->assertEquals('middle',$dto['middle']);
        $this->assertEquals('lastname',$dto['lastname']);
    }
}
