<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Variable;
$variable = new \PhpParser\Node\Expr\Variable('variableName');
$anotherVariable = new \PhpParser\Node\Expr\Variable('anoterVariableName');
$arrayItems = [new \PhpParser\Node\Expr\ArrayItem($variable), new \PhpParser\Node\Expr\ArrayItem($anotherVariable)];
return new \PhpParser\Node\Expr\List_($arrayItems);
