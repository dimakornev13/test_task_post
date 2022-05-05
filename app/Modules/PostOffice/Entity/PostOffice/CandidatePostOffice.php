<?php

namespace App\Modules\PostOffice\Entity\PostOffice;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use App\Modules\PostOffice\Entity\Postman\PostmanAbstract;

class CandidatePostOffice implements PostOfficeInterface
{
    /**
     * @param PostmanAbstract[] $postmen
     */
    public function __construct(array $postmen)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Good time for filling postmen
     * @param ItemAbstract[] $items
     * @return PostmanAbstract[]
     */
    public function liveDay(array $items = []): array
    {
        // TODO: Implement liveDay() method.
        return [];
    }

    /**
     * @return bool
     */
    public function isEmptyItemsQueue(): bool
    {
        // TODO: Implement isEmptyItemsQueue() method.
        return true;
    }

    /**
     * @return bool
     */
    public function isAllItemsDelivered(): bool
    {
        // TODO: Implement isAllItemsDelivered() method.
        return true;
    }
}
