<?php

declare (strict_types=1);
namespace Rector\Testing\Guard;

use _PhpScoper88fe6e0ad041\Nette\Utils\Strings;
use Rector\Core\Exception\Testing\SuperfluousAfterContentFixtureException;
use Symplify\SmartFileSystem\SmartFileInfo;
final class FixtureGuard
{
    /**
     * @see https://regex101.com/r/xaNeNi/3
     * @var string
     */
    private const BEFORE_AFTER_CONTENT_REGEX = '#^(?<before_content>.*?)\\-\\-\\-\\-\\-\\n(?<after_content>.*?)$#s';
    public function ensureFileInfoHasDifferentBeforeAndAfterContent(\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : void
    {
        $match = \_PhpScoper88fe6e0ad041\Nette\Utils\Strings::match($smartFileInfo->getContents(), self::BEFORE_AFTER_CONTENT_REGEX);
        if ($match === null) {
            return;
        }
        $beforeContent = \trim($match['before_content']);
        $afterContent = \trim($match['after_content']);
        if ($beforeContent !== $afterContent) {
            return;
        }
        $exceptionMessage = \sprintf('The part after "-----" can be removed in "%s" file. It is the same as top half, so no change is required.', $smartFileInfo->getRelativeFilePathFromCwd());
        throw new \Rector\Core\Exception\Testing\SuperfluousAfterContentFixtureException($exceptionMessage);
    }
}
