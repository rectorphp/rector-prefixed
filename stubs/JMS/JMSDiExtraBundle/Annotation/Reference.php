<?php

declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\JMS\DiExtraBundle\Annotation;

if (\class_exists('_PhpScoperbd5d0c5f7638\\JMS\\DiExtraBundle\\Annotation\\Reference')) {
    return;
}
abstract class Reference
{
    /**
     * @var string
     */
    public $value;
    /**
     * @var bool
     */
    public $required;
    /**
     * @var bool
     */
    public $strict = \true;
}