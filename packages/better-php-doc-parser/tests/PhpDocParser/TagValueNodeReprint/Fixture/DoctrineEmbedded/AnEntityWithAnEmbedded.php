<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\DoctrineEmbedded;

use _PhpScopere8e811afab72\Doctrine\ORM\Mapping as ORM;
use _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Source\Embeddable;
final class AnEntityWithAnEmbedded
{
    /**
     * @ORM\Embedded(class="Embeddable")
     */
    private $embedded;
}
