<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Tests\PhpDocParser\TagValueNodeReprint\Fixture\DoctrineColumn;

use _PhpScopere8e811afab72\Doctrine\ORM\Mapping as ORM;
final class FromOfficialDocs
{
    /**
     * @ORM\Column(type="integer", name="login_count", nullable=false, columnDefinition="CHAR(2) NOT NULL")
     */
    private $loginCount;
}
