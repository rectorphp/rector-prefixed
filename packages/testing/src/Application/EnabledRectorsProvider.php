<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\Rector\Testing\Application;

use _PhpScopere8e811afab72\Rector\Core\Configuration\RenamedClassesDataCollector;
use _PhpScopere8e811afab72\Rector\Core\Contract\Rector\RectorInterface;
use _PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector;
use _PhpScopere8e811afab72\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
final class EnabledRectorsProvider
{
    /**
     * @var mixed[][]
     */
    private $enabledRectorsWithConfiguration = [];
    /**
     * @var RenamedClassesDataCollector
     */
    private $renamedClassesDataCollector;
    public function __construct(\_PhpScopere8e811afab72\Rector\Core\Configuration\RenamedClassesDataCollector $renamedClassesDataCollector)
    {
        $this->renamedClassesDataCollector = $renamedClassesDataCollector;
    }
    /**
     * @param mixed[] $configuration
     */
    public function addEnabledRector(string $rector, array $configuration = []) : void
    {
        $this->enabledRectorsWithConfiguration[$rector] = $configuration;
        if (!\_PhpScopere8e811afab72\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return;
        }
        if (!\is_a($rector, \_PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector::class, \true)) {
            return;
        }
        // only in unit tests
        $this->renamedClassesDataCollector->setOldToNewClasses($configuration[\_PhpScopere8e811afab72\Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES] ?? []);
    }
    public function reset() : void
    {
        $this->enabledRectorsWithConfiguration = [];
    }
    public function isConfigured() : bool
    {
        return (bool) $this->enabledRectorsWithConfiguration;
    }
    /**
     * @return mixed[][]
     */
    public function getEnabledRectors() : array
    {
        return $this->enabledRectorsWithConfiguration;
    }
    /**
     * @return mixed[]
     */
    public function getRectorConfiguration(\_PhpScopere8e811afab72\Rector\Core\Contract\Rector\RectorInterface $rector) : array
    {
        foreach ($this->enabledRectorsWithConfiguration as $rectorClass => $configuration) {
            if (!\is_a($rector, $rectorClass, \true)) {
                continue;
            }
            return $configuration;
        }
        return [];
    }
}
