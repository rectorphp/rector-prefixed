<?php

declare (strict_types=1);
namespace RectorPrefix20210112\PackageVersions;

use RectorPrefix20210112\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\RectorPrefix20210112\Composer\InstalledVersions::class);
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
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/semver' => '3.2.4@a02fdf930a3c1c3ed3a49b5f63859c0c20e10464', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.1@2bc2f58079c920c2ecbb6935645abf6f2f5f94ba', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.10@5c1eb9aac80cb236f1b7fbe52e691afe4cc9f430', 'phpstan/phpstan' => '0.12.66@4110a2425c6bd53acbdfcda07885e87b66e9ba3e', 'phpstan/phpstan-phpunit' => '0.12.17@432575b41cf2d4f44e460234acaf56119ed97d36', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.1@5e61d63b1ef4fb4852994038267ad45e12f3ec52', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.2.1@d0a82d965296083fe463d655a3644cbe49cbaa80', 'symfony/console' => 'v5.2.1@47c02526c532fb381374dab26df05e7313978976', 'symfony/dependency-injection' => 'v5.2.1@7f8a9e9eff0581a33e20f6c5d41096fe22832d25', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.1@59b190ce16ddf32771a22087b60f6dafd3407147', 'symfony/event-dispatcher' => 'v5.2.1@1c93f7a1dff592c252574c79a8635a8a80856042', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/filesystem' => 'v5.2.1@fa8f8cab6b65e2d99a118e082935344c5ba8c60d', 'symfony/finder' => 'v5.2.1@0b9231a5922fd7287ba5b411893c0ecd2733e5ba', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.1@a1f6218b29897ab52acba58cfa905b83625bef8d', 'symfony/http-kernel' => 'v5.2.1@1feb619286d819180f7b8bc0dc44f516d9c62647', 'symfony/polyfill-ctype' => 'v1.22.0@c6c942b1ac76c82448322025e084cadc56048b4e', 'symfony/polyfill-intl-grapheme' => 'v1.22.0@267a9adeb8ecb8071040a740930e077cdfb987af', 'symfony/polyfill-intl-normalizer' => 'v1.22.0@6e971c891537eb617a00bb07a43d182a6915faba', 'symfony/polyfill-mbstring' => 'v1.22.0@f377a3dd1fde44d37b9831d68dc8dea3ffd28e13', 'symfony/polyfill-php73' => 'v1.22.0@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2', 'symfony/polyfill-php80' => 'v1.22.0@dc3063ba22c2a1fd2f45ed856374d79114998f91', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.1@5bd67751d2e3f7d6f770c9154b8fbcb2aa05f7ed', 'symfony/var-dumper' => 'v5.2.1@13e7e882eaa55863faa7c4ad7c60f12f1a8b5089', 'symfony/var-exporter' => 'v5.2.1@fbc3507f23d263d75417e09a12d77c009f39676c', 'symfony/yaml' => 'v5.2.1@290ea5e03b8cf9b42c783163123f54441fb06939', 'symplify/autowire-array-parameter' => '9.0.32@4c33eae460e4df5be84afc6ae88409da86f821a4', 'symplify/composer-json-manipulator' => '9.0.32@4e22d7c861d40017626d76577e366d8ca6a38d6a', 'symplify/console-color-diff' => '9.0.32@ad90a87f4bac93f8e543c43e10f07cb960d1fc6d', 'symplify/console-package-builder' => '9.0.32@e1b3e69d7d0fb6132dc8b8c1555ca460afa605be', 'symplify/easy-testing' => '9.0.32@66ad2a4c1e6e67f3dea9c2b96b57fa0d4df5f442', 'symplify/markdown-diff' => '9.0.32@f757ae8475b12ad8f8ab04413fcdb62f0ddadd53', 'symplify/package-builder' => '9.0.32@3e4efce8953c7c27a085e6a5a7126c330df486fc', 'symplify/php-config-printer' => '9.0.32@f65c3f4ba22478b0587972a132234492e65f10e9', 'symplify/rule-doc-generator' => '9.0.32@2dc84103babef4d0c1f262e38956dea71abaaf33', 'symplify/set-config-resolver' => '9.0.32@1f70c38a290cc7019f8eda31e89bcbe45b2f7621', 'symplify/simple-php-doc-parser' => '9.0.32@107b08dd4adc6e62e0b848be349715b6a4f1cb94', 'symplify/skipper' => '9.0.32@64494798ca0aad2c0a6933f27582be37bb0c65d4', 'symplify/smart-file-system' => '9.0.32@0d53dca72282408cd29e76fb1a59ed07b55388e9', 'symplify/symfony-php-config' => '9.0.32@4a1ce3c461e67309450b9e08f1c8eca5551e1b3d', 'symplify/symplify-kernel' => '9.0.32@bf85bace4e37e38981e70d866caf14600560cb0f', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-master@e71a107b1b3bf2cafa8706d8ece45a7ba5cfd20b', 'rector/rector' => 'dev-master@e71a107b1b3bf2cafa8706d8ece45a7ba5cfd20b');
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
        if (!\class_exists(\RectorPrefix20210112\Composer\InstalledVersions::class, \false) || !\RectorPrefix20210112\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \RectorPrefix20210112\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\RectorPrefix20210112\Composer\InstalledVersions::class, \false) && \RectorPrefix20210112\Composer\InstalledVersions::getRawData()) {
            return \RectorPrefix20210112\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \RectorPrefix20210112\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
