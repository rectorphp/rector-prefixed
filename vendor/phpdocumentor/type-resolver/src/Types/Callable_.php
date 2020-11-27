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

use _PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Type;
/**
 * Value Object representing a Callable type.
 *
 * @psalm-immutable
 */
final class Callable_ implements \_PhpScoperbd5d0c5f7638\phpDocumentor\Reflection\Type
{
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString() : string
    {
        return 'callable';
    }
}