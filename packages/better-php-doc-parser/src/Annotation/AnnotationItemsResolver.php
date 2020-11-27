<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\Annotation;

use _PhpScoperbd5d0c5f7638\Doctrine\Common\Annotations\Annotation;
use _PhpScoperbd5d0c5f7638\Nette\Utils\Strings;
final class AnnotationItemsResolver
{
    /**
     * @var AnnotationVisibilityDetector
     */
    private $annotationVisibilityDetector;
    public function __construct(\Rector\BetterPhpDocParser\Annotation\AnnotationVisibilityDetector $annotationVisibilityDetector)
    {
        $this->annotationVisibilityDetector = $annotationVisibilityDetector;
    }
    /**
     * @param object|Annotation|mixed[] $annotationOrItems
     * @return mixed[]
     */
    public function resolve($annotationOrItems) : array
    {
        if (\is_array($annotationOrItems)) {
            return $annotationOrItems;
        }
        if (\is_object($annotationOrItems)) {
            // special case for private property annotations
            if ($this->annotationVisibilityDetector->isPrivate($annotationOrItems)) {
                return $this->resolvePrivatePropertyValues($annotationOrItems);
            }
            return \get_object_vars($annotationOrItems);
        }
        return [];
    }
    /**
     * @see https://ocramius.github.io/blog/fast-php-object-to-array-conversion/
     * @return mixed[]
     */
    private function resolvePrivatePropertyValues(object $object) : array
    {
        $items = [];
        foreach ((array) $object as $messedPropertyName => $value) {
            $propertyName = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::after($messedPropertyName, "\0", -1);
            $items[$propertyName] = $value;
        }
        return $items;
    }
}