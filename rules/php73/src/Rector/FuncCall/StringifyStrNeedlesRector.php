<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Php73\Rector\FuncCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Cast\String_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PHPStan\Type\StringType;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://wiki.php.net/rfc/deprecations_php_7_3#string_search_functions_with_integer_needle
 * @see \Rector\Php73\Tests\Rector\FuncCall\StringifyStrNeedlesRector\StringifyStrNeedlesRectorTest
 */
final class StringifyStrNeedlesRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string[]
     */
    private const NEEDLE_STRING_SENSITIVE_FUNCTIONS = ['strpos', 'strrpos', 'stripos', 'strstr', 'stripos', 'strripos', 'strstr', 'strchr', 'strrchr', 'stristr'];
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Makes needles explicit strings', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$needle = 5;
$fivePosition = strpos('725', $needle);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$needle = 5;
$fivePosition = strpos('725', (string) $needle);
CODE_SAMPLE
)]);
    }
    /**
     * @return string[]
     */
    public function getNodeTypes() : array
    {
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall::class];
    }
    /**
     * @param FuncCall $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (!$this->isNames($node, self::NEEDLE_STRING_SENSITIVE_FUNCTIONS)) {
            return null;
        }
        // is argument string?
        $needleArgNode = $node->args[1]->value;
        $nodeStaticType = $this->getStaticType($needleArgNode);
        if ($nodeStaticType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\StringType) {
            return null;
        }
        if ($node->args[1]->value instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Cast\String_) {
            return null;
        }
        $node->args[1]->value = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Cast\String_($node->args[1]->value);
        return $node;
    }
}
