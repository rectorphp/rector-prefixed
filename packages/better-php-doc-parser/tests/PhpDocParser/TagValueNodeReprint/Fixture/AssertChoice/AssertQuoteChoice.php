<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\AssertChoice;

use _PhpScopere8e811afab72\Symfony\Component\Validator\Constraints as Assert;
class AssertQuoteChoice
{
    const CHOICE_ONE = 'choice_one';
    const CHOICE_TWO = 'choice_two';
    /**
     * @Assert\Choice({AssertQuoteChoice::CHOICE_ONE, AssertQuoteChoice::CHOICE_TWO})
     */
    private $someChoice = self::CHOICE_ONE;
}
