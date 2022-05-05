<?php
namespace App\Modules\PostOffice\Entity\Item;

class Letter extends ItemAbstract
{
    public const TYPE_ITEM = 'Letter';

    /**
     * @param int $expirationDay
     */
    public function __construct(int $expirationDay)
    {
        parent::__construct(self::TYPE_ITEM, $expirationDay);
    }
}
