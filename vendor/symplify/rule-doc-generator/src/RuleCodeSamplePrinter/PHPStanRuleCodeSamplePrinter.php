<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\RuleCodeSamplePrinter;

use RectorPrefix20210317\Symplify\PackageBuilder\Neon\NeonPrinter;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use Symplify\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface;
use Symplify\RuleDocGenerator\Printer\CodeSamplePrinter\BadGoodCodeSamplePrinter;
use Symplify\RuleDocGenerator\Printer\MarkdownCodeWrapper;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
final class PHPStanRuleCodeSamplePrinter implements \Symplify\RuleDocGenerator\Contract\RuleCodeSamplePrinterInterface
{
    /**
     * @var NeonPrinter
     */
    private $neonPrinter;
    /**
     * @var MarkdownCodeWrapper
     */
    private $markdownCodeWrapper;
    /**
     * @var BadGoodCodeSamplePrinter
     */
    private $badGoodCodeSamplePrinter;
    /**
     * @param \Symplify\PackageBuilder\Neon\NeonPrinter $neonPrinter
     * @param \Symplify\RuleDocGenerator\Printer\MarkdownCodeWrapper $markdownCodeWrapper
     * @param \Symplify\RuleDocGenerator\Printer\CodeSamplePrinter\BadGoodCodeSamplePrinter $badGoodCodeSamplePrinter
     */
    public function __construct($neonPrinter, $markdownCodeWrapper, $badGoodCodeSamplePrinter)
    {
        $this->neonPrinter = $neonPrinter;
        $this->markdownCodeWrapper = $markdownCodeWrapper;
        $this->badGoodCodeSamplePrinter = $badGoodCodeSamplePrinter;
    }
    public function isMatch(string $class) : bool
    {
        /** @noRector */
        return \is_a($class, 'PHPStan\\Rules\\Rule', \true);
    }
    /**
     * @return string[]
     */
    public function print(\Symplify\RuleDocGenerator\Contract\CodeSampleInterface $codeSample, \Symplify\RuleDocGenerator\ValueObject\RuleDefinition $ruleDefinition) : array
    {
        if ($codeSample instanceof \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample) {
            return $this->printConfigurableCodeSample($codeSample, $ruleDefinition);
        }
        return $this->badGoodCodeSamplePrinter->print($codeSample);
    }
    /**
     * @return string[]
     * @param \Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample $configuredCodeSample
     * @param \Symplify\RuleDocGenerator\ValueObject\RuleDefinition $ruleDefinition
     */
    private function printConfigurableCodeSample($configuredCodeSample, $ruleDefinition) : array
    {
        $lines = [];
        $phpstanNeon = ['services' => [['class' => $ruleDefinition->getRuleClass(), 'tags' => ['phpstan.rules.rule'], 'arguments' => $configuredCodeSample->getConfiguration()]]];
        $printedNeon = $this->neonPrinter->printNeon($phpstanNeon);
        $lines[] = $this->markdownCodeWrapper->printYamlCode($printedNeon);
        $lines[] = '↓';
        $newLines = $this->badGoodCodeSamplePrinter->print($configuredCodeSample);
        return \array_merge($lines, $newLines);
    }
}
