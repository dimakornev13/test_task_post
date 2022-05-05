<?php

namespace App\Modules\PostOffice\Entity\Item;

abstract class ItemAbstract
{
    private int    $expirationDay;
    private string $type;

    /**
     * @param string $type
     * @param int $expirationDay
     */
    public function __construct(string $type, int $expirationDay)
    {
        $this->type          = $type;
        $this->expirationDay = $expirationDay;
    }

    /**
     * @return int
     */
    public function getExpirationDay(): int
    {
        return $this->expirationDay;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
