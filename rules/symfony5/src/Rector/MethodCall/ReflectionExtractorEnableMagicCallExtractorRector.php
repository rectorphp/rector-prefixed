<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Symfony5\Rector\MethodCall;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/symfony/symfony/blob/5.x/UPGRADE-5.2.md#propertyinfo
 * @see \Rector\Symfony5\Tests\Rector\MethodCall\ReflectionExtractorEnableMagicCallExtractorRector\ReflectionExtractorEnableMagicCallExtractorRectorTest
 */
final class ReflectionExtractorEnableMagicCallExtractorRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const OLD_OPTION_NAME = 'enable_magic_call_extraction';
    /**
     * @var string
     */
    private const NEW_OPTION_NAME = 'enable_magic_methods_extraction';
    /**
     * @var string[]
     */
    private const METHODS_WITH_OPTION = ['getWriteInfo', 'getReadInfo'];
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Migrates from deprecated enable_magic_call_extraction context option in ReflectionExtractor', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class SomeClass
{
    public function run()
    {
        $reflectionExtractor = new ReflectionExtractor();
        $readInfo = $reflectionExtractor->getReadInfo(Dummy::class, 'bar', [
            'enable_magic_call_extraction' => true,
        ]);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

class SomeClass
{
    public function run()
    {
        $reflectionExtractor = new ReflectionExtractor();
        $readInfo = $reflectionExtractor->getReadInfo(Dummy::class, 'bar', [
            'enable_magic_methods_extraction' => ReflectionExtractor::MAGIC_CALL | ReflectionExtractor::MAGIC_GET | ReflectionExtractor::MAGIC_SET,
        ]);
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
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param MethodCall $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if ($this->shouldSkip($node)) {
            return null;
        }
        $contextOptionValue = $this->getContextOptionValue($node);
        if ($contextOptionValue === null) {
            return null;
        }
        /** @var Array_ $contextOptions */
        $contextOptions = $node->args[2]->value;
        $contextOptions->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem($this->prepareEnableMagicMethodsExtractionFlags($contextOptionValue), new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_(self::NEW_OPTION_NAME));
        return $node;
    }
    private function shouldSkip(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall $methodCall) : bool
    {
        if (!$this->isObjectType($methodCall, '_PhpScoperb75b35f52b74\\Symfony\\Component\\PropertyInfo\\Extractor\\ReflectionExtractor')) {
            return \true;
        }
        if (!$this->isNames($methodCall->name, self::METHODS_WITH_OPTION)) {
            return \true;
        }
        if (\count((array) $methodCall->args) < 3) {
            return \true;
        }
        /** @var Array_ $contextOptions */
        $contextOptions = $methodCall->args[2]->value;
        return $contextOptions->items === [];
    }
    private function getContextOptionValue(\_PhpScoperb75b35f52b74\PhpParser\Node\Expr\MethodCall $methodCall) : ?bool
    {
        /** @var Array_ $contextOptions */
        $contextOptions = $methodCall->args[2]->value;
        $contextOptionValue = null;
        foreach ($contextOptions->items as $index => $arrayItem) {
            if (!$arrayItem instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem) {
                continue;
            }
            if (!$arrayItem->key instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_) {
                continue;
            }
            if ($arrayItem->key->value !== self::OLD_OPTION_NAME) {
                continue;
            }
            $contextOptionValue = $this->isTrue($arrayItem->value);
            unset($contextOptions->items[$index]);
        }
        return $contextOptionValue;
    }
    private function prepareEnableMagicMethodsExtractionFlags(bool $enableMagicCallExtractionValue) : \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BitwiseOr
    {
        $classConstFetch = $this->createClassConstFetch('_PhpScoperb75b35f52b74\\Symfony\\Component\\PropertyInfo\\Extractor\\ReflectionExtractor', 'MAGIC_GET');
        $magicSet = $this->createClassConstFetch('_PhpScoperb75b35f52b74\\Symfony\\Component\\PropertyInfo\\Extractor\\ReflectionExtractor', 'MAGIC_SET');
        if (!$enableMagicCallExtractionValue) {
            return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BitwiseOr($classConstFetch, $magicSet);
        }
        return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BitwiseOr(new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp\BitwiseOr($this->createClassConstFetch('_PhpScoperb75b35f52b74\\Symfony\\Component\\PropertyInfo\\Extractor\\ReflectionExtractor', 'MAGIC_CALL'), $classConstFetch), $magicSet);
    }
}
