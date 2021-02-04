<?php

declare (strict_types=1);
namespace RectorPrefix20210204\PackageVersions;

use RectorPrefix20210204\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\RectorPrefix20210204\Composer\InstalledVersions::class);
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
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/semver' => '3.2.4@a02fdf930a3c1c3ed3a49b5f63859c0c20e10464', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.11.1@ce77a7ba1770462cd705a91a151b6c3746f9c6ad', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.1@2bc2f58079c920c2ecbb6935645abf6f2f5f94ba', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.10@5c1eb9aac80cb236f1b7fbe52e691afe4cc9f430', 'phpstan/phpstan' => '0.12.69@8f436ea35241da33487fd0d38b4bc3e6dfe30ea8', 'phpstan/phpstan-phpunit' => '0.12.17@432575b41cf2d4f44e460234acaf56119ed97d36', 'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.3@d6aed6c1bbf6f59e521f46437475a0ff4878d388', 'symfony/cache-contracts' => 'v2.2.0@8034ca0b61d4dd967f3698aaa1da2507b631d0cb', 'symfony/config' => 'v5.2.3@50e0e1314a3b2609d32b6a5a0d0fb5342494c4ab', 'symfony/console' => 'v5.2.3@89d4b176d12a2946a1ae4e34906a025b7b6b135a', 'symfony/dependency-injection' => 'v5.2.3@62f72187be689540385dce6c68a5d4c16f034139', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.3@48f18b3609e120ea66d59142c23dc53e9562c26d', 'symfony/event-dispatcher' => 'v5.2.3@4f9760f8074978ad82e2ce854dff79a71fe45367', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/expression-language' => 'v5.2.3@7bf30a4e29887110f8bd1882ccc82ee63c8a5133', 'symfony/filesystem' => 'v5.2.3@262d033b57c73e8b59cd6e68a45c528318b15038', 'symfony/finder' => 'v5.2.3@4adc8d172d602008c204c2e16956f99257248e03', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.3@20c554c0f03f7cde5ce230ed248470cccbc34c36', 'symfony/http-kernel' => 'v5.2.3@89bac04f29e7b0b52f9fa6a4288ca7a8f90a1a05', 'symfony/polyfill-ctype' => 'v1.22.0@c6c942b1ac76c82448322025e084cadc56048b4e', 'symfony/polyfill-intl-grapheme' => 'v1.22.0@267a9adeb8ecb8071040a740930e077cdfb987af', 'symfony/polyfill-intl-normalizer' => 'v1.22.0@6e971c891537eb617a00bb07a43d182a6915faba', 'symfony/polyfill-mbstring' => 'v1.22.0@f377a3dd1fde44d37b9831d68dc8dea3ffd28e13', 'symfony/polyfill-php73' => 'v1.22.0@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2', 'symfony/polyfill-php80' => 'v1.22.0@dc3063ba22c2a1fd2f45ed856374d79114998f91', 'symfony/process' => 'v5.2.3@313a38f09c77fbcdc1d223e57d368cea76a2fd2f', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.3@c95468897f408dd0aca2ff582074423dd0455122', 'symfony/var-dumper' => 'v5.2.3@72ca213014a92223a5d18651ce79ef441c12b694', 'symfony/var-exporter' => 'v5.2.3@5aed4875ab514c8cb9b6ff4772baa25fa4c10307', 'symfony/yaml' => 'v5.2.3@338cddc6d74929f6adf19ca5682ac4b8e109cdb0', 'symplify/astral' => '9.0.50@14f9052c8046825879b57a1f1c2b2bb0311e2f12', 'symplify/autowire-array-parameter' => '9.0.50@5e90ba5c23356cf02e046b52b71b2dc8d4ef751a', 'symplify/composer-json-manipulator' => '9.0.50@d4c9c4daee6094f4864e1467e98fd99836beff68', 'symplify/console-color-diff' => '9.0.50@e717dd03c5a29bb762a921389d1b553b5efc4bdb', 'symplify/console-package-builder' => '9.0.50@d8c0e9e353e1dce2c5d237ca6e50958b437650ab', 'symplify/easy-testing' => '9.0.50@cafdfbc77c64d8bf4ed3a0a357acff2aa7628e68', 'symplify/markdown-diff' => '9.0.50@476b810770c2d4808a2f491a3f093ca3135a579f', 'symplify/package-builder' => '9.0.50@9409196fe9749862e5b9f7940dd7d100a2c28931', 'symplify/php-config-printer' => '9.0.50@b003ef0f61ab9233da73dac9d8a6bfb078173876', 'symplify/rule-doc-generator' => '9.0.50@911e4c6bd10330040b89abd8a2333248042a5dfb', 'symplify/set-config-resolver' => '9.0.50@740481d1a9f5304af66ca2f4ee17129dfc512f3d', 'symplify/simple-php-doc-parser' => '9.0.50@50b6f4f1dbc78f79a9e12a126462efaa97041370', 'symplify/skipper' => '9.0.50@8298caa54836dad586b4e349dd4b8bb7f6ec1440', 'symplify/smart-file-system' => '9.0.50@2329c4a5e3118a48754c2494e23065ec76932785', 'symplify/symfony-php-config' => '9.0.50@08a75d24a3716bcaddaab2f28bbfdc8436d8fc65', 'symplify/symplify-kernel' => '9.0.50@4baaf5e7c160ec9d1f7b0a95b4961a21e115c503', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-master@3784552b70e7413555a70ba8b11617b7a830242a', 'rector/rector' => 'dev-master@3784552b70e7413555a70ba8b11617b7a830242a');
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
        if (!\class_exists(\RectorPrefix20210204\Composer\InstalledVersions::class, \false) || !\RectorPrefix20210204\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \RectorPrefix20210204\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\RectorPrefix20210204\Composer\InstalledVersions::class, \false) && \RectorPrefix20210204\Composer\InstalledVersions::getRawData()) {
            return \RectorPrefix20210204\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \RectorPrefix20210204\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
