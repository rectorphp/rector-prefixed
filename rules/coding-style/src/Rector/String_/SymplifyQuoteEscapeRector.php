<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\CodingStyle\Rector\String_;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\CodingStyle\Tests\Rector\String_\SymplifyQuoteEscapeRector\SymplifyQuoteEscapeRectorTest
 */
final class SymplifyQuoteEscapeRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/qEkCe9/2
     */
    private const ESCAPED_CHAR_REGEX = '#\\\\|\\$|\\n|\\t#sim';
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Prefer quote that are not inside the string', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
         $name = "\" Tom";
         $name = '\' Sara';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
         $name = '" Tom';
         $name = "' Sara";
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_::class];
    }
    /**
     * @param String_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        $doubleQuoteCount = \substr_count($node->value, '"');
        $singleQuoteCount = \substr_count($node->value, "'");
        $kind = $node->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::KIND);
        if ($kind === \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_::KIND_SINGLE_QUOTED) {
            $this->processSingleQuoted($node, $doubleQuoteCount, $singleQuoteCount);
        }
        $quoteKind = $node->getAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::KIND);
        if ($quoteKind === \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_::KIND_DOUBLE_QUOTED) {
            $this->processDoubleQuoted($node, $singleQuoteCount, $doubleQuoteCount);
        }
        return $node;
    }
    private function processSingleQuoted(\_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_ $string, int $doubleQuoteCount, int $singleQuoteCount) : void
    {
        if ($doubleQuoteCount === 0 && $singleQuoteCount > 0) {
            // contains chars that will be newly escaped
            if ($this->isMatchEscapedChars($string->value)) {
                return;
            }
            $string->setAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::KIND, \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_::KIND_DOUBLE_QUOTED);
            // invoke override
            $string->setAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        }
    }
    private function processDoubleQuoted(\_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_ $string, int $singleQuoteCount, int $doubleQuoteCount) : void
    {
        if ($singleQuoteCount === 0 && $doubleQuoteCount > 0) {
            // contains chars that will be newly escaped
            if ($this->isMatchEscapedChars($string->value)) {
                return;
            }
            $string->setAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::KIND, \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_::KIND_SINGLE_QUOTED);
            // invoke override
            $string->setAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::ORIGINAL_NODE, null);
        }
    }
    private function isMatchEscapedChars(string $string) : bool
    {
        return (bool) \_PhpScoperb75b35f52b74\Nette\Utils\Strings::match($string, self::ESCAPED_CHAR_REGEX);
    }
}
