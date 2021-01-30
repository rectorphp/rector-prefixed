<?php

declare (strict_types=1);
namespace RectorPrefix20210130\PackageVersions;

use RectorPrefix20210130\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\RectorPrefix20210130\Composer\InstalledVersions::class);
/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'rector/rector';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/semver' => '3.2.4@a02fdf930a3c1c3ed3a49b5f63859c0c20e10464', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.1@2bc2f58079c920c2ecbb6935645abf6f2f5f94ba', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.10@5c1eb9aac80cb236f1b7fbe52e691afe4cc9f430', 'phpstan/phpstan' => '0.12.69@8f436ea35241da33487fd0d38b4bc3e6dfe30ea8', 'phpstan/phpstan-phpunit' => '0.12.17@432575b41cf2d4f44e460234acaf56119ed97d36', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.2@d6aed6c1bbf6f59e521f46437475a0ff4878d388', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.2.2@50e0e1314a3b2609d32b6a5a0d0fb5342494c4ab', 'symfony/console' => 'v5.2.2@d62ec79478b55036f65e2602e282822b8eaaff0a', 'symfony/dependency-injection' => 'v5.2.2@62f72187be689540385dce6c68a5d4c16f034139', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.2@4fd4a377f7b7ec7c3f3b40346a1411e0a83f9d40', 'symfony/event-dispatcher' => 'v5.2.2@4f9760f8074978ad82e2ce854dff79a71fe45367', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/expression-language' => 'v5.2.2@7bf30a4e29887110f8bd1882ccc82ee63c8a5133', 'symfony/filesystem' => 'v5.2.2@262d033b57c73e8b59cd6e68a45c528318b15038', 'symfony/finder' => 'v5.2.2@196f45723b5e618bf0e23b97e96d11652696ea9e', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.2@16dfa5acf8103f0394d447f8eea3ea49f9e50855', 'symfony/http-kernel' => 'v5.2.2@831b51e9370ece0febd0950dd819c63f996721c7', 'symfony/polyfill-ctype' => 'v1.22.0@c6c942b1ac76c82448322025e084cadc56048b4e', 'symfony/polyfill-intl-grapheme' => 'v1.22.0@267a9adeb8ecb8071040a740930e077cdfb987af', 'symfony/polyfill-intl-normalizer' => 'v1.22.0@6e971c891537eb617a00bb07a43d182a6915faba', 'symfony/polyfill-mbstring' => 'v1.22.0@f377a3dd1fde44d37b9831d68dc8dea3ffd28e13', 'symfony/polyfill-php73' => 'v1.22.0@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2', 'symfony/polyfill-php80' => 'v1.22.0@dc3063ba22c2a1fd2f45ed856374d79114998f91', 'symfony/process' => 'v5.2.2@313a38f09c77fbcdc1d223e57d368cea76a2fd2f', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.2@c95468897f408dd0aca2ff582074423dd0455122', 'symfony/var-dumper' => 'v5.2.2@72ca213014a92223a5d18651ce79ef441c12b694', 'symfony/var-exporter' => 'v5.2.2@5aed4875ab514c8cb9b6ff4772baa25fa4c10307', 'symfony/yaml' => 'v5.2.2@6bb8b36c6dea8100268512bf46e858c8eb5c545e', 'symplify/astral' => 'dev-master@9327e47e6011a0fe682d18f93aad298117d2294a', 'symplify/autowire-array-parameter' => 'dev-master@2db30bf3cf54f042ce76ace3a6602db2dbfa5bf1', 'symplify/composer-json-manipulator' => 'dev-master@bcfa12471095dd844a34426e70c06ca05fae532d', 'symplify/console-color-diff' => 'dev-master@ad6befc72b42ba2f37972615d0f6d93865561fbd', 'symplify/console-package-builder' => 'dev-master@6e682d58f8ba290891583c665a7902afd60bcb2f', 'symplify/easy-testing' => 'dev-master@dc735eee6730f8bf71faef0cf20f7f59c139d058', 'symplify/markdown-diff' => 'dev-master@08e6eeaf269dbb68f5990deff13ce7f8f6dc25d7', 'symplify/package-builder' => 'dev-master@2a1e990721eeec4b04307a59b3b3396f73bc9bb7', 'symplify/php-config-printer' => 'dev-master@341cc627c60140145660984f71eb64ace8b3f714', 'symplify/rule-doc-generator' => 'dev-master@dde2f6a5eb282cf3f2ce4f5e561d733af2d9c29f', 'symplify/set-config-resolver' => 'dev-master@ed783e6f9836795f41b1aa7a91c24e698ac17beb', 'symplify/simple-php-doc-parser' => 'dev-master@e7d963b93b44fd93e1d70bbd22153b66982615ba', 'symplify/skipper' => 'dev-master@110e49a8f75c6f7610c18d34e1bbd7b741a7b166', 'symplify/smart-file-system' => 'dev-master@2329c4a5e3118a48754c2494e23065ec76932785', 'symplify/symfony-php-config' => 'dev-master@8d8c102111c78e738654adcaf407a59c9e2ffed1', 'symplify/symplify-kernel' => 'dev-master@f350e162155ea4e5316209d37a75932154007e57', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => '0.9.17@1fc272e22834aa309f2184f186fed278a87fe054', 'rector/rector' => '0.9.17@1fc272e22834aa309f2184f186fed278a87fe054');
    private function __construct()
    {
    }
    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!\class_exists(\RectorPrefix20210130\Composer\InstalledVersions::class, \false) || !\RectorPrefix20210130\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \RectorPrefix20210130\Composer\InstalledVersions::getRootPackage()['name'];
    }
    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName) : string
    {
        if (\class_exists(\RectorPrefix20210130\Composer\InstalledVersions::class, \false) && \RectorPrefix20210130\Composer\InstalledVersions::getRawData()) {
            return \RectorPrefix20210130\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \RectorPrefix20210130\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
