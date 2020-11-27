<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Exception;

use LogicException;
class EvaledAnonymousClassCannotBeLocated extends \LogicException
{
    public static function create() : self
    {
        return new self('Evaled anonymous class cannot be located');
    }
}