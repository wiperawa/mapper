<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\models;

use wiperawa\mapper\tests\dto\AddressSetterDto;
use wiperawa\mapper\traits\ExtractTrait;

class SettersModel {
    use ExtractTrait;

    public $name = 'firstname';

    public $city = 'moscow';

    public function fieldsToExtract(){
        return [
            'name',
            'address' => [
                AddressSetterDto::class,
                'city' => 'city',
            ]
        ];
    }
}