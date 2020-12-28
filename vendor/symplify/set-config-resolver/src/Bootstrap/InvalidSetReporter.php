<?php

declare (strict_types=1);
namespace RectorPrefix20201228\Symplify\SetConfigResolver\Bootstrap;

use RectorPrefix20201228\Nette\Utils\ObjectHelpers;
use RectorPrefix20201228\Symfony\Component\Console\Style\SymfonyStyle;
use RectorPrefix20201228\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use RectorPrefix20201228\Symplify\SetConfigResolver\Exception\SetNotFoundException;
final class InvalidSetReporter
{
    /**
     * @var SymfonyStyle
     */
    private $symfonyStyle;
    public function __construct()
    {
        $symfonyStyleFactory = new \RectorPrefix20201228\Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory();
        $this->symfonyStyle = $symfonyStyleFactory->create();
    }
    public function report(\RectorPrefix20201228\Symplify\SetConfigResolver\Exception\SetNotFoundException $setNotFoundException) : void
    {
        $message = $setNotFoundException->getMessage();
        $suggestedSet = \RectorPrefix20201228\Nette\Utils\ObjectHelpers::getSuggestion($setNotFoundException->getAvailableSetNames(), $setNotFoundException->getSetName());
        if ($suggestedSet !== null) {
            $message .= \sprintf('. Did you mean "%s"?', $suggestedSet);
            $this->symfonyStyle->error($message);
        } elseif ($setNotFoundException->getAvailableSetNames() !== []) {
            $this->symfonyStyle->error($message);
            $this->symfonyStyle->note('Pick one of:');
            $this->symfonyStyle->listing($setNotFoundException->getAvailableSetNames());
        }
    }
}
