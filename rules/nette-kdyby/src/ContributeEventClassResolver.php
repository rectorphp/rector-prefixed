<?php

declare (strict_types=1);
namespace Rector\NetteKdyby;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\NetteKdyby\Naming\VariableNaming;
use Rector\NetteKdyby\ValueObject\EventAndListenerTree;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\StaticTypeMapper\StaticTypeMapper;
final class ContributeEventClassResolver
{
    /**
     * @var string[][]
     */
    private const CONTRIBUTTE_EVENT_GETTER_METHODS_WITH_TYPE = [
        // application
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\ShutdownEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication', 'Throwable' => 'getThrowable'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\StartupEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\ErrorEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication', 'Throwable' => 'getThrowable'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\PresenterEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication', 'RectorPrefix2020DecSat\\Nette\\Application\\IPresenter' => 'getPresenter'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\RequestEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication', 'RectorPrefix2020DecSat\\Nette\\Application\\Request' => 'getRequest'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\ResponseEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\Application' => 'getApplication', 'RectorPrefix2020DecSat\\Nette\\Application\\IResponse' => 'getResponse'],
        // presenter
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\PresenterShutdownEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\IPresenter' => 'getPresenter', 'RectorPrefix2020DecSat\\Nette\\Application\\IResponse' => 'getResponse'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Application\\PresenterStartupEvent' => ['RectorPrefix2020DecSat\\Nette\\Application\\UI\\Presenter' => 'getPresenter'],
        // nette/security
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Security\\LoggedInEvent' => ['RectorPrefix2020DecSat\\Nette\\Security\\User' => 'getUser'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Security\\LoggedOutEvent' => ['RectorPrefix2020DecSat\\Nette\\Security\\User' => 'getUser'],
        // latte
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Latte\\LatteCompileEvent' => ['RectorPrefix2020DecSat\\Latte\\Engine' => 'getEngine'],
        'RectorPrefix2020DecSat\\Contributte\\Events\\Extra\\Event\\Latte\\TemplateCreateEvent' => ['RectorPrefix2020DecSat\\Nette\\Bridges\\ApplicationLatte\\Template' => 'getTemplate'],
    ];
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var VariableNaming
     */
    private $variableNaming;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var BetterStandardPrinter
     */
    private $betterStandardPrinter;
    public function __construct(\Rector\Core\PhpParser\Printer\BetterStandardPrinter $betterStandardPrinter, \Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper, \Rector\NetteKdyby\Naming\VariableNaming $variableNaming)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->variableNaming = $variableNaming;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->betterStandardPrinter = $betterStandardPrinter;
    }
    public function resolveGetterMethodByEventClassAndParam(string $eventClass, \PhpParser\Node\Param $param, ?\Rector\NetteKdyby\ValueObject\EventAndListenerTree $eventAndListenerTree = null) : string
    {
        $getterMethodsWithType = self::CONTRIBUTTE_EVENT_GETTER_METHODS_WITH_TYPE[$eventClass] ?? null;
        $paramType = $param->type;
        // unwrap nullable type
        if ($paramType instanceof \PhpParser\Node\NullableType) {
            $paramType = $paramType->type;
        }
        if ($eventAndListenerTree !== null) {
            $getterMethodBlueprints = $eventAndListenerTree->getGetterMethodBlueprints();
            foreach ($getterMethodBlueprints as $getterMethodBlueprint) {
                if (!$getterMethodBlueprint->getReturnTypeNode() instanceof \PhpParser\Node\Name) {
                    continue;
                }
                if ($this->betterStandardPrinter->areNodesEqual($getterMethodBlueprint->getReturnTypeNode(), $paramType)) {
                    return $getterMethodBlueprint->getMethodName();
                }
            }
        }
        if ($paramType === null || $paramType instanceof \PhpParser\Node\Identifier) {
            if ($paramType === null) {
                $staticType = new \PHPStan\Type\MixedType();
            } else {
                $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($paramType);
            }
            return $this->createGetterFromParamAndStaticType($param, $staticType);
        }
        $type = $this->nodeNameResolver->getName($paramType);
        if ($type === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        // system contribute event
        if (isset($getterMethodsWithType[$type])) {
            return $getterMethodsWithType[$type];
        }
        $paramName = $this->nodeNameResolver->getName($param->var);
        if ($eventAndListenerTree !== null) {
            $getterMethodBlueprints = $eventAndListenerTree->getGetterMethodBlueprints();
            foreach ($getterMethodBlueprints as $getterMethodBlueprint) {
                if ($getterMethodBlueprint->getVariableName() === $paramName) {
                    return $getterMethodBlueprint->getMethodName();
                }
            }
        }
        $staticType = $this->staticTypeMapper->mapPhpParserNodePHPStanType($paramType);
        return $this->createGetterFromParamAndStaticType($param, $staticType);
    }
    private function createGetterFromParamAndStaticType(\PhpParser\Node\Param $param, \PHPStan\Type\Type $type) : string
    {
        $variableName = $this->variableNaming->resolveFromNodeAndType($param, $type);
        if ($variableName === null) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        return 'get' . \ucfirst($variableName);
    }
}
