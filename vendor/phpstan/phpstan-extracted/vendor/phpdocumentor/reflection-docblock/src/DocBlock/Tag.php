<?php

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright 2010-2015 Mike van Riel<mike@phpdoc.org>
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://phpdoc.org
 */
namespace _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\DocBlock;

use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\DocBlock\Tags\Formatter;
interface Tag
{
    public function getName();
    public static function create($body);
    public function render(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\phpDocumentor\Reflection\DocBlock\Tags\Formatter $formatter = null);
    public function __toString();
}
