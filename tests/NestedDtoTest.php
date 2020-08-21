<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\tests\dto\AddressDto;
use wiperawa\mapper\tests\models\NestedDtoModel;

class NestedDtoTest extends TestCase {

    public function testNestedDtoAsFieldConfiguration(){
        $model = new NestedDtoModel();

        $ret = $model->extract();

        $this->assertInstanceOf(AddressDto::class, $ret['address']);
        $this->assertEquals('lenina', $ret['address']->address);
        $this->assertEquals('moscow', $ret['address']->city);
    }
}