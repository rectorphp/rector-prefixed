<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\Symplify\SetConfigResolver\ValueObject;

use _PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo;
final class Set
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var SmartFileInfo
     */
    private $setFileInfo;
    public function __construct(string $name, \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo $setFileInfo)
    {
        $this->name = $name;
        $this->setFileInfo = $setFileInfo;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getSetFileInfo() : \_PhpScoperb75b35f52b74\Symplify\SmartFileSystem\SmartFileInfo
    {
        return $this->setFileInfo;
    }
    public function getSetPathname() : string
    {
        return $this->setFileInfo->getPathname();
    }
}
