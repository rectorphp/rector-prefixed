<?php

namespace _PhpScoperbd5d0c5f7638\WhileLoopLookForAssignsInBranchesVariableExistence;

class Foo
{
    public function doFoo()
    {
        $lastOffset = 1;
        while (\false !== ($index = \strpos('abc', \DIRECTORY_SEPARATOR, $lastOffset))) {
            $lastOffset = $index + 1;
        }
    }
}
