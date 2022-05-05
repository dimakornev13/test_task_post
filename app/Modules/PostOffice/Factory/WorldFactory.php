<?php
namespace App\Modules\PostOffice\Factory;

use App\Modules\PostOffice\Entity\PostOffice\PostOfficeInterface;
use App\Modules\PostOffice\World;

class WorldFactory
{
    /**
     * @return World
     */
    public static function createDefaultWorld(): World
    {
        $postOffice = PostOfficeFactory::createExamplePostOffice();

        return self::createWorld($postOffice);
    }

    /**
     * @return World
     */
    public static function createCandidateWorld(): World
    {
        $postOffice = PostOfficeFactory::createCandidatePostOffice();

        return self::createWorld($postOffice);
    }

    /**
     * @param PostOfficeInterface $postOffice
     * @return World
     */
    private static function createWorld(PostOfficeInterface $postOffice): World
    {
        $itemFactory = new ItemFactory();

        return new World($itemFactory, $postOffice);
    }
}
