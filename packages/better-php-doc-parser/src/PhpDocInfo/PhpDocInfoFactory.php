<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo;

use _PhpScoperb75b35f52b74\PhpParser\Node;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Lexer\Lexer;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Parser\PhpDocParser;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Parser\TokenIterator;
use _PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNodeFactoryInterface;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocRemover;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\StartAndEnd;
use _PhpScoperb75b35f52b74\Rector\Core\Configuration\CurrentNodeProvider;
use _PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScoperb75b35f52b74\Rector\StaticTypeMapper\StaticTypeMapper;
final class PhpDocInfoFactory
{
    /**
     * @var PhpDocParser
     */
    private $betterPhpDocParser;
    /**
     * @var Lexer
     */
    private $lexer;
    /**
     * @var CurrentNodeProvider
     */
    private $currentNodeProvider;
    /**
     * @var StaticTypeMapper
     */
    private $staticTypeMapper;
    /**
     * @var AttributeAwareNodeFactory
     */
    private $attributeAwareNodeFactory;
    /**
     * @var PhpDocTypeChanger
     */
    private $phpDocTypeChanger;
    /**
     * @var PhpDocRemover
     */
    private $phpDocRemover;
    public function __construct(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Ast\AttributeAwareNodeFactory $attributeAwareNodeFactory, \_PhpScoperb75b35f52b74\Rector\Core\Configuration\CurrentNodeProvider $currentNodeProvider, \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Lexer\Lexer $lexer, \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocParser\BetterPhpDocParser $betterPhpDocParser, \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocRemover $phpDocRemover, \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger $phpDocTypeChanger, \_PhpScoperb75b35f52b74\Rector\StaticTypeMapper\StaticTypeMapper $staticTypeMapper)
    {
        $this->betterPhpDocParser = $betterPhpDocParser;
        $this->lexer = $lexer;
        $this->currentNodeProvider = $currentNodeProvider;
        $this->staticTypeMapper = $staticTypeMapper;
        $this->attributeAwareNodeFactory = $attributeAwareNodeFactory;
        $this->phpDocTypeChanger = $phpDocTypeChanger;
        $this->phpDocRemover = $phpDocRemover;
    }
    public function createFromNodeOrEmpty(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        $phpDocInfo = $this->createFromNode($node);
        if ($phpDocInfo !== null) {
            return $phpDocInfo;
        }
        return $this->createEmpty($node);
    }
    public function createFromNode(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : ?\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** needed for @see PhpDocNodeFactoryInterface */
        $this->currentNodeProvider->setNode($node);
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            if ($node->getComments() !== []) {
                return null;
            }
            // create empty node
            $content = '';
            $tokens = [];
            $phpDocNode = new \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode([]);
        } else {
            $content = $docComment->getText();
            $tokens = $this->lexer->tokenize($content);
            $phpDocNode = $this->parseTokensToPhpDocNode($tokens);
            $this->setPositionOfLastToken($phpDocNode);
        }
        return $this->createFromPhpDocNode($phpDocNode, $content, $tokens, $node);
    }
    public function createEmpty(\_PhpScoperb75b35f52b74\PhpParser\Node $node) : \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** needed for @see PhpDocNodeFactoryInterface */
        $this->currentNodeProvider->setNode($node);
        $attributeAwarePhpDocNode = new \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode([]);
        return $this->createFromPhpDocNode($attributeAwarePhpDocNode, '', [], $node);
    }
    /**
     * @param mixed[][] $tokens
     */
    private function parseTokensToPhpDocNode(array $tokens) : \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode
    {
        $tokenIterator = new \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Parser\TokenIterator($tokens);
        return $this->betterPhpDocParser->parse($tokenIterator);
    }
    /**
     * Needed for printing
     */
    private function setPositionOfLastToken(\_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode $attributeAwarePhpDocNode) : void
    {
        if ($attributeAwarePhpDocNode->children === []) {
            return;
        }
        $phpDocChildNodes = $attributeAwarePhpDocNode->children;
        /** @var AttributeAwareNodeInterface $lastChildNode */
        $lastChildNode = \array_pop($phpDocChildNodes);
        /** @var StartAndEnd $startAndEnd */
        $startAndEnd = $lastChildNode->getAttribute(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::START_END);
        if ($startAndEnd !== null) {
            $attributeAwarePhpDocNode->setAttribute(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::LAST_TOKEN_POSITION, $startAndEnd->getEnd());
        }
    }
    /**
     * @param mixed[] $tokens
     */
    private function createFromPhpDocNode(\_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocNode $attributeAwarePhpDocNode, string $content, array $tokens, \_PhpScoperb75b35f52b74\PhpParser\Node $node) : \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo
    {
        /** @var AttributeAwarePhpDocNode $attributeAwarePhpDocNode */
        $attributeAwarePhpDocNode = $this->attributeAwareNodeFactory->createFromNode($attributeAwarePhpDocNode, $content);
        $phpDocInfo = new \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo($attributeAwarePhpDocNode, $tokens, $content, $this->staticTypeMapper, $node, $this->phpDocTypeChanger, $this->phpDocRemover, $this->attributeAwareNodeFactory);
        $node->setAttribute(\_PhpScoperb75b35f52b74\Rector\NodeTypeResolver\Node\AttributeKey::PHP_DOC_INFO, $phpDocInfo);
        return $phpDocInfo;
    }
}
