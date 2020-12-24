<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Core\Application;

use _PhpScopere8e811afab72\PhpParser\Lexer;
use _PhpScopere8e811afab72\Rector\ChangesReporting\Collector\AffectedFilesCollector;
use _PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Node\CustomNode\FileNode;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Parser\Parser;
use _PhpScopere8e811afab72\Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use _PhpScopere8e811afab72\Rector\Core\Stubs\StubLoader;
use _PhpScopere8e811afab72\Rector\Core\ValueObject\Application\ParsedStmtsAndTokens;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\FileSystem\CurrentFileInfoProvider;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use _PhpScopere8e811afab72\Rector\PostRector\Application\PostFileProcessor;
use _PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo;
final class FileProcessor
{
    /**
     * @var FormatPerservingPrinter
     */
    private $formatPerservingPrinter;
    /**
     * @var Parser
     */
    private $parser;
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var RectorNodeTraverser
     */
    private $rectorNodeTraverser;
    /**
     * @var NodeScopeAndMetadataDecorator
     */
    private $nodeScopeAndMetadataDecorator;
    /**
     * @var CurrentFileInfoProvider
     */
    private $currentFileInfoProvider;
    /**
     * @var StubLoader
     */
    private $stubLoader;
    /**
     * @var AffectedFilesCollector
     */
    private $affectedFilesCollector;
    /**
     * @var PostFileProcessor
     */
    private $postFileProcessor;
    /**
     * @var TokensByFilePathStorage
     */
    private $tokensByFilePathStorage;
    public function __construct(\_PhpScopere8e811afab72\Rector\ChangesReporting\Collector\AffectedFilesCollector $affectedFilesCollector, \_PhpScopere8e811afab72\Rector\NodeTypeResolver\FileSystem\CurrentFileInfoProvider $currentFileInfoProvider, \_PhpScopere8e811afab72\Rector\Core\PhpParser\Printer\FormatPerservingPrinter $formatPerservingPrinter, \_PhpScopere8e811afab72\PhpParser\Lexer $lexer, \_PhpScopere8e811afab72\Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator, \_PhpScopere8e811afab72\Rector\Core\PhpParser\Parser\Parser $parser, \_PhpScopere8e811afab72\Rector\PostRector\Application\PostFileProcessor $postFileProcessor, \_PhpScopere8e811afab72\Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser $rectorNodeTraverser, \_PhpScopere8e811afab72\Rector\Core\Stubs\StubLoader $stubLoader, \_PhpScopere8e811afab72\Rector\Core\Application\TokensByFilePathStorage $tokensByFilePathStorage)
    {
        $this->formatPerservingPrinter = $formatPerservingPrinter;
        $this->parser = $parser;
        $this->lexer = $lexer;
        $this->rectorNodeTraverser = $rectorNodeTraverser;
        $this->nodeScopeAndMetadataDecorator = $nodeScopeAndMetadataDecorator;
        $this->currentFileInfoProvider = $currentFileInfoProvider;
        $this->stubLoader = $stubLoader;
        $this->affectedFilesCollector = $affectedFilesCollector;
        $this->postFileProcessor = $postFileProcessor;
        $this->tokensByFilePathStorage = $tokensByFilePathStorage;
    }
    public function parseFileInfoToLocalCache(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if ($this->tokensByFilePathStorage->hasForFileInfo($smartFileInfo)) {
            return;
        }
        $this->currentFileInfoProvider->setCurrentFileInfo($smartFileInfo);
        // store tokens by absolute path, so we don't have to print them right now
        $parsedStmtsAndTokens = $this->parseAndTraverseFileInfoToNodes($smartFileInfo);
        $this->tokensByFilePathStorage->addForRealPath($smartFileInfo, $parsedStmtsAndTokens);
    }
    public function printToFile(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : string
    {
        $parsedStmtsAndTokens = $this->tokensByFilePathStorage->getForFileInfo($smartFileInfo);
        return $this->formatPerservingPrinter->printParsedStmstAndTokens($smartFileInfo, $parsedStmtsAndTokens);
    }
    /**
     * See https://github.com/nikic/PHP-Parser/issues/344#issuecomment-298162516.
     */
    public function printToString(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : string
    {
        $this->makeSureFileIsParsed($smartFileInfo);
        $parsedStmtsAndTokens = $this->tokensByFilePathStorage->getForFileInfo($smartFileInfo);
        return $this->formatPerservingPrinter->printParsedStmstAndTokensToString($parsedStmtsAndTokens);
    }
    public function refactor(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        $this->stubLoader->loadStubs();
        $this->currentFileInfoProvider->setCurrentFileInfo($smartFileInfo);
        $this->makeSureFileIsParsed($smartFileInfo);
        $parsedStmtsAndTokens = $this->tokensByFilePathStorage->getForFileInfo($smartFileInfo);
        $this->currentFileInfoProvider->setCurrentStmts($parsedStmtsAndTokens->getNewStmts());
        // run file node only if
        $fileNode = new \_PhpScopere8e811afab72\Rector\Core\PhpParser\Node\CustomNode\FileNode($smartFileInfo, $parsedStmtsAndTokens->getNewStmts());
        $result = $this->rectorNodeTraverser->traverseFileNode($fileNode);
        $newStmts = $this->rectorNodeTraverser->traverse($parsedStmtsAndTokens->getNewStmts());
        // this is needed for new tokens added in "afterTraverse()"
        $parsedStmtsAndTokens->updateNewStmts($newStmts);
        $this->affectedFilesCollector->removeFromList($smartFileInfo);
        while ($otherTouchedFile = $this->affectedFilesCollector->getNext()) {
            $this->refactor($otherTouchedFile);
        }
    }
    public function postFileRefactor(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if (!$this->tokensByFilePathStorage->hasForFileInfo($smartFileInfo)) {
            $this->parseFileInfoToLocalCache($smartFileInfo);
        }
        $parsedStmtsAndTokens = $this->tokensByFilePathStorage->getForFileInfo($smartFileInfo);
        $this->currentFileInfoProvider->setCurrentStmts($parsedStmtsAndTokens->getNewStmts());
        $this->currentFileInfoProvider->setCurrentFileInfo($smartFileInfo);
        $newStmts = $this->postFileProcessor->traverse($parsedStmtsAndTokens->getNewStmts());
        // this is needed for new tokens added in "afterTraverse()"
        $parsedStmtsAndTokens->updateNewStmts($newStmts);
    }
    private function parseAndTraverseFileInfoToNodes(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \_PhpScopere8e811afab72\Rector\Core\ValueObject\Application\ParsedStmtsAndTokens
    {
        $oldStmts = $this->parser->parseFileInfo($smartFileInfo);
        $oldTokens = $this->lexer->getTokens();
        // needed for \Rector\NodeTypeResolver\PHPStan\Scope\NodeScopeResolver
        $parsedStmtsAndTokens = new \_PhpScopere8e811afab72\Rector\Core\ValueObject\Application\ParsedStmtsAndTokens($oldStmts, $oldStmts, $oldTokens);
        $this->tokensByFilePathStorage->addForRealPath($smartFileInfo, $parsedStmtsAndTokens);
        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($oldStmts, $smartFileInfo);
        return new \_PhpScopere8e811afab72\Rector\Core\ValueObject\Application\ParsedStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }
    private function makeSureFileIsParsed(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if ($this->tokensByFilePathStorage->hasForFileInfo($smartFileInfo)) {
            return;
        }
        throw new \_PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException(\sprintf('File "%s" was not preparsed, so it cannot be printed.%sCheck "%s" method.', $smartFileInfo->getRealPath(), \PHP_EOL, self::class . '::parseFileInfoToLocalCache()'));
    }
}
