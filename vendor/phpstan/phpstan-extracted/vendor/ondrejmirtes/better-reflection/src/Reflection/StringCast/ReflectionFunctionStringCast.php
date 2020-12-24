<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\StringCast;

use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionFunction;
use _PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionParameter;
use function array_reduce;
use function count;
use function sprintf;
/**
 * @internal
 */
final class ReflectionFunctionStringCast
{
    public static function toString(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionFunction $functionReflection) : string
    {
        $parametersFormat = $functionReflection->getNumberOfParameters() > 0 ? "\n\n  - Parameters [%d] {%s\n  }" : '';
        return \sprintf('Function [ <%s> function %s ] {%s' . $parametersFormat . "\n}", self::sourceToString($functionReflection), $functionReflection->getName(), self::fileAndLinesToString($functionReflection), \count($functionReflection->getParameters()), self::parametersToString($functionReflection));
    }
    private static function sourceToString(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionFunction $functionReflection) : string
    {
        if ($functionReflection->isUserDefined()) {
            return 'user';
        }
        return \sprintf('internal:%s', $functionReflection->getExtensionName());
    }
    private static function fileAndLinesToString(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionFunction $functionReflection) : string
    {
        if ($functionReflection->isInternal()) {
            return '';
        }
        return \sprintf("\n  @@ %s %d - %d", $functionReflection->getFileName(), $functionReflection->getStartLine(), $functionReflection->getEndLine());
    }
    private static function parametersToString(\_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionFunction $functionReflection) : string
    {
        return \array_reduce($functionReflection->getParameters(), static function (string $string, \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\ReflectionParameter $parameterReflection) : string {
            return $string . "\n    " . \_PhpScoperb75b35f52b74\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\StringCast\ReflectionParameterStringCast::toString($parameterReflection);
        }, '');
    }
}
