<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Rector\Core\ValueObject;

final class StaticNonPhpFileSuffixes
{
    /**
     * @var string[]
     */
    public const SUFFIXES = ['neon', 'yaml', 'xml', 'yml', 'twig', 'latte', 'blade.php'];
    public static function getSuffixRegexPattern() : string
    {
        $quotedSuffixes = [];
        foreach (self::SUFFIXES as $suffix) {
            $quotedSuffixes[] = \preg_quote($suffix, '#');
        }
        return '#\\.(' . \implode('|', $quotedSuffixes) . ')$#i';
    }
}
