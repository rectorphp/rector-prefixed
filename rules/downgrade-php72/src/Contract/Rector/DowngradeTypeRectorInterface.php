<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\DowngradePhp72\Contract\Rector;

interface DowngradeTypeRectorInterface
{
    /**
     * Name of the type to remove
     */
    public function getTypeToRemove() : string;
}
