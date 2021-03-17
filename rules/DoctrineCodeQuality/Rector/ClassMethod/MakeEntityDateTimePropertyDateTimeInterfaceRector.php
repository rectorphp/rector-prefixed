<?php

declare (strict_types=1);
namespace Rector\DoctrineCodeQuality\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\DoctrineCodeQuality\NodeAnalyzer\SetterClassMethodAnalyzer;
use Rector\DoctrineCodeQuality\NodeManipulator\PropertyTypeManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see related to maker bundle https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html
 *
 * @see \Rector\Tests\DoctrineCodeQuality\Rector\ClassMethod\MakeEntityDateTimePropertyDateTimeInterfaceRector\MakeEntityDateTimePropertyDateTimeInterfaceRectorTest
 */
final class MakeEntityDateTimePropertyDateTimeInterfaceRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var SetterClassMethodAnalyzer
     */
    private $setterClassMethodAnalyzer;
    /**
     * @var PropertyTypeManipulator
     */
    private $propertyTypeManipulator;
    /**
     * @param \Rector\DoctrineCodeQuality\NodeAnalyzer\SetterClassMethodAnalyzer $setterClassMethodAnalyzer
     * @param \Rector\DoctrineCodeQuality\NodeManipulator\PropertyTypeManipulator $propertyTypeManipulator
     */
    public function __construct($setterClassMethodAnalyzer, $propertyTypeManipulator)
    {
        $this->setterClassMethodAnalyzer = $setterClassMethodAnalyzer;
        $this->propertyTypeManipulator = $propertyTypeManipulator;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Make maker bundle generate DateTime property accept DateTimeInterface too', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class User
{
    /**
     * @var DateTime|null
     */
    private $bornAt;

    public function setBornAt(DateTimeInterface $bornAt)
    {
        $this->bornAt = $bornAt;
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class User
{
    /**
     * @var DateTimeInterface|null
     */
    private $bornAt;

    public function setBornAt(DateTimeInterface $bornAt)
    {
        $this->bornAt = $bornAt;
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $property = $this->setterClassMethodAnalyzer->matchDateTimeSetterProperty($node);
        if (!$property instanceof \PhpParser\Node\Stmt\Property) {
            return null;
        }
        if (!$this->isObjectType($property, new \PHPStan\Type\ObjectType('DateTime'))) {
            return null;
        }
        $this->propertyTypeManipulator->changePropertyType($property, 'DateTime', 'DateTimeInterface');
        return $node;
    }
}
