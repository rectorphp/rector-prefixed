<?php

declare (strict_types=1);
namespace Rector\Nette\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Core\Rector\AbstractRector;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Nette\Tests\Rector\MethodCall\ConvertAddUploadWithThirdArgumentTrueToAddMultiUploadRector\ConvertAddUploadWithThirdArgumentTrueToAddMultiUploadRectorTest
 */
final class ConvertAddUploadWithThirdArgumentTrueToAddMultiUploadRector extends \Rector\Core\Rector\AbstractRector
{
    public function getRuleDefinition() : \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('convert addUpload() with 3rd argument true to addMultiUpload()', [new \RectorPrefix20201228\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
$form = new Nette\Forms\Form();
$form->addUpload('...', '...', true);
CODE_SAMPLE
, <<<'CODE_SAMPLE'
$form = new Nette\Forms\Form();
$form->addMultiUpload('...', '...');
CODE_SAMPLE
)]);
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
        if (!$this->isObjectType($node->var, 'RectorPrefix20201228\\Nette\\Forms\\Form')) {
            return null;
        }
        if (!$this->isName($node->name, 'addUpload')) {
            return null;
        }
        $args = $node->args;
        if (!isset($args[2])) {
            return null;
        }
        if ($this->isTrue($node->args[2]->value)) {
            $node->name = new \PhpParser\Node\Identifier('addMultiUpload');
            unset($node->args[2]);
            return $node;
        }
        return null;
    }
}
