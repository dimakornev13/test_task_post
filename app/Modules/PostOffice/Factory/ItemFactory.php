<?php

namespace App\Modules\PostOffice\Factory;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use App\Modules\PostOffice\Entity\Item\Letter;
use App\Modules\PostOffice\Entity\Item\Package;
use App\Modules\PostOffice\Entity\Item\Wrapper;
use LogicException;

class ItemFactory
{
    public const LETTER_ITEM_TYPE  = 'l';
    public const WRAPPER_ITEM_TYPE = 'w';
    public const PACKAGE_ITEM_TYPE = 'p';

    /**
     * @param $currentDay
     * @param array $itemStringList
     * @return array
     */
    public function deserializeItems($currentDay, array $itemStringList): array
    {
        $items = [];

        foreach ($itemStringList as $itemString) {
            $itemsOfType = $this->deserializeOneTypeItems($currentDay, $itemString);
            $items       = array_merge($items, $itemsOfType);
        }

        return $items;
    }

    /**
     * @param $currentDay
     * @param $typeItemString
     * @return array
     */
    private function deserializeOneTypeItems($currentDay, $typeItemString): array
    {
        [$type, $count, $lifetime] = explode('-', $typeItemString, 3);

        $item  = $this->buildItem($type, $currentDay, $lifetime);
        $items = [];

        for ($i = 0; $i < $count; $i++) {
            $items[] = clone $item;
        }

        return $items;
    }

    /**
     * @param $type
     * @param $currentDay
     * @param $lifetime
     * @return Letter|Package|Wrapper
     */
    private function buildItem($type, $currentDay, $lifetime): ItemAbstract
    {
        $expirationDay = $currentDay + $lifetime - 1;

        switch ($type) {
            case self::LETTER_ITEM_TYPE:
                return new Letter($expirationDay);
            case self::WRAPPER_ITEM_TYPE:
                return new Wrapper($expirationDay);
            case self::PACKAGE_ITEM_TYPE:
                return new Package($expirationDay);
            default:
                throw new LogicException(sprintf(
                    'Incorrect type of item "%s". Available types: "%s".',
                    $type,
                    implode('", "', [self::LETTER_ITEM_TYPE, self::WRAPPER_ITEM_TYPE, self::PACKAGE_ITEM_TYPE])
                ));
        }
    }
}
