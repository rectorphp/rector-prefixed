<?php

declare (strict_types=1);
namespace PHPStan\DependencyInjection;

use _PhpScoper88fe6e0ad041\Nette;
use _PhpScoper88fe6e0ad041\Nette\Schema\Expect;
use PHPStan\Analyser\TypeSpecifierFactory;
use PHPStan\Broker\BrokerFactory;
use PHPStan\PhpDoc\TypeNodeResolverExtension;
use PHPStan\Rules\RegistryFactory;
class ConditionalTagsExtension extends \_PhpScoper88fe6e0ad041\Nette\DI\CompilerExtension
{
    public function getConfigSchema() : \_PhpScoper88fe6e0ad041\Nette\Schema\Schema
    {
        $bool = \_PhpScoper88fe6e0ad041\Nette\Schema\Expect::bool();
        return \_PhpScoper88fe6e0ad041\Nette\Schema\Expect::arrayOf(\_PhpScoper88fe6e0ad041\Nette\Schema\Expect::structure([\PHPStan\Broker\BrokerFactory::PROPERTIES_CLASS_REFLECTION_EXTENSION_TAG => $bool, \PHPStan\Broker\BrokerFactory::METHODS_CLASS_REFLECTION_EXTENSION_TAG => $bool, \PHPStan\Broker\BrokerFactory::DYNAMIC_METHOD_RETURN_TYPE_EXTENSION_TAG => $bool, \PHPStan\Broker\BrokerFactory::DYNAMIC_STATIC_METHOD_RETURN_TYPE_EXTENSION_TAG => $bool, \PHPStan\Broker\BrokerFactory::DYNAMIC_FUNCTION_RETURN_TYPE_EXTENSION_TAG => $bool, \PHPStan\Broker\BrokerFactory::OPERATOR_TYPE_SPECIFYING_EXTENSION_TAG => $bool, \PHPStan\Rules\RegistryFactory::RULE_TAG => $bool, \PHPStan\PhpDoc\TypeNodeResolverExtension::EXTENSION_TAG => $bool, \PHPStan\Analyser\TypeSpecifierFactory::FUNCTION_TYPE_SPECIFYING_EXTENSION_TAG => $bool, \PHPStan\Analyser\TypeSpecifierFactory::METHOD_TYPE_SPECIFYING_EXTENSION_TAG => $bool, \PHPStan\Analyser\TypeSpecifierFactory::STATIC_METHOD_TYPE_SPECIFYING_EXTENSION_TAG => $bool])->min(1));
    }
    public function beforeCompile() : void
    {
        /** @var mixed[] $config */
        $config = $this->config;
        $builder = $this->getContainerBuilder();
        foreach ($config as $type => $tags) {
            $services = $builder->findByType($type);
            if (\count($services) === 0) {
                throw new \PHPStan\ShouldNotHappenException(\sprintf('No services of type "%s" found.', $type));
            }
            foreach ($services as $service) {
                foreach ($tags as $tag => $parameter) {
                    if ((bool) $parameter) {
                        $service->addTag($tag);
                        continue;
                    }
                }
            }
        }
    }
}
