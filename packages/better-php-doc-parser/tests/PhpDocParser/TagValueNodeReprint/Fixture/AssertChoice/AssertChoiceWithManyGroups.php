<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\AssertChoice;

use RectorPrefix20201228\Symfony\Component\Validator\Constraints as Assert;
class AssertChoiceWithManyGroups
{
    /**
     * @Assert\Choice(callback={"App\Entity\Genre", "getGenres"}, groups={"registration", "again"})
     */
    private $ratingType;
}
