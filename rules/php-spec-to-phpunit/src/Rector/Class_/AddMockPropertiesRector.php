<?php

declare (strict_types=1);
namespace Rector\PhpSpecToPHPUnit\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;
use Rector\Core\PhpParser\Node\Manipulator\ClassInsertManipulator;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpSpecToPHPUnit\PhpSpecMockCollector;
use Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector;
/**
 * @see \Rector\PhpSpecToPHPUnit\Tests\Rector\Variable\PhpSpecToPHPUnitRector\PhpSpecToPHPUnitRectorTest
 */
final class AddMockPropertiesRector extends \Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector
{
    /**
     * @var PhpSpecMockCollector
     */
    private $phpSpecMockCollector;
    /**
     * @var ClassInsertManipulator
     */
    private $classInsertManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\ClassInsertManipulator $classInsertManipulator, \Rector\PhpSpecToPHPUnit\PhpSpecMockCollector $phpSpecMockCollector)
    {
        $this->phpSpecMockCollector = $phpSpecMockCollector;
        $this->classInsertManipulator = $classInsertManipulator;
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isInPhpSpecBehavior($node)) {
            return null;
        }
        $classMocks = $this->phpSpecMockCollector->resolveClassMocksFromParam($node);
        /** @var string $class */
        $class = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NAME);
        foreach ($classMocks as $name => $methods) {
            if (\count($methods) <= 1) {
                continue;
            }
            // non-ctor used mocks are probably local only
            if (!\in_array('let', $methods, \true)) {
                continue;
            }
            $this->phpSpecMockCollector->addPropertyMock($class, $name);
            $variableType = $this->phpSpecMockCollector->getTypeForClassAndVariable($node, $name);
            $unionType = new \PHPStan\Type\UnionType([new \PHPStan\Type\ObjectType($variableType), new \PHPStan\Type\ObjectType('_PhpScoperbd5d0c5f7638\\PHPUnit\\Framework\\MockObject\\MockObject')]);
            $this->classInsertManipulator->addPropertyToClass($node, $name, $unionType);
        }
        return null;
    }
}