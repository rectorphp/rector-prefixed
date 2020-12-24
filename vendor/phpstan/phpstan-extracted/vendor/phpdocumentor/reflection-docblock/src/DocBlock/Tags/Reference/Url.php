<?php

/**
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @copyright 2010-2017 Mike van Riel<mike@phpdoc.org>
 *  @license   http://www.opensource.org/licenses/mit-license.php MIT
 *  @link      http://phpdoc.org
 */
namespace _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\DocBlock\Tags\Reference;

use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Webmozart\Assert\Assert;
/**
 * Url reference used by {@see phpDocumentor\Reflection\DocBlock\Tags\See}
 */
final class Url implements \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference
{
    /**
     * @var string
     */
    private $uri;
    /**
     * Url constructor.
     */
    public function __construct($uri)
    {
        \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Webmozart\Assert\Assert::stringNotEmpty($uri);
        $this->uri = $uri;
    }
    public function __toString()
    {
        return $this->uri;
    }
}
