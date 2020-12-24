<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\NetteToSymfony\NodeFactory;

use _PhpScopere8e811afab72\PhpParser\Node\Name;
use _PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_;
use _PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo;
final class SymfonyControllerFactory
{
    /**
     * @var NodeNameResolver
     */
    private $nodeNameResolver;
    /**
     * @var ActionWithFormProcessClassMethodFactory
     */
    private $actionWithFormProcessClassMethodFactory;
    public function __construct(\_PhpScopere8e811afab72\Rector\NodeNameResolver\NodeNameResolver $nodeNameResolver, \_PhpScopere8e811afab72\Rector\NetteToSymfony\NodeFactory\ActionWithFormProcessClassMethodFactory $actionWithFormProcessClassMethodFactory)
    {
        $this->nodeNameResolver = $nodeNameResolver;
        $this->actionWithFormProcessClassMethodFactory = $actionWithFormProcessClassMethodFactory;
    }
    public function createNamespace(\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_ $node, \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_ $formTypeClass) : ?\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_
    {
        /** @var SmartFileInfo|null $fileInfo */
        $fileInfo = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        if ($fileInfo === null) {
            return null;
        }
        /** @var string $namespaceName */
        $namespaceName = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::NAMESPACE_NAME);
        $formControllerClass = new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_('SomeFormController');
        $formControllerClass->extends = new \_PhpScopere8e811afab72\PhpParser\Node\Name\FullyQualified('_PhpScopere8e811afab72\\Symfony\\Bundle\\FrameworkBundle\\Controller\\AbstractController');
        $formTypeClass = $namespaceName . '\\' . $this->nodeNameResolver->getName($formTypeClass);
        $formControllerClass->stmts[] = $this->actionWithFormProcessClassMethodFactory->create($formTypeClass);
        $namespace = new \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_(new \_PhpScopere8e811afab72\PhpParser\Node\Name($namespaceName));
        $namespace->stmts[] = $formControllerClass;
        return $namespace;
    }
}
