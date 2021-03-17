<?php

declare (strict_types=1);
namespace PhpParser\Node;

use PhpParser\Node;
use PhpParser\NodeAbstract;
class MatchArm extends \PhpParser\NodeAbstract
{
    /** @var null|Node\Expr[] */
    public $conds;
    /** @var Node\Expr */
    public $body;
    /**
     * @param null|Node\Expr[] $conds
     * @param \PhpParser\Node\Expr $body
     * @param mixed[] $attributes
     */
    public function __construct($conds, $body, $attributes = [])
    {
        $this->conds = $conds;
        $this->body = $body;
        $this->attributes = $attributes;
    }
    public function getSubNodeNames() : array
    {
        return ['conds', 'body'];
    }
    public function getType() : string
    {
        return 'MatchArm';
    }
}
