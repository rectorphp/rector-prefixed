<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception;

use UnexpectedValueException;
use function sprintf;
final class MissingComposerJson extends \UnexpectedValueException implements \_PhpScoperbd5d0c5f7638\Roave\BetterReflection\SourceLocator\Type\Composer\Factory\Exception\Exception
{
    public static function inProjectPath(string $path) : self
    {
        return new self(\sprintf('Could not locate a "composer.json" file in "%s"', $path));
    }
}