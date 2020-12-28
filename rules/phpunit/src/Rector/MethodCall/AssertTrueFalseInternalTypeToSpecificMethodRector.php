<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\PhpParser\Node\Manipulator\IdentifierManipulator;
use Rector\Core\Rector\AbstractPHPUnitRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\PHPUnit\Tests\Rector\MethodCall\AssertTrueFalseInternalTypeToSpecificMethodRector\AssertTrueFalseInternalTypeToSpecificMethodRectorTest
 */
final class AssertTrueFalseInternalTypeToSpecificMethodRector extends \Rector\Core\Rector\AbstractPHPUnitRector
{
    /**
     * @var array<string, string>
     */
    private const OLD_FUNCTIONS_TO_TYPES = ['is_array' => 'array', 'is_bool' => 'bool', 'is_callable' => 'callable', 'is_double' => 'double', 'is_float' => 'float', 'is_int' => 'int', 'is_integer' => 'integer', 'is_iterable' => 'iterable', 'is_numeric' => 'numeric', 'is_object' => 'object', 'is_real' => 'real', 'is_resource' => 'resource', 'is_scalar' => 'scalar', 'is_string' => 'string'];
    /**
     * @var array<string, string>
     */
    private const RENAME_METHODS_MAP = ['assertTrue' => 'assertInternalType', 'assertFalse' => 'assertNotInternalType'];
    /**
     * @var IdentifierManipulator
     */
    private $identifierManipulator;
    public function __construct(\Rector\Core\PhpParser\Node\Manipulator\IdentifierManipulator $identifierManipulator)
    {
        $this->identifierManipulator = $identifierManipulator;
    }
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Turns true/false with internal type comparisons to their method name alternatives in PHPUnit TestCase', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('$this->assertTrue(is_{internal_type}($anything), "message");', '$this->assertInternalType({internal_type}, $anything, "message");'), new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('$this->assertFalse(is_{internal_type}($anything), "message");', '$this->assertNotInternalType({internal_type}, $anything, "message");')]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class, \PhpParser\Node\Expr\StaticCall::class];
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(\PhpParser\Node $node) : ?\PhpParser\Node
    {
        $oldMethods = \array_keys(self::RENAME_METHODS_MAP);
        if (!$this->isPHPUnitMethodNames($node, $oldMethods)) {
            return null;
        }
        /** @var FuncCall|Node $firstArgumentValue */
        $firstArgumentValue = $node->args[0]->value;
        if (!$firstArgumentValue instanceof \PhpParser\Node\Expr\FuncCall) {
            return null;
        }
        $functionName = $this->getName($firstArgumentValue);
        if (!isset(self::OLD_FUNCTIONS_TO_TYPES[$functionName])) {
            return null;
        }
        $this->identifierManipulator->renameNodeWithMap($node, self::RENAME_METHODS_MAP);
        return $this->moveFunctionArgumentsUp($node);
    }
    /**
     * @param MethodCall|StaticCall $node
     */
    private function moveFunctionArgumentsUp(\PhpParser\Node $node) : \PhpParser\Node
    {
        /** @var FuncCall $isFunctionNode */
        $isFunctionNode = $node->args[0]->value;
        $firstArgumentValue = $isFunctionNode->args[0]->value;
        $isFunctionName = $this->getName($isFunctionNode);
        $newArgs = [new \PhpParser\Node\Arg(new \PhpParser\Node\Scalar\String_(self::OLD_FUNCTIONS_TO_TYPES[$isFunctionName])), new \PhpParser\Node\Arg($firstArgumentValue)];
        $oldArguments = $node->args;
        unset($oldArguments[0]);
        $node->args = $this->appendArgs($newArgs, $oldArguments);
        return $node;
    }
}
