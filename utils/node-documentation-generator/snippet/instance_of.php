<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
$class = new \PhpParser\Node\Name\FullyQualified('SomeClassName');
return new \PhpParser\Node\Expr\Instanceof_($variable, $class);