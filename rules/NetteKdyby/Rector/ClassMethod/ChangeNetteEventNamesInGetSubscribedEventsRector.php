<?php

declare (strict_types=1);
namespace Rector\NetteKdyby\Rector\ClassMethod;

use RectorPrefix20210317\Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\Core\Rector\AbstractRector;
use Rector\NetteKdyby\NodeAnalyzer\GetSubscribedEventsClassMethodAnalyzer;
use Rector\NetteKdyby\NodeManipulator\GetSubscribedEventsArrayManipulator;
use Rector\NetteKdyby\NodeManipulator\ListeningClassMethodArgumentManipulator;
use Rector\NetteKdyby\NodeResolver\ListeningMethodsCollector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\Tests\NetteKdyby\Rector\ClassMethod\ChangeNetteEventNamesInGetSubscribedEventsRector\ChangeNetteEventNamesInGetSubscribedEventsRectorTest
 */
final class ChangeNetteEventNamesInGetSubscribedEventsRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var GetSubscribedEventsArrayManipulator
     */
    private $getSubscribedEventsArrayManipulator;
    /**
     * @var ListeningClassMethodArgumentManipulator
     */
    private $listeningClassMethodArgumentManipulator;
    /**
     * @var ListeningMethodsCollector
     */
    private $listeningMethodsCollector;
    /**
     * @var GetSubscribedEventsClassMethodAnalyzer
     */
    private $getSubscribedEventsClassMethodAnalyzer;
    /**
     * @param \Rector\NetteKdyby\NodeManipulator\GetSubscribedEventsArrayManipulator $getSubscribedEventsArrayManipulator
     * @param \Rector\NetteKdyby\NodeManipulator\ListeningClassMethodArgumentManipulator $listeningClassMethodArgumentManipulator
     * @param \Rector\NetteKdyby\NodeResolver\ListeningMethodsCollector $listeningMethodsCollector
     * @param \Rector\NetteKdyby\NodeAnalyzer\GetSubscribedEventsClassMethodAnalyzer $getSubscribedEventsClassMethodAnalyzer
     */
    public function __construct($getSubscribedEventsArrayManipulator, $listeningClassMethodArgumentManipulator, $listeningMethodsCollector, $getSubscribedEventsClassMethodAnalyzer)
    {
        $this->getSubscribedEventsArrayManipulator = $getSubscribedEventsArrayManipulator;
        $this->listeningClassMethodArgumentManipulator = $listeningClassMethodArgumentManipulator;
        $this->listeningMethodsCollector = $listeningMethodsCollector;
        $this->getSubscribedEventsClassMethodAnalyzer = $getSubscribedEventsClassMethodAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change EventSubscriber from Kdyby to Contributte', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Application\UI\Presenter;

class GetApplesSubscriber implements Subscriber
{
    public function getSubscribedEvents()
    {
        return [
            Application::class . '::onShutdown',
        ];
    }

    public function onShutdown(Presenter $presenter)
    {
        $presenterName = $presenter->getName();
        // ...
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Contributte\Events\Extra\Event\Application\ShutdownEvent;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;

class GetApplesSubscriber implements Subscriber
{
    public static function getSubscribedEvents()
    {
        return [
            ShutdownEvent::class => 'onShutdown',
        ];
    }

    public function onShutdown(ShutdownEvent $shutdownEvent)
    {
        $presenter = $shutdownEvent->getPresenter();
        $presenterName = $presenter->getName();
        // ...
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
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }
    /**
     * @param \PhpParser\Node $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->getSubscribedEventsClassMethodAnalyzer->detect($node)) {
            return null;
        }
        $this->visibilityManipulator->makeStatic($node);
        $this->refactorEventNames($node);
        $listeningClassMethods = $this->listeningMethodsCollector->collectFromClassAndGetSubscribedEventClassMethod($node, \Rector\NetteKdyby\NodeResolver\ListeningMethodsCollector::EVENT_TYPE_CONTRIBUTTE);
        $this->listeningClassMethodArgumentManipulator->change($listeningClassMethods);
        return $node;
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $classMethod
     */
    private function refactorEventNames($classMethod) : void
    {
        $this->traverseNodesWithCallable((array) $classMethod->stmts, function (\PhpParser\Node $node) {
            if (!$node instanceof \PhpParser\Node\Stmt\Return_) {
                return null;
            }
            if ($node->expr === null) {
                return null;
            }
            $returnedExpr = $node->expr;
            if (!$returnedExpr instanceof \PhpParser\Node\Expr\Array_) {
                return null;
            }
            $this->refactorArrayWithEventTable($returnedExpr);
            $this->getSubscribedEventsArrayManipulator->change($returnedExpr);
        });
    }
    /**
     * @param \PhpParser\Node\Expr\Array_ $array
     */
    private function refactorArrayWithEventTable($array) : void
    {
        foreach ($array->items as $arrayItem) {
            if ($arrayItem === null) {
                continue;
            }
            if ($arrayItem->key !== null) {
                continue;
            }
            $methodName = $this->resolveMethodNameFromKdybyEventName($arrayItem->value);
            $arrayItem->key = $arrayItem->value;
            $arrayItem->value = new \PhpParser\Node\Scalar\String_($methodName);
        }
    }
    /**
     * @param \PhpParser\Node\Expr $expr
     */
    private function resolveMethodNameFromKdybyEventName($expr) : string
    {
        $kdybyEventName = $this->valueResolver->getValue($expr);
        if (\RectorPrefix20210317\Nette\Utils\Strings::contains($kdybyEventName, '::')) {
            return (string) \RectorPrefix20210317\Nette\Utils\Strings::after($kdybyEventName, '::', -1);
        }
        throw new \Rector\Core\Exception\NotImplementedYetException($kdybyEventName);
    }
}
