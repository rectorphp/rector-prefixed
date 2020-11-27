<?php

declare (strict_types=1);
namespace Rector\Utils\DoctrineAnnotationParserSyncer\Rector\Assign;

use _PhpScoperbd5d0c5f7638\Doctrine\Common\Annotations\AnnotationReader;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Core\Rector\AbstractRector;
use Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser;
use Rector\Utils\DoctrineAnnotationParserSyncer\Contract\Rector\ClassSyncerRectorInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
final class AssignNewDocParserRector extends \Rector\Core\Rector\AbstractRector implements \Rector\Utils\DoctrineAnnotationParserSyncer\Contract\Rector\ClassSyncerRectorInterface
{
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        if (!$this->isInClassNamed($node, \_PhpScoperbd5d0c5f7638\Doctrine\Common\Annotations\AnnotationReader::class)) {
            return null;
        }
        if (!$this->isName($node->var, 'preParser')) {
            return null;
        }
        $node->expr = new \PhpParser\Node\Expr\New_(new \PhpParser\Node\Name\FullyQualified(\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser::class));
        return $node;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change $this->preParser assign to new doc parser', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
namespace Doctrine\Common\Annotations;

class AnnotationReader
{
    public function run()
    {
        $this->preParser = ...
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
namespace Doctrine\Common\Annotations;

class AnnotationReader
{
    public function run()
    {
        $this->preParser = new \Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser();
    }
}
CODE_SAMPLE
)]);
    }
}