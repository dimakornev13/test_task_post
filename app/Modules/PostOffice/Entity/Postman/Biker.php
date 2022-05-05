<?php

namespace App\Modules\PostOffice\Entity\Postman;

use App\Modules\PostOffice\Entity\Item\Letter;
use App\Modules\PostOffice\Entity\Item\Package;
use App\Modules\PostOffice\Entity\Item\Wrapper;
use App\Modules\PostOffice\Enums\ItemType;
use App\Modules\PostOffice\Enums\PostmanType;

class Biker extends PostmanAbstract
{
    public function __construct()
    {
        $limits = [
            ItemType::Letter  => 2,
            ItemType::Wrapper => 3,
            ItemType::Package => 1,
        ];

        parent::__construct(PostmanType::Biker, $limits);
    }
}
