<?php

declare (strict_types=1);
namespace Rector\Compiler\PhpScoper;

use _PhpScoperbd5d0c5f7638\Nette\Utils\Strings;
final class StaticEasyPrefixer
{
    /**
     * @var string[]
     */
    public const EXCLUDED_CLASSES = [
        '_PhpScoperbd5d0c5f7638\\Symfony\\Component\\EventDispatcher\\EventSubscriberInterface',
        '_PhpScoperbd5d0c5f7638\\Symfony\\Component\\Console\\Style\\SymfonyStyle',
        // part of public interface of configs.php
        '_PhpScoperbd5d0c5f7638\\Symfony\\Component\\DependencyInjection\\Loader\\Configurator\\ContainerConfigurator',
    ];
    /**
     * @var string[]
     */
    private const EXCLUDED_NAMESPACES = [
        'Hoa\\*',
        'PhpParser\\*',
        'PHPStan\\*',
        'Rector\\*',
        'Symplify\\*',
        // doctrine annotations to autocomplete
        'Doctrine\\ORM\\Mapping\\*',
    ];
    /**
     * @var string
     * @see https://regex101.com/r/P8sXfr/1
     */
    private const QUOTED_VALUE_REGEX = '#\'\\\\(\\w|@)#';
    public static function prefixClass(string $class, string $prefix) : string
    {
        foreach (self::EXCLUDED_NAMESPACES as $excludedNamespace) {
            $excludedNamespace = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::substring($excludedNamespace, 0, -2) . '\\';
            if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::startsWith($class, $excludedNamespace)) {
                return $class;
            }
        }
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::startsWith($class, '@')) {
            return $class;
        }
        return $prefix . '\\' . $class;
    }
    public static function unPrefixQuotedValues(string $prefix, string $content) : string
    {
        $match = \sprintf('\'%s\\\\r\\\\n\'', $prefix);
        $content = \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::replace($content, '#' . $match . '#', '\'\\\\r\\\\n\'');
        $match = \sprintf('\'%s\\\\', $prefix);
        return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::replace($content, '#' . $match . '#', "'");
    }
    public static function unPreSlashQuotedValues(string $content) : string
    {
        return \_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::replace($content, self::QUOTED_VALUE_REGEX, "'\$1");
    }
    /**
     * @return string[]
     */
    public static function getExcludedNamespacesAndClasses() : array
    {
        return \array_merge(self::EXCLUDED_NAMESPACES, self::EXCLUDED_CLASSES);
    }
}