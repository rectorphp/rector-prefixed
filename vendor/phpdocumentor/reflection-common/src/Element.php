<?php

declare (strict_types=1);
/**
 * phpDocumentor
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */
namespace _PhpScoper88fe6e0ad041\phpDocumentor\Reflection;

/**
 * Interface for Api Elements
 */
interface Element
{
    /**
     * Returns the Fqsen of the element.
     */
    public function getFqsen() : \_PhpScoper88fe6e0ad041\phpDocumentor\Reflection\Fqsen;
    /**
     * Returns the name of the element.
     */
    public function getName() : string;
}
