<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\PhpDocNode;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\AttributeTrait;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\TagAwareNodeInterface;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PartPhpDocTagPrinter\Behavior\ArrayPartPhpDocTagPrinterTrait;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\PhpDocNode\PrintTagValueNodeTrait;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Utils\ArrayItemStaticHelper;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\TagValueNodeConfiguration;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObjectFactory\TagValueNodeConfigurationFactory;
use _PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException;
abstract class AbstractTagValueNode implements \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface, \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode
{
    use AttributeTrait;
    use PrintTagValueNodeTrait;
    use ArrayPartPhpDocTagPrinterTrait;
    /**
     * @var mixed[]
     */
    protected $items = [];
    /**
     * @var TagValueNodeConfiguration
     */
    protected $tagValueNodeConfiguration;
    /**
     * @param mixed[] $items
     */
    public function __construct(array $items, ?string $originalContent = null)
    {
        $this->items = $items;
        $this->resolveOriginalContentSpacingAndOrder($originalContent);
    }
    /**
     * Generic fallback
     */
    public function __toString() : string
    {
        return $this->printItems($this->items);
    }
    /**
     * @return mixed[]
     */
    public function getItems() : array
    {
        return $this->items;
    }
    /**
     * @param mixed $value
     */
    public function changeItem(string $key, $value) : void
    {
        $this->items[$key] = $value;
    }
    public function removeItem(string $key) : void
    {
        unset($this->items[$key]);
    }
    /**
     * @param mixed[] $contentItems
     * @return mixed[]
     */
    public function filterOutMissingItems(array $contentItems) : array
    {
        if ($this->tagValueNodeConfiguration->getOrderedVisibleItems() === null) {
            return $contentItems;
        }
        return \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Utils\ArrayItemStaticHelper::filterAndSortVisibleItems($contentItems, $this->tagValueNodeConfiguration->getOrderedVisibleItems());
    }
    /**
     * @param mixed[] $items
     */
    protected function printItems(array $items) : string
    {
        $items = $this->completeItemsQuotes($items);
        $items = $this->filterOutMissingItems($items);
        $items = $this->makeKeysExplicit($items);
        return $this->printContentItems($items);
    }
    /**
     * @param string[] $items
     */
    protected function printContentItems(array $items) : string
    {
        $items = $this->filterOutMissingItems($items);
        // remove null values
        $items = \array_filter($items);
        if ($items === []) {
            if ($this->shouldPrintEmptyBrackets()) {
                return '()';
            }
            return '';
        }
        // print array value to string
        foreach ($items as $key => $value) {
            if (!\is_array($value)) {
                continue;
            }
            $arrayItemAsString = $this->printArrayItem($value, $key, $this->tagValueNodeConfiguration);
            $arrayItemAsString = $this->correctArraySingleItemPrint($value, $arrayItemAsString);
            /** @var string $key */
            $items[$key] = $arrayItemAsString;
        }
        return \sprintf('(%s%s%s)', $this->tagValueNodeConfiguration->hasNewlineAfterOpening() ? \PHP_EOL : '', \implode(', ', $items), $this->tagValueNodeConfiguration->hasNewlineBeforeClosing() ? \PHP_EOL : '');
    }
    /**
     * @param PhpDocTagValueNode[] $tagValueNodes
     */
    protected function printNestedTag(array $tagValueNodes, bool $haveFinalComma, ?string $openingSpace, ?string $closingSpace) : string
    {
        $tagValueNodesAsString = $this->printTagValueNodesSeparatedByComma($tagValueNodes);
        if ($openingSpace === null) {
            $openingSpace = \PHP_EOL . '    ';
        }
        if ($closingSpace === null) {
            $closingSpace = \PHP_EOL;
        }
        return \sprintf('{%s%s%s%s}', $openingSpace, $tagValueNodesAsString, $haveFinalComma ? ',' : '', $closingSpace);
    }
    protected function resolveOriginalContentSpacingAndOrder(?string $originalContent) : void
    {
        $tagValueNodeConfigurationFactory = new \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObjectFactory\TagValueNodeConfigurationFactory();
        // prevent override
        if ($this->tagValueNodeConfiguration !== null) {
            throw new \_PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException();
        }
        $this->tagValueNodeConfiguration = $tagValueNodeConfigurationFactory->createFromOriginalContent($originalContent, $this);
    }
    private function shouldPrintEmptyBrackets() : bool
    {
        // @todo decouple
        if ($this->tagValueNodeConfiguration->getOriginalContent() !== null && \_PhpScoperb75b35f52b74\Nette\Utils\Strings::endsWith($this->tagValueNodeConfiguration->getOriginalContent(), '()')) {
            return \true;
        }
        if (!$this->tagValueNodeConfiguration->hasOpeningBracket()) {
            return \false;
        }
        return $this->tagValueNodeConfiguration->hasClosingBracket();
    }
    /**
     * @param mixed[] $value
     */
    private function correctArraySingleItemPrint(array $value, string $arrayItemAsString) : string
    {
        if (\count($value) !== 1) {
            return $arrayItemAsString;
        }
        if ($this->tagValueNodeConfiguration->getOriginalContent() === null) {
            return $arrayItemAsString;
        }
        // item is in the original in same format → use it
        if ($this->tagValueNodeConfiguration->originalContentContains($arrayItemAsString)) {
            return $arrayItemAsString;
        }
        // is original item used the same, just without {} brackets?
        $nakedItem = \trim($arrayItemAsString, '{}');
        if (!$this->tagValueNodeConfiguration->originalContentContains('(' . $nakedItem . ')')) {
            return $arrayItemAsString;
        }
        return $nakedItem;
    }
    /**
     * @param PhpDocTagValueNode[] $tagValueNodes
     */
    private function printTagValueNodesSeparatedByComma(array $tagValueNodes) : string
    {
        if ($tagValueNodes === []) {
            return '';
        }
        $itemsAsStrings = [];
        foreach ($tagValueNodes as $tagValueNode) {
            $item = '';
            if ($tagValueNode instanceof \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\TagAwareNodeInterface) {
                $item .= $tagValueNode->getTag();
            }
            $item .= (string) $tagValueNode;
            $itemsAsStrings[] = $item;
        }
        return \implode(', ', $itemsAsStrings);
    }
}
