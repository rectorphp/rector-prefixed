<?php

declare (strict_types=1);
namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\UnionType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
final class UnionTypeNodeMapper implements \Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface
{
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var PhpParserNodeMapper
     */
    private $phpParserNodeMapper;
    /**
     * @param \Rector\NodeTypeResolver\PHPStan\Type\TypeFactory $typeFactory
     */
    public function __construct($typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }
    /**
     * @required
     * @param \Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper $phpParserNodeMapper
     */
    public function autowireUnionTypeNodeMapper($phpParserNodeMapper) : void
    {
        $this->phpParserNodeMapper = $phpParserNodeMapper;
    }
    /**
     * @return class-string<Node>
     */
    public function getNodeType() : string
    {
        return \PhpParser\Node\UnionType::class;
    }
    /**
     * @param UnionType $node
     */
    public function mapToPHPStan(\PhpParser\Node $node) : \PHPStan\Type\Type
    {
        $types = [];
        foreach ($node->types as $unionedType) {
            $types[] = $this->phpParserNodeMapper->mapToPHPStanType($unionedType);
        }
        return $this->typeFactory->createMixedPassedOrUnionType($types);
    }
}
