<?php

declare (strict_types=1);
namespace RectorPrefix20210309\PackageVersions;

use RectorPrefix20210309\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\RectorPrefix20210309\Composer\InstalledVersions::class);
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
    const VERSIONS = array('composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6', 'composer/semver' => '3.2.4@a02fdf930a3c1c3ed3a49b5f63859c0c20e10464', 'composer/xdebug-handler' => '1.4.5@f28d44c286812c714741478d968104c5e604a1d4', 'doctrine/annotations' => '1.12.1@b17c5014ef81d212ac539f07a1001832df1b6d3b', 'doctrine/inflector' => '2.0.3@9cf661f4eb38f7c881cac67c75ea9b00bf97b210', 'doctrine/lexer' => '1.2.1@e864bbf5904cb8f5bb334f99209b48018522f042', 'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.2@e4ca6f4669121ca6876b1d048c612480e39a28d5', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/utils' => 'v3.2.2@967cfc4f9a1acd5f1058d76715a424c53343c20c', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'phpstan/phpdoc-parser' => '0.4.12@2e17e4a90702d8b7ead58f4e08478a8e819ba6b8', 'phpstan/phpstan' => '0.12.81@0dd5b0ebeff568f7000022ea5f04aa86ad3124b8', 'phpstan/phpstan-phpunit' => '0.12.18@ab44aec7cfb5cb267b8bc30a8caea86dd50d1f72', 'psr/cache' => '2.0.0@213f9dbc5b9bfbc4f8db86d2838dc968752ce13b', 'psr/container' => '1.1.1@8622567409010282b7aeebe4bb841fe98b58dcaf', 'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'psr/simple-cache' => '1.0.1@408d5eafb83c57f6365a3ca330ff23aa4a5fa39b', 'sebastian/diff' => '4.0.4@3461e3fccc7cfdfc2720be910d3bd73c69be590d', 'symfony/cache' => 'v5.2.4@d15fb2576cdbe2c40d7c851e62f85b0faff3dd3d', 'symfony/cache-contracts' => 'dev-main@648977af9989c325eec96ba2671a20d5ff58fbea', 'symfony/config' => 'v5.2.4@212d54675bf203ff8aef7d8cee8eecfb72f4a263', 'symfony/console' => 'v5.2.4@d6d0cc30d8c0fda4e7b213c20509b0159a8f4556', 'symfony/dependency-injection' => 'v5.2.4@f7d89110c55d88620dc811f342f94393b8a045d4', 'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665', 'symfony/error-handler' => 'v5.2.4@b547d3babcab5c31e01de59ee33e9d9c1421d7d0', 'symfony/event-dispatcher' => 'v5.2.4@d08d6ec121a425897951900ab692b612a61d6240', 'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2', 'symfony/expression-language' => 'v5.2.4@3fc560e62bc5121751b792b11505db03a12cf83c', 'symfony/filesystem' => 'v5.2.4@710d364200997a5afde34d9fe57bd52f3cc1e108', 'symfony/finder' => 'v5.2.4@0d639a0943822626290d169965804f79400e6a04', 'symfony/http-client-contracts' => 'v2.3.1@41db680a15018f9c1d4b23516059633ce280ca33', 'symfony/http-foundation' => 'v5.2.4@54499baea7f7418bce7b5ec92770fd0799e8e9bf', 'symfony/http-kernel' => 'v5.2.4@c452dbe4f385f030c3957821bf921b13815d6140', 'symfony/polyfill-ctype' => 'v1.22.1@c6c942b1ac76c82448322025e084cadc56048b4e', 'symfony/polyfill-intl-grapheme' => 'v1.22.1@5601e09b69f26c1828b13b6bb87cb07cddba3170', 'symfony/polyfill-intl-normalizer' => 'v1.22.1@43a0283138253ed1d48d352ab6d0bdb3f809f248', 'symfony/polyfill-mbstring' => 'v1.22.1@5232de97ee3b75b0360528dae24e73db49566ab1', 'symfony/polyfill-php73' => 'v1.22.1@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2', 'symfony/polyfill-php80' => 'v1.22.1@dc3063ba22c2a1fd2f45ed856374d79114998f91', 'symfony/polyfill-uuid' => 'v1.22.1@9773608c15d3fe6ba2b6456a124777a7b8ffee2a', 'symfony/process' => 'v5.2.4@313a38f09c77fbcdc1d223e57d368cea76a2fd2f', 'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1', 'symfony/string' => 'v5.2.4@4e78d7d47061fa183639927ec40d607973699609', 'symfony/uid' => 'v5.2.4@959f69a8c0d68a37311eeabd630a0ef0979bc1d0', 'symfony/var-dumper' => 'v5.2.4@6a81fec0628c468cf6d5c87a4d003725e040e223', 'symfony/var-exporter' => 'v5.2.4@5aed4875ab514c8cb9b6ff4772baa25fa4c10307', 'symfony/yaml' => 'v5.2.4@7d6ae0cce3c33965af681a4355f1c4de326ed277', 'symplify/astral' => 'v9.2.6@7822604075dd170d53530a015b4d8259640eb170', 'symplify/autowire-array-parameter' => 'v9.2.6@18bd8fc637240d122c530c61f2fc442a49d526b6', 'symplify/composer-json-manipulator' => 'v9.2.6@0cbba95321e25af2a6387faed32de5696afe7c58', 'symplify/console-color-diff' => 'v9.2.6@05aad767f4b8a31c4bb80b31aaf5c3a9a25b2f8d', 'symplify/console-package-builder' => 'v9.2.6@8c5c1802e95060ef171ac3dfd7d48fab2ea34411', 'symplify/easy-testing' => 'v9.2.6@478605832a3011777c26f59a59ddf125d9ce8bd8', 'symplify/markdown-diff' => 'v9.2.6@53d91bf0703d5c49d856d9c4293d2221d7a1deeb', 'symplify/package-builder' => 'v9.2.6@74a0f748e75bf30dfc47234e050bab9e82875a64', 'symplify/php-config-printer' => 'v9.2.6@ab9f4e0c13febb4ce641ac08fbe794bffbfc4876', 'symplify/rule-doc-generator' => 'v9.2.6@beacc57d3ae00edb68fa8de760a79888b3f8378c', 'symplify/set-config-resolver' => 'v9.2.6@fd3dedb442d0405d8b8ffbf1db639ae1e7287a9e', 'symplify/simple-php-doc-parser' => 'v9.2.6@7ff4c6f3afc2b788c6d06bc50af078d1b7e9f712', 'symplify/skipper' => 'v9.2.6@e442f94611006b91a43213a15241fd7664abff76', 'symplify/smart-file-system' => 'v9.2.6@a8dee43d23d6ca8dc490c9d2c4c30ee966f75297', 'symplify/symfony-php-config' => 'v9.2.6@bcb8d86f2ac66eb7b639900935a081a4d107235f', 'symplify/symplify-kernel' => 'v9.2.6@ac429d0b845b5db6174bc825847b5ea33ae93110', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'rector/rector-prefixed' => 'dev-master@88ba75f820d617aa0d42e4df9906c9589fcc0ec1', 'rector/rector' => 'dev-master@88ba75f820d617aa0d42e4df9906c9589fcc0ec1');
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
        if (!\class_exists(\RectorPrefix20210309\Composer\InstalledVersions::class, \false) || !\RectorPrefix20210309\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \RectorPrefix20210309\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\RectorPrefix20210309\Composer\InstalledVersions::class, \false) && \RectorPrefix20210309\Composer\InstalledVersions::getRawData()) {
            return \RectorPrefix20210309\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \RectorPrefix20210309\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}
