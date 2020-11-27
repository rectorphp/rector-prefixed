<?php

// mirrors: https://github.com/Atlantic18/DoctrineExtensions/blob/v2.4.x/lib/Gedmo/Mapping/Annotation/SoftDeleteable.php
declare (strict_types=1);
namespace _PhpScoperbd5d0c5f7638\Gedmo\Mapping\Annotation;

if (\class_exists('_PhpScoperbd5d0c5f7638\\Gedmo\\Mapping\\Annotation\\SoftDeleteable')) {
    return;
}
/**
 * @Annotation
 */
class SoftDeleteable
{
    /**
     * @var string
     */
    public $fieldName = 'deletedAt';
    /**
     * @var bool
     */
    public $timeAware = \false;
    /**
     * @var bool
     */
    public $hardDelete = \true;
}
