<?php

namespace _PhpScoperbd5d0c5f7638\ConstantConditionNotPhpDoc;

class IfCondition
{
    /**
     * @param object $object
     */
    public function doFoo(self $self, $object) : void
    {
        if ($self) {
        }
        if ($object) {
        }
    }
}
