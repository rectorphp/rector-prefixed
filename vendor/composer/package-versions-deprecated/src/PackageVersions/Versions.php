<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PackageVersions;

use _PhpScoperb75b35f52b74\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\_PhpScoperb75b35f52b74\Composer\InstalledVersions::class);
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
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.0@d0427c1811462dbb6c503143eabe5478b26685f7', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.10@5c1eb9aac80cb236f1b7fbe52e691afe4cc9f430', 'phpstan/phpstan' => '0.12.64@23eb1cb7ae125f45f1d0e48051bcf67a9a9b08aa', 'phpstan/phpstan-phpunit' => '0.12.17@432575b41cf2d4f44e460234acaf56119ed97d36', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.1@5e61d63b1ef4fb4852994038267ad45e12f3ec52', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.2.1@d0a82d965296083fe463d655a3644cbe49cbaa80', 'symfony/console' => 'v5.2.1@47c02526c532fb381374dab26df05e7313978976', 'symfony/dependency-injection' => 'v5.2.1@7f8a9e9eff0581a33e20f6c5d41096fe22832d25', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.1@59b190ce16ddf32771a22087b60f6dafd3407147', 'symfony/event-dispatcher' => 'v5.2.1@1c93f7a1dff592c252574c79a8635a8a80856042', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/filesystem' => 'v5.2.1@fa8f8cab6b65e2d99a118e082935344c5ba8c60d', 'symfony/finder' => 'v5.2.1@0b9231a5922fd7287ba5b411893c0ecd2733e5ba', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.1@a1f6218b29897ab52acba58cfa905b83625bef8d', 'symfony/http-kernel' => 'v5.2.1@1feb619286d819180f7b8bc0dc44f516d9c62647', 'symfony/polyfill-ctype' => 'v1.20.0@f4ba089a5b6366e453971d3aad5fe8e897b37f41', 'symfony/polyfill-intl-grapheme' => 'v1.20.0@c7cf3f858ec7d70b89559d6e6eb1f7c2517d479c', 'symfony/polyfill-intl-normalizer' => 'v1.20.0@727d1096295d807c309fb01a851577302394c897', 'symfony/polyfill-mbstring' => 'v1.20.0@39d483bdf39be819deabf04ec872eb0b2410b531', 'symfony/polyfill-php73' => 'v1.20.0@8ff431c517be11c78c48a39a66d37431e26a6bed', 'symfony/polyfill-php80' => 'v1.20.0@e70aa8b064c5b72d3df2abd5ab1e90464ad009de', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.1@5bd67751d2e3f7d6f770c9154b8fbcb2aa05f7ed', 'symfony/var-dumper' => 'v5.2.1@13e7e882eaa55863faa7c4ad7c60f12f1a8b5089', 'symfony/var-exporter' => 'v5.2.1@fbc3507f23d263d75417e09a12d77c009f39676c', 'symfony/yaml' => 'v5.2.1@290ea5e03b8cf9b42c783163123f54441fb06939', 'symplify/autowire-array-parameter' => '9.0.14@05055c7e1978a7c037c5e7d06ecd2be0534c4e2a', 'symplify/composer-json-manipulator' => '9.0.14@cbf81741c3caa3fca1f0a88d56ea96a742ff2ca9', 'symplify/console-color-diff' => '9.0.14@f7c8a28eeff141b6ed956a94b129ae2285767b85', 'symplify/easy-testing' => '9.0.14@1aa3a514f9c94220b89efac25fb03927f21a3367', 'symplify/markdown-diff' => '9.0.14@b21a1f5d2a3aed0e54fff546f4e61745dc683bf7', 'symplify/package-builder' => '9.0.14@cd81932541986719ce3ff3770d0a35784a8ddc5d', 'symplify/php-config-printer' => '9.0.14@5b1fc5559fb83f90f828894f868942e42e773f25', 'symplify/rule-doc-generator' => '9.0.14@139950683b7c68f2087ba20c58a7a14b83090f5b', 'symplify/set-config-resolver' => '9.0.14@e84cce710722839569303b9cd7fbbd133ff2c6fc', 'symplify/simple-php-doc-parser' => '9.0.14@66b69b5e186f45a264ad372c45d610ca6ae9b085', 'symplify/skipper' => '9.0.14@a4c3b2ea27380a013234aebeac9bf8e4434c9806', 'symplify/smart-file-system' => '9.0.14@7f0b4af8ebc9dea9d15073259bcb40a079b52970', 'symplify/symfony-php-config' => '9.0.14@40690716503af0c67cdef38b241309550c1b33db', 'symplify/symplify-kernel' => '9.0.14@2c8a0177e305522b9bcda44301cfe58edc69038a', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-102342986c94576596a60253e99c558e29aa33ac@102342986c94576596a60253e99c558e29aa33ac', 'rector/rector' => 'dev-102342986c94576596a60253e99c558e29aa33ac@102342986c94576596a60253e99c558e29aa33ac');
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
        if (!\class_exists(\_PhpScoperb75b35f52b74\Composer\InstalledVersions::class, \false) || !\_PhpScoperb75b35f52b74\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \_PhpScoperb75b35f52b74\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\_PhpScoperb75b35f52b74\Composer\InstalledVersions::class, \false) && \_PhpScoperb75b35f52b74\Composer\InstalledVersions::getRawData()) {
            return \_PhpScoperb75b35f52b74\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \_PhpScoperb75b35f52b74\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
