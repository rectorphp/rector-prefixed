<?php

declare (strict_types=1);
namespace Rector\AttributeAwarePhpDoc\AttributeAwareNodeFactory\PhpDoc;

use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareUsesTagValueNode;
use Rector\AttributeAwarePhpDoc\Contract\AttributeNodeAwareFactory\AttributeNodeAwareFactoryInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
final class AttributeAwareUsesTagValueNodeFactory implements \Rector\AttributeAwarePhpDoc\Contract\AttributeNodeAwareFactory\AttributeNodeAwareFactoryInterface
{
    public function getOriginalNodeClass() : string
    {
        return \PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode::class;
    }
    /**
     * @param \PHPStan\PhpDocParser\Ast\Node $node
     */
    public function isMatch($node) : bool
    {
        return \is_a($node, \PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode::class, \true);
    }
    /**
     * @param \PHPStan\PhpDocParser\Ast\Node $node
     * @param string $docContent
     */
    public function create($node, $docContent) : \Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
    {
        return new \Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareUsesTagValueNode($node->type, $node->description);
    }
}
