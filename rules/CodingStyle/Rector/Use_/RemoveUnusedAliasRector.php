<?php

declare (strict_types=1);
namespace Rector\CodingStyle\Rector\Use_;

use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\CodingStyle\Naming\NameRenamer;
use Rector\CodingStyle\Node\DocAliasResolver;
use Rector\CodingStyle\Node\UseManipulator;
use Rector\CodingStyle\Node\UseNameAliasToNameResolver;
use Rector\CodingStyle\ValueObject\NameAndParent;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\CodingStyle\Rector\Use_\RemoveUnusedAliasRector\RemoveUnusedAliasRectorTest
 */
final class RemoveUnusedAliasRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NameAndParent[][]
     */
    private $resolvedNodeNames = [];
    /**
     * @var array<string, string[]>
     */
    private $useNamesAliasToName = [];
    /**
     * @var string[]
     */
    private $resolvedDocPossibleAliases = [];
    /**
     * @var DocAliasResolver
     */
    private $docAliasResolver;
    /**
     * @var UseNameAliasToNameResolver
     */
    private $useNameAliasToNameResolver;
    /**
     * @var UseManipulator
     */
    private $useManipulator;
    /**
     * @var NameRenamer
     */
    private $nameRenamer;
    /**
     * @param \Rector\CodingStyle\Node\DocAliasResolver $docAliasResolver
     * @param \Rector\CodingStyle\Node\UseManipulator $useManipulator
     * @param \Rector\CodingStyle\Node\UseNameAliasToNameResolver $useNameAliasToNameResolver
     * @param \Rector\CodingStyle\Naming\NameRenamer $nameRenamer
     */
    public function __construct($docAliasResolver, $useManipulator, $useNameAliasToNameResolver, $nameRenamer)
    {
        $this->docAliasResolver = $docAliasResolver;
        $this->useNameAliasToNameResolver = $useNameAliasToNameResolver;
        $this->useManipulator = $useManipulator;
        $this->nameRenamer = $nameRenamer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Removes unused use aliases. Keep annotation aliases like "Doctrine\\ORM\\Mapping as ORM" to keep convention format', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Kernel as BaseKernel;

class SomeClass extends BaseKernel
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Kernel;

class SomeClass extends Kernel
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Use_::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if ($this->shouldSkipUse($node)) {
            return null;
        }
        $searchNode = $this->resolveSearchNode($node);
        if (!$searchNode instanceof \PhpParser\Node) {
            return null;
        }
        $this->resolvedNodeNames = $this->useManipulator->resolveUsedNameNodes($searchNode);
        $this->resolvedDocPossibleAliases = $this->docAliasResolver->resolve($searchNode);
        $this->useNamesAliasToName = $this->useNameAliasToNameResolver->resolve($node);
        // lowercase
        $this->resolvedDocPossibleAliases = $this->lowercaseArray($this->resolvedDocPossibleAliases);
        $this->resolvedNodeNames = \array_change_key_case($this->resolvedNodeNames, \CASE_LOWER);
        $this->useNamesAliasToName = \array_change_key_case($this->useNamesAliasToName, \CASE_LOWER);
        foreach ($node->uses as $use) {
            if ($use->alias === null) {
                continue;
            }
            $lastName = $use->name->getLast();
            $lowercasedLastName = \strtolower($lastName);
            /** @var string $aliasName */
            $aliasName = $this->getName($use->alias);
            if ($this->shouldSkip($node, $use->name, $lastName, $aliasName)) {
                continue;
            }
            // only last name is used → no need for alias
            if (isset($this->resolvedNodeNames[$lowercasedLastName])) {
                $use->alias = null;
                continue;
            }
            $this->refactorAliasName($aliasName, $lastName, $use);
        }
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\Use_ $use
     */
    private function shouldSkipUse($use) : bool
    {
        // skip cases without namespace, problematic to analyse
        $namespace = $use->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NODE);
        if ($namespace === null) {
            return \true;
        }
        return !$this->hasUseAlias($use);
    }
    /**
     * @param \PhpParser\Node\Stmt\Use_ $use
     */
    private function resolveSearchNode($use) : ?\PhpParser\Node
    {
        $searchNode = $use->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
        if ($searchNode !== null) {
            return $searchNode;
        }
        return $use->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::NEXT_NODE);
    }
    /**
     * @param string[] $values
     * @return string[]
     */
    private function lowercaseArray($values) : array
    {
        return \array_map(function (string $value) : string {
            return \strtolower($value);
        }, $values);
    }
    /**
     * @param \PhpParser\Node\Stmt\Use_ $use
     * @param \PhpParser\Node\Name $name
     * @param string $lastName
     * @param string $aliasName
     */
    private function shouldSkip($use, $name, $lastName, $aliasName) : bool
    {
        // PHP is case insensitive
        $loweredLastName = \strtolower($lastName);
        $loweredAliasName = \strtolower($aliasName);
        // both are used → nothing to remove
        if (isset($this->resolvedNodeNames[$loweredLastName], $this->resolvedNodeNames[$loweredAliasName])) {
            return \true;
        }
        // part of some @Doc annotation
        if (\in_array($loweredAliasName, $this->resolvedDocPossibleAliases, \true)) {
            return \true;
        }
        return (bool) $this->betterNodeFinder->findFirstNext($use, function (\PhpParser\Node $node) use($name) : bool {
            if (!$node instanceof \PhpParser\Node\Expr\ClassConstFetch) {
                return \false;
            }
            if (!$node->class instanceof \PhpParser\Node\Name) {
                return \false;
            }
            return $node->class->toString() === $name->toString();
        });
    }
    /**
     * @param string $aliasName
     * @param string $lastName
     * @param \PhpParser\Node\Stmt\UseUse $useUse
     */
    private function refactorAliasName($aliasName, $lastName, $useUse) : void
    {
        // only alias name is used → use last name directly
        $lowerAliasName = \strtolower($aliasName);
        if (!isset($this->resolvedNodeNames[$lowerAliasName])) {
            return;
        }
        // keep to differentiate 2 aliases classes
        $lowerLastName = \strtolower($lastName);
        if (\count($this->useNamesAliasToName[$lowerLastName] ?? []) > 1) {
            return;
        }
        $this->nameRenamer->renameNameNode($this->resolvedNodeNames[$lowerAliasName], $lastName);
        $useUse->alias = null;
    }
    /**
     * @param \PhpParser\Node\Stmt\Use_ $use
     */
    private function hasUseAlias($use) : bool
    {
        foreach ($use->uses as $useUse) {
            if ($useUse->alias !== null) {
                return \true;
            }
        }
        return \false;
    }
}
