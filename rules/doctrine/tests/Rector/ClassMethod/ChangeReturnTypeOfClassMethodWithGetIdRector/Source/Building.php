<?php

declare (strict_types=1);
namespace Rector\Doctrine\Tests\Rector\ClassMethod\ChangeReturnTypeOfClassMethodWithGetIdRector\Source;

use RectorPrefix20210306\Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 */
class Building
{
    private $id;
    public function getId() : \RectorPrefix20210306\Ramsey\Uuid\UuidInterface
    {
        return $this->id;
    }
}
