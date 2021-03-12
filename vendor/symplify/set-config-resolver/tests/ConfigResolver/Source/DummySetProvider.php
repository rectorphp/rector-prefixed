<?php

declare (strict_types=1);
namespace RectorPrefix20210312\Symplify\SetConfigResolver\Tests\ConfigResolver\Source;

use RectorPrefix20210312\Symplify\SetConfigResolver\Contract\SetProviderInterface;
use RectorPrefix20210312\Symplify\SetConfigResolver\Provider\AbstractSetProvider;
use RectorPrefix20210312\Symplify\SetConfigResolver\ValueObject\Set;
use RectorPrefix20210312\Symplify\SmartFileSystem\SmartFileInfo;
final class DummySetProvider extends \RectorPrefix20210312\Symplify\SetConfigResolver\Provider\AbstractSetProvider implements \RectorPrefix20210312\Symplify\SetConfigResolver\Contract\SetProviderInterface
{
    /**
     * @var Set[]
     */
    private $sets = [];
    public function __construct()
    {
        $this->sets[] = new \RectorPrefix20210312\Symplify\SetConfigResolver\ValueObject\Set('some_set', new \RectorPrefix20210312\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/../Source/some_set.yaml'));
        $this->sets[] = new \RectorPrefix20210312\Symplify\SetConfigResolver\ValueObject\Set('some_php_set', new \RectorPrefix20210312\Symplify\SmartFileSystem\SmartFileInfo(__DIR__ . '/../Source/some_php_set.php'));
    }
    /**
     * @return Set[]
     */
    public function provide() : array
    {
        return $this->sets;
    }
}
