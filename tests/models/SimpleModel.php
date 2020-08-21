<?php
declare(strict_types=1);

namespace wiperawa\mapper\tests\models;

use wiperawa\mapper\traits\ExtractTrait;

class SimpleModel {
use ExtractTrait;

    public $firstname = 'firstname';

    public $middle = 'middle';

    public  $lastname = 'lastname';

}