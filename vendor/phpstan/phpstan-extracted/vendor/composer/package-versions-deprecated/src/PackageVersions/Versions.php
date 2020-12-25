<?php

declare (strict_types=1);
namespace _HumbugBox221ad6f1b81f\PackageVersions;

use _HumbugBox221ad6f1b81f\Composer\InstalledVersions;
use OutOfBoundsException;
\class_exists(\_HumbugBox221ad6f1b81f\Composer\InstalledVersions::class);
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
    const ROOT_PACKAGE_NAME = 'phpstan/phpstan-src';
    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS = array('clue/block-react' => 'v1.4.0@c8e7583ae55127b89d6915480ce295bac81c4f88', 'clue/ndjson-react' => 'v1.1.0@767ec9543945802b5766fab0da4520bf20626f66', 'composer/ca-bundle' => '1.2.8@8a7ecad675253e4654ea05505233285377405215', 'composer/package-versions-deprecated' => '1.11.99@c8c9aa8a14cc3d3bec86d0a8c3fa52ea79936855', 'composer/xdebug-handler' => '1.4.4@6e076a124f7ee146f2487554a94b6a19a74887ba', 'evenement/evenement' => 'v3.0.1@531bfb9d15f8aa57454f5f0285b18bec903b8fb7', 'hoa/compiler' => '3.17.08.08@aa09caf0bf28adae6654ca6ee415ee2f522672de', 'hoa/consistency' => '1.17.05.02@fd7d0adc82410507f332516faf655b6ed22e4c2f', 'hoa/event' => '1.17.01.13@6c0060dced212ffa3af0e34bb46624f990b29c54', 'hoa/exception' => '1.17.01.16@091727d46420a3d7468ef0595651488bfc3a458f', 'hoa/file' => '1.17.07.11@35cb979b779bc54918d2f9a4e02ed6c7a1fa67ca', 'hoa/iterator' => '2.17.01.10@d1120ba09cb4ccd049c86d10058ab94af245f0cc', 'hoa/math' => '1.17.05.16@7150785d30f5d565704912116a462e9f5bc83a0c', 'hoa/protocol' => '1.17.01.14@5c2cf972151c45f373230da170ea015deecf19e2', 'hoa/regex' => '1.17.01.13@7e263a61b6fb45c1d03d8e5ef77668518abd5bec', 'hoa/stream' => '1.17.02.21@3293cfffca2de10525df51436adf88a559151d82', 'hoa/ustring' => '4.17.01.16@e6326e2739178799b1fe3fdd92029f9517fa17a0', 'hoa/visitor' => '2.17.01.16@c18fe1cbac98ae449e0d56e87469103ba08f224a', 'hoa/zformat' => '1.17.01.10@522c381a2a075d4b9dbb42eb4592dd09520e4ac2', 'jean85/pretty-package-versions' => '1.5.1@a917488320c20057da87f67d0d40543dd9427f7a', 'jetbrains/phpstorm-stubs' => 'dev-master@b2402e4a525593f68ff46303dcc6bc625437276a', 'nette/bootstrap' => 'v3.0.2@67830a65b42abfb906f8e371512d336ebfb5da93', 'nette/di' => 'v3.0.5@766e8185196a97ded4f9128db6d79a3a124b7eb6', 'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50', 'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75', 'nette/php-generator' => 'v3.5.0@9162f7455059755dcbece1b5570d1bbfc6f0ab0d', 'nette/robot-loader' => 'v3.3.1@15c1ecd0e6e69e8d908dfc4cca7b14f3b850a96b', 'nette/schema' => 'v1.0.2@febf71fb4052c824046f5a33f4f769a6e7fa0cb4', 'nette/utils' => 'v3.1.3@c09937fbb24987b2a41c6022ebe84f4f1b8eec0f', 'nikic/php-parser' => 'v4.10.4@c6d052fc58cb876152f89f532b95a8d7907e7f0e', 'ondram/ci-detector' => '3.5.1@594e61252843b68998bddd48078c5058fe9028bd', 'ondrejmirtes/better-reflection' => '4.3.47@79c0ffb0c3a9903ecffc59d53bb2f1299b6c8a56', 'phpdocumentor/reflection-common' => '2.2.0@1d01c49d4ed62f25aa84a747ad35d5a16924662b', 'phpdocumentor/reflection-docblock' => '4.3.4@da3fd972d6bafd628114f7e7e036f45944b62e9c', 'phpdocumentor/type-resolver' => '1.4.0@6a467b8989322d92aa1c8bf2bebcc6e5c2ba55c0', 'phpstan/php-8-stubs' => '0.1.10@4f642d719cbfec45df4e09165c3aa5c2ab991265', 'phpstan/phpdoc-parser' => '0.4.9@98a088b17966bdf6ee25c8a4b634df313d8aa531', 'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f', 'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363', 'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc', 'react/cache' => 'v1.1.0@44a568925556b0bd8cacc7b49fb0f1cf0d706a0c', 'react/child-process' => 'v0.6.1@6895afa583d51dc10a4b9e93cd3bce17b3b77ac3', 'react/dns' => 'v1.4.0@665260757171e2ab17485b44e7ffffa7acb6ca1f', 'react/event-loop' => 'v1.1.1@6d24de090cd59cfc830263cfba965be77b563c13', 'react/http' => 'v1.1.0@754b0c18545d258922ffa907f3b18598280fdecd', 'react/promise' => 'v2.8.0@f3cff96a19736714524ca0dd1d4130de73dbbbc4', 'react/promise-stream' => 'v1.2.0@6384d8b76cf7dcc44b0bf3343fb2b2928412d1fe', 'react/promise-timer' => 'v1.6.0@daee9baf6ef30c43ea4c86399f828bb5f558f6e6', 'react/socket' => 'v1.6.0@e2b96b23a13ca9b41ab343268dbce3f8ef4d524a', 'react/stream' => 'v1.1.1@7c02b510ee3f582c810aeccd3a197b9c2f52ff1a', 'ringcentral/psr7' => '1.3.0@360faaec4b563958b673fb52bbe94e37f14bc686', 'roave/signature' => '1.1.0@c4e8a59946bad694ab5682a76e7884a9157a8a2c', 'symfony/console' => 'v4.4.16@20f73dd143a5815d475e0838ff867bce1eebd9d5', 'symfony/finder' => 'v4.4.16@26f63b8d4e92f2eecd90f6791a563ebb001abe31', 'symfony/polyfill-ctype' => 'v1.20.0@f4ba089a5b6366e453971d3aad5fe8e897b37f41', 'symfony/polyfill-mbstring' => 'v1.20.0@39d483bdf39be819deabf04ec872eb0b2410b531', 'symfony/polyfill-php73' => 'v1.20.0@8ff431c517be11c78c48a39a66d37431e26a6bed', 'symfony/polyfill-php80' => 'v1.20.0@e70aa8b064c5b72d3df2abd5ab1e90464ad009de', 'symfony/service-contracts' => 'v1.1.8@ffc7f5692092df31515df2a5ecf3b7302b3ddacf', 'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389', 'brianium/habitat' => 'v1.0.0@d0979e3bb379cbc78ecb42b3ac171bc2b7e06d96', 'brianium/paratest' => '4.0.0@2a06a82742fa303b59179fb8d6037dfb9897b9b2', 'composer/semver' => '1.7.1@38276325bd896f90dfcfe30029aa5db40df387a7', 'doctrine/instantiator' => '1.3.1@f350df0268e904597e3bd9c4685c53e0e333feea', 'myclabs/deep-copy' => '1.10.1@969b211f9a51aa1f6c01d1d2aef56d3bd91598e5', 'nategood/httpful' => '0.2.20@c1cd4d46a4b281229032cf39d4dd852f9887c0f6', 'phar-io/manifest' => '1.0.3@7761fcacf03b4d4f16e7ccb606d4879ca431fcf4', 'phar-io/version' => '2.0.1@45a2ec53a73c70ce41d55cedef9063630abaf1b6', 'phing/phing' => '2.16.3@b34c2bf9cd6abd39b4287dee31e68673784c8567', 'php-parallel-lint/php-parallel-lint' => 'v1.2.0@474f18bc6cc6aca61ca40bfab55139de614e51ca', 'phpspec/prophecy' => 'v1.10.3@451c3cd1418cf640de218914901e51b064abb093', 'phpstan/phpstan-deprecation-rules' => '0.12.5@bfabc6a1b4617fbcbff43f03a4c04eae9bafae21', 'phpstan/phpstan-php-parser' => '0.12.2@0b27eec1e92d48fa82199844dec119b1b22baba0', 'phpstan/phpstan-phpunit' => '0.12.16@1dd916d181b0539dea5cd37e91546afb8b107e17', 'phpstan/phpstan-strict-rules' => '0.12.5@334898a32217e4605e0f9cfa3d3fc3101bda26be', 'phpunit/php-code-coverage' => '6.1.4@807e6013b00af69b6c5d9ceb4282d0393dbb9d8d', 'phpunit/php-file-iterator' => '2.0.2@050bedf145a257b1ff02746c31894800e5122946', 'phpunit/php-text-template' => '1.2.1@31f8b717e51d9a2afca6c9f046f5d69fc27c8686', 'phpunit/php-timer' => '2.1.2@1038454804406b0b5f5f520358e78c1c2f71501e', 'phpunit/php-token-stream' => '3.1.1@995192df77f63a59e47f025390d2d1fdf8f425ff', 'phpunit/phpunit' => '7.5.20@9467db479d1b0487c99733bb1e7944d32deded2c', 'sebastian/code-unit-reverse-lookup' => '1.0.1@4419fcdb5eabb9caa61a27c7a1db532a6b55dd18', 'sebastian/comparator' => '3.0.2@5de4fc177adf9bce8df98d8d141a7559d7ccf6da', 'sebastian/diff' => '3.0.2@720fcc7e9b5cf384ea68d9d930d480907a0c1a29', 'sebastian/environment' => '4.2.3@464c90d7bdf5ad4e8a6aea15c091fec0603d4368', 'sebastian/exporter' => '3.1.2@68609e1261d215ea5b21b7987539cbfbe156ec3e', 'sebastian/global-state' => '2.0.0@e8ba02eed7bbbb9e59e43dedd3dddeff4a56b0c4', 'sebastian/object-enumerator' => '3.0.3@7cfd9e65d11ffb5af41198476395774d4c8a84c5', 'sebastian/object-reflector' => '1.1.1@773f97c67f28de00d397be301821b06708fca0be', 'sebastian/recursion-context' => '3.0.0@5b0cd723502bac3b006cbf3dbf7a1e3fcefe4fa8', 'sebastian/resource-operations' => '2.0.1@4d7a795d35b889bf80a0cc04e08d77cedfa917a9', 'sebastian/version' => '2.0.1@99732be0ddb3361e16ad77b68ba41efc8e979019', 'symfony/process' => 'v5.1.8@f00872c3f6804150d6a0f73b4151daab96248101', 'theseer/tokenizer' => '1.2.0@75a63c33a8577608444246075ea0af0d052e452a', 'phpstan/phpstan' => '0.12.64@3a08cb018572938ca6b90f861c5fbca17baeab4c', 'phpstan/phpstan-src' => '0.12.64@3a08cb018572938ca6b90f861c5fbca17baeab4c');
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
        if (!\class_exists(\_HumbugBox221ad6f1b81f\Composer\InstalledVersions::class, \false) || !\_HumbugBox221ad6f1b81f\Composer\InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }
        return \_HumbugBox221ad6f1b81f\Composer\InstalledVersions::getRootPackage()['name'];
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
        if (\class_exists(\_HumbugBox221ad6f1b81f\Composer\InstalledVersions::class, \false) && \_HumbugBox221ad6f1b81f\Composer\InstalledVersions::getRawData()) {
            return \_HumbugBox221ad6f1b81f\Composer\InstalledVersions::getPrettyVersion($packageName) . '@' . \_HumbugBox221ad6f1b81f\Composer\InstalledVersions::getReference($packageName);
        }
        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }
        throw new \OutOfBoundsException('Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files');
    }
}