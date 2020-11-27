<?php

declare (strict_types=1);
namespace _PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Spanish;

use _PhpScoper88fe6e0ad041\Doctrine\Inflector\GenericLanguageInflectorFactory;
use _PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends \_PhpScoper88fe6e0ad041\Doctrine\Inflector\GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : \_PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Ruleset
    {
        return \_PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Spanish\Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : \_PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Ruleset
    {
        return \_PhpScoper88fe6e0ad041\Doctrine\Inflector\Rules\Spanish\Rules::getPluralRuleset();
    }
}
