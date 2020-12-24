<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp;

use _PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp;
class NotEqual extends \_PhpScoperb75b35f52b74\PhpParser\Node\Expr\BinaryOp
{
    public function getOperatorSigil() : string
    {
        return '!=';
    }
    public function getType() : string
    {
        return 'Expr_BinaryOp_NotEqual';
    }
}
