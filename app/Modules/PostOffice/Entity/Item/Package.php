<?php
namespace App\Modules\PostOffice\Entity\Item;

class Package extends ItemAbstract
{
    public const TYPE_ITEM = 'Package';

    /**
     * @param int $expirationDay
     */
    public function __construct(int $expirationDay)
    {
        parent::__construct(self::TYPE_ITEM, $expirationDay);
    }
}
