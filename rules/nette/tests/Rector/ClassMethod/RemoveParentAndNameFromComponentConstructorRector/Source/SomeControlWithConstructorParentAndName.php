<?php

declare (strict_types=1);
namespace Rector\Nette\Tests\Rector\ClassMethod\RemoveParentAndNameFromComponentConstructorRector\Source;

use _PhpScoperbd5d0c5f7638\Nette\Application\UI\Control;
final class SomeControlWithConstructorParentAndName extends \_PhpScoperbd5d0c5f7638\Nette\Application\UI\Control
{
    public function __construct($parent = null, $name = '')
    {
        $this->parent = $parent;
        $this->name = $name;
    }
}