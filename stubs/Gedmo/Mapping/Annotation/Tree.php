<?php

declare (strict_types=1);
namespace _PhpScoper88fe6e0ad041\Gedmo\Mapping\Annotation;

if (\class_exists('_PhpScoper88fe6e0ad041\\Gedmo\\Mapping\\Annotation\\Tree')) {
    return;
}
/**
 * @Annotation
 */
class Tree
{
    /** @var string */
    public $type = 'nested';
    /** @var string */
    public $activateLocking = \false;
    /** @var integer */
    public $lockingTimeout = 3;
    /** @var string $identifierMethod */
    public $identifierMethod;
}
