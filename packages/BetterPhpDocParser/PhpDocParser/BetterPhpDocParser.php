<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\PhpDocParser;

use RectorPrefix20210317\Nette\Utils\Strings;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser;
use Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode;
use Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory;
use Rector\BetterPhpDocParser\Attributes\Attribute\Attribute;
use Rector\BetterPhpDocParser\Contract\PhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\Contract\PhpDocParserAwareInterface;
use Rector\BetterPhpDocParser\Contract\SpecificPhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\Contract\StringTagMatchingPhpDocNodeFactoryInterface;
use Rector\BetterPhpDocParser\PhpDocNodeFactory\MultiPhpDocNodeFactory;
use Rector\BetterPhpDocParser\Printer\MultilineSpaceFormatPreserver;
use Rector\BetterPhpDocParser\ValueObject\StartAndEnd;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesCaller;
/**
 * @see \Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\TagValueNodeReprintTest
 */
final class BetterPhpDocParser extends \PHPStan\PhpDocParser\Parser\PhpDocParser
{
    /**
     * @var string
     * @see https://regex101.com/r/HlGzME/1
     */
    private const TAG_REGEX = '#@(var|param|return|throws|property|deprecated)#';
    /**
     * @see https://regex101.com/r/iCJqCv/1
     * @var string
     */
    private const SPACE_REGEX = '#\\s+#';
    /**
     * @var PhpDocNodeFactoryInterface[]
     */
    private $phpDocNodeFactories = [];
    /**
     * @var PrivatesCaller
     */
    private $privatesCaller;
    /**
     * @var PrivatesAccessor
     */
    private $privatesAccessor;
    /**
     * @var AttributeAwareNodeFactory
     */
    private $attributeAwareNodeFactory;
    /**
     * @var MultilineSpaceFormatPreserver
     */
    private $multilineSpaceFormatPreserver;
    /**
     * @var CurrentNodeProvider
     */
    private $currentNodeProvider;
    /**
     * @var ClassAnnotationMatcher
     */
    private $classAnnotationMatcher;
    /**
     * @var AnnotationContentResolver
     */
    private $annotationContentResolver;
    /**
     * @var StringTagMatchingPhpDocNodeFactoryInterface[]
     */
    private $stringTagMatchingPhpDocNodeFactories = [];
    /**
     * @param PhpDocNodeFactoryInterface[] $phpDocNodeFactories
     * @param StringTagMatchingPhpDocNodeFactoryInterface[] $stringTagMatchingPhpDocNodeFactories
     * @param \Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory $attributeAwareNodeFactory
     * @param \Rector\BetterPhpDocParser\Printer\MultilineSpaceFormatPreserver $multilineSpaceFormatPreserver
     * @param \Rector\Core\Configuration\CurrentNodeProvider $currentNodeProvider
     * @param \Rector\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher $classAnnotationMatcher
     * @param \Rector\BetterPhpDocParser\PhpDocParser\AnnotationContentResolver $annotationContentResolver
     */
    public function __construct(\PHPStan\PhpDocParser\Parser\TypeParser $typeParser, \PHPStan\PhpDocParser\Parser\ConstExprParser $constExprParser, $attributeAwareNodeFactory, $multilineSpaceFormatPreserver, $currentNodeProvider, $classAnnotationMatcher, $annotationContentResolver, $phpDocNodeFactories = [], $stringTagMatchingPhpDocNodeFactories = [])
    {
        parent::__construct($typeParser, $constExprParser);
        $this->privatesCaller = new \RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesCaller();
        $this->privatesAccessor = new \RectorPrefix20210317\Symplify\PackageBuilder\Reflection\PrivatesAccessor();
        $this->attributeAwareNodeFactory = $attributeAwareNodeFactory;
        $this->multilineSpaceFormatPreserver = $multilineSpaceFormatPreserver;
        $this->currentNodeProvider = $currentNodeProvider;
        $this->classAnnotationMatcher = $classAnnotationMatcher;
        $this->annotationContentResolver = $annotationContentResolver;
        $this->setPhpDocNodeFactories($phpDocNodeFactories);
        $this->stringTagMatchingPhpDocNodeFactories = $stringTagMatchingPhpDocNodeFactories;
    }
    /**
     * @return AttributeAwarePhpDocNode|PhpDocNode
     */
    public function parse(\PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator) : \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode
    {
        $originalTokenIterator = clone $tokenIterator;
        $tokenIterator->consumeTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_OPEN_PHPDOC);
        $tokenIterator->tryConsumeTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_PHPDOC_EOL);
        $children = [];
        if (!$tokenIterator->isCurrentTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_CLOSE_PHPDOC)) {
            $children[] = $this->parseChildAndStoreItsPositions($tokenIterator);
            while ($tokenIterator->tryConsumeTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_PHPDOC_EOL) && !$tokenIterator->isCurrentTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_CLOSE_PHPDOC)) {
                $children[] = $this->parseChildAndStoreItsPositions($tokenIterator);
            }
        }
        // might be in the middle of annotations
        $tokenIterator->tryConsumeTokenType(\PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_CLOSE_PHPDOC);
        $phpDocNode = new \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode(\array_values($children));
        $docContent = $this->annotationContentResolver->resolveFromTokenIterator($originalTokenIterator);
        return $this->attributeAwareNodeFactory->createFromNode($phpDocNode, $docContent);
    }
    public function parseTag(\PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator) : \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode
    {
        $tag = $this->resolveTag($tokenIterator);
        $phpDocTagNode = $this->createPhpDocTagNodeFromStringMatch($tag, $tokenIterator);
        if ($phpDocTagNode instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode) {
            return $phpDocTagNode;
        }
        if ($phpDocTagNode instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode) {
            return new \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode($tag, $phpDocTagNode);
        }
        $phpDocTagValueNode = $this->parseTagValue($tokenIterator, $tag);
        return new \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode($tag, $phpDocTagValueNode);
    }
    public function parseTagValue(\PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator, string $tag) : \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
    {
        $currentPhpNode = $this->currentNodeProvider->getNode();
        if (!$currentPhpNode instanceof \PhpParser\Node) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $tagValueNode = null;
        // class-annotation
        $phpDocNodeFactory = $this->matchTagToPhpDocNodeFactory($tag);
        if ($phpDocNodeFactory !== null) {
            $fullyQualifiedAnnotationClass = $this->classAnnotationMatcher->resolveTagFullyQualifiedName($tag, $currentPhpNode);
            $tagValueNode = $phpDocNodeFactory->createFromNodeAndTokens($currentPhpNode, $tokenIterator, $fullyQualifiedAnnotationClass);
        }
        $originalTokenIterator = clone $tokenIterator;
        $docContent = $this->annotationContentResolver->resolveFromTokenIterator($originalTokenIterator);
        // fallback to original parser
        if (!$tagValueNode instanceof \PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode) {
            $tagValueNode = parent::parseTagValue($tokenIterator, $tag);
        }
        return $this->attributeAwareNodeFactory->createFromNode($tagValueNode, $docContent);
    }
    /**
     * @param PhpDocNodeFactoryInterface[] $phpDocNodeFactories
     */
    private function setPhpDocNodeFactories($phpDocNodeFactories) : void
    {
        foreach ($phpDocNodeFactories as $phpDocNodeFactory) {
            $classes = $this->resolvePhpDocNodeFactoryClasses($phpDocNodeFactory);
            foreach ($classes as $class) {
                $this->phpDocNodeFactories[$class] = $phpDocNodeFactory;
            }
        }
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function parseChildAndStoreItsPositions($tokenIterator) : \PHPStan\PhpDocParser\Ast\Node
    {
        $originalTokenIterator = clone $tokenIterator;
        $docContent = $this->annotationContentResolver->resolveFromTokenIterator($originalTokenIterator);
        $tokenStart = $this->getTokenIteratorIndex($tokenIterator);
        $phpDocNode = $this->privatesCaller->callPrivateMethod($this, 'parseChild', [$tokenIterator]);
        $tokenEnd = $this->resolveTokenEnd($tokenIterator);
        $startAndEnd = new \Rector\BetterPhpDocParser\ValueObject\StartAndEnd($tokenStart, $tokenEnd);
        $attributeAwareNode = $this->attributeAwareNodeFactory->createFromNode($phpDocNode, $docContent);
        $attributeAwareNode->setAttribute(\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::START_END, $startAndEnd);
        $possibleMultilineText = $this->multilineSpaceFormatPreserver->resolveCurrentPhpDocNodeText($attributeAwareNode);
        if ($possibleMultilineText) {
            // add original text, for keeping trimmed spaces
            $originalContent = $this->getOriginalContentFromTokenIterator($tokenIterator);
            // we try to match original content without trimmed spaces
            $currentTextPattern = '#(?<line>' . \preg_quote($possibleMultilineText, '#') . ')#s';
            $currentTextPattern = \RectorPrefix20210317\Nette\Utils\Strings::replace($currentTextPattern, self::SPACE_REGEX, '\\s+');
            $match = \RectorPrefix20210317\Nette\Utils\Strings::match($originalContent, $currentTextPattern);
            if (isset($match['line'])) {
                $attributeAwareNode->setAttribute(\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::ORIGINAL_CONTENT, $match['line']);
            }
        }
        return $attributeAwareNode;
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function resolveTag($tokenIterator) : string
    {
        $tag = $tokenIterator->currentTokenValue();
        $tokenIterator->next();
        // basic annotation
        if (\RectorPrefix20210317\Nette\Utils\Strings::match($tag, self::TAG_REGEX)) {
            return $tag;
        }
        // is not e.g "@var "
        // join tags like "@ORM\Column" etc.
        if ($tokenIterator->currentTokenType() !== \PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_IDENTIFIER) {
            return $tag;
        }
        $oldTag = $tag;
        $tag .= $tokenIterator->currentTokenValue();
        $isTagMatchedByFactories = (bool) $this->matchTagToPhpDocNodeFactory($tag);
        if (!$isTagMatchedByFactories) {
            return $oldTag;
        }
        $tokenIterator->next();
        return $tag;
    }
    /**
     * @param string $tag
     */
    private function matchTagToPhpDocNodeFactory($tag) : ?\Rector\BetterPhpDocParser\Contract\PhpDocNodeFactoryInterface
    {
        $currentPhpNode = $this->currentNodeProvider->getNode();
        if (!$currentPhpNode instanceof \PhpParser\Node) {
            throw new \Rector\Core\Exception\ShouldNotHappenException();
        }
        $fullyQualifiedAnnotationClass = $this->classAnnotationMatcher->resolveTagFullyQualifiedName($tag, $currentPhpNode);
        return $this->phpDocNodeFactories[$fullyQualifiedAnnotationClass] ?? null;
    }
    /**
     * @return string[]
     * @param \Rector\BetterPhpDocParser\Contract\PhpDocNodeFactoryInterface $phpDocNodeFactory
     */
    private function resolvePhpDocNodeFactoryClasses($phpDocNodeFactory) : array
    {
        if ($phpDocNodeFactory instanceof \Rector\BetterPhpDocParser\Contract\SpecificPhpDocNodeFactoryInterface) {
            return $phpDocNodeFactory->getClasses();
        }
        if ($phpDocNodeFactory instanceof \Rector\BetterPhpDocParser\PhpDocNodeFactory\MultiPhpDocNodeFactory) {
            return $phpDocNodeFactory->getTagValueNodeClassesToAnnotationClasses();
        }
        throw new \Rector\Core\Exception\ShouldNotHappenException();
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function getTokenIteratorIndex($tokenIterator) : int
    {
        return (int) $this->privatesAccessor->getPrivateProperty($tokenIterator, 'index');
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function resolveTokenEnd($tokenIterator) : int
    {
        $tokenEnd = $this->getTokenIteratorIndex($tokenIterator);
        return $this->adjustTokenEndToFitClassAnnotation($tokenIterator, $tokenEnd);
    }
    /**
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function getOriginalContentFromTokenIterator($tokenIterator) : string
    {
        $originalTokens = $this->privatesAccessor->getPrivateProperty($tokenIterator, 'tokens');
        $originalContent = '';
        foreach ($originalTokens as $originalToken) {
            // skip opening
            if ($originalToken[1] === \PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_OPEN_PHPDOC) {
                continue;
            }
            // skip closing
            if ($originalToken[1] === \PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_CLOSE_PHPDOC) {
                continue;
            }
            if ($originalToken[1] === \PHPStan\PhpDocParser\Lexer\Lexer::TOKEN_PHPDOC_EOL) {
                $originalToken[0] = \PHP_EOL;
            }
            $originalContent .= $originalToken[0];
        }
        return \trim($originalContent);
    }
    /**
     * @see https://github.com/rectorphp/rector/issues/2158
     *
     * Need to find end of this bracket first, because the parseChild() skips class annotatinos
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     * @param int $tokenEnd
     */
    private function adjustTokenEndToFitClassAnnotation($tokenIterator, $tokenEnd) : int
    {
        $tokens = $this->privatesAccessor->getPrivateProperty($tokenIterator, 'tokens');
        if ($tokens[$tokenEnd][0] !== '(') {
            return $tokenEnd;
        }
        while ($tokens[$tokenEnd][0] !== ')') {
            ++$tokenEnd;
            // to prevent missing index error
            if (!isset($tokens[$tokenEnd])) {
                return --$tokenEnd;
            }
        }
        ++$tokenEnd;
        return $tokenEnd;
    }
    /**
     * @param string $tag
     * @param \PHPStan\PhpDocParser\Parser\TokenIterator $tokenIterator
     */
    private function createPhpDocTagNodeFromStringMatch($tag, $tokenIterator) : ?\PHPStan\PhpDocParser\Ast\Node
    {
        foreach ($this->stringTagMatchingPhpDocNodeFactories as $stringTagMatchingPhpDocNodeFactory) {
            if (!$stringTagMatchingPhpDocNodeFactory->match($tag)) {
                continue;
            }
            if ($stringTagMatchingPhpDocNodeFactory instanceof \Rector\BetterPhpDocParser\Contract\PhpDocParserAwareInterface) {
                $stringTagMatchingPhpDocNodeFactory->setPhpDocParser($this);
            }
            return $stringTagMatchingPhpDocNodeFactory->createFromTokens($tokenIterator);
        }
        return null;
    }
}
