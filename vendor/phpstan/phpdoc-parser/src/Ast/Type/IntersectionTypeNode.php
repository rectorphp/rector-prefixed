<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Type;

class IntersectionTypeNode implements \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Type\TypeNode
{
    /** @var TypeNode[] */
    public $types;
    public function __construct(array $types)
    {
        $this->types = $types;
    }
    public function __toString() : string
    {
        return '(' . \implode(' & ', $this->types) . ')';
    }
}
