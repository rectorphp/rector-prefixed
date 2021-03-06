<?php

declare (strict_types=1);
namespace RectorPrefix20210306\Symplify\EasyTesting\FixtureSplitter;

use RectorPrefix20210306\Nette\Utils\Strings;
use RectorPrefix20210306\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent;
use RectorPrefix20210306\Symplify\EasyTesting\ValueObject\SplitLine;
use RectorPrefix20210306\Symplify\SmartFileSystem\SmartFileInfo;
use RectorPrefix20210306\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class TrioFixtureSplitter
{
    public function splitFileInfo(\RectorPrefix20210306\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : \RectorPrefix20210306\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent
    {
        $parts = \RectorPrefix20210306\Nette\Utils\Strings::split($smartFileInfo->getContents(), \RectorPrefix20210306\Symplify\EasyTesting\ValueObject\SplitLine::SPLIT_LINE_REGEX);
        $this->ensureHasThreeParts($parts, $smartFileInfo);
        return new \RectorPrefix20210306\Symplify\EasyTesting\ValueObject\FixtureSplit\TrioContent($parts[0], $parts[1], $parts[2]);
    }
    /**
     * @param mixed[] $parts
     */
    private function ensureHasThreeParts(array $parts, \RectorPrefix20210306\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        if (\count($parts) === 3) {
            return;
        }
        $message = \sprintf('The fixture "%s" should have 3 parts. %d found', $smartFileInfo->getRelativeFilePathFromCwd(), \count($parts));
        throw new \RectorPrefix20210306\Symplify\SymplifyKernel\Exception\ShouldNotHappenException($message);
    }
}
