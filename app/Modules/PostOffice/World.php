<?php

namespace App\Modules\PostOffice;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use App\Modules\PostOffice\Entity\Postman\PostmanAbstract;
use App\Modules\PostOffice\Entity\PostOffice\PostOfficeInterface;
use App\Modules\PostOffice\Factory\ItemFactory;

class World
{
    private ItemFactory         $itemFactory;
    private PostOfficeInterface $postOffice;

    private int   $totalDiscontentIndex;
    private int   $totalDiscontentUser;
    private int   $currentDay;
    private int   $itemsInCount;
    private int   $itemsOutCount;
    private array $dailyItemsStream = [];

    /**
     * @param ItemFactory $itemFactory
     * @param PostOfficeInterface $postOffice
     */
    public function __construct(ItemFactory $itemFactory, PostOfficeInterface $postOffice)
    {
        $this->itemFactory = $itemFactory;
        $this->postOffice  = $postOffice;
    }

    /**
     * @return int
     */
    public function getCurrentDay(): int
    {
        return $this->currentDay;
    }

    /**
     * @return int
     */
    public function getTotalDiscontentIndex(): int
    {
        return $this->totalDiscontentIndex;
    }

    /**
     * @return int
     */
    public function getTotalDiscontentUsers(): int
    {
        return $this->totalDiscontentUser;
    }

    /**
     * @return int
     */
    public function getItemsInCount(): int
    {
        return $this->itemsInCount;
    }

    /**
     * @return int
     */
    public function getItemsOutCount(): int
    {
        return $this->itemsOutCount;
    }

    /**
     * @param array
     * @return void
     */
    public function run(array $dailyItemsStream): void
    {
        $this->createWorld($dailyItemsStream);
        $this->live();
    }

    /**
     * @param array $dailyItemsStream
     */
    private function createWorld(array $dailyItemsStream): void
    {
        $this->clearWorld();
        $this->setDailyItemsStream($dailyItemsStream);
    }

    /**
     * @return void
     */
    private function clearWorld(): void
    {
        $this->totalDiscontentIndex = 0;
        $this->totalDiscontentUser  = 0;
        $this->currentDay           = 0;
        $this->itemsInCount         = 0;
        $this->itemsOutCount        = 0;
        $this->dailyItemsStream     = [];
    }

    /**
     * @param array $dailyItemsStream
     */
    private function setDailyItemsStream(array $dailyItemsStream): void
    {
        $this->dailyItemsStream = $dailyItemsStream;
    }

    /**
     * @return void
     */
    private function live(): void
    {
        while (true) {
            if ($this->isAllItemsDelivered()) {
                break;
            }

            $this->liveDay();
        }

        $this->checkLostItems();
    }

    /**
     * @return bool
     */
    private function isAllItemsDelivered(): bool
    {
        if (count($this->dailyItemsStream)) {
            return false;
        }

        if (!$this->postOffice->isAllItemsDelivered()) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    private function liveDay(): void
    {
        $this->startNewDay();

        $dailyItems = $this->getDailyItemsStream();
        $postmen    = $this->postOffice->liveDay($dailyItems);

        if ($this->isLastDay()) {
            $this->deliverItemsByAllPostmen($postmen);

            return;
        }

        $this->deliverItemsByFullPostmen($postmen);
    }

    /**
     * @return void
     */
    private function checkLostItems(): void
    {
        $lostItemsCount = $this->itemsInCount - $this->itemsOutCount;

        if ($lostItemsCount > 0) {
            $this->totalDiscontentUser  += $lostItemsCount;
            $this->totalDiscontentIndex += $lostItemsCount * $this->currentDay;
        }
    }

    /**
     * @return void
     */
    private function startNewDay(): void
    {
        $this->currentDay++;
    }

    /**
     * @return bool
     */
    private function isLastDay(): bool
    {
        if (count($this->dailyItemsStream)) {
            return false;
        }

        if (!$this->postOffice->isEmptyItemsQueue()) {
            return false;
        }

        return true;
    }

    /**
     * @return ItemAbstract[]
     */
    private function getDailyItemsStream(): array
    {
        if (!count($this->dailyItemsStream)) {
            return [];
        }

        $itemsOnCurrentDay  = array_shift($this->dailyItemsStream);
        $items              = $this->itemFactory->deserializeItems($this->currentDay, $itemsOnCurrentDay);
        $this->itemsInCount += count($items);

        return $items;
    }

    /**
     * @param array $postmen
     */
    private function deliverItemsByFullPostmen(array $postmen): void
    {
        $fullPostmen = $this->getFullPostmen($postmen);

        $this->deliverItemAndCalculateDiscontentOfUsers($fullPostmen);
    }

    /**
     * @param array $postmen
     */
    private function deliverItemsByAllPostmen(array $postmen): void
    {
        $this->deliverItemAndCalculateDiscontentOfUsers($postmen);
    }

    /**
     * @param PostmanAbstract[] $postmen
     * @return PostmanAbstract[]
     */
    private function getFullPostmen(array $postmen): array
    {
        $fullPostmen = [];

        foreach ($postmen as $postman) {
            if ($postman->isFull()) {
                $fullPostmen[] = $postman;
            }
        }

        return $fullPostmen;
    }

    /**
     * @param PostmanAbstract[] $postmen
     */
    private function deliverItemAndCalculateDiscontentOfUsers(array $postmen): void
    {
        foreach ($postmen as $postman) {
            $items = $postman->pullAllItems();

            foreach ($items as $item) {
                $discontentIndex = $this->getCurrentDay() - $item->getExpirationDay();

                if ($discontentIndex > 0) {
                    $this->totalDiscontentIndex += $discontentIndex;
                    $this->totalDiscontentUser++;
                }
            }

            $this->itemsOutCount += count($items);
        }
    }
}
