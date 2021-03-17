<?php

declare (strict_types=1);
namespace Symplify\RuleDocGenerator\ValueObject\CodeSample;

use Rector\Core\Exception\Configuration\InvalidConfigurationException;
use Symplify\RuleDocGenerator\Contract\CodeSampleInterface;
use Symplify\RuleDocGenerator\ValueObject\AbstractCodeSample;
final class ConfiguredCodeSample extends \Symplify\RuleDocGenerator\ValueObject\AbstractCodeSample implements \Symplify\RuleDocGenerator\Contract\CodeSampleInterface
{
    /**
     * @var array<string, mixed>
     */
    private $configuration = [];
    /**
     * @param array<string, mixed> $configuration
     * @param string $badCode
     * @param string $goodCode
     */
    public function __construct($badCode, $goodCode, $configuration)
    {
        if ($configuration === []) {
            $message = \sprintf('Configuration cannot be empty. Look for "%s"', $badCode);
            throw new \Rector\Core\Exception\Configuration\InvalidConfigurationException($message);
        }
        $this->configuration = $configuration;
        parent::__construct($badCode, $goodCode);
    }
    /**
     * @return array<string, mixed>
     */
    public function getConfiguration() : array
    {
        return $this->configuration;
    }
}
