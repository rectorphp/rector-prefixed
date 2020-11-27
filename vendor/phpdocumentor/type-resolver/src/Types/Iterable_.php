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

/**
 * Value Object representing iterable type
 *
 * @psalm-immutable
 */
final class Iterable_ extends \_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Types\AbstractList
{
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        if ($this->keyType) {
            return 'iterable<' . $this->keyType . ',' . $this->valueType . '>';
        }
        if ($this->valueType instanceof \_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Types\Mixed_) {
            return 'iterable';
        }
        return 'iterable<' . $this->valueType . '>';
    }
}
