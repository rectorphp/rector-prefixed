<?php

declare (strict_types=1);
namespace Rector\Php70\Rector\FuncCall;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Php70\EregToPcreTransformer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see http://php.net/reference.pcre.pattern.posix
 * @see https://stackoverflow.com/a/17033826/1348344
 * @see https://docstore.mik.ua/orelly/webprog/pcook/ch13_02.htm
 *
 * @see \Rector\Tests\Php70\Rector\FuncCall\EregToPregMatchRector\EregToPregMatchRectorTest
 */
final class EregToPregMatchRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const OLD_NAMES_TO_NEW_ONES = ['ereg' => 'preg_match', 'eregi' => 'preg_match', 'ereg_replace' => 'preg_replace', 'eregi_replace' => 'preg_replace', 'split' => 'preg_split', 'spliti' => 'preg_split'];
    /**
     * @var EregToPcreTransformer
     */
    private $eregToPcreTransformer;
    /**
     * @param \Rector\Php70\EregToPcreTransformer $eregToPcreTransformer
     */
    public function __construct($eregToPcreTransformer)
    {
        $this->eregToPcreTransformer = $eregToPcreTransformer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Changes ereg*() to preg*() calls', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample('ereg("hi")', 'preg_match("#hi#");')]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $functionName = $this->getName($node);
        if ($functionName === null) {
            return null;
        }
        if (!isset(self::OLD_NAMES_TO_NEW_ONES[$functionName])) {
            return null;
        }
        $patternNode = $node->args[0]->value;
        if ($patternNode instanceof \PhpParser\Node\Scalar\String_) {
            $this->processStringPattern($node, $patternNode, $functionName);
        } elseif ($patternNode instanceof \PhpParser\Node\Expr\Variable) {
            $this->processVariablePattern($node, $patternNode, $functionName);
        }
        $this->processSplitLimitArgument($node, $functionName);
        $node->name = new \PhpParser\Node\Name(self::OLD_NAMES_TO_NEW_ONES[$functionName]);
        // ereg|eregi 3rd argument return value fix
        if (\in_array($functionName, ['ereg', 'eregi'], \true) && isset($node->args[2])) {
            $parentNode = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parentNode instanceof \PhpParser\Node\Expr\Assign) {
                return $this->createTernaryWithStrlenOfFirstMatch($node);
            }
        }
        return $node;
    }
    /**
     * @param \PhpParser\Node\Expr\FuncCall $funcCall
     * @param \PhpParser\Node\Scalar\String_ $string
     * @param string $functionName
     */
    private function processStringPattern($funcCall, $string, $functionName) : void
    {
        $pattern = $string->value;
        $pattern = $this->eregToPcreTransformer->transform($pattern, $this->isCaseInsensitiveFunction($functionName));
        $funcCall->args[0]->value = new \PhpParser\Node\Scalar\String_($pattern);
    }
    /**
     * @param \PhpParser\Node\Expr\FuncCall $funcCall
     * @param \PhpParser\Node\Expr\Variable $variable
     * @param string $functionName
     */
    private function processVariablePattern($funcCall, $variable, $functionName) : void
    {
        $pregQuotePatternNode = $this->nodeFactory->createFuncCall('preg_quote', [new \PhpParser\Node\Arg($variable), new \PhpParser\Node\Arg(new \PhpParser\Node\Scalar\String_('#'))]);
        $startConcat = new \PhpParser\Node\Expr\BinaryOp\Concat(new \PhpParser\Node\Scalar\String_('#'), $pregQuotePatternNode);
        $endDelimiter = $this->isCaseInsensitiveFunction($functionName) ? '#mi' : '#m';
        $concat = new \PhpParser\Node\Expr\BinaryOp\Concat($startConcat, new \PhpParser\Node\Scalar\String_($endDelimiter));
        $funcCall->args[0]->value = $concat;
    }
    /**
     * Equivalent of:
     * split(' ', 'hey Tom', 0);
     * ↓
     * preg_split('# #', 'hey Tom', 1);
     * @param \PhpParser\Node\Expr\FuncCall $funcCall
     * @param string $functionName
     */
    private function processSplitLimitArgument($funcCall, $functionName) : void
    {
        if (!\RectorPrefix20210317\Nette\Utils\Strings::startsWith($functionName, 'split')) {
            return;
        }
        // 3rd argument - $limit, 0 → 1
        if (!isset($funcCall->args[2])) {
            return;
        }
        if (!$funcCall->args[2]->value instanceof \PhpParser\Node\Scalar\LNumber) {
            return;
        }
        /** @var LNumber $limitNumberNode */
        $limitNumberNode = $funcCall->args[2]->value;
        if ($limitNumberNode->value !== 0) {
            return;
        }
        $limitNumberNode->value = 1;
    }
    /**
     * @param \PhpParser\Node\Expr\FuncCall $funcCall
     */
    private function createTernaryWithStrlenOfFirstMatch($funcCall) : \PhpParser\Node\Expr\Ternary
    {
        $arrayDimFetch = new \PhpParser\Node\Expr\ArrayDimFetch($funcCall->args[2]->value, new \PhpParser\Node\Scalar\LNumber(0));
        $strlenFuncCall = $this->nodeFactory->createFuncCall('strlen', [$arrayDimFetch]);
        return new \PhpParser\Node\Expr\Ternary($funcCall, $strlenFuncCall, $this->nodeFactory->createFalse());
    }
    /**
     * @param string $functionName
     */
    private function isCaseInsensitiveFunction($functionName) : bool
    {
        if (\RectorPrefix20210317\Nette\Utils\Strings::contains($functionName, 'eregi')) {
            return \true;
        }
        return \RectorPrefix20210317\Nette\Utils\Strings::contains($functionName, 'spliti');
    }
}
