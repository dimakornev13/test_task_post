<?php

namespace App\Modules\PostOffice\Entity\PostOffice;

use App\Modules\PostOffice\Entity\Item\ItemAbstract;
use App\Modules\PostOffice\Entity\Postman\PostmanAbstract;
use LogicException;

class CandidatePostOffice implements PostOfficeInterface
{
    /** @var PostmanAbstract[] */
    private array $postmen;

    /** @var ItemAbstract[] */
    private array $postQueue = [];

    /**
     * @param PostmanAbstract[] $postmen
     */
    public function __construct(array $postmen)
    {
        $this->postmen = $postmen;

        $this->checkInput('postmen', PostmanAbstract::class);
    }

    /**
     * Good time for filling postmen
     * @param ItemAbstract[] $items
     * @return PostmanAbstract[]
     */
    public function liveDay(array $items = []): array
    {
        // todo refactor
        $this->postQueue = array_merge($this->postQueue, $items);

        $this->checkInput('postQueue', ItemAbstract::class);

        $this->unFillPostmen();

        $this->sortPostQueueByExpirationDate();

        $this->fillPostmen();

        return $this->postmen;
    }

    /**
     * @return bool
     */
    public function isEmptyItemsQueue(): bool
    {
        return count($this->postQueue) == 0;
    }

    /**
     * @return bool
     */
    public function isAllItemsDelivered(): bool
    {
        $countPostmenWithPost = collect($this->postmen)
            ->filter(function (PostmanAbstract $postman) {
                return $postman->hasItems();
            })->count();

        return $this->isEmptyItemsQueue() && $countPostmenWithPost === 0;
    }

    /**
     * return void
     * @param string $field
     * @param string $shouldBeInstanceOf
     */
    private function checkInput(string $field, string $shouldBeInstanceOf): void
    {
        foreach ($this->{$field} as $postman) {
            if (($postman instanceof $shouldBeInstanceOf) === false) {
                throw new LogicException(
                    sprintf('Given object must be an instance of %s class. %s given.', $shouldBeInstanceOf, get_class($postman))
                );
            }
        }
    }

    /**
     * return void
     */
    private function fillPostmen(): void
    {
        foreach ($this->postmen as $postman) {
            foreach ($this->postQueue as $key => $post) {
                if ($postman->isFull())
                    break;

                if ($postman->getItemFreeSlotCount($post) == 0)
                    continue;

                $postman->putItem($post);
                unset($this->postQueue[$key]);
            }
        }
    }

    /**
     * return void
     */
    private function unFillPostmen(): void
    {
        foreach ($this->postmen as $postman) {
            $this->postQueue = array_merge($this->postQueue, $postman->pullAllItems());
        }
    }

    /**
     * return void
     */
    private function sortPostQueueByExpirationDate(): void
    {
        $this->postQueue = collect($this->postQueue)->sortBy(function (ItemAbstract $post) {
            return $post->getExpirationDay();
        })->toArray();
    }
}
