<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638;

use PhpParser\Node\MatchArm;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
$conds = [new \PhpParser\Node\Scalar\LNumber(1)];
$body = new \PhpParser\Node\Scalar\String_('yes');
return new \PhpParser\Node\MatchArm($conds, $body);