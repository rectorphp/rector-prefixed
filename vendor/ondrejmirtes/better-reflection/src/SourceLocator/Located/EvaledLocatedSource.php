<?php

declare (strict_types=1);
namespace _PhpScoper88fe6e0ad041\Roave\BetterReflection\SourceLocator\Located;

class EvaledLocatedSource extends \_PhpScoper88fe6e0ad041\Roave\BetterReflection\SourceLocator\Located\LocatedSource
{
    /**
     * {@inheritDoc}
     */
    public function __construct(string $source)
    {
        parent::__construct($source, null);
    }
    public function isEvaled() : bool
    {
        return \true;
    }
}
