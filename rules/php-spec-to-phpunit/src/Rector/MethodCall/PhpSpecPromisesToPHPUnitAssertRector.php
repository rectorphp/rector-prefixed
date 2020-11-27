<?php

declare (strict_types=1);
namespace Rector\PhpSpecToPHPUnit\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpSpecToPHPUnit\MatchersManipulator;
use Rector\PhpSpecToPHPUnit\Naming\PhpSpecRenaming;
use Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector;
/**
 * @see \Rector\PhpSpecToPHPUnit\Tests\Rector\Variable\PhpSpecToPHPUnitRector\PhpSpecToPHPUnitRectorTest
 */
final class PhpSpecPromisesToPHPUnitAssertRector extends \Rector\PhpSpecToPHPUnit\Rector\AbstractPhpSpecToPHPUnitRector
{
    /**
     * @see https://github.com/phpspec/phpspec/blob/master/src/PhpSpec/Wrapper/Subject.php
     * ↓
     * @see https://phpunit.readthedocs.io/en/8.0/assertions.html
     * @var string[][]
     */
    private const NEW_METHOD_TO_OLD_METHODS = [
        'assertInstanceOf' => ['shouldBeAnInstanceOf', 'shouldHaveType', 'shouldReturnAnInstanceOf'],
        'assertSame' => ['shouldBe', 'shouldReturn'],
        'assertNotSame' => ['shouldNotBe', 'shouldNotReturn'],
        'assertCount' => ['shouldHaveCount'],
        'assertEquals' => ['shouldBeEqualTo'],
        'assertNotEquals' => ['shouldNotBeEqualTo'],
        'assertContains' => ['shouldContain'],
        'assertNotContains' => ['shouldNotContain'],
        // types
        'assertIsIterable' => ['shouldBeArray'],
        'assertIsNotIterable' => ['shouldNotBeArray'],
        'assertIsString' => ['shouldBeString'],
        'assertIsNotString' => ['shouldNotBeString'],
        'assertIsBool' => ['shouldBeBool', 'shouldBeBoolean'],
        'assertIsNotBool' => ['shouldNotBeBool', 'shouldNotBeBoolean'],
        'assertIsCallable' => ['shouldBeCallable'],
        'assertIsNotCallable' => ['shouldNotBeCallable'],
        'assertIsFloat' => ['shouldBeDouble', 'shouldBeFloat'],
        'assertIsNotFloat' => ['shouldNotBeDouble', 'shouldNotBeFloat'],
        'assertIsInt' => ['shouldBeInt', 'shouldBeInteger'],
        'assertIsNotInt' => ['shouldNotBeInt', 'shouldNotBeInteger'],
        'assertIsNull' => ['shouldBeNull'],
        'assertIsNotNull' => ['shouldNotBeNull'],
        'assertIsNumeric' => ['shouldBeNumeric'],
        'assertIsNotNumeric' => ['shouldNotBeNumeric'],
        'assertIsObject' => ['shouldBeObject'],
        'assertIsNotObject' => ['shouldNotBeObject'],
        'assertIsResource' => ['shouldBeResource'],
        'assertIsNotResource' => ['shouldNotBeResource'],
        'assertIsScalar' => ['shouldBeScalar'],
        'assertIsNotScalar' => ['shouldNotBeScalar'],
        'assertNan' => ['shouldBeNan'],
        'assertFinite' => ['shouldBeFinite', 'shouldNotBeFinite'],
        'assertInfinite' => ['shouldBeInfinite', 'shouldNotBeInfinite'],
    ];
    /**
     * @var string
     */
    private const THIS = 'this';
    /**
     * @var string
     */
    private $testedClass;
    /**
     * @var bool
     */
    private $isBoolAssert = \false;
    /**
     * @var bool
     */
    private $isPrepared = \false;
    /**
     * @var string[]
     */
    private $matchersKeys = [];
    /**
     * @var PropertyFetch
     */
    private $testedObjectPropertyFetch;
    /**
     * @var PhpSpecRenaming
     */
    private $phpSpecRenaming;
    /**
     * @var MatchersManipulator
     */
    private $matchersManipulator;
    public function __construct(\Rector\PhpSpecToPHPUnit\MatchersManipulator $matchersManipulator, \Rector\PhpSpecToPHPUnit\Naming\PhpSpecRenaming $phpSpecRenaming)
    {
        $this->phpSpecRenaming = $phpSpecRenaming;
        $this->matchersManipulator = $matchersManipulator;
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $this->isPrepared = \false;
        $this->matchersKeys = [];
        if (!$this->isInPhpSpecBehavior($node)) {
            return null;
        }
        if ($this->isName($node->name, 'getWrappedObject')) {
            return $node->var;
        }
        if ($this->isName($node->name, 'during')) {
            return $this->processDuring($node);
        }
        if ($this->isName($node->name, 'duringInstantiation')) {
            return $this->processDuringInstantiation($node);
        }
        if ($this->isName($node->name, 'getMatchers')) {
            return null;
        }
        $this->prepare($node);
        if ($this->isName($node->name, 'beConstructed*')) {
            return $this->processBeConstructed($node);
        }
        $this->processMatchersKeys($node);
        foreach (self::NEW_METHOD_TO_OLD_METHODS as $newMethod => $oldMethods) {
            if ($this->isNames($node->name, $oldMethods)) {
                return $this->createAssertMethod($newMethod, $node->var, $node->args[0]->value ?? null);
            }
        }
        if ($this->shouldSkip($node)) {
            return null;
        }
        // $this->clone() → clone $this->testedObject
        if ($this->isName($node->name, 'clone')) {
            return new \PhpParser\Node\Expr\Clone_($this->testedObjectPropertyFetch);
        }
        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }
        /** @var Class_ $classLike */
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        $classMethod = $classLike->getMethod($methodName);
        // it's a method call, skip
        if ($classMethod !== null) {
            return null;
        }
        $node->var = $this->testedObjectPropertyFetch;
        return $node;
    }
    private function processDuring(\PhpParser\Node\Expr\MethodCall $methodCall) : \PhpParser\Node\Expr\MethodCall
    {
        if (!isset($methodCall->args[0])) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $name = $this->getValue($methodCall->args[0]->value);
        $thisObjectPropertyMethodCall = new \PhpParser\Node\Expr\MethodCall($this->testedObjectPropertyFetch, $name);
        if (isset($methodCall->args[1]) && $methodCall->args[1]->value instanceof \PhpParser\Node\Expr\Array_) {
            /** @var Array_ $array */
            $array = $methodCall->args[1]->value;
            if (isset($array->items[0])) {
                $thisObjectPropertyMethodCall->args[] = new \PhpParser\Node\Arg($array->items[0]->value);
            }
        }
        /** @var MethodCall $parentMethodCall */
        $parentMethodCall = $methodCall->var;
        $parentMethodCall->name = new \PhpParser\Node\Identifier('expectException');
        // add $this->object->someCall($withArgs)
        $this->addNodeAfterNode($thisObjectPropertyMethodCall, $methodCall);
        return $parentMethodCall;
    }
    private function processDuringInstantiation(\PhpParser\Node\Expr\MethodCall $methodCall) : \PhpParser\Node\Expr\MethodCall
    {
        /** @var MethodCall $parentMethodCall */
        $parentMethodCall = $methodCall->var;
        $parentMethodCall->name = new \PhpParser\Node\Identifier('expectException');
        return $parentMethodCall;
    }
    private function prepare(\PhpParser\Node $node) : void
    {
        if ($this->isPrepared) {
            return;
        }
        /** @var Class_ $classLike */
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        $this->matchersKeys = $this->matchersManipulator->resolveMatcherNamesFromClass($classLike);
        $this->testedClass = $this->phpSpecRenaming->resolveTestedClass($node);
        $this->testedObjectPropertyFetch = $this->createTestedObjectPropertyFetch($classLike);
        $this->isPrepared = \true;
    }
    private function processBeConstructed(\PhpParser\Node\Expr\MethodCall $methodCall) : ?\PhpParser\Node
    {
        if ($this->isName($methodCall->name, 'beConstructedWith')) {
            $new = new \PhpParser\Node\Expr\New_(new \PhpParser\Node\Name\FullyQualified($this->testedClass));
            $new->args = $methodCall->args;
            return new \PhpParser\Node\Expr\Assign($this->testedObjectPropertyFetch, $new);
        }
        if ($this->isName($methodCall->name, 'beConstructedThrough')) {
            // static method
            $methodName = $this->getValue($methodCall->args[0]->value);
            $staticCall = $this->createStaticCall($this->testedClass, $methodName);
            $this->moveConstructorArguments($methodCall, $staticCall);
            return new \PhpParser\Node\Expr\Assign($this->testedObjectPropertyFetch, $staticCall);
        }
        return null;
    }
    /**
     * @see https://johannespichler.com/writing-custom-phpspec-matchers/
     */
    private function processMatchersKeys(\PhpParser\Node\Expr\MethodCall $methodCall) : void
    {
        foreach ($this->matchersKeys as $matcherKey) {
            if (!$this->isName($methodCall->name, 'should' . \ucfirst($matcherKey))) {
                continue;
            }
            if (!$methodCall->var instanceof \PhpParser\Node\Expr\MethodCall) {
                continue;
            }
            // 1. assign callable to variable
            $thisGetMatchers = $this->createMethodCall(self::THIS, 'getMatchers');
            $arrayDimFetch = new \PhpParser\Node\Expr\ArrayDimFetch($thisGetMatchers, new \PhpParser\Node\Scalar\String_($matcherKey));
            $matcherCallableVariable = new \PhpParser\Node\Expr\Variable('matcherCallable');
            $assign = new \PhpParser\Node\Expr\Assign($matcherCallableVariable, $arrayDimFetch);
            // 2. call it on result
            $funcCall = new \PhpParser\Node\Expr\FuncCall($matcherCallableVariable);
            $funcCall->args = $methodCall->args;
            $methodCall->name = $methodCall->var->name;
            $methodCall->var = $this->testedObjectPropertyFetch;
            $methodCall->args = [];
            $funcCall->args[] = new \PhpParser\Node\Arg($methodCall);
            $this->addNodesAfterNode([$assign, $funcCall], $methodCall);
            $this->removeNode($methodCall);
            return;
        }
    }
    private function createAssertMethod(string $name, \PhpParser\Node\Expr $value, ?\PhpParser\Node\Expr $expected) : \PhpParser\Node\Expr\MethodCall
    {
        $this->isBoolAssert = \false;
        // special case with bool!
        if ($expected !== null) {
            $name = $this->resolveBoolMethodName($name, $expected);
        }
        $assetMethodCall = $this->createMethodCall(self::THIS, $name);
        if (!$this->isBoolAssert && $expected) {
            $assetMethodCall->args[] = new \PhpParser\Node\Arg($this->thisToTestedObjectPropertyFetch($expected));
        }
        $assetMethodCall->args[] = new \PhpParser\Node\Arg($this->thisToTestedObjectPropertyFetch($value));
        return $assetMethodCall;
    }
    private function shouldSkip(\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$this->isVariableName($methodCall->var, self::THIS)) {
            return \true;
        }
        // skip "createMock" method
        return $this->isName($methodCall->name, 'createMock');
    }
    private function createTestedObjectPropertyFetch(\PhpParser\Node\Stmt\Class_ $class) : \PhpParser\Node\Expr\PropertyFetch
    {
        $propertyName = $this->phpSpecRenaming->resolveObjectPropertyName($class);
        return new \PhpParser\Node\Expr\PropertyFetch(new \PhpParser\Node\Expr\Variable(self::THIS), $propertyName);
    }
    private function moveConstructorArguments(\PhpParser\Node\Expr\MethodCall $methodCall, \PhpParser\Node\Expr\StaticCall $staticCall) : void
    {
        if (!isset($methodCall->args[1])) {
            return;
        }
        if (!$methodCall->args[1]->value instanceof \PhpParser\Node\Expr\Array_) {
            return;
        }
        /** @var Array_ $array */
        $array = $methodCall->args[1]->value;
        foreach ($array->items as $arrayItem) {
            $staticCall->args[] = new \PhpParser\Node\Arg($arrayItem->value);
        }
    }
    private function resolveBoolMethodName(string $name, \PhpParser\Node\Expr $expr) : string
    {
        if (!$this->isBool($expr)) {
            return $name;
        }
        if ($name === 'assertSame') {
            $this->isBoolAssert = \true;
            return $this->isFalse($expr) ? 'assertFalse' : 'assertTrue';
        }
        if ($name === 'assertNotSame') {
            $this->isBoolAssert = \true;
            return $this->isFalse($expr) ? 'assertNotFalse' : 'assertNotTrue';
        }
        return $name;
    }
    private function thisToTestedObjectPropertyFetch(\PhpParser\Node\Expr $expr) : \PhpParser\Node\Expr
    {
        if (!$this->isVariableName($expr, self::THIS)) {
            return $expr;
        }
        return $this->testedObjectPropertyFetch;
    }
}