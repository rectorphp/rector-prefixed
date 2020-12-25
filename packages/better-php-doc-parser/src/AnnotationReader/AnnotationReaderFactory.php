<?php

declare (strict_types=1);
namespace Rector\BetterPhpDocParser\AnnotationReader;

use _PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\AnnotationReader;
use _PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\AnnotationRegistry;
use _PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\DocParser;
use _PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\Reader;
use Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader;
use Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser;
final class AnnotationReaderFactory
{
    /**
     * @var string[]
     */
    private const IGNORED_NAMES = [
        '_PhpScoperfce0de0de1ce\\ORM\\GeneratedValue',
        'GeneratedValue',
        '_PhpScoperfce0de0de1ce\\ORM\\InheritanceType',
        'InheritanceType',
        '_PhpScoperfce0de0de1ce\\ORM\\OrderBy',
        'OrderBy',
        '_PhpScoperfce0de0de1ce\\ORM\\DiscriminatorMap',
        'DiscriminatorMap',
        '_PhpScoperfce0de0de1ce\\ORM\\UniqueEntity',
        'UniqueEntity',
        '_PhpScoperfce0de0de1ce\\Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        '_PhpScoperfce0de0de1ce\\Gedmo\\Slug',
        'Slug',
        '_PhpScoperfce0de0de1ce\\Gedmo\\SoftDeleteable',
        'SoftDeleteable',
        '_PhpScoperfce0de0de1ce\\Gedmo\\Blameable',
        'Blameable',
        '_PhpScoperfce0de0de1ce\\Gedmo\\Versioned',
        'Versioned',
        // nette @inject dummy annotation
        'inject',
    ];
    public function create() : \_PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\Reader
    {
        \_PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
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
    private function createAnnotationReader() : \_PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\Reader
    {
        // these 2 classes are generated by "bin/rector sync-annotation-parser" command
        if (\class_exists(\Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader::class) && \class_exists(\Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser::class)) {
            $constantPreservingDocParser = new \Rector\DoctrineAnnotationGenerated\ConstantPreservingDocParser();
            return new \Rector\DoctrineAnnotationGenerated\ConstantPreservingAnnotationReader($constantPreservingDocParser);
        }
        // fallback for testing incompatibilities
        return new \_PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\AnnotationReader(new \_PhpScoperfce0de0de1ce\Doctrine\Common\Annotations\DocParser());
    }
}
