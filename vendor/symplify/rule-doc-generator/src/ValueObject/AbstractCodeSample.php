<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\ValueObject;

use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use RectorPrefix20210317\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
abstract class AbstractCodeSample implements \Symplify\RuleDocGenerator\Contract\CodeSampleInterface
{
    /**
     * @var string
     */
    private $goodCode;
    /**
     * @var string
     */
    private $badCode;
    /**
     * @param string $badCode
     * @param string $goodCode
     */
    public function __construct($badCode, $goodCode)
    {
        $badCode = \trim($badCode);
        $goodCode = \trim($goodCode);
        if ($badCode === '') {
            throw new \RectorPrefix20210317\Symplify\SymplifyKernel\Exception\ShouldNotHappenException('Bad sample good code cannot be empty');
        }
        if ($goodCode === $badCode) {
            $errorMessage = \sprintf('Good and bad code cannot be identical: "%s"', $goodCode);
            throw new \RectorPrefix20210317\Symplify\SymplifyKernel\Exception\ShouldNotHappenException($errorMessage);
        }
        $this->goodCode = $goodCode;
        $this->badCode = $badCode;
    }
    public function getGoodCode() : string
    {
        return $this->goodCode;
    }
    public function getBadCode() : string
    {
        return $this->badCode;
    }
}
