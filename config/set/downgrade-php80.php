<?php

declare (strict_types=1);
namespace RectorPrefix20210303;

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\DowngradePhp80\Rector\Catch_\DowngradeNonCapturingCatchesRector;
use Rector\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector;
use Rector\DowngradePhp80\Rector\ClassConstFetch\DowngradeClassOnObjectToGetClassRector;
use Rector\DowngradePhp80\Rector\ClassMethod\DowngradeTrailingCommasInParamUseRector;
use Rector\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeStaticTypeDeclarationRector;
use Rector\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector;
use Rector\DowngradePhp80\Rector\NullsafeMethodCall\DowngradeNullsafeToTernaryOperatorRector;
use Rector\DowngradePhp80\Rector\Property\DowngradeUnionTypeTypedPropertyRector;
use RectorPrefix20210303\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
return static function (\RectorPrefix20210303\Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator $containerConfigurator) : void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\DowngradePhp80\Rector\Property\DowngradeUnionTypeTypedPropertyRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\FunctionLike\DowngradeUnionTypeDeclarationRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\FunctionLike\DowngradeMixedTypeDeclarationRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\FunctionLike\DowngradeStaticTypeDeclarationRector::class);
    $parameters = $containerConfigurator->parameters();
    $parameters->set(\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, \Rector\Core\ValueObject\PhpVersion::PHP_74);
    // skip root namespace classes, like \DateTime or \Exception [default: true]
    $parameters->set(\Rector\Core\Configuration\Option::IMPORT_SHORT_CLASSES, \false);
    // skip classes used in PHP DocBlocks, like in /** @var \Some\Class */ [default: true]
    $parameters->set(\Rector\Core\Configuration\Option::IMPORT_DOC_BLOCKS, \false);
    $services->set(\Rector\DowngradePhp80\Rector\Class_\DowngradePropertyPromotionRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\Catch_\DowngradeNonCapturingCatchesRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\Expression\DowngradeMatchToSwitchRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\ClassConstFetch\DowngradeClassOnObjectToGetClassRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\NullsafeMethodCall\DowngradeNullsafeToTernaryOperatorRector::class);
    $services->set(\Rector\DowngradePhp80\Rector\ClassMethod\DowngradeTrailingCommasInParamUseRector::class);
};
