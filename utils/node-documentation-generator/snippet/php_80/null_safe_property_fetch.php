<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\Variable;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
return new \PhpParser\Node\Expr\NullsafePropertyFetch($variable, 'someProperty');