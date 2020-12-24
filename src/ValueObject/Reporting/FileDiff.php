<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Core\ValueObject\Reporting;

use _PhpScopere8e811afab72\Rector\ChangesReporting\ValueObject\RectorWithFileAndLineChange;
use _PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo;
final class FileDiff
{
    /**
     * @var string
     */
    private $diff;
    /**
     * @var string
     */
    private $diffConsoleFormatted;
    /**
     * @var RectorWithFileAndLineChange[]
     */
    private $rectorWithFileAndLineChanges = [];
    /**
     * @var SmartFileInfo
     */
    private $smartFileInfo;
    /**
     * @param RectorWithFileAndLineChange[] $rectorWithFileAndLineChanges
     */
    public function __construct(\_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo, string $diff, string $diffConsoleFormatted, array $rectorWithFileAndLineChanges = [])
    {
        $this->smartFileInfo = $smartFileInfo;
        $this->diff = $diff;
        $this->rectorWithFileAndLineChanges = $rectorWithFileAndLineChanges;
        $this->diffConsoleFormatted = $diffConsoleFormatted;
    }
    public function getDiff() : string
    {
        return $this->diff;
    }
    public function getDiffConsoleFormatted() : string
    {
        return $this->diffConsoleFormatted;
    }
    public function getRelativeFilePath() : string
    {
        return $this->smartFileInfo->getRelativeFilePath();
    }
    public function getFileInfo() : \_PhpScopere8e811afab72\Symplify\SmartFileSystem\SmartFileInfo
    {
        return $this->smartFileInfo;
    }
    /**
     * @return RectorWithFileAndLineChange[]
     */
    public function getRectorChanges() : array
    {
        return $this->rectorWithFileAndLineChanges;
    }
    /**
     * @return string[]
     */
    public function getRectorClasses() : array
    {
        $rectorClasses = [];
        foreach ($this->rectorWithFileAndLineChanges as $rectorWithFileAndLineChange) {
            $rectorClasses[] = $rectorWithFileAndLineChange->getRectorClass();
        }
        $rectorClasses = \array_unique($rectorClasses);
        \sort($rectorClasses);
        return $rectorClasses;
    }
}
