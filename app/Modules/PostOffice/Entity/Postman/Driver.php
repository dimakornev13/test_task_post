<?php

namespace App\Modules\PostOffice\Entity\Postman;

use App\Modules\PostOffice\Enums\ItemType;
use App\Modules\PostOffice\Enums\PostmanType;

class Driver extends PostmanAbstract
{
    public function __construct()
    {
        $limits = [
            ItemType::Letter  => 1,
            ItemType::Wrapper => 2,
            ItemType::Package => 3,
        ];

        parent::__construct(PostmanType::Driver, $limits);
    }
}
