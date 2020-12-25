<?php

declare (strict_types=1);
namespace Rector\Symfony3\FormHelper;

use _PhpScoper17db12703726\Nette\Utils\Strings;
use Rector\Symfony\ServiceMapProvider;
final class FormTypeStringToTypeProvider
{
    /**
     * @var array<string, string>
     */
    private const SYMFONY_CORE_NAME_TO_TYPE_MAP = ['form' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\FormType', 'birthday' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\BirthdayType', 'checkbox' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\CheckboxType', 'collection' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\CollectionType', 'country' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\CountryType', 'currency' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\CurrencyType', 'date' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\DateType', 'datetime' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\DatetimeType', 'email' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\EmailType', 'file' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\FileType', 'hidden' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\HiddenType', 'integer' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\IntegerType', 'language' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\LanguageType', 'locale' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\LocaleType', 'money' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\MoneyType', 'number' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\NumberType', 'password' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\PasswordType', 'percent' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\PercentType', 'radio' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\RadioType', 'range' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\RangeType', 'repeated' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\RepeatedType', 'search' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\SearchType', 'textarea' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TextareaType', 'text' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType', 'time' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TimeType', 'timezone' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\TimezoneType', 'url' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\UrlType', 'button' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\ButtonType', 'submit' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\SubmitType', 'reset' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\ResetType', 'entity' => '_PhpScoper17db12703726\\Symfony\\Bridge\\Doctrine\\Form\\Type\\EntityType', 'choice' => '_PhpScoper17db12703726\\Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType'];
    /**
     * @var array<string, string>
     */
    private $customServiceFormTypeByAlias = [];
    /**
     * @var ServiceMapProvider
     */
    private $serviceMapProvider;
    public function __construct(\Rector\Symfony\ServiceMapProvider $serviceMapProvider)
    {
        $this->serviceMapProvider = $serviceMapProvider;
    }
    public function matchClassForNameWithPrefix(string $name) : ?string
    {
        $nameToTypeMap = $this->getNameToTypeMap();
        if (\_PhpScoper17db12703726\Nette\Utils\Strings::startsWith($name, 'form.type.')) {
            $name = \_PhpScoper17db12703726\Nette\Utils\Strings::substring($name, \strlen('form.type.'));
        }
        return $nameToTypeMap[$name] ?? null;
    }
    /**
     * @return array<string, string>
     */
    private function getNameToTypeMap() : array
    {
        $customServiceFormTypeByAlias = $this->provideCustomServiceFormTypeByAliasFromContainerXml();
        return \array_merge(self::SYMFONY_CORE_NAME_TO_TYPE_MAP, $customServiceFormTypeByAlias);
    }
    /**
     * @return array<string, string>
     */
    private function provideCustomServiceFormTypeByAliasFromContainerXml() : array
    {
        if ($this->customServiceFormTypeByAlias !== []) {
            return $this->customServiceFormTypeByAlias;
        }
        $serviceMap = $this->serviceMapProvider->provide();
        $formTypeServiceDefinitions = $serviceMap->getServicesByTag('form.type');
        foreach ($formTypeServiceDefinitions as $formTypeServiceDefinition) {
            $formTypeTag = $formTypeServiceDefinition->getTag('form.type');
            if ($formTypeTag === null) {
                continue;
            }
            $alias = $formTypeTag->getData()['alias'] ?? null;
            if (!\is_string($alias)) {
                continue;
            }
            $class = $formTypeServiceDefinition->getClass();
            if ($class === null) {
                continue;
            }
            $this->customServiceFormTypeByAlias[$alias] = $class;
        }
        return $this->customServiceFormTypeByAlias;
    }
}