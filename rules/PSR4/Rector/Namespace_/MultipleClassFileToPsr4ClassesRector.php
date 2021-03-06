<?php

declare (strict_types=1);
namespace Rector\PSR4\Rector\Namespace_;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\FileSystemRector\ValueObject\MovedFileWithNodes;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PSR4\FileInfoAnalyzer\FileInfoDeletionAnalyzer;
use Rector\PSR4\NodeManipulator\NamespaceManipulator;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
/**
 * @see \Rector\Tests\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector\MultipleClassFileToPsr4ClassesRectorTest
 */
final class MultipleClassFileToPsr4ClassesRector extends \Rector\Core\Rector\AbstractRector
{
    /**
     * @var NamespaceManipulator
     */
    private $namespaceManipulator;
    /**
     * @var FileInfoDeletionAnalyzer
     */
    private $fileInfoDeletionAnalyzer;
    /**
     * @param \Rector\PSR4\NodeManipulator\NamespaceManipulator $namespaceManipulator
     * @param \Rector\PSR4\FileInfoAnalyzer\FileInfoDeletionAnalyzer $fileInfoDeletionAnalyzer
     */
    public function __construct($namespaceManipulator, $fileInfoDeletionAnalyzer)
    {
        $this->namespaceManipulator = $namespaceManipulator;
        $this->fileInfoDeletionAnalyzer = $fileInfoDeletionAnalyzer;
    }
    public function getRuleDefinition() : \Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Change multiple classes in one file to standalone PSR-4 classes.', [new \Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
namespace App\Exceptions;

use Exception;

final class FirstException extends Exception
{
}

final class SecondException extends Exception
{
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
// new file: "app/Exceptions/FirstException.php"
namespace App\Exceptions;

use Exception;

final class FirstException extends Exception
{
}

// new file: "app/Exceptions/SecondException.php"
namespace App\Exceptions;

use Exception;

final class SecondException extends Exception
{
}
CODE_SAMPLE
)]);
    }
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes() : array
    {
        return [\PhpParser\Node\Stmt\Namespace_::class, \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace::class];
    }
    /**
     * @param Namespace_|FileWithoutNamespace $node
     */
    public function refactor($node) : ?\PhpParser\Node
    {
        if (!$this->hasAtLeastTwoClassLikes($node)) {
            return null;
        }
        $nodeToReturn = null;
        if ($node instanceof \PhpParser\Node\Stmt\Namespace_) {
            $nodeToReturn = $this->refactorNamespace($node);
        }
        if ($node instanceof \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace) {
            $nodeToReturn = $this->refactorFileWithoutNamespace($node);
        }
        // 1. remove this node
        if ($nodeToReturn !== null) {
            return $nodeToReturn;
        }
        /** @var SmartFileInfo $smartFileInfo */
        $smartFileInfo = $node->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        // 2. nothing to return - remove the file
        $this->removedAndAddedFilesCollector->removeFile($smartFileInfo);
        return null;
    }
    /**
     * @param \PhpParser\Node $node
     */
    private function hasAtLeastTwoClassLikes($node) : bool
    {
        $classes = $this->betterNodeFinder->findClassLikes($node);
        return \count($classes) > 1;
    }
    /**
     * @param \PhpParser\Node\Stmt\Namespace_ $namespace
     */
    private function refactorNamespace($namespace) : ?\PhpParser\Node\Stmt\Namespace_
    {
        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findClassLikes($namespace->stmts);
        $this->namespaceManipulator->removeClassLikes($namespace);
        $nodeToReturn = null;
        foreach ($classLikes as $classLike) {
            $newNamespace = clone $namespace;
            $newNamespace->stmts[] = $classLike;
            // 1. is the class that will be kept in original file?
            if ($this->fileInfoDeletionAnalyzer->isClassLikeAndFileInfoMatch($classLike)) {
                $nodeToReturn = $newNamespace;
                continue;
            }
            // 2. new file
            $this->printNewNodes($classLike, $newNamespace);
        }
        return $nodeToReturn;
    }
    /**
     * @param \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace $fileWithoutNamespace
     */
    private function refactorFileWithoutNamespace($fileWithoutNamespace) : ?\Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace
    {
        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findClassLikes($fileWithoutNamespace->stmts);
        $nodeToReturn = null;
        foreach ($classLikes as $classLike) {
            // 1. is the class that will be kept in original file?
            if ($this->fileInfoDeletionAnalyzer->isClassLikeAndFileInfoMatch($classLike)) {
                $nodeToReturn = $fileWithoutNamespace;
                continue;
            }
            // 2. is new file
            $this->printNewNodes($classLike, $fileWithoutNamespace);
        }
        return $nodeToReturn;
    }
    /**
     * @param Namespace_|FileWithoutNamespace $mainNode
     * @param \PhpParser\Node\Stmt\ClassLike $classLike
     */
    private function printNewNodes($classLike, $mainNode) : void
    {
        /** @var SmartFileInfo $smartFileInfo */
        $smartFileInfo = $mainNode->getAttribute(\Rector\NodeTypeResolver\Node\AttributeKey::FILE_INFO);
        $declares = [];
        $declare = $this->betterNodeFinder->findFirstPreviousOfTypes($mainNode, [\PhpParser\Node\Stmt\Declare_::class]);
        if ($declare instanceof \PhpParser\Node\Stmt\Declare_) {
            $declares = [$declare];
        }
        if ($mainNode instanceof \Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace) {
            $nodesToPrint = \array_merge($declares, [$classLike]);
        } else {
            $nodesToPrint = \array_merge($declares, [$mainNode]);
        }
        $fileDestination = $this->createClassLikeFileDestination($classLike, $smartFileInfo);
        $movedFileWithNodes = new \Rector\FileSystemRector\ValueObject\MovedFileWithNodes($nodesToPrint, $fileDestination, $smartFileInfo);
        $this->removedAndAddedFilesCollector->addMovedFile($movedFileWithNodes);
    }
    /**
     * @param \PhpParser\Node\Stmt\ClassLike $classLike
     * @param \Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo
     */
    private function createClassLikeFileDestination($classLike, $smartFileInfo) : string
    {
        $currentDirectory = \dirname($smartFileInfo->getRealPath());
        return $currentDirectory . \DIRECTORY_SEPARATOR . $classLike->name . '.php';
    }
}
