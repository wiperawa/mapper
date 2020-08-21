<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\tests\dto\NestedModelDto;
use wiperawa\mapper\tests\models\NestedFieldsModel;


class NestedFieldsTest extends TestCase {

    public function testNestedFieldInModel () {
        $model = new NestedFieldsModel();
        $dto = $model->extract(NestedModelDto::class);

        $this->assertEquals('field', $dto->nestedField);
    }

    public function testNestedFieldInDto(){
        $model = new NestedFieldsModel();
        $dto = $model->extract(NestedModelDto::class);

        $this->assertEquals('nested_field_in_dto', $dto->nestedDto->field);
    }
}