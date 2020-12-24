<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CakePHP\Rector\FileWithoutNamespace;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\New_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Name;
use _PhpScoperb75b35f52b74\Rector\CakePHP\ImplicitNameResolver;
use _PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/cakephp/upgrade/blob/05d85c147bb1302b576b818cabb66a40462aaed0/src/Shell/Task/AppUsesTask.php#L183
 *
 * @see \Rector\CakePHP\Tests\Rector\FileWithoutNamespace\ImplicitShortClassNameUseStatementRector\ImplicitShortClassNameUseStatementRectorTest
 */
final class ImplicitShortClassNameUseStatementRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var ImplicitNameResolver
     */
    private $implicitNameResolver;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\CakePHP\ImplicitNameResolver $implicitNameResolver)
    {
        $this->implicitNameResolver = $implicitNameResolver;
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Collect implicit class names and add imports', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use App\Foo\Plugin;

class LocationsFixture extends TestFixture implements Plugin
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use App\Foo\Plugin;
use Cake\TestSuite\Fixture\TestFixture;

class LocationsFixture extends TestFixture implements Plugin
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace::class];
    }
    /**
     * @param FileWithoutNamespace $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        $names = $this->findNames($node);
        /** @var Name[] $names */
        if ($names === []) {
            return null;
        }
        $resolvedNames = $this->resolveNames($names);
        if ($resolvedNames === []) {
            return null;
        }
        $uses = $this->nodeFactory->createUsesFromNames($resolvedNames);
        $node->stmts = \array_merge($uses, $node->stmts);
        return $node;
    }
    /**
     * @return Name[]
     */
    private function findNames(\_PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace $fileWithoutNamespace) : array
    {
        return $this->betterNodeFinder->find($fileWithoutNamespace->stmts, function (\_PhpScoperb75b35f52b74\PhpParser\Node $node) : bool {
            if (!$node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Name) {
                return \false;
            }
            $parent = $node->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            return !$parent instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\New_;
        });
    }
    /**
     * @param Name[] $names
     * @return string[]
     */
    private function resolveNames(array $names) : array
    {
        $resolvedNames = [];
        foreach ($names as $name) {
            $classShortName = $this->getName($name);
            $resolvedName = $this->implicitNameResolver->resolve($classShortName);
            if ($resolvedName === null) {
                continue;
            }
            $resolvedNames[] = $resolvedName;
        }
        return $resolvedNames;
    }
}
