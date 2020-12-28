<?php

declare (strict_types=1);
namespace RectorPrefix20201228\Symplify\RuleDocGenerator\Printer\CodeSamplePrinter;

use RectorPrefix20201228\Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use RectorPrefix20201228\Symplify\RuleDocGenerator\Printer\MarkdownCodeWrapper;
final class BadGoodCodeSamplePrinter
{
    /**
     * @var MarkdownCodeWrapper
     */
    private $markdownCodeWrapper;
    public function __construct(\RectorPrefix20201228\Symplify\RuleDocGenerator\Printer\MarkdownCodeWrapper $markdownCodeWrapper)
    {
        $this->markdownCodeWrapper = $markdownCodeWrapper;
    }
    /**
     * @return string[]
     */
    public function print(\RectorPrefix20201228\Symplify\RuleDocGenerator\Contract\CodeSampleInterface $codeSample) : array
    {
        $lines = [];
        $lines[] = $this->markdownCodeWrapper->printPhpCode($codeSample->getBadCode());
        $lines[] = ':x:';
        $lines[] = '<br>';
        $lines[] = $this->markdownCodeWrapper->printPhpCode($codeSample->getGoodCode());
        $lines[] = ':+1:';
        return $lines;
    }
}
