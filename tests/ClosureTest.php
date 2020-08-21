<?php
declare(strict_types=1);
namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\tests\dto\ClosureDto;
use wiperawa\mapper\tests\models\ClosureModel;

class ClosureTest extends TestCase {

    public function testClosureInExportFields(){
        $model = new ClosureModel();

        $ret = $model->extract();

        $this->assertEquals('firstname lastname',$ret['name']);
    }

    public function testClosureInNestedDtoField(){
        $model = new ClosureModel();

        $dto = $model->extract(ClosureDto::class);

        $this->assertEquals('moscow',$dto->address['city']);
        $this->assertEquals('lenina',$dto->address['address']);
    }

}