<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\PhpDocNodeFactory\StringMatchingPhpDocNodeFactory;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use Rector\BetterPhpDocParser\Contract\StringTagMatchingPhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Nette\NetteInjectTagNode;
final class NetteInjectPhpDocNodeFactory implements \Rector\BetterPhpDocParser\Contract\StringTagMatchingPhpDocNodeFactoryInterface
{
    /**
     * @param string $tag
     */
    public function match($tag) : bool
    {
        return $tag === \Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Nette\NetteInjectTagNode::NAME;
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    public function createFromTokens($tokenIterator) : ?\PHPStan\PhpDocParser\Ast\Node
    {
        return new \Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Nette\NetteInjectTagNode();
    }
}
