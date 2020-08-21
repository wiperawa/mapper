<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests;

use PHPUnit\Framework\TestCase;
use wiperawa\mapper\Mapper;

class ClosureModel
{

    public $firstname = 'firstname';
    public $lastname = 'lastname';

}

class ClosureDto
{
    public $name;
    public $address;
}

class ClosureTest extends TestCase
{

    private $mapper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->mapper = (new Mapper())
            ->setMap([
                'name' => function ($current) {
                    return $current->firstname . ' ' . $current->lastname;
                },
                'address' => [
                    'address' => function () {
                        return 'lenina';
                    },
                    'city' => function () {
                        return 'moscow';
                    }
                ]
            ]);

        parent::__construct($name, $data, $dataName);
    }

    public function testClosureInExportFields()
    {
        $model = new ClosureModel();

        $ret = $this->mapper->extract($model);

        $this->assertEquals('firstname lastname', $ret['name']);
    }

    public function testClosureInNestedDtoField()
    {
        $model = new ClosureModel();

        $dto = $this->mapper->extract($model,ClosureDto::class);

        $this->assertEquals('moscow', $dto->address['city']);
        $this->assertEquals('lenina', $dto->address['address']);
    }

}