<?php

declare (strict_types=1);
namespace Rector\SymfonyPHPUnit\Naming;

use _PhpScoperbd5d0c5f7638\Nette\Utils\Strings;
use PHPStan\Type\ObjectType;
use Rector\Naming\Naming\PropertyNaming;
final class ServiceNaming
{
    /**
     * @var PropertyNaming
     */
    private $propertyNaming;
    public function __construct(\Rector\Naming\Naming\PropertyNaming $propertyNaming)
    {
        $this->propertyNaming = $propertyNaming;
    }
    public function resolvePropertyNameFromServiceType(string $serviceType) : string
    {
        if (\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::contains($serviceType, '_') && !\_PhpScoperbd5d0c5f7638\Nette\Utils\Strings::contains($serviceType, '\\')) {
            return $this->propertyNaming->underscoreToName($serviceType);
        }
        return $this->propertyNaming->fqnToVariableName(new \PHPStan\Type\ObjectType($serviceType));
    }
}