<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Unset_;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
return new \PhpParser\Node\Stmt\Unset_([$variable]);