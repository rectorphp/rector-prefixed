<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\Rector\Class_;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ClassConstFetch;
use _PhpScoperb75b35f52b74\PhpParser\Node\Identifier;
use _PhpScoperb75b35f52b74\PhpParser\Node\Name\FullyQualified;
use _PhpScoperb75b35f52b74\PhpParser\Node\Scalar\LNumber;
use _PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod;
use _PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_;
use _PhpScoperb75b35f52b74\PHPStan\Type\ArrayType;
use _PhpScoperb75b35f52b74\PHPStan\Type\MixedType;
use _PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector;
use _PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature;
use _PhpScoperb75b35f52b74\Rector\Symfony\Contract\Tag\TagInterface;
use _PhpScoperb75b35f52b74\Rector\Symfony\ServiceMapProvider;
use _PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\ServiceDefinition;
use _PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag;
use _PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag;
use _PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @see \Rector\SymfonyCodeQuality\Tests\Rector\Class_\EventListenerToEventSubscriberRector\EventListenerToEventSubscriberRectorTest
 */
final class EventListenerToEventSubscriberRector extends \_PhpScoperb75b35f52b74\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     */
    private const EVENT_SUBSCRIBER_INTERFACE = '_PhpScoperb75b35f52b74\\Symfony\\Component\\EventDispatcher\\EventSubscriberInterface';
    /**
     * @var string
     */
    private const KERNEL_EVENTS_CLASS = '_PhpScoperb75b35f52b74\\Symfony\\Component\\HttpKernel\\KernelEvents';
    /**
     * @var string
     */
    private const CONSOLE_EVENTS_CLASS = '_PhpScoperb75b35f52b74\\Symfony\\Component\\Console\\ConsoleEvents';
    /**
     * @var string
     * @see https://regex101.com/r/qiHZ4T/1
     */
    private const LISTENER_MATCH_REGEX = '#^(.*?)(Listener)?$#';
    /**
     * @var string
     * @see https://regex101.com/r/j6SAga/1
     */
    private const SYMFONY_FAMILY_REGEX = '#^(Symfony|Sensio|Doctrine)\\b#';
    /**
     * @var bool
     */
    private $areListenerClassesLoaded = \false;
    /**
     * @var EventNameToClassAndConstant[]
     */
    private $eventNamesToClassConstants = [];
    /**
     * @var ServiceDefinition[][][]
     */
    private $listenerClassesToEvents = [];
    /**
     * @var ServiceMapProvider
     */
    private $applicationServiceMapProvider;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\Symfony\ServiceMapProvider $applicationServiceMapProvider)
    {
        $this->applicationServiceMapProvider = $applicationServiceMapProvider;
        $this->eventNamesToClassConstants = [
            // kernel events
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.request', self::KERNEL_EVENTS_CLASS, 'REQUEST'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.exception', self::KERNEL_EVENTS_CLASS, 'EXCEPTION'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.view', self::KERNEL_EVENTS_CLASS, 'VIEW'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.controller', self::KERNEL_EVENTS_CLASS, 'CONTROLLER'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.controller_arguments', self::KERNEL_EVENTS_CLASS, 'CONTROLLER_ARGUMENTS'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.response', self::KERNEL_EVENTS_CLASS, 'RESPONSE'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.terminate', self::KERNEL_EVENTS_CLASS, 'TERMINATE'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('kernel.finish_request', self::KERNEL_EVENTS_CLASS, 'FINISH_REQUEST'),
            // console events
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('console.command', self::CONSOLE_EVENTS_CLASS, 'COMMAND'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('console.terminate', self::CONSOLE_EVENTS_CLASS, 'TERMINATE'),
            new \_PhpScoperb75b35f52b74\Rector\SymfonyCodeQuality\ValueObject\EventNameToClassAndConstant('console.error', self::CONSOLE_EVENTS_CLASS, 'ERROR'),
        ];
    }
    public function getRuleDefinition() : \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change Symfony Event listener class to Event Subscriber based on configuration in service.yaml file', [new \_PhpScoperb75b35f52b74\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
class SomeListener
{
     public function methodToBeCalled()
     {
     }
}

// in config.yaml
services:
    SomeListener:
        tags:
            - { name: kernel.event_listener, event: 'some_event', method: 'methodToBeCalled' }
CODE_SAMPLE
, <<<'CODE_SAMPLE'
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SomeEventSubscriber implements EventSubscriberInterface
{
     /**
      * @return string[]
      */
     public static function getSubscribedEvents(): array
     {
         return ['some_event' => 'methodToBeCalled'];
     }

     public function methodToBeCalled()
     {
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
        return [\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_::class];
    }
    /**
     * @param Class_ $node
     */
    public function refactor(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\PhpParser\Node
    {
        // anonymous class
        if ($node->name === null) {
            return null;
        }
        // is already a subscriber
        if ($this->isAlreadyEventSubscriber($node)) {
            return null;
        }
        // there must be event dispatcher in the application
        $listenerClassesToEventsToMethods = $this->getListenerClassesToEventsToMethods();
        if ($listenerClassesToEventsToMethods === []) {
            return null;
        }
        $className = $this->getName($node);
        if (!isset($listenerClassesToEventsToMethods[$className])) {
            return null;
        }
        return $this->changeListenerToSubscriberWithMethods($node, $listenerClassesToEventsToMethods[$className]);
    }
    private function isAlreadyEventSubscriber(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_ $class) : bool
    {
        foreach ((array) $class->implements as $implement) {
            if ($this->isName($implement, '_PhpScoperb75b35f52b74\\Symfony\\Component\\EventDispatcher\\EventSubscriberInterface')) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * @return ServiceDefinition[][][]
     */
    private function getListenerClassesToEventsToMethods() : array
    {
        if ($this->areListenerClassesLoaded) {
            return $this->listenerClassesToEvents;
        }
        $serviceMap = $this->applicationServiceMapProvider->provide();
        $eventListeners = $serviceMap->getServicesByTag('kernel.event_listener');
        foreach ($eventListeners as $eventListener) {
            // skip Symfony core listeners
            if (\_PhpScoperb75b35f52b74\Nette\Utils\Strings::match((string) $eventListener->getClass(), self::SYMFONY_FAMILY_REGEX)) {
                continue;
            }
            foreach ($eventListener->getTags() as $tag) {
                if (!$tag instanceof \_PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag) {
                    continue;
                }
                $eventName = $tag->getEvent();
                $this->listenerClassesToEvents[$eventListener->getClass()][$eventName][] = $eventListener;
            }
        }
        $this->areListenerClassesLoaded = \true;
        return $this->listenerClassesToEvents;
    }
    /**
     * @param mixed[] $eventsToMethods
     */
    private function changeListenerToSubscriberWithMethods(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_ $class, array $eventsToMethods) : \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Class_
    {
        $class->implements[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Name\FullyQualified(self::EVENT_SUBSCRIBER_INTERFACE);
        $classShortName = $this->getShortName($class);
        // remove suffix
        $classShortName = \_PhpScoperb75b35f52b74\Nette\Utils\Strings::replace($classShortName, self::LISTENER_MATCH_REGEX, '$1');
        $class->name = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier($classShortName . 'EventSubscriber');
        $classMethod = $this->createGetSubscribedEventsClassMethod($eventsToMethods);
        $class->stmts[] = $classMethod;
        return $class;
    }
    /**
     * @param mixed[][] $eventsToMethods
     */
    private function createGetSubscribedEventsClassMethod(array $eventsToMethods) : \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod
    {
        $getSubscribersClassMethod = $this->nodeFactory->createPublicMethod('getSubscribedEvents');
        $eventsToMethodsArray = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_();
        $this->makeStatic($getSubscribersClassMethod);
        foreach ($eventsToMethods as $eventName => $methodNamesWithPriorities) {
            $eventNameExpr = $this->createEventName($eventName);
            if (\count($methodNamesWithPriorities) === 1) {
                $this->createSingleMethod($methodNamesWithPriorities, $eventName, $eventNameExpr, $eventsToMethodsArray);
            } else {
                $this->createMultipleMethods($methodNamesWithPriorities, $eventNameExpr, $eventsToMethodsArray, $eventName);
            }
        }
        $getSubscribersClassMethod->stmts[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\Return_($eventsToMethodsArray);
        $this->decorateClassMethodWithReturnType($getSubscribersClassMethod);
        return $getSubscribersClassMethod;
    }
    /**
     * @return String_|ClassConstFetch
     */
    private function createEventName(string $eventName) : \_PhpScoperb75b35f52b74\PhpParser\Node
    {
        if (\class_exists($eventName)) {
            return $this->createClassConstantReference($eventName);
        }
        // is string a that could be caught in constant, e.g. KernelEvents?
        foreach ($this->eventNamesToClassConstants as $eventNameToClassConstant) {
            if ($eventNameToClassConstant->getEventName() !== $eventName) {
                continue;
            }
            return $this->createClassConstFetch($eventNameToClassConstant->getEventClass(), $eventNameToClassConstant->getEventConstant());
        }
        return new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_($eventName);
    }
    /**
     * @param ClassConstFetch|String_ $expr
     * @param ServiceDefinition[] $methodNamesWithPriorities
     */
    private function createSingleMethod(array $methodNamesWithPriorities, string $eventName, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $eventsToMethodsArray) : void
    {
        /** @var EventListenerTag[]|Tag[] $eventTags */
        $eventTags = $methodNamesWithPriorities[0]->getTags();
        foreach ($eventTags as $eventTag) {
            if ($eventTag instanceof \_PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag && $eventTag->getEvent() === $eventName) {
                $methodName = $eventTag->getMethod();
                $priority = $eventTag->getPriority();
                break;
            }
        }
        if (!isset($methodName, $priority)) {
            return;
        }
        if ($priority !== 0) {
            $methodNameWithPriorityArray = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_();
            $methodNameWithPriorityArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_($methodName));
            $methodNameWithPriorityArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\LNumber((int) $priority));
            $eventsToMethodsArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem($methodNameWithPriorityArray, $expr);
        } else {
            $eventsToMethodsArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_($methodName), $expr);
        }
    }
    /**
     * @param ClassConstFetch|String_ $expr
     * @param ServiceDefinition[] $methodNamesWithPriorities
     */
    private function createMultipleMethods(array $methodNamesWithPriorities, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr $expr, \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_ $eventsToMethodsArray, string $eventName) : void
    {
        $eventItems = [];
        $alreadyUsedTags = [];
        foreach ($methodNamesWithPriorities as $methodNamesWithPriority) {
            foreach ($methodNamesWithPriority->getTags() as $tag) {
                if (!$tag instanceof \_PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag) {
                    continue;
                }
                if ($this->shouldSkip($eventName, $tag, $alreadyUsedTags)) {
                    continue;
                }
                $eventItems[] = $this->createEventItem($tag);
                $alreadyUsedTags[] = $tag;
            }
        }
        $multipleMethodsArray = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_($eventItems);
        $eventsToMethodsArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem($multipleMethodsArray, $expr);
    }
    private function decorateClassMethodWithReturnType(\_PhpScoperb75b35f52b74\PhpParser\Node\Stmt\ClassMethod $classMethod) : void
    {
        if ($this->isAtLeastPhpVersion(\_PhpScoperb75b35f52b74\Rector\Core\ValueObject\PhpVersionFeature::SCALAR_TYPES)) {
            $classMethod->returnType = new \_PhpScoperb75b35f52b74\PhpParser\Node\Identifier('array');
        }
        $returnType = new \_PhpScoperb75b35f52b74\PHPStan\Type\ArrayType(new \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType(), new \_PhpScoperb75b35f52b74\PHPStan\Type\MixedType(\true));
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($classMethod);
        $phpDocInfo->changeReturnType($returnType);
    }
    /**
     * @param TagInterface[] $alreadyUsedTags
     */
    private function shouldSkip(string $eventName, \_PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag $eventListenerTag, array $alreadyUsedTags) : bool
    {
        if ($eventName !== $eventListenerTag->getEvent()) {
            return \true;
        }
        return \in_array($eventListenerTag, $alreadyUsedTags, \true);
    }
    private function createEventItem(\_PhpScoperb75b35f52b74\Rector\Symfony\ValueObject\Tag\EventListenerTag $eventListenerTag) : \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem
    {
        if ($eventListenerTag->getPriority() !== 0) {
            $methodNameWithPriorityArray = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\Array_();
            $methodNameWithPriorityArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_($eventListenerTag->getMethod()));
            $methodNameWithPriorityArray->items[] = new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\LNumber($eventListenerTag->getPriority()));
            return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem($methodNameWithPriorityArray);
        }
        return new \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ArrayItem(new \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_($eventListenerTag->getMethod()));
    }
}
