<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Type\Php;

use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\Scope;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\SpecifiedTypes;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifier;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifierAwareExtension;
use _PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifierContext;
use _PhpScoperb75b35f52b74\PHPStan\Reflection\FunctionReflection;
use _PhpScoperb75b35f52b74\PHPStan\Type\FloatType;
use _PhpScoperb75b35f52b74\PHPStan\Type\FunctionTypeSpecifyingExtension;
class IsFloatFunctionTypeSpecifyingExtension implements \_PhpScoperb75b35f52b74\PHPStan\Type\FunctionTypeSpecifyingExtension, \_PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifierAwareExtension
{
    /** @var \PHPStan\Analyser\TypeSpecifier */
    private $typeSpecifier;
    public function isFunctionSupported(\_PhpScoperb75b35f52b74\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall $node, \_PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifierContext $context) : bool
    {
        return \in_array(\strtolower($functionReflection->getName()), ['is_float', 'is_double', 'is_real'], \true) && isset($node->args[0]) && !$context->null();
    }
    public function specifyTypes(\_PhpScoperb75b35f52b74\PHPStan\Reflection\FunctionReflection $functionReflection, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\FuncCall $node, \_PhpScoperb75b35f52b74\PHPStan\Analyser\Scope $scope, \_PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifierContext $context) : \_PhpScoperb75b35f52b74\PHPStan\Analyser\SpecifiedTypes
    {
        if ($context->null()) {
            throw new \_PhpScoperb75b35f52b74\PHPStan\ShouldNotHappenException();
        }
        return $this->typeSpecifier->create($node->args[0]->value, new \_PhpScoperb75b35f52b74\PHPStan\Type\FloatType(), $context);
    }
    public function setTypeSpecifier(\_PhpScoperb75b35f52b74\PHPStan\Analyser\TypeSpecifier $typeSpecifier) : void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
