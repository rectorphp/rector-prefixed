<?php

declare (strict_types=1);
namespace Rector\NetteToSymfony\Rector\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassLike;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see https://doc.nette.org/en/2.4/forms
 * ↓
 * @see https://symfony.com/doc/current/forms.html
 *
 * @see https://github.com/nette/forms/blob/master/src/Forms/Container.php
 * ↓
 * @see https://github.com/symfony/symfony/tree/master/src/Symfony/Component/Form/Extension/Core/Type
 * @see \Rector\Tests\NetteToSymfony\Rector\MethodCall\NetteFormToSymfonyFormRector\NetteFormToSymfonyFormRectorTest
 */
final class NetteFormToSymfonyFormRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const ADD_METHOD_TO_FORM_TYPE = [
        'addText' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType',
        'addPassword' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\PasswordType',
        'addTextArea' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType',
        'addEmail' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\EmailType',
        'addInteger' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\IntegerType',
        'addHidden' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\HiddenType',
        // https://symfony.com/doc/current/reference/forms/types/checkbox.html
        'addCheckbox' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\CheckboxType',
        'addUpload' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType',
        'addImage' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType',
        'addMultiUpload' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType',
        // https://symfony.com/doc/current/reference/forms/types/choice.html#select-tag-checkboxes-or-radio-buttons
        'addSelect' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType',
        'addRadioList' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType',
        'addCheckboxList' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType',
        'addMultiSelect' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType',
        'addSubmit' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\SubmitType',
        'addButton' => 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ButtonType',
    ];
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Migrate Nette\\Forms in Presenter to Symfony', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Nette\Application\UI;

class SomePresenter extends UI\Presenter
{
    public function someAction()
    {
        $form = new UI\Form;
        $form->addText('name', 'Name:');
        $form->addPassword('password', 'Password:');
        $form->addSubmit('login', 'Sign up');
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Nette\Application\UI;

class SomePresenter extends UI\Presenter
{
    public function someAction()
    {
        $form = $this->createFormBuilder();
        $form->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
            'label' => 'Name:'
        ]);
        $form->add('password', \Symfony\Component\Form\Extension\Core\Type\PasswordType::class, [
            'label' => 'Password:'
        ]);
        $form->add('login', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
            'label' => 'Sign up'
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
        return [\PhpParser\Node\Expr\New_::class, \PhpParser\Node\Expr\MethodCall::class];
    }
    /**
     * @param New_|MethodCall $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        $classLike = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::CLASS_NODE);
        if (!$classLike instanceof \PhpParser\Node\Stmt\ClassLike) {
            return null;
        }
        if (!$this->isObjectType($classLike, new \PHPStan\Type\ObjectType('Nette\\Application\\IPresenter'))) {
            return null;
        }
        if ($node instanceof \PhpParser\Node\Expr\New_) {
            return $this->processNew($node);
        }
        /** @var MethodCall $node */
        if (!$this->isObjectType($node->var, new \PHPStan\Type\ObjectType('Nette\\Application\\UI\\Form'))) {
            return null;
        }
        foreach (self::ADD_METHOD_TO_FORM_TYPE as $method => $classType) {
            if (!$this->isName($node->name, $method)) {
                continue;
            }
            $this->processAddMethod($node, $method, $classType);
        }
        return $node;
    }
    /**
     * @param \PhpParser\Node\Expr\New_ $new
     */
    private function processNew($new) : ?\PhpParser\Node\Expr\MethodCall
    {
        if (!$this->isName($new->class, 'Nette\\Application\\UI\\Form')) {
            return null;
        }
        return $this->nodeFactory->createMethodCall('this', 'createFormBuilder');
    }
    /**
     * @param \PhpParser\Node\Expr\MethodCall $methodCall
     * @param string $method
     * @param string $classType
     */
    private function processAddMethod($methodCall, $method, $classType) : void
    {
        $methodCall->name = new \PhpParser\Node\Identifier('add');
        // remove unused params
        if ($method === 'addText') {
            unset($methodCall->args[3], $methodCall->args[4]);
        }
        // has label
        $optionsArray = new \PhpParser\Node\Expr\Array_();
        if (isset($methodCall->args[1])) {
            $optionsArray->items[] = new \PhpParser\Node\Expr\ArrayItem($methodCall->args[1]->value, new \PhpParser\Node\Scalar\String_('label'));
        }
        $this->addChoiceTypeOptions($method, $optionsArray);
        $this->addMultiFileTypeOptions($method, $optionsArray);
        $methodCall->args[1] = new \PhpParser\Node\Arg($this->nodeFactory->createClassConstReference($classType));
        if ($optionsArray->items !== []) {
            $methodCall->args[2] = new \PhpParser\Node\Arg($optionsArray);
        }
    }
    /**
     * @param string $method
     * @param \PhpParser\Node\Expr\Array_ $optionsArray
     */
    private function addChoiceTypeOptions($method, $optionsArray) : void
    {
        if ($method === 'addSelect') {
            $expanded = \false;
            $multiple = \false;
        } elseif ($method === 'addRadioList') {
            $expanded = \true;
            $multiple = \false;
        } elseif ($method === 'addCheckboxList') {
            $expanded = \true;
            $multiple = \true;
        } elseif ($method === 'addMultiSelect') {
            $expanded = \false;
            $multiple = \true;
        } else {
            return;
        }
        $optionsArray->items[] = new \PhpParser\Node\Expr\ArrayItem($expanded ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse(), new \PhpParser\Node\Scalar\String_('expanded'));
        $optionsArray->items[] = new \PhpParser\Node\Expr\ArrayItem($multiple ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse(), new \PhpParser\Node\Scalar\String_('multiple'));
    }
    /**
     * @param string $method
     * @param \PhpParser\Node\Expr\Array_ $optionsArray
     */
    private function addMultiFileTypeOptions($method, $optionsArray) : void
    {
        if ($method !== 'addMultiUpload') {
            return;
        }
        $optionsArray->items[] = new \PhpParser\Node\Expr\ArrayItem($this->nodeFactory->createTrue(), new \PhpParser\Node\Scalar\String_('multiple'));
    }
}
