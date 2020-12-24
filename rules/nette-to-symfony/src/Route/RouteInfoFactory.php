<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\NetteToSymfony\Route;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\ClassConstFetch;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\New_;
use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall;
use _PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_;
use _PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\Value\ValueResolver;
use _PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo;
use _PhpScoperb75b35f52b74\Rector\NodeCollector\NodeCollector\ParsedNodeCollector;
use _PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver;
final class RouteInfoFactory
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var ValueResolver
     */
    private $valueResolver;
    /**
     * @var ParsedNodeCollector
     */
    private $parsedNodeCollector;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScoperb75b35f52b74\Rector\NodeCollector\NodeCollector\ParsedNodeCollector $parsedNodeCollector, \_PhpScoperb75b35f52b74\Rector\Core\PhpParser\Node\Value\ValueResolver $valueResolver)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->valueResolver = $valueResolver;
        $this->parsedNodeCollector = $parsedNodeCollector;
    }
    public function createFromNode(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo
    {
        if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\New_) {
            if (!isset($node->args[0]) || !isset($node->args[1])) {
                return null;
            }
            return $this->createRouteInfoFromArgs($node);
        }
        // Route::create()
        if ($node instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\StaticCall) {
            if (!isset($node->args[0]) || !isset($node->args[1])) {
                return null;
            }
            if (!$this->nodeNameResolver->isNames($node->name, ['get', 'head', 'post', 'put', 'patch', 'delete'])) {
                return null;
            }
            /** @var string $methodName */
            $methodName = $this->nodeNameResolver->getName($node->name);
            $uppercasedMethodName = \strtoupper($methodName);
            $methods = [];
            if ($uppercasedMethodName !== null) {
                $methods[] = $uppercasedMethodName;
            }
            return $this->createRouteInfoFromArgs($node, $methods);
        }
        return null;
    }
    /**
     * @param New_|StaticCall $node
     * @param string[] $methods
     */
    private function createRouteInfoFromArgs(\_PhpScoperb75b35f52b74\PhpParser\Node $node, array $methods = []) : ?\_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo
    {
        $pathArgument = $node->args[0]->value;
        $routePath = $this->valueResolver->getValue($pathArgument);
        // route path is needed
        if ($routePath === null || !\is_string($routePath)) {
            return null;
        }
        $routePath = $this->normalizeArgumentWrappers($routePath);
        $targetNode = $node->args[1]->value;
        if ($targetNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\ClassConstFetch) {
            return $this->createForClassConstFetch($node, $methods, $routePath);
        }
        if ($targetNode instanceof \_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_) {
            return $this->createForString($targetNode, $routePath);
        }
        return null;
    }
    private function normalizeArgumentWrappers(string $routePath) : string
    {
        return \str_replace(['<', '>'], ['{', '}'], $routePath);
    }
    /**
     * @param New_|StaticCall $node
     * @param string[] $methods
     */
    private function createForClassConstFetch(\_PhpScoperb75b35f52b74\PhpParser\Node $node, array $methods, string $routePath) : ?\_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo
    {
        /** @var ClassConstFetch $controllerMethodNode */
        $controllerMethodNode = $node->args[1]->value;
        // SomePresenter::class
        if ($this->nodeNameResolver->isName($controllerMethodNode->name, 'class')) {
            $presenterClass = $this->nodeNameResolver->getName($controllerMethodNode->class);
            if ($presenterClass === null) {
                return null;
            }
            if (!\class_exists($presenterClass)) {
                return null;
            }
            if (\method_exists($presenterClass, 'run')) {
                return new \_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo($presenterClass, 'run', $routePath, $methods);
            }
        }
        return null;
    }
    private function createForString(\_PhpScoperb75b35f52b74\PhpParser\Node\Scalar\String_ $string, string $routePath) : ?\_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo
    {
        $targetValue = $string->value;
        if (!\_PhpScoperb75b35f52b74\Nette\Utils\Strings::contains($targetValue, ':')) {
            return null;
        }
        [$controller, $method] = \explode(':', $targetValue);
        // detect class by controller name?
        // foreach all instance and try to match a name $controller . 'Presenter/Controller'
        $classNode = $this->parsedNodeCollector->findByShortName($controller . 'Presenter');
        if ($classNode === null) {
            $classNode = $this->parsedNodeCollector->findByShortName($controller . 'Controller');
        }
        // unable to find here
        if ($classNode === null) {
            return null;
        }
        $controllerClass = $this->nodeNameResolver->getName($classNode);
        if ($controllerClass === null) {
            return null;
        }
        $methodName = null;
        if (\method_exists($controllerClass, 'render' . \ucfirst($method))) {
            $methodName = 'render' . \ucfirst($method);
        } elseif (\method_exists($controllerClass, 'action' . \ucfirst($method))) {
            $methodName = 'action' . \ucfirst($method);
        }
        if ($methodName === null) {
            return null;
        }
        return new \_PhpScoperb75b35f52b74\Rector\NetteToSymfony\ValueObject\RouteInfo($controllerClass, $methodName, $routePath, []);
    }
}
