<?php

namespace App\Modules\PostOffice\Entity\Postman;

use App\Modules\PostOffice\Enums\ItemType;
use App\Modules\PostOffice\Enums\PostmanType;

class Postman extends PostmanAbstract
{
    public function __construct()
    {
        $limits = [
            ItemType::Letter  => 3,
            ItemType::Wrapper => 1,
            ItemType::Package => 2,
        ];

        parent::__construct(PostmanType::Postman, $limits);
    }
}
