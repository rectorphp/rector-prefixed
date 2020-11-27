<?php

declare (strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */
namespace _PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Types;

use _PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Fqsen;
use _PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Type;
/**
 * Value Object representing the type 'string'.
 *
 * @psalm-immutable
 */
final class ClassString implements \_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Type
{
    /** @var Fqsen|null */
    private $fqsen;
    /**
     * Initializes this representation of a class string with the given Fqsen.
     */
    public function __construct(?\_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Fqsen $fqsen = null)
    {
        $this->fqsen = $fqsen;
    }
    /**
     * Returns the FQSEN associated with this object.
     */
    public function getFqsen() : ?\_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Fqsen
    {
        return $this->fqsen;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        if ($this->fqsen === null) {
            return 'class-string';
        }
        return 'class-string<' . (string) $this->fqsen . '>';
    }
}