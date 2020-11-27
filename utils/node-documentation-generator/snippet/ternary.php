<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
$trueConstFetch = new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('true'));
$falseConstFetch = new \PhpParser\Node\Expr\ConstFetch(new \PhpParser\Node\Name('false'));
return new \PhpParser\Node\Expr\Ternary($variable, $trueConstFetch, $falseConstFetch);
