<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Tests\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine;

use RectorPrefix20210301\Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(
 *     name="building",
 *     indexes={
 *          @ORM\Index(name="isDemoBuilding", columns={"is_demo_building"})
 *     }
 * )
 */
final class IndexInTable
{
}
