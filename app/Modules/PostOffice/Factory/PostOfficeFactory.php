<?php
namespace App\Modules\PostOffice\Factory;

use App\Modules\PostOffice\Entity\Postman\Biker;
use App\Modules\PostOffice\Entity\Postman\Driver;
use App\Modules\PostOffice\Entity\Postman\Postman;
use App\Modules\PostOffice\Entity\PostOffice\CandidatePostOffice;
use App\Modules\PostOffice\Entity\PostOffice\ExamplePostOffice;

class PostOfficeFactory
{
    /**
     * @return ExamplePostOffice
     */
    public static function createExamplePostOffice(): ExamplePostOffice
    {
        return new ExamplePostOffice([new Postman(), new Biker(), new Driver()]);
    }

    /**
     * @return CandidatePostOffice
     */
    public static function createCandidatePostOffice(): CandidatePostOffice
    {
        return new CandidatePostOffice([new Postman(), new Biker(), new Driver()]);
    }
}
