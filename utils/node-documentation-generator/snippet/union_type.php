<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\Identifier;
use PhpParser\Node\UnionType;
$unionedTypes = [new \PhpParser\Node\Identifier('string'), new \PhpParser\Node\Identifier('int')];
return new \PhpParser\Node\UnionType($unionedTypes);
