<?php

namespace App\Modules\PostOffice\Entity\Postman;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use LogicException;

abstract class PostmanAbstract
{
    private string $type;
    private int    $totalLimit;
    private array  $typeItemLimits;
    /** @var ItemAbstract[]|array  */
    private array  $items = [];

    /**
     * @param string $type
     * @param array $typeItemLimits
     * @param int $totalLimit
     */
    public function __construct(string $type, array $typeItemLimits, int $totalLimit = 3)
    {
        $this->type           = $type;
        $this->typeItemLimits = $typeItemLimits;
        $this->totalLimit     = $totalLimit;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getTotalFreeSlotCount(): int
    {
        return ($this->totalLimit - $this->getItemCount());
    }

    /**
     * @param ItemAbstract $item
     * @return int
     */
    public function getItemFreeSlotCount(ItemAbstract $item): int
    {
        if (!$this->getTotalFreeSlotCount()) {
            return 0;
        }

        $itemType = $item->getType();

        return ($this->getTypeItemLimit($itemType) - $this->getTypeItemCount($itemType));
    }

    /**
     * @param ItemAbstract $item
     */
    public function putItem(ItemAbstract $item): void
    {
        $this->checkCanPutItem($item);

        $this->items[] = $item;
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        return !$this->getTotalFreeSlotCount();
    }

    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        return (bool)$this->getItemCount();
    }

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return count($this->items);
    }

    /**
     * @return array
     */
    public function pullAllItems(): array
    {
        $allItems = $this->items;

        $this->clearAllItems();

        return $allItems;
    }

    /**
     * @return void
     */
    private function clearAllItems(): void
    {
        $this->items = [];
    }

    /**
     * @param ItemAbstract $item
     */
    private function checkCanPutItem(ItemAbstract $item): void
    {
        $this->checkItem($item);
        $this->checkTotalItemLimit();
        $this->checkTypeItemLimit($item->getType());
    }

    /**
     * @param ItemAbstract $item
     */
    private function checkItem(ItemAbstract $item): void
    {
        $supportedType = array_keys($this->typeItemLimits);

        if (!in_array($item->getType(), $supportedType, true)) {
            throw new LogicException(sprintf(
                'Item of type "%s" not supported. Available item types: "%s".',
                $item->getType(),
                implode('", "', $supportedType)
            ));
        }
    }

    /**
     * @return void
     */
    private function checkTotalItemLimit(): void
    {
        if ($this->getItemCount() === $this->totalLimit) {
            throw new LogicException('Postman can transfer only %s items.');
        }
    }

    /**
     * @param string $itemType
     */
    private function checkTypeItemLimit(string $itemType): void
    {
        if ($this->getTypeItemCount($itemType) >= $this->getTypeItemLimit($itemType)) {
            throw new LogicException(
                sprintf(
                    'Postman can transfer only %s item(s) of type "%s".',
                    $this->getTypeItemLimit($itemType),
                    $itemType
                )
            );
        }
    }

    /**
     * @param string $itemType
     * @return int
     */
    private function getTypeItemLimit(string $itemType): int
    {
        return $this->typeItemLimits[$itemType] ?? 0;
    }

    /**
     * @param string $itemType
     * @return array
     */
    private function getItemsOfType(string $itemType): array
    {
        $items = [];

        foreach ($this->items as $item) {
            if ($item->getType() !== $itemType) {
                continue;
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @param string $itemType
     * @return int
     */
    private function getTypeItemCount(string $itemType): int
    {
        return count($this->getItemsOfType($itemType));
    }
}
