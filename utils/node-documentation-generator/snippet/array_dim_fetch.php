<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
$dimension = new \PhpParser\Node\Scalar\LNumber(0);
return new \PhpParser\Node\Expr\ArrayDimFetch($variable, $dimension);