<?php

declare (strict_types=1);
namespace Rector\Symfony3\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Core\Rector\AbstractRector;
use Rector\Symfony3\NodeAnalyzer\FormAddMethodCallAnalyzer;
use Rector\Symfony3\NodeAnalyzer\FormCollectionAnalyzer;
use Rector\Symfony3\NodeAnalyzer\FormOptionsArrayMatcher;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://github.com/symfony/symfony/blob/2.8/UPGRADE-2.8.md#form
 *
 * @see \Rector\Tests\Symfony3\Rector\MethodCall\ChangeCollectionTypeOptionNameFromTypeToEntryTypeRector\ChangeCollectionTypeOptionNameFromTypeToEntryTypeRectorTest
 */
final class ChangeCollectionTypeOptionNameFromTypeToEntryTypeRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const OLD_TO_NEW_OPTION_NAME = ['type' => 'entry_type', 'options' => 'entry_options'];
    /**
     * @var FormAddMethodCallAnalyzer
     */
    private $formAddMethodCallAnalyzer;
    /**
     * @var FormOptionsArrayMatcher
     */
    private $formOptionsArrayMatcher;
    /**
     * @var FormCollectionAnalyzer
     */
    private $formCollectionAnalyzer;
    /**
     * @param \Rector\Symfony3\NodeAnalyzer\FormAddMethodCallAnalyzer $formAddMethodCallAnalyzer
     * @param \Rector\Symfony3\NodeAnalyzer\FormOptionsArrayMatcher $formOptionsArrayMatcher
     * @param \Rector\Symfony3\NodeAnalyzer\FormCollectionAnalyzer $formCollectionAnalyzer
     */
    public function __construct($formAddMethodCallAnalyzer, $formOptionsArrayMatcher, $formCollectionAnalyzer)
    {
        $this->formAddMethodCallAnalyzer = $formAddMethodCallAnalyzer;
        $this->formOptionsArrayMatcher = $formOptionsArrayMatcher;
        $this->formCollectionAnalyzer = $formCollectionAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Rename `type` option to `entry_type` in CollectionType', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tags', CollectionType::class, [
            'type' => ChoiceType::class,
            'options' => [1, 2, 3],
        ]);
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tags', CollectionType::class, [
            'entry_type' => ChoiceType::class,
            'entry_options' => [1, 2, 3],
        ]);
    }
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->formAddMethodCallAnalyzer->matches($node)) {
            return null;
        }
        if (!$this->formCollectionAnalyzer->isCollectionType($node)) {
            return null;
        }
        $optionsArray = $this->formOptionsArrayMatcher->match($node);
        if (!$optionsArray instanceof \PhpParser\Node\Expr\Array_) {
            return null;
        }
        $this->refactorOptionsArray($optionsArray);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Expr\Array_ $optionsArray
     */
    private function refactorOptionsArray($optionsArray) : void
    {
        foreach ($optionsArray->items as $arrayItem) {
            if ($arrayItem === null) {
                continue;
            }
            if ($arrayItem->key === null) {
                continue;
            }
            foreach (self::OLD_TO_NEW_OPTION_NAME as $oldName => $newName) {
                if (!$this->valueResolver->isValue($arrayItem->key, $oldName)) {
                    continue;
                }
                $arrayItem->key = new \PhpParser\Node\Scalar\String_($newName);
            }
        }
    }
}
