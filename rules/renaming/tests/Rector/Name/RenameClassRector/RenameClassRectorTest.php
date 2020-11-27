<?php

declare (strict_types=1);
namespace Rector\Renaming\Tests\Rector\Name\RenameClassRector;

use Iterator;
use _PhpScoperbd5d0c5f7638\Manual\Twig\TwigFilter;
use _PhpScoperbd5d0c5f7638\Manual_Twig_Filter;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Fixture\DuplicatedClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\AbstractManualExtension;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClass;
use Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClassWithTypo;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
final class RenameClassRectorTest extends \Rector\Testing\PHPUnit\AbstractRectorTestCase
{
    /**
     * @dataProvider provideData()
     */
    public function test(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->doTestFileInfo($fileInfo);
    }
    public function provideData() : \Iterator
    {
        return $this->yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }
    /**
     * @see https://github.com/rectorphp/rector/issues/1438
     */
    public function testClassNameDuplication() : void
    {
        $fixtureFileInfo = new \Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/FixtureDuplication/skip_duplicated_class.php.inc');
        $this->doTestFileInfo($fixtureFileInfo);
    }
    /**
     * @return array<string, mixed[]>
     */
    protected function getRectorsWithConfiguration() : array
    {
        return [\Rector\Renaming\Rector\Name\RenameClassRector::class => [\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
            'FqnizeNamespaced' => '_PhpScoperbd5d0c5f7638\\Abc\\FqnizeNamespaced',
            \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClass::class => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClass::class,
            \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\OldClassWithTypo::class => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\NewClassWithoutTypo::class,
            'DateTime' => 'DateTimeInterface',
            'Countable' => 'stdClass',
            \_PhpScoperbd5d0c5f7638\Manual_Twig_Filter::class => \_PhpScoperbd5d0c5f7638\Manual\Twig\TwigFilter::class,
            'Twig_AbstractManualExtension' => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Source\AbstractManualExtension::class,
            'Twig_Extension_Sandbox' => '_PhpScoperbd5d0c5f7638\\Twig\\Extension\\SandboxExtension',
            // Renaming class itself and its namespace
            '_PhpScoperbd5d0c5f7638\\MyNamespace\\MyClass' => '_PhpScoperbd5d0c5f7638\\MyNewNamespace\\MyNewClass',
            '_PhpScoperbd5d0c5f7638\\MyNamespace\\MyTrait' => '_PhpScoperbd5d0c5f7638\\MyNewNamespace\\MyNewTrait',
            '_PhpScoperbd5d0c5f7638\\MyNamespace\\MyInterface' => '_PhpScoperbd5d0c5f7638\\MyNewNamespace\\MyNewInterface',
            'MyOldClass' => '_PhpScoperbd5d0c5f7638\\MyNamespace\\MyNewClass',
            'AnotherMyOldClass' => 'AnotherMyNewClass',
            '_PhpScoperbd5d0c5f7638\\MyNamespace\\AnotherMyClass' => 'MyNewClassWithoutNamespace',
            // test duplicated class - @see https://github.com/rectorphp/rector/issues/1438
            'Rector\\Renaming\\Tests\\Rector\\Name\\RenameClassRector\\Fixture\\SingularClass' => \Rector\Renaming\Tests\Rector\Name\RenameClassRector\Fixture\DuplicatedClass::class,
        ]]];
    }
}
