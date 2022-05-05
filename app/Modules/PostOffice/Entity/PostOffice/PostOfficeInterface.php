<?php

namespace App\Modules\PostOffice\Entity\PostOffice;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use App\Modules\PostOffice\Entity\Postman\PostmanAbstract;

interface PostOfficeInterface
{
    /**
     * @param PostmanAbstract[] $postmen
     */
    public function __construct(array $postmen);

    /**
     * Good time for filling postmen
     * @param ItemAbstract[] $items
     * @return PostmanAbstract[]
     */
    public function liveDay(array $items = []): array;

    /**
     * @return bool
     */
    public function isEmptyItemsQueue(): bool;

    /**
     * @return bool
     */
    public function isAllItemsDelivered(): bool;
}
