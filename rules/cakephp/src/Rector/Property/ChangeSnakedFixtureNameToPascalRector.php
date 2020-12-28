<?php

declare (strict_types=1);
namespace Rector\CakePHP\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Util\StaticRectorStrings;
use Rector\NodeTypeResolver\Node\AttributeKey;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CakePHP\Tests\Rector\Property\ChangeSnakedFixtureNameToPascal\ChangeSnakedFixtureNameToPascalTest
 *
 * @see https://book.cakephp.org/3.0/en/appendices/3-7-migration-guide.html
 */
final class ChangeSnakedFixtureNameToPascalRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes $fixtues style from snake_case to PascalCase.', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeTest
{
    protected $fixtures = [
        'app.posts',
        'app.users',
        'some_plugin.posts/special_posts',
    ];
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeTest
{
    protected $fixtures = [
        'app.Posts',
        'app.Users',
        'some_plugin.Posts/SpecialPosts',
    ];
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Property::class];
    }
    /**
     * @param Property $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if ($classLike === null) {
            return null;
        }
        if (!$this->isName($node, 'fixtures')) {
            return null;
        }
        foreach ($node->props as $prop) {
            $this->refactorPropertyWithArrayDefault($prop);
        }
        return $node;
    }
    private function refactorPropertyWithArrayDefault(\PhpParser\Node\Stmt\PropertyProperty $propertyProperty) : void
    {
        if (!$propertyProperty->default instanceof \PhpParser\Node\Expr\Array_) {
            return;
        }
        $array = $propertyProperty->default;
        foreach ($array->items as $arrayItem) {
            if (!$arrayItem instanceof \PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if (!$arrayItem->value instanceof \PhpParser\Node\Scalar\String_) {
                continue;
            }
            $this->renameFixtureName($arrayItem->value);
        }
    }
    private function renameFixtureName(\PhpParser\Node\Scalar\String_ $string) : void
    {
        [$prefix, $table] = \explode('.', $string->value);
        $table = \array_map(function (string $token) : string {
            return \Rector\Core\Util\StaticRectorStrings::underscoreToPascalCase($token);
        }, \explode('/', $table));
        $table = \implode('/', $table);
        $string->value = \sprintf('%s.%s', $prefix, $table);
    }
}
