<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\CodingStyle\Rector\Assign;

use _PhpScopere8e811afab72\Nette\Utils\Json;
use _PhpScopere8e811afab72\Nette\Utils\JsonException;
use _PhpScopere8e811afab72\Nette\Utils\Strings;
use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Expr;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Array_;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Assign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\AssignOp\Concat as ConcatAssign;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall;
use _PhpScopere8e811afab72\PhpParser\Node\Expr\Variable;
use _PhpScopere8e811afab72\PhpParser\Node\Scalar\String_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression;
use _PhpScopere8e811afab72\Rector\CodingStyle\Node\ConcatJoiner;
use _PhpScopere8e811afab72\Rector\CodingStyle\Node\ConcatManipulator;
use _PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\ConcatExpressionJoinData;
use _PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\NodeToRemoveAndConcatItem;
use _PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException;
use _PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector;
use _PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use _PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
/**
 * @sponsor Thanks https://spaceflow.io/ for sponsoring this rule - visit them on https://github.com/SpaceFlow-app
 *
 * @see \Rector\CodingStyle\Tests\Rector\Assign\ManualJsonStringToJsonEncodeArrayRector\ManualJsonStringToJsonEncodeArrayRectorTest
 */
final class ManualJsonStringToJsonEncodeArrayRector extends \_PhpScopere8e811afab72\Rector\Core\Rector\AbstractRector
{
    /**
     * @var string
     * @see https://regex101.com/r/85PZHm/1
     */
    private const UNQUOTED_OBJECT_HASH_REGEX = '#(?<start>[^\\"])(?<hash>____\\w+____)#';
    /**
     * @var string
     * @see https://regex101.com/r/jdJ6n9/1
     */
    private const JSON_STRING_REGEX = '#{(.*?\\:.*?)}#s';
    /**
     * @var ConcatJoiner
     */
    private $concatJoiner;
    /**
     * @var ConcatManipulator
     */
    private $concatManipulator;
    public function __construct(\_PhpScopere8e811afab72\Rector\CodingStyle\Node\ConcatJoiner $concatJoiner, \_PhpScopere8e811afab72\Rector\CodingStyle\Node\ConcatManipulator $concatManipulator)
    {
        $this->concatJoiner = $concatJoiner;
        $this->concatManipulator = $concatManipulator;
    }
    public function getRuleDefinition() : \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition
    {
        return new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\RuleDefinition('Add extra space before new assign set', [new \_PhpScopere8e811afab72\Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample(<<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $someJsonAsString = '{"role_name":"admin","numberz":{"id":"10"}}';
    }
}
CODE_SAMPLE
, <<<'CODE_SAMPLE'
final class SomeClass
{
    public function run()
    {
        $data = [
            'role_name' => 'admin',
            'numberz' => ['id' => 10]
        ];

        $someJsonAsString = Nette\Utils\Json::encode($data);
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
        return [\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign::class];
    }
    /**
     * @param Assign $node
     */
    public function refactor(\_PhpScopere8e811afab72\PhpParser\Node $node) : ?\_PhpScopere8e811afab72\PhpParser\Node
    {
        if ($node->expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_) {
            $stringValue = $node->expr->value;
            // A. full json string
            $isJsonString = $this->isJsonString($stringValue);
            if ($isJsonString) {
                return $this->processJsonString($node, $stringValue);
            }
            // B. just start of a json? join with all the strings that concat so same variable
            $concatExpressionJoinData = $this->collectContentAndPlaceholderNodesFromNextExpressions($node);
            $stringValue .= $concatExpressionJoinData->getString();
            return $this->removeNodesAndCreateJsonEncodeFromStringValue($concatExpressionJoinData->getNodesToRemove(), $stringValue, $concatExpressionJoinData->getPlaceholdersToNodes(), $node);
        }
        if ($node->expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat) {
            // process only first concat
            $parentNode = $node->getAttribute(\_PhpScopere8e811afab72\Rector\NodeTypeResolver\Node\AttributeKey::PARENT_NODE);
            if ($parentNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat) {
                return null;
            }
            $concatStringAndPlaceholders = $this->concatJoiner->joinToStringAndPlaceholderNodes($node->expr);
            // B. just start of a json? join with all the strings that concat so same variable
            $concatExpressionJoinData = $this->collectContentAndPlaceholderNodesFromNextExpressions($node);
            $placeholderNodes = \array_merge($concatStringAndPlaceholders->getPlaceholderNodes(), $concatExpressionJoinData->getPlaceholdersToNodes());
            $stringValue = $concatStringAndPlaceholders->getContent();
            $stringValue .= $concatExpressionJoinData->getString();
            return $this->removeNodesAndCreateJsonEncodeFromStringValue($concatExpressionJoinData->getNodesToRemove(), $stringValue, $placeholderNodes, $node);
        }
        return null;
    }
    private function isJsonString(string $stringValue) : bool
    {
        if (!(bool) \_PhpScopere8e811afab72\Nette\Utils\Strings::match($stringValue, self::JSON_STRING_REGEX)) {
            return \false;
        }
        try {
            return (bool) \_PhpScopere8e811afab72\Nette\Utils\Json::decode($stringValue, \_PhpScopere8e811afab72\Nette\Utils\Json::FORCE_ARRAY);
        } catch (\_PhpScopere8e811afab72\Nette\Utils\JsonException $jsonException) {
            return \false;
        }
    }
    private function processJsonString(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign $assign, string $stringValue) : \_PhpScopere8e811afab72\PhpParser\Node
    {
        $arrayNode = $this->createArrayNodeFromJsonString($stringValue);
        return $this->createAndReturnJsonEncodeFromArray($assign, $arrayNode);
    }
    private function collectContentAndPlaceholderNodesFromNextExpressions(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign $assign) : \_PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\ConcatExpressionJoinData
    {
        $concatExpressionJoinData = new \_PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\ConcatExpressionJoinData();
        $currentNode = $assign;
        while ($nextExprAndConcatItem = $this->matchNextExprAssignConcatToSameVariable($assign->var, $currentNode)) {
            $concatItemNode = $nextExprAndConcatItem->getConcatItemNode();
            if ($concatItemNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_) {
                $concatExpressionJoinData->addString($concatItemNode->value);
            } elseif ($concatItemNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat) {
                $joinToStringAndPlaceholderNodes = $this->concatJoiner->joinToStringAndPlaceholderNodes($concatItemNode);
                $content = $joinToStringAndPlaceholderNodes->getContent();
                $concatExpressionJoinData->addString($content);
                foreach ($joinToStringAndPlaceholderNodes->getPlaceholderNodes() as $placeholder => $expr) {
                    /** @var string $placeholder */
                    $concatExpressionJoinData->addPlaceholderToNode($placeholder, $expr);
                }
            } elseif ($concatItemNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr) {
                $objectHash = '____' . \spl_object_hash($concatItemNode) . '____';
                $concatExpressionJoinData->addString($objectHash);
                $concatExpressionJoinData->addPlaceholderToNode($objectHash, $concatItemNode);
            }
            $concatExpressionJoinData->addNodeToRemove($nextExprAndConcatItem->getRemovedExpr());
            // jump to next one
            $currentNode = $this->getNextExpression($currentNode);
            if ($currentNode === null) {
                return $concatExpressionJoinData;
            }
        }
        return $concatExpressionJoinData;
    }
    /**
     * @param Node[] $nodesToRemove
     * @param Expr[] $placeholderNodes
     */
    private function removeNodesAndCreateJsonEncodeFromStringValue(array $nodesToRemove, string $stringValue, array $placeholderNodes, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign $assign) : ?\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign
    {
        $stringValue = \_PhpScopere8e811afab72\Nette\Utils\Strings::replace($stringValue, self::UNQUOTED_OBJECT_HASH_REGEX, '$1"$2"');
        if (!$this->isJsonString($stringValue)) {
            return null;
        }
        $this->removeNodes($nodesToRemove);
        $jsonArray = $this->createArrayNodeFromJsonString($stringValue);
        $this->replaceNodeObjectHashPlaceholdersWithNodes($jsonArray, $placeholderNodes);
        return $this->createAndReturnJsonEncodeFromArray($assign, $jsonArray);
    }
    private function createArrayNodeFromJsonString(string $stringValue) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_
    {
        $array = \_PhpScopere8e811afab72\Nette\Utils\Json::decode($stringValue, \_PhpScopere8e811afab72\Nette\Utils\Json::FORCE_ARRAY);
        return $this->createArray($array);
    }
    /**
     * Creates + adds
     *
     * $jsonData = ['...'];
     * $json = Nette\Utils\Json::encode($jsonData);
     */
    private function createAndReturnJsonEncodeFromArray(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign $assign, \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_ $jsonArray) : \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign
    {
        $jsonDataVariable = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable('jsonData');
        $jsonDataAssign = new \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign($jsonDataVariable, $jsonArray);
        $this->addNodeBeforeNode($jsonDataAssign, $assign);
        $assign->expr = $this->createStaticCall('_PhpScopere8e811afab72\\Nette\\Utils\\Json', 'encode', [$jsonDataVariable]);
        return $assign;
    }
    /**
     * @param Assign|ConcatAssign $currentNode
     */
    private function matchNextExprAssignConcatToSameVariable(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr, \_PhpScopere8e811afab72\PhpParser\Node $currentNode) : ?\_PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\NodeToRemoveAndConcatItem
    {
        $nextExpression = $this->getNextExpression($currentNode);
        if (!$nextExpression instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
            return null;
        }
        $nextExpressionNode = $nextExpression->expr;
        if ($nextExpressionNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\AssignOp\Concat) {
            // is assign to same variable?
            if (!$this->areNodesEqual($expr, $nextExpressionNode->var)) {
                return null;
            }
            return new \_PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\NodeToRemoveAndConcatItem($nextExpressionNode, $nextExpressionNode->expr);
        }
        // $value = $value . '...';
        if ($nextExpressionNode instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign) {
            if (!$nextExpressionNode->expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\BinaryOp\Concat) {
                return null;
            }
            // is assign to same variable?
            if (!$this->areNodesEqual($expr, $nextExpressionNode->var)) {
                return null;
            }
            $firstConcatItem = $this->concatManipulator->getFirstConcatItem($nextExpressionNode->expr);
            // is the first concat the same variable
            if (!$this->areNodesEqual($expr, $firstConcatItem)) {
                return null;
            }
            // return all but first node
            $allButFirstConcatItem = $this->concatManipulator->removeFirstItemFromConcat($nextExpressionNode->expr);
            return new \_PhpScopere8e811afab72\Rector\CodingStyle\ValueObject\NodeToRemoveAndConcatItem($nextExpressionNode, $allButFirstConcatItem);
        }
        return null;
    }
    /**
     * @param Expr[] $placeholderNodes
     */
    private function replaceNodeObjectHashPlaceholdersWithNodes(\_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_ $array, array $placeholderNodes) : void
    {
        // traverse and replace placeholder by original nodes
        $this->traverseNodesWithCallable($array, function (\_PhpScopere8e811afab72\PhpParser\Node $node) use($placeholderNodes) : ?Expr {
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Array_ && \count((array) $node->items) === 1) {
                $onlyItem = $node->items[0];
                if ($onlyItem === null) {
                    throw new \_PhpScopere8e811afab72\Rector\Core\Exception\ShouldNotHappenException();
                }
                $placeholderNode = $this->matchPlaceholderNode($onlyItem->value, $placeholderNodes);
                if ($placeholderNode && $this->isImplodeToJson($placeholderNode)) {
                    /** @var FuncCall $placeholderNode */
                    return $placeholderNode->args[1]->value;
                }
            }
            return $this->matchPlaceholderNode($node, $placeholderNodes);
        });
    }
    /**
     * @param Expr[] $placeholderNodes
     */
    private function matchPlaceholderNode(\_PhpScopere8e811afab72\PhpParser\Node $node, array $placeholderNodes) : ?\_PhpScopere8e811afab72\PhpParser\Node\Expr
    {
        if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_) {
            return null;
        }
        return $placeholderNodes[$node->value] ?? null;
    }
    /**
     * Matches: "implode('","', $items)"
     */
    private function isImplodeToJson(\_PhpScopere8e811afab72\PhpParser\Node\Expr $expr) : bool
    {
        if (!$expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\FuncCall) {
            return \false;
        }
        if (!$this->isName($expr, 'implode')) {
            return \false;
        }
        if (!isset($expr->args[1])) {
            return \false;
        }
        $firstArgumentValue = $expr->args[0]->value;
        if ($firstArgumentValue instanceof \_PhpScopere8e811afab72\PhpParser\Node\Scalar\String_ && $firstArgumentValue->value !== '","') {
            return \false;
        }
        return \true;
    }
}
