<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\AssertType;

use RectorPrefix2020DecSat\Symfony\Component\Validator\Constraints as Assert;
final class AssertArrayType
{
    /**
     * @Assert\Type(type={"alpha", "digit"})
     */
    public $accessCode;
}
