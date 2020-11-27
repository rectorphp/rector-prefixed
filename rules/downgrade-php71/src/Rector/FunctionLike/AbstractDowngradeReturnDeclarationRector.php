<?php

declare (strict_types=1);
namespace Rector\DowngradePhp71\Rector\FunctionLike;

use PhpParser\Node;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\IterableType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\DowngradePhp71\Contract\Rector\DowngradeReturnDeclarationRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Traversable;
abstract class AbstractDowngradeReturnDeclarationRector extends \Rector\DowngradePhp71\Rector\FunctionLike\AbstractDowngradeRector implements \Rector\DowngradePhp71\Contract\Rector\DowngradeReturnDeclarationRectorInterface
{
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Function_::class, \PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->shouldRemoveReturnDeclaration($node)) {
            return null;
        }
        if ($this->addDocBlock) {
            $this->addDocBlockReturn($node);
        }
        $node->returnType = null;
        return $node;
    }
    /**
     * @param ClassMethod|Function_ $functionLike
     */
    private function addDocBlockReturn(\PhpParser\Node\FunctionLike $functionLike) : void
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $functionLike->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            $phpDocInfo = $this->phpDocInfoFactory->createEmpty($functionLike);
        }
        if ($functionLike->returnType === null) {
            return;
        }
        $type = $this->staticTypeMapper->mapPhpParserNodePHPStanType($functionLike->returnType);
        if ($type instanceof \PHPStan\Type\IterableType) {
            $type = new \PHPStan\Type\UnionType([$type, new \PHPStan\Type\IntersectionType([new \PHPStan\Type\ObjectType(\Traversable::class)])]);
        }
        $phpDocInfo->changeReturnType($type);
    }
}
