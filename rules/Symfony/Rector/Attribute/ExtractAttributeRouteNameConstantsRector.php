<?php

declare (strict_types=1);
namespace Rector\Symfony\Rector\Attribute;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Symfony\SymfonyRouteTagValueNode;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\AddedFileWithNodes;
use Rector\Symfony\ConstantNameAndValueMatcher;
use Rector\Symfony\ConstantNameAndValueResolver;
use Rector\Symfony\NodeFactory\RouteNameClassFactory;
use Rector\Symfony\ValueObject\ClassName;
use Rector\Symfony\ValueObject\ConstantNameAndValue;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ExtraFileCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileSystem;
/**
 * @see https://tomasvotruba.com/blog/2020/12/21/5-new-combos-opened-by-symfony-52-and-php-80/
 *
 * @see \Rector\Tests\Symfony\Rector\Attribute\ExtractAttributeRouteNameConstantsRector\ExtractAttributeRouteNameConstantsRectorTest
 */
final class ExtractAttributeRouteNameConstantsRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const ROUTE_NAME_FILE_LOCATION = 'src/ValueObject/Routing/RouteName.php';
    /**
     * @var RouteNameClassFactory
     */
    private $routeNameClassFactory;
    /**
     * @var bool
     */
    private $isRouteNameValueObjectCreated = \false;
    /**
     * @var ConstantNameAndValueMatcher
     */
    private $constantNameAndValueMatcher;
    /**
     * @var ConstantNameAndValueResolver
     */
    private $constantNameAndValueResolver;
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;
    /**
     * @param \Rector\Symfony\NodeFactory\RouteNameClassFactory $routeNameClassFactory
     * @param \Rector\Symfony\ConstantNameAndValueMatcher $constantNameAndValueMatcher
     * @param \Rector\Symfony\ConstantNameAndValueResolver $constantNameAndValueResolver
     * @param \Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem
     */
    public function __construct($routeNameClassFactory, $constantNameAndValueMatcher, $constantNameAndValueResolver, $smartFileSystem)
    {
        $this->routeNameClassFactory = $routeNameClassFactory;
        $this->constantNameAndValueMatcher = $constantNameAndValueMatcher;
        $this->constantNameAndValueResolver = $constantNameAndValueResolver;
        $this->smartFileSystem = $smartFileSystem;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Extract #[Route] attribute name argument from string to constant', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\ExtraFileCodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Routing\Annotation\Route;

class SomeClass
{
    #[Route(path: "path", name: "/name")]
    public function run()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Routing\Annotation\Route;

class SomeClass
{
    #[Route(path: "path", name: RouteName::NAME)]
    public function run()
    {
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class RouteName
{
    /**
     * @var string
     */
    public NAME = 'name';
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Attribute::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->isName($node->name, \Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Symfony\SymfonyRouteTagValueNode::CLASS_NAME)) {
            return null;
        }
        $this->createRouteNameValueObject();
        foreach ($node->args as $arg) {
            if (!$this->isName($arg, 'name')) {
                continue;
            }
            $constantNameAndValue = $this->constantNameAndValueMatcher->matchFromArg($arg, 'ROUTE_');
            if (!$constantNameAndValue instanceof \Rector\Symfony\ValueObject\ConstantNameAndValue) {
                continue;
            }
            $arg->value = $this->nodeFactory->createClassConstFetch(\Rector\Symfony\ValueObject\ClassName::ROUTE_CLASS_NAME, $constantNameAndValue->getName());
        }
        return $node;
    }
    private function createRouteNameValueObject() : void
    {
        if ($this->isRouteNameValueObjectCreated) {
            return;
        }
        if ($this->smartFileSystem->exists(self::ROUTE_NAME_FILE_LOCATION)) {
            // avoid override
            return;
        }
        $routeAttributes = $this->nodeRepository->findAttributes(\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Symfony\SymfonyRouteTagValueNode::CLASS_NAME);
        $constantNameAndValues = $this->constantNameAndValueResolver->resolveFromAttributes($routeAttributes, 'ROUTE_');
        $namespace = $this->routeNameClassFactory->create($constantNameAndValues, self::ROUTE_NAME_FILE_LOCATION);
        $addedFileWithNodes = new \Rector\FileSystemRector\ValueObject\AddedFileWithNodes(self::ROUTE_NAME_FILE_LOCATION, [$namespace]);
        $this->removedAndAddedFilesCollector->addAddedFile($addedFileWithNodes);
        $this->isRouteNameValueObjectCreated = \true;
    }
}
