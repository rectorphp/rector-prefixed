<?php

declare (strict_types=1);
namespace RectorPrefix20210302\PackageVersions;

use RectorPrefix20210302\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\RectorPrefix20210302\Composer\InstalledVersions::class);
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
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/semver' => '3.2.4@a02fdf930a3c1c3ed3a49b5f63859c0c20e10464', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.12.1@b17c5014ef81d212ac539f07a1001832df1b6d3b', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.2@e4ca6f4669121ca6876b1d048c612480e39a28d5', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.1@2bc2f58079c920c2ecbb6935645abf6f2f5f94ba', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.12@2e17e4a90702d8b7ead58f4e08478a8e819ba6b8', 'phpstan/phpstan' => '0.12.80@c6a1b17f22ecf708d434d6bee05092647ec7e686', 'phpstan/phpstan-phpunit' => '0.12.17@432575b41cf2d4f44e460234acaf56119ed97d36', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.3@d6aed6c1bbf6f59e521f46437475a0ff4878d388', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.2.3@50e0e1314a3b2609d32b6a5a0d0fb5342494c4ab', 'symfony/console' => 'v5.2.3@89d4b176d12a2946a1ae4e34906a025b7b6b135a', 'symfony/dependency-injection' => 'v5.2.3@62f72187be689540385dce6c68a5d4c16f034139', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.3@48f18b3609e120ea66d59142c23dc53e9562c26d', 'symfony/event-dispatcher' => 'v5.2.3@4f9760f8074978ad82e2ce854dff79a71fe45367', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/expression-language' => 'v5.2.3@7bf30a4e29887110f8bd1882ccc82ee63c8a5133', 'symfony/filesystem' => 'v5.2.3@262d033b57c73e8b59cd6e68a45c528318b15038', 'symfony/finder' => 'v5.2.3@4adc8d172d602008c204c2e16956f99257248e03', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.3@20c554c0f03f7cde5ce230ed248470cccbc34c36', 'symfony/http-kernel' => 'v5.2.3@89bac04f29e7b0b52f9fa6a4288ca7a8f90a1a05', 'symfony/polyfill-ctype' => 'v1.22.1@c6c942b1ac76c82448322025e084cadc56048b4e', 'symfony/polyfill-intl-grapheme' => 'v1.22.1@5601e09b69f26c1828b13b6bb87cb07cddba3170', 'symfony/polyfill-intl-normalizer' => 'v1.22.1@43a0283138253ed1d48d352ab6d0bdb3f809f248', 'symfony/polyfill-mbstring' => 'v1.22.1@5232de97ee3b75b0360528dae24e73db49566ab1', 'symfony/polyfill-php73' => 'v1.22.1@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2', 'symfony/polyfill-php80' => 'v1.22.1@dc3063ba22c2a1fd2f45ed856374d79114998f91', 'symfony/process' => 'v5.2.3@313a38f09c77fbcdc1d223e57d368cea76a2fd2f', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.3@c95468897f408dd0aca2ff582074423dd0455122', 'symfony/var-dumper' => 'v5.2.3@72ca213014a92223a5d18651ce79ef441c12b694', 'symfony/var-exporter' => 'v5.2.3@5aed4875ab514c8cb9b6ff4772baa25fa4c10307', 'symfony/yaml' => 'v5.2.3@338cddc6d74929f6adf19ca5682ac4b8e109cdb0', 'symplify/astral' => 'v9.2.4@2fec3a847feb4c90e2eac70436b124c6f6ec4d8b', 'symplify/autowire-array-parameter' => 'v9.2.4@8daf626dc55284d36d69f6fce270930b2a7be462', 'symplify/composer-json-manipulator' => 'v9.2.4@239e9efc3d41cf60df7512c9449203418c590707', 'symplify/console-color-diff' => 'v9.2.4@8f4cd2e40c1e9685c34a72adc8273e60e8dc14b2', 'symplify/console-package-builder' => 'v9.2.4@cf782495206ac8fca7d308f46c7ad6ac5bd79a92', 'symplify/easy-testing' => 'v9.2.4@5e8c10a525563e9d34cd404dd8c289c720b614c5', 'symplify/markdown-diff' => 'v9.2.4@7050b2ed0b0f33a722a46427cdf99a83fc3222fb', 'symplify/package-builder' => 'v9.2.4@d43f747ae3095f2fd7a124f591c86f3930730b11', 'symplify/php-config-printer' => 'v9.2.4@1b007a8c46983daaeb9faa0692e02c84509b8ea7', 'symplify/rule-doc-generator' => 'v9.2.4@56f01cfdbb397acf13459e02b73fcb6cbbfdf673', 'symplify/set-config-resolver' => 'v9.2.4@c2b1efccc41f496e82d9af497bc59cfe2d2212e9', 'symplify/simple-php-doc-parser' => 'v9.2.4@b59683706f3b2b73e3fe203f04f58e5838a24580', 'symplify/skipper' => 'v9.2.4@b77767be18e90ba1375fd06e80aa23d70d87ee3d', 'symplify/smart-file-system' => 'v9.2.4@ef20e90676db3af6397de2b0dd86fb902cf872da', 'symplify/symfony-php-config' => 'v9.2.4@a7a006d9674a61dcb430697d3bc9a2297c87d188', 'symplify/symplify-kernel' => 'v9.2.4@319c0afde5e48aec50d091076d005ff0ebcc4aa3', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-master@66f6b4a57353a7c8b6bfd46a704ecf6953d01b37', 'rector/rector' => 'dev-master@66f6b4a57353a7c8b6bfd46a704ecf6953d01b37');
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
        if (!\class_exists(\RectorPrefix20210302\Composer\InstalledVersions::class, \false) || !\RectorPrefix20210302\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \RectorPrefix20210302\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\RectorPrefix20210302\Composer\InstalledVersions::class, \false) && \RectorPrefix20210302\Composer\InstalledVersions::getRawData()) {
            return \RectorPrefix20210302\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \RectorPrefix20210302\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
