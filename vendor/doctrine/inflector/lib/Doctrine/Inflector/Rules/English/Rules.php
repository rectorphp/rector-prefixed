<?php

declare (strict_types=1);
namespace _PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English;

use _PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Patterns;
use _PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Ruleset;
use _PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Substitutions;
use _PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Transformations(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Inflectible::getSingular()), new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Patterns(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Uninflected::getSingular()), (new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Ruleset
    {
        return new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Ruleset(new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Transformations(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Inflectible::getPlural()), new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Patterns(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Uninflected::getPlural()), new \_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\Substitutions(...\_PhpScoperfce0de0de1ce\Doctrine\Inflector\Rules\English\Inflectible::getIrregular()));
    }
}
