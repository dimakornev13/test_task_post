<?php
namespace App\Modules\PostOffice\Entity\Item;

class Wrapper extends ItemAbstract
{
    public const TYPE_ITEM = 'Wrapper';

    /**
     * @param int $expirationDay
     */
    public function __construct(int $expirationDay)
    {
        parent::__construct(self::TYPE_ITEM, $expirationDay);
    }
}
