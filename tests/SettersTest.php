<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\tests\dto\SettersDto;
use wiperawa\mapper\tests\models\SettersModel;

class SettersTest extends TestCase {

    public function testSetters (){

        $model = new SettersModel();

        $dto = $model->extract(SettersDto::class);

        $this->assertEquals('firstname',$dto->getName());
        $this->assertEquals('moscow',$dto->address->getCity());
    }
}