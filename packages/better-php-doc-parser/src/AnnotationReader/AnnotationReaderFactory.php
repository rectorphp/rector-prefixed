<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\AnnotationReader;

use RectorPrefix20210111\Doctrine\Common\Annotations\AnnotationReader;
use RectorPrefix20210111\Doctrine\Common\Annotations\AnnotationRegistry;
use RectorPrefix20210111\Doctrine\Common\Annotations\DocParser;
use RectorPrefix20210111\Doctrine\Common\Annotations\Reader;
use Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader;
use Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser;
final class AnnotationReaderFactory
{
    /**
     * @var string[]
     */
    private const IGNORED_NAMES = [
        'ORM\\GeneratedValue',
        'GeneratedValue',
        'ORM\\InheritanceType',
        'InheritanceType',
        'ORM\\OrderBy',
        'OrderBy',
        'ORM\\DiscriminatorMap',
        'DiscriminatorMap',
        'ORM\\UniqueEntity',
        'UniqueEntity',
        'Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        'Gedmo\\Slug',
        'Slug',
        'Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        'Gedmo\\Blameable',
        'Blameable',
        'Gedmo\\Versioned',
        'Versioned',
        // nette @inject dummy annotation
        'inject',
    ];
    public function create() : \RectorPrefix20210111\Doctrine\Common\Annotations\Reader
    {
        \RectorPrefix20210111\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
        // generated
        $annotationReader = $this->createAnnotationReader();
        // without this the reader will try to resolve them and fails with an exception
        // don't forget to add it to "stubs/Doctrine/Empty" directory, because the class needs to exists
        // and run "composer dump-autoload", because the directory is loaded by classmap
        foreach (self::IGNORED_NAMES as $ignoredName) {
            $annotationReader::addGlobalIgnoredName($ignoredName);
        }
        // warning: nested tags must be parse-able, e.g. @ORM\Table must include @ORM\UniqueConstraint!
        return $annotationReader;
    }
    /**
     * @return AnnotationReader|ConstantPreservingAnnotationReader
     */
    private function createAnnotationReader() : \RectorPrefix20210111\Doctrine\Common\Annotations\Reader
    {
        // these 2 classes are generated by "bin/rector sync-annotation-parser" command
        if (\class_exists(\Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader::class) && \class_exists(\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser::class)) {
            $constantPreservingDocParser = new \Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser();
            return new \Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader($constantPreservingDocParser);
        }
        // fallback for testing incompatibilities
        return new \RectorPrefix20210111\Doctrine\Common\Annotations\AnnotationReader(new \RectorPrefix20210111\Doctrine\Common\Annotations\DocParser());
    }
}
