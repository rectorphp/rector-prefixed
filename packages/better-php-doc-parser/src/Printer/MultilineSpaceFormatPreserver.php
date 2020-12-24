<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Printer;

use _PhpScoperb75b35f52b74\Nette\Utils\Strings;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Node;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use _PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use _PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode;
use _PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute;
use _PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface;
final class MultilineSpaceFormatPreserver
{
    /**
     * @var string
     * @see https://regex101.com/r/R2zdQt/1
     */
    public const NEWLINE_WITH_SPACE_REGEX = '#\\n {1,}$#s';
    public function resolveCurrentPhpDocNodeText(\_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\Node $node) : ?string
    {
        if ($node instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode && \property_exists($node->value, 'description')) {
            return $node->value->description;
        }
        if ($node instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode) {
            return $node->text;
        }
        if (!$node instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode) {
            return null;
        }
        if (!$node->value instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\GenericTagValueNode) {
            return null;
        }
        if (\substr_count($node->value->value, "\n") > 0) {
            return $node->value->value;
        }
        return null;
    }
    /**
     * Fix multiline BC break - https://github.com/phpstan/phpdoc-parser/pull/26/files
     */
    public function fixMultilineDescriptions(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface $attributeAwareNode) : \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
    {
        $originalContent = $attributeAwareNode->getAttribute(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::ORIGINAL_CONTENT);
        if (!$originalContent) {
            return $attributeAwareNode;
        }
        $nodeWithRestoredSpaces = $this->restoreOriginalSpacingInText($attributeAwareNode);
        if ($nodeWithRestoredSpaces !== null) {
            $attributeAwareNode = $nodeWithRestoredSpaces;
            $attributeAwareNode->setAttribute(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::HAS_DESCRIPTION_WITH_ORIGINAL_SPACES, \true);
        }
        return $attributeAwareNode;
    }
    /**
     * @param PhpDocTextNode|AttributeAwareNodeInterface $attributeAwareNode
     */
    private function restoreOriginalSpacingInText(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface $attributeAwareNode) : ?\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
    {
        /** @var string $originalContent */
        $originalContent = $attributeAwareNode->getAttribute(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Attributes\Attribute\Attribute::ORIGINAL_CONTENT);
        $oldSpaces = \_PhpScoperb75b35f52b74\Nette\Utils\Strings::matchAll($originalContent, '#\\s+#ms');
        $currentText = $this->resolveCurrentPhpDocNodeText($attributeAwareNode);
        if ($currentText === null) {
            return null;
        }
        $newParts = \_PhpScoperb75b35f52b74\Nette\Utils\Strings::split($currentText, '#\\s+#');
        // we can't do this!
        if (\count($oldSpaces) + 1 !== \count($newParts)) {
            return null;
        }
        $newText = '';
        foreach ($newParts as $key => $newPart) {
            $newText .= $newPart;
            if (isset($oldSpaces[$key])) {
                if (\_PhpScoperb75b35f52b74\Nette\Utils\Strings::match($oldSpaces[$key][0], self::NEWLINE_WITH_SPACE_REGEX)) {
                    // remove last extra space
                    $oldSpaces[$key][0] = \_PhpScoperb75b35f52b74\Nette\Utils\Strings::substring($oldSpaces[$key][0], 0, -1);
                }
                $newText .= $oldSpaces[$key][0];
            }
        }
        if ($newText === '') {
            return null;
        }
        return $this->setNewTextToPhpDocNode($attributeAwareNode, $newText);
    }
    private function setNewTextToPhpDocNode(\_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface $attributeAwareNode, string $newText) : \_PhpScoperb75b35f52b74\Rector\BetterPhpDocParser\Contract\PhpDocNode\AttributeAwareNodeInterface
    {
        if ($attributeAwareNode instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode && \property_exists($attributeAwareNode->value, 'description')) {
            $attributeAwareNode->value->description = $newText;
        }
        if ($attributeAwareNode instanceof \_PhpScoperb75b35f52b74\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode) {
            $attributeAwareNode->text = $newText;
        }
        if ($attributeAwareNode instanceof \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwarePhpDocTagNode && $attributeAwareNode->value instanceof \_PhpScoperb75b35f52b74\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode) {
            $attributeAwareNode->value->value = $newText;
        }
        return $attributeAwareNode;
    }
}
