<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\models;

use wiperawa\mapper\tests\dto\AddressDto;
use wiperawa\mapper\traits\ExtractTrait;

class NestedDtoModel {

    use ExtractTrait;

    public $firstname = 'firstname';

    public $lastname = 'lastname';

    public $address = 'lenina';

    public $city = 'moscow';


    public function fieldsToExtract(){
        return [
            'firstname',
            'lastname',
            'address' => [
                AddressDto::class,
                'address' => 'address',
                'city' => 'city'
            ]
        ];
    }
}