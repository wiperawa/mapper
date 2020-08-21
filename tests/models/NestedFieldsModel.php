<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\models;

use wiperawa\mapper\traits\ExtractTrait;

class NestedModel {
    public $field = 'field';
}

class NestedFieldsModel {
    use ExtractTrait;

    public $firstname = 'firstname';

    public $middle = 'middle';

    public  $lastname = 'lastname';

    public $nestedFieldInDto = 'nested_field_in_dto';

    public $nestedModel;

    public function __construct()
    {
        $this->nestedModel = new NestedModel();
    }

    private function fieldsToExtract(){
        return [
            'firstname',
            'lastname',
            'middle',
            'nestedField' => 'nestedModel.field',
            'nestedDto.field' => 'nestedFieldInDto',
        ];
    }
}