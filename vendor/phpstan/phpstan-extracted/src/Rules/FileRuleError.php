<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Rules;

interface FileRuleError extends \_PhpScopere8e811afab72\PHPStan\Rules\RuleError
{
    public function getFile() : string;
}
