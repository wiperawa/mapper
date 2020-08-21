<?php
declare(strict_types=1);
namespace wiperawa\mapper\tests\models;

use wiperawa\mapper\traits\ExtractTrait;

class ClosureModel {
    use ExtractTrait;

    private $firstname = 'firstname';
    private $lastname = 'lastname';


    private function fieldsToExtract(): array{
        return [
            'name' => function( ClosureModel $current){
                return $current->firstname.' '.$current->lastname;
            },
            'address' => [
                'address' => function () {
                    return 'lenina';
                },
                'city' => function () {
                    return 'moscow';
                }
            ]
        ];
    }
}