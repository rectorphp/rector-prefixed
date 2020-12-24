<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\BetterPhpDocParser\Printer;

use _PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode;
use _PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode;
final class SpacePatternFactory
{
    /**
     * @var string
     */
    private const TYPE_PATTERN = '[\\w\\\\\\[\\]\\(\\)\\{\\}\\:\\?\\$\\-\\,\\&|<>\\s]+';
    public function createSpacePattern(\_PhpScopere8e811afab72\PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode $phpDocTagNode) : string
    {
        $spacePattern = \preg_quote($phpDocTagNode->name, '#') . '(?<space>\\s+)';
        // we have to match exact @param space, in case of multiple @param s
        if ($phpDocTagNode->value instanceof \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode) {
            return $this->createSpacePatternForParamTagValueNode($phpDocTagNode->value, $spacePattern);
        }
        if ($phpDocTagNode->value instanceof \_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareGenericTagValueNode) {
            $originalValue = $phpDocTagNode->value->getAttribute('original_value') ?? $phpDocTagNode->value->value;
            // break by line break, to prevent false content positive
            $originalValueParts = \explode(\PHP_EOL, $originalValue);
            if (isset($originalValueParts[0])) {
                $originalValue = $originalValueParts[0];
            }
            $spacePattern .= \preg_quote($originalValue, '#');
        }
        return '#' . $spacePattern . '#';
    }
    private function createSpacePatternForParamTagValueNode(\_PhpScopere8e811afab72\Rector\AttributeAwarePhpDoc\Ast\PhpDoc\AttributeAwareParamTagValueNode $attributeAwareParamTagValueNode, string $spacePattern) : string
    {
        // type could be changed, so better keep it here
        $spacePattern .= self::TYPE_PATTERN;
        if ($attributeAwareParamTagValueNode->parameterName !== '') {
            $spacePattern .= '\\s+';
            if ($attributeAwareParamTagValueNode->isReference()) {
                $spacePattern .= '&';
            }
            if ($attributeAwareParamTagValueNode->isVariadic) {
                $spacePattern .= '...';
            }
            $spacePattern .= \preg_quote($attributeAwareParamTagValueNode->parameterName, '#');
        }
        return '#' . $spacePattern . '#';
    }
}
