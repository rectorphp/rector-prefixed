<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Rules\RuleErrors;

/**
 * @internal Use PHPStan\Rules\RuleErrorBuilder instead.
 */
class RuleError115 implements \_PhpScoperb75b35f52b74\PHPStan\Rules\RuleError, \_PhpScoperb75b35f52b74\PHPStan\Rules\LineRuleError, \_PhpScoperb75b35f52b74\PHPStan\Rules\IdentifierRuleError, \_PhpScoperb75b35f52b74\PHPStan\Rules\MetadataRuleError, \_PhpScoperb75b35f52b74\PHPStan\Rules\NonIgnorableRuleError
{
    /** @var string */
    public $message;
    /** @var int */
    public $line;
    /** @var string */
    public $identifier;
    /** @var mixed[] */
    public $metadata;
    public function getMessage() : string
    {
        return $this->message;
    }
    public function getLine() : int
    {
        return $this->line;
    }
    public function getIdentifier() : string
    {
        return $this->identifier;
    }
    /**
     * @return mixed[]
     */
    public function getMetadata() : array
    {
        return $this->metadata;
    }
}
