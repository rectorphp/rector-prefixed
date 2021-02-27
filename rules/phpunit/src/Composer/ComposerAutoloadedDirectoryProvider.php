<?php

declare (strict_types=1);
namespace Rector\PHPUnit\Composer;

use RectorPrefix20210227\Nette\Utils\Arrays;
use RectorPrefix20210227\Nette\Utils\Json;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use RectorPrefix20210227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use RectorPrefix20210227\Symplify\SmartFileSystem\SmartFileSystem;
final class ComposerAutoloadedDirectoryProvider
{
    /**
     * @var string[]
     */
    private const AUTOLOAD_SECTIONS = [\RectorPrefix20210227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD, \RectorPrefix20210227\Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection::AUTOLOAD_DEV];
    /**
     * @var string
     */
    private $composerFilePath;
    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;
    public function __construct(\RectorPrefix20210227\Symplify\SmartFileSystem\SmartFileSystem $smartFileSystem)
    {
        $this->composerFilePath = \getcwd() . '/composer.json';
        $this->smartFileSystem = $smartFileSystem;
    }
    /**
     * @return string[]|mixed[]
     */
    public function provide() : array
    {
        if (\Rector\Testing\PHPUnit\StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return [\getcwd() . '/src', \getcwd() . '/tests', \getcwd() . '/packages', \getcwd() . '/rules'];
        }
        $composerJson = $this->loadComposerJsonArray();
        $autoloadDirectories = [];
        foreach (self::AUTOLOAD_SECTIONS as $autoloadSection) {
            if (!isset($composerJson[$autoloadSection])) {
                continue;
            }
            $sectionDirectories = $this->collectDirectoriesFromAutoload($composerJson[$autoloadSection]);
            $autoloadDirectories[] = $sectionDirectories;
        }
        return \RectorPrefix20210227\Nette\Utils\Arrays::flatten($autoloadDirectories);
    }
    /**
     * @return mixed[]
     */
    private function loadComposerJsonArray() : array
    {
        if (!\file_exists($this->composerFilePath)) {
            return [];
        }
        $composerFileContent = $this->smartFileSystem->readFile($this->composerFilePath);
        return \RectorPrefix20210227\Nette\Utils\Json::decode($composerFileContent, \RectorPrefix20210227\Nette\Utils\Json::FORCE_ARRAY);
    }
    /**
     * @param string[] $composerJsonAutoload
     * @return string[]
     */
    private function collectDirectoriesFromAutoload(array $composerJsonAutoload) : array
    {
        $autoloadDirectories = [];
        if (isset($composerJsonAutoload['psr-4'])) {
            /** @var string[] $psr4 */
            $psr4 = $composerJsonAutoload['psr-4'];
            $autoloadDirectories = \array_merge($autoloadDirectories, $psr4);
        }
        if (isset($composerJsonAutoload['classmap'])) {
            /** @var string[] $classmap */
            $classmap = $composerJsonAutoload['classmap'];
            foreach ($classmap as $fileOrDirectory) {
                $fileOrDirectory = \getcwd() . '/' . $fileOrDirectory;
                // skip file, we look only for directories
                if (\file_exists($fileOrDirectory)) {
                    continue;
                }
                $autoloadDirectories[] = $fileOrDirectory;
            }
        }
        return \array_values($autoloadDirectories);
    }
}
