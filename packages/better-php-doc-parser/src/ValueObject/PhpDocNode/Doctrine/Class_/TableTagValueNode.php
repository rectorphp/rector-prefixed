<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\Class_;

use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\SilentKeyNodeInterface;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\AroundSpaces;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\AbstractDoctrineTagValueNode;
use _PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException;
final class TableTagValueNode extends \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\PhpDocNode\Doctrine\AbstractDoctrineTagValueNode implements \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\SilentKeyNodeInterface
{
    /**
     * @var bool
     */
    private $haveIndexesFinalComma = \false;
    /**
     * @var bool
     */
    private $haveUniqueConstraintsFinalComma = \false;
    /**
     * @var IndexTagValueNode[]
     */
    private $indexes = [];
    /**
     * @var UniqueConstraintTagValueNode[]
     */
    private $uniqueConstraints = [];
    /**
     * @var AroundSpaces|null
     */
    private $indexesAroundSpaces;
    /**
     * @var AroundSpaces|null
     */
    private $uniqueConstraintsAroundSpaces;
    /**
     * @param mixed[] $options
     * @param IndexTagValueNode[] $indexes
     * @param UniqueConstraintTagValueNode[] $uniqueConstraints
     */
    public function __construct(?string $name, ?string $schema, array $indexes, array $uniqueConstraints, array $options, ?string $originalContent = null, bool $haveIndexesFinalComma = \false, bool $haveUniqueConstraintsFinalComma = \false, ?\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\AroundSpaces $indexesAroundSpaces = null, ?\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\ValueObject\AroundSpaces $uniqueConstraintsAroundSpaces = null)
    {
        $this->items['name'] = $name;
        $this->items['schema'] = $schema;
        $this->items['options'] = $options;
        $this->indexes = $indexes;
        $this->uniqueConstraints = $uniqueConstraints;
        $this->resolveOriginalContentSpacingAndOrder($originalContent);
        $this->haveIndexesFinalComma = $haveIndexesFinalComma;
        $this->haveUniqueConstraintsFinalComma = $haveUniqueConstraintsFinalComma;
        $this->indexesAroundSpaces = $indexesAroundSpaces;
        $this->uniqueConstraintsAroundSpaces = $uniqueConstraintsAroundSpaces;
    }
    public function __toString() : string
    {
        $items = $this->items;
        $items = $this->addCustomItems($items);
        $items = $this->completeItemsQuotes($items, ['indexes', 'uniqueConstraints']);
        $items = $this->filterOutMissingItems($items);
        $items = $this->makeKeysExplicit($items);
        return $this->printContentItems($items);
    }
    public function getShortName() : string
    {
        return '_PhpScoperb75b35f52b74\\@ORM\\Table';
    }
    public function getSilentKey() : string
    {
        return 'name';
    }
    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    private function addCustomItems(array $items) : array
    {
        if ($this->indexes !== []) {
            if ($this->indexesAroundSpaces === null) {
                throw new \_PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException();
            }
            $items['indexes'] = $this->printNestedTag($this->indexes, $this->haveIndexesFinalComma, $this->indexesAroundSpaces->getOpeningSpace(), $this->indexesAroundSpaces->getClosingSpace());
        }
        if ($this->uniqueConstraints !== []) {
            if ($this->uniqueConstraintsAroundSpaces === null) {
                throw new \_PhpScoperb75b35f52b74\Rector\Core\Exception\ShouldNotHappenException();
            }
            $items['uniqueConstraints'] = $this->printNestedTag($this->uniqueConstraints, $this->haveUniqueConstraintsFinalComma, $this->uniqueConstraintsAroundSpaces->getOpeningSpace(), $this->uniqueConstraintsAroundSpaces->getClosingSpace());
        }
        return $items;
    }
}
