<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
$classMethod = new \PhpParser\Node\Stmt\ClassMethod('methodName');
$classMethod->flags = \PhpParser\Node\Stmt\Class_::MODIFIER_PUBLIC;
return $classMethod;