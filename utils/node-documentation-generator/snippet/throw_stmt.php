<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Throw_;
$string = new \PhpParser\Node\Scalar\String_('some string');
return new \PhpParser\Node\Stmt\Throw_($string);