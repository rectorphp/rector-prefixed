<?php

declare (strict_types=1);
namespace _PhpScoper50d83356d739\Doctrine\Inflector;

use _PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset;
use function array_unshift;
abstract class GenericLanguageInflectorFactory implements \_PhpScoper50d83356d739\Doctrine\Inflector\LanguageInflectorFactory
{
    /** @var Ruleset[] */
    private $singularRulesets = [];
    /** @var Ruleset[] */
    private $pluralRulesets = [];
    public final function __construct()
    {
        $this->singularRulesets[] = $this->getSingularRuleset();
        $this->pluralRulesets[] = $this->getPluralRuleset();
    }
    public final function build() : \Doctrine\Inflector\Inflector
    {
        return new \Doctrine\Inflector\Inflector(new \_PhpScoper50d83356d739\Doctrine\Inflector\CachedWordInflector(new \_PhpScoper50d83356d739\Doctrine\Inflector\RulesetInflector(...$this->singularRulesets)), new \_PhpScoper50d83356d739\Doctrine\Inflector\CachedWordInflector(new \_PhpScoper50d83356d739\Doctrine\Inflector\RulesetInflector(...$this->pluralRulesets)));
    }
    public final function withSingularRules(?\_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset $singularRules, bool $reset = \false) : \_PhpScoper50d83356d739\Doctrine\Inflector\LanguageInflectorFactory
    {
        if ($reset) {
            $this->singularRulesets = [];
        }
        if ($singularRules instanceof \_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset) {
            \array_unshift($this->singularRulesets, $singularRules);
        }
        return $this;
    }
    public final function withPluralRules(?\_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset $pluralRules, bool $reset = \false) : \_PhpScoper50d83356d739\Doctrine\Inflector\LanguageInflectorFactory
    {
        if ($reset) {
            $this->pluralRulesets = [];
        }
        if ($pluralRules instanceof \_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset) {
            \array_unshift($this->pluralRulesets, $pluralRules);
        }
        return $this;
    }
    protected abstract function getSingularRuleset() : \_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset;
    protected abstract function getPluralRuleset() : \_PhpScoper50d83356d739\Doctrine\Inflector\Rules\Ruleset;
}
