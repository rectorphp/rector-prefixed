<?php

declare (strict_types=1);
namespace Rector\Testing\PHPUnit;

use Iterator;
use RectorPrefix2020DecSat\Nette\Utils\Strings;
use PHPStan\Analyser\NodeScopeResolver;
use RectorPrefix2020DecSat\PHPUnit\Framework\ExpectationFailedException;
use Rector\Core\Application\FileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesProcessor;
use Rector\Core\Bootstrap\RectorConfigsResolver;
use Rector\Core\Configuration\Configuration;
use Rector\Core\Configuration\Option;
use Rector\Core\Contract\Rector\PhpRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\HttpKernel\RectorKernel;
use Rector\Core\NonPhpFile\NonPhpFileProcessor;
use Rector\Core\Stubs\StubLoader;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Core\ValueObject\StaticNonPhpFileSuffixes;
use Rector\Naming\Tests\Rector\Class_\RenamePropertyToMatchTypeRector\Source\ContainerInterface;
use Rector\Testing\Application\EnabledRectorsProvider;
use Rector\Testing\Contract\RunnableInterface;
use Rector\Testing\Finder\RectorsFinder;
use Rector\Testing\Guard\FixtureGuard;
use Rector\Testing\PhpConfigPrinter\PhpConfigPrinterFactory;
use Rector\Testing\PHPUnit\Behavior\MovingFilesTrait;
use Rector\Testing\PHPUnit\Behavior\RunnableTestTrait;
use Rector\Testing\ValueObject\InputFilePathWithExpectedFile;
use RectorPrefix2020DecSat\Symfony\Component\Console\Output\OutputInterface;
use RectorPrefix2020DecSat\Symfony\Component\Console\Style\SymfonyStyle;
use RectorPrefix2020DecSat\Symfony\Component\DependencyInjection\Container;
use RectorPrefix2020DecSat\Symfony\Component\HttpKernel\KernelInterface;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\DataProvider\StaticFixtureUpdater;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;
abstract class AbstractRectorTestCase extends \Symplify\PackageBuilder\Testing\AbstractKernelTestCase
{
    use MovingFilesTrait;
    use RunnableTestTrait;
    /**
     * @var int
     */
    private const PHP_VERSION_UNDEFINED = 0;
    /**
     * @var FileProcessor
     */
    protected $fileProcessor;
    /**
     * @var SmartFileSystem
     */
    protected $smartFileSystem;
    /**
     * @var NonPhpFileProcessor
     */
    protected $nonPhpFileProcessor;
    /**
     * @var ParameterProvider
     */
    protected $parameterProvider;
    /**
     * @var RunnableRectorFactory
     */
    protected $runnableRectorFactory;
    /**
     * @var NodeScopeResolver
     */
    protected $nodeScopeResolver;
    /**
     * @var FixtureGuard
     */
    protected $fixtureGuard;
    /**
     * @var RemovedAndAddedFilesCollector
     */
    protected $removedAndAddedFilesCollector;
    /**
     * @var SmartFileInfo
     */
    protected $originalTempFileInfo;
    /**
     * @var Container|ContainerInterface|null
     */
    protected static $allRectorContainer;
    /**
     * @var bool
     */
    private $autoloadTestFixture = \true;
    /**
     * @var mixed[]
     */
    private $oldParameterValues = [];
    protected function setUp() : void
    {
        $this->runnableRectorFactory = new \Rector\Testing\PHPUnit\RunnableRectorFactory();
        $this->smartFileSystem = new \Symplify\SmartFileSystem\SmartFileSystem();
        $this->fixtureGuard = new \Rector\Testing\Guard\FixtureGuard();
        if ($this->provideConfigFileInfo() !== null) {
            $configFileInfos = $this->resolveConfigs($this->provideConfigFileInfo());
            $this->bootKernelWithConfigInfos(\Rector\Core\HttpKernel\RectorKernel::class, $configFileInfos);
            $enabledRectorsProvider = static::$container->get(\Rector\Testing\Application\EnabledRectorsProvider::class);
            $enabledRectorsProvider->reset();
        } else {
            // prepare container with all rectors
            // cache only rector tests - defined in phpunit.xml
            if (\defined('RECTOR_REPOSITORY')) {
                $this->createRectorRepositoryContainer();
            } else {
                // boot core config, where 3rd party services might be loaded
                $rootRectorPhp = \getcwd() . '/rector.php';
                $configs = [];
                if (\file_exists($rootRectorPhp)) {
                    $configs[] = $rootRectorPhp;
                }
                // 3rd party
                $configs[] = $this->getConfigFor3rdPartyTest();
                $this->bootKernelWithConfigs(\Rector\Core\HttpKernel\RectorKernel::class, $configs);
            }
            $enabledRectorsProvider = $this->getService(\Rector\Testing\Application\EnabledRectorsProvider::class);
            $enabledRectorsProvider->reset();
            $this->configureEnabledRectors($enabledRectorsProvider);
        }
        // load stubs
        $stubLoader = static::$container->get(\Rector\Core\Stubs\StubLoader::class);
        $stubLoader->loadStubs();
        // disable any output
        $symfonyStyle = static::$container->get(\RectorPrefix2020DecSat\Symfony\Component\Console\Style\SymfonyStyle::class);
        $symfonyStyle->setVerbosity(\RectorPrefix2020DecSat\Symfony\Component\Console\Output\OutputInterface::VERBOSITY_QUIET);
        $this->fileProcessor = static::$container->get(\Rector\Core\Application\FileProcessor::class);
        $this->nonPhpFileProcessor = static::$container->get(\Rector\Core\NonPhpFile\NonPhpFileProcessor::class);
        $this->parameterProvider = static::$container->get(\Symplify\PackageBuilder\Parameter\ParameterProvider::class);
        $this->removedAndAddedFilesCollector = $this->getService(\Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector::class);
        $this->removedAndAddedFilesCollector->reset();
        // needed for PHPStan, because the analyzed file is just create in /temp
        $this->nodeScopeResolver = static::$container->get(\PHPStan\Analyser\NodeScopeResolver::class);
        $this->configurePhpVersionFeatures();
        // so the files are removed and added
        $configuration = static::$container->get(\Rector\Core\Configuration\Configuration::class);
        $configuration->setIsDryRun(\false);
        $this->oldParameterValues = [];
    }
    protected function tearDown() : void
    {
        $this->restoreOldParameterValues();
        // restore PHP version if changed
        if ($this->getPhpVersion() !== self::PHP_VERSION_UNDEFINED) {
            $this->setParameter(\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, \Rector\Core\ValueObject\PhpVersion::PHP_10);
        }
    }
    protected function getRectorClass() : string
    {
        // can be implemented
        return '';
    }
    protected function provideConfigFileInfo() : ?\Symplify\SmartFileSystem\SmartFileInfo
    {
        // can be implemented
        return null;
    }
    /**
     * @deprecated Use config instead, just to narrow 2 ways to add configured config to just 1. Now
     * with PHP its easy pick.
     *
     * @return mixed[]
     */
    protected function getRectorsWithConfiguration() : array
    {
        // can be implemented, has the highest priority
        return [];
    }
    /**
     * @return mixed[]
     */
    protected function getCurrentTestRectorClassesWithConfiguration() : array
    {
        if ($this->getRectorsWithConfiguration() !== []) {
            foreach (\array_keys($this->getRectorsWithConfiguration()) as $rectorClass) {
                $this->ensureRectorClassIsValid($rectorClass, 'getRectorsWithConfiguration');
            }
            return $this->getRectorsWithConfiguration();
        }
        $rectorClass = $this->getRectorClass();
        $this->ensureRectorClassIsValid($rectorClass, 'getRectorClass');
        return [$rectorClass => null];
    }
    protected function yieldFilesFromDirectory(string $directory, string $suffix = '*.php.inc') : \Iterator
    {
        return \Symplify\EasyTesting\DataProvider\StaticFixtureFinder::yieldDirectory($directory, $suffix);
    }
    /**
     * @param mixed $value
     */
    protected function setParameter(string $name, $value) : void
    {
        $parameterProvider = $this->getService(\Symplify\PackageBuilder\Parameter\ParameterProvider::class);
        if ($name !== \Rector\Core\Configuration\Option::PHP_VERSION_FEATURES) {
            $oldParameterValue = $parameterProvider->provideParameter($name);
            $this->oldParameterValues[$name] = $oldParameterValue;
        }
        $parameterProvider->changeParameter($name, $value);
    }
    /**
     * @deprecated Will be supported in Symplify 9
     * @param SmartFileInfo[] $configFileInfos
     */
    protected function bootKernelWithConfigInfos(string $class, array $configFileInfos) : \RectorPrefix2020DecSat\Symfony\Component\HttpKernel\KernelInterface
    {
        $configFiles = [];
        foreach ($configFileInfos as $configFileInfo) {
            $configFiles[] = $configFileInfo->getRealPath();
        }
        return $this->bootKernelWithConfigs($class, $configFiles);
    }
    protected function getPhpVersion() : int
    {
        // to be implemented
        return 0;
    }
    protected function assertFileMissing(string $temporaryFilePath) : void
    {
        // PHPUnit 9.0 ready
        if (\method_exists($this, 'assertFileDoesNotExist')) {
            $this->assertFileDoesNotExist($temporaryFilePath);
        } else {
            // PHPUnit 8.0 ready
            $this->assertFileNotExists($temporaryFilePath);
        }
    }
    protected function doTestFileInfoWithoutAutoload(\Symplify\SmartFileSystem\SmartFileInfo $fileInfo) : void
    {
        $this->autoloadTestFixture = \false;
        $this->doTestFileInfo($fileInfo);
        $this->autoloadTestFixture = \true;
    }
    /**
     * @param InputFilePathWithExpectedFile[] $extraFiles
     */
    protected function doTestFileInfo(\Symplify\SmartFileSystem\SmartFileInfo $fixtureFileInfo, array $extraFiles = []) : void
    {
        $this->fixtureGuard->ensureFileInfoHasDifferentBeforeAndAfterContent($fixtureFileInfo);
        $inputFileInfoAndExpectedFileInfo = \Symplify\EasyTesting\StaticFixtureSplitter::splitFileInfoToLocalInputAndExpectedFileInfos($fixtureFileInfo, $this->autoloadTestFixture);
        $inputFileInfo = $inputFileInfoAndExpectedFileInfo->getInputFileInfo();
        $this->nodeScopeResolver->setAnalysedFiles([$inputFileInfo->getRealPath()]);
        $expectedFileInfo = $inputFileInfoAndExpectedFileInfo->getExpectedFileInfo();
        $this->doTestFileMatchesExpectedContent($inputFileInfo, $expectedFileInfo, $fixtureFileInfo, $extraFiles);
        $this->originalTempFileInfo = $inputFileInfo;
        // runnable?
        if (!\file_exists($inputFileInfo->getPathname())) {
            return;
        }
        if (!\RectorPrefix2020DecSat\Nette\Utils\Strings::contains($inputFileInfo->getContents(), \Rector\Testing\Contract\RunnableInterface::class)) {
            return;
        }
        $this->assertOriginalAndFixedFileResultEquals($inputFileInfo, $expectedFileInfo);
    }
    protected function getTempPath() : string
    {
        return \Symplify\EasyTesting\StaticFixtureSplitter::getTemporaryPath();
    }
    protected function doTestExtraFile(string $expectedExtraFileName, string $expectedExtraContentFilePath) : void
    {
        $temporaryPath = \Symplify\EasyTesting\StaticFixtureSplitter::getTemporaryPath();
        $expectedFilePath = $temporaryPath . '/' . $expectedExtraFileName;
        $this->assertFileExists($expectedFilePath);
        $this->assertFileEquals($expectedExtraContentFilePath, $expectedFilePath);
    }
    protected function getFixtureTempDirectory() : string
    {
        return \sys_get_temp_dir() . '/_temp_fixture_easy_testing';
    }
    /**
     * @return SmartFileInfo[]
     */
    private function resolveConfigs(\Symplify\SmartFileSystem\SmartFileInfo $configFileInfo) : array
    {
        $configFileInfos = [$configFileInfo];
        $rectorConfigsResolver = new \Rector\Core\Bootstrap\RectorConfigsResolver();
        $setFileInfos = $rectorConfigsResolver->resolveSetFileInfosFromConfigFileInfos($configFileInfos);
        return \array_merge($configFileInfos, $setFileInfos);
    }
    private function createRectorRepositoryContainer() : void
    {
        if (self::$allRectorContainer === null) {
            $this->createContainerWithAllRectors();
            self::$allRectorContainer = self::$container;
            return;
        }
        // load from cache
        self::$container = self::$allRectorContainer;
    }
    private function getConfigFor3rdPartyTest() : string
    {
        $rectorClassesWithConfiguration = $this->getCurrentTestRectorClassesWithConfiguration();
        $filePath = \sys_get_temp_dir() . '/rector_temp_tests/current_test.php';
        $this->createPhpConfigFileAndDumpToPath($rectorClassesWithConfiguration, $filePath);
        return $filePath;
    }
    private function configureEnabledRectors(\Rector\Testing\Application\EnabledRectorsProvider $enabledRectorsProvider) : void
    {
        foreach ($this->getCurrentTestRectorClassesWithConfiguration() as $rectorClass => $configuration) {
            $enabledRectorsProvider->addEnabledRector($rectorClass, (array) $configuration);
        }
    }
    private function configurePhpVersionFeatures() : void
    {
        if ($this->getPhpVersion() === self::PHP_VERSION_UNDEFINED) {
            return;
        }
        $this->setParameter(\Rector\Core\Configuration\Option::PHP_VERSION_FEATURES, $this->getPhpVersion());
    }
    private function restoreOldParameterValues() : void
    {
        if ($this->oldParameterValues === []) {
            return;
        }
        $parameterProvider = $this->getService(\Symplify\PackageBuilder\Parameter\ParameterProvider::class);
        foreach ($this->oldParameterValues as $name => $oldParameterValue) {
            $parameterProvider->changeParameter($name, $oldParameterValue);
        }
    }
    private function ensureRectorClassIsValid(string $rectorClass, string $methodName) : void
    {
        if (\is_a($rectorClass, \Rector\Core\Contract\Rector\PhpRectorInterface::class, \true)) {
            return;
        }
        throw new \Rector\Core\Exception\ShouldNotHappenException(\sprintf('Class "%s" in "%s()" method must be type of "%s"', $rectorClass, $methodName, \Rector\Core\Contract\Rector\PhpRectorInterface::class));
    }
    /**
     * @param InputFilePathWithExpectedFile[] $extraFiles
     */
    private function doTestFileMatchesExpectedContent(\Symplify\SmartFileSystem\SmartFileInfo $originalFileInfo, \Symplify\SmartFileSystem\SmartFileInfo $expectedFileInfo, \Symplify\SmartFileSystem\SmartFileInfo $fixtureFileInfo, array $extraFiles = []) : void
    {
        $this->setParameter(\Rector\Core\Configuration\Option::SOURCE, [$originalFileInfo->getRealPath()]);
        if (!\RectorPrefix2020DecSat\Nette\Utils\Strings::endsWith($originalFileInfo->getFilename(), '.blade.php') && \in_array($originalFileInfo->getSuffix(), ['php', 'phpt'], \true)) {
            if ($extraFiles === []) {
                $this->fileProcessor->parseFileInfoToLocalCache($originalFileInfo);
                $this->fileProcessor->refactor($originalFileInfo);
                $this->fileProcessor->postFileRefactor($originalFileInfo);
            } else {
                $fileInfosToProcess = [$originalFileInfo];
                foreach ($extraFiles as $extraFile) {
                    $fileInfosToProcess[] = $extraFile->getInputFileInfo();
                }
                // life-cycle trio :)
                foreach ($fileInfosToProcess as $fileInfoToProcess) {
                    $this->fileProcessor->parseFileInfoToLocalCache($fileInfoToProcess);
                }
                foreach ($fileInfosToProcess as $fileInfoToProcess) {
                    $this->fileProcessor->refactor($fileInfoToProcess);
                }
                foreach ($fileInfosToProcess as $fileInfoToProcess) {
                    $this->fileProcessor->postFileRefactor($fileInfoToProcess);
                }
            }
            // mimic post-rectors
            $changedContent = $this->fileProcessor->printToString($originalFileInfo);
            $removedAndAddedFilesProcessor = $this->getService(\Rector\Core\Application\FileSystem\RemovedAndAddedFilesProcessor::class);
            $removedAndAddedFilesProcessor->run();
        } elseif (\RectorPrefix2020DecSat\Nette\Utils\Strings::match($originalFileInfo->getFilename(), \Rector\Core\ValueObject\StaticNonPhpFileSuffixes::getSuffixRegexPattern())) {
            $changedContent = $this->nonPhpFileProcessor->processFileInfo($originalFileInfo);
        } else {
            $message = \sprintf('Suffix "%s" is not supported yet', $originalFileInfo->getSuffix());
            throw new \Rector\Core\Exception\ShouldNotHappenException($message);
        }
        $relativeFilePathFromCwd = $fixtureFileInfo->getRelativeFilePathFromCwd();
        try {
            $this->assertStringEqualsFile($expectedFileInfo->getRealPath(), $changedContent, $relativeFilePathFromCwd);
        } catch (\RectorPrefix2020DecSat\PHPUnit\Framework\ExpectationFailedException $expectationFailedException) {
            \Symplify\EasyTesting\DataProvider\StaticFixtureUpdater::updateFixtureContent($originalFileInfo, $changedContent, $fixtureFileInfo);
            $contents = $expectedFileInfo->getContents();
            // make sure we don't get a diff in which every line is different (because of differences in EOL)
            $contents = $this->normalizeNewlines($contents);
            // if not exact match, check the regex version (useful for generated hashes/uuids in the code)
            $this->assertStringMatchesFormat($contents, $changedContent, $relativeFilePathFromCwd);
        }
    }
    private function createContainerWithAllRectors() : void
    {
        $rectorsFinder = new \Rector\Testing\Finder\RectorsFinder();
        $coreRectorClasses = $rectorsFinder->findCoreRectorClasses();
        $listForConfig = [];
        foreach ($coreRectorClasses as $rectorClass) {
            $listForConfig[$rectorClass] = null;
        }
        foreach (\array_keys($this->getCurrentTestRectorClassesWithConfiguration()) as $rectorClass) {
            $listForConfig[$rectorClass] = null;
        }
        $filePath = \sys_get_temp_dir() . '/rector_temp_tests/all_rectors.php';
        $this->createPhpConfigFileAndDumpToPath($listForConfig, $filePath);
        $this->bootKernelWithConfigs(\Rector\Core\HttpKernel\RectorKernel::class, [$filePath]);
    }
    /**
     * @param array<string, mixed[]|null> $rectorClassesWithConfiguration
     */
    private function createPhpConfigFileAndDumpToPath(array $rectorClassesWithConfiguration, string $filePath) : void
    {
        $phpConfigPrinterFactory = new \Rector\Testing\PhpConfigPrinter\PhpConfigPrinterFactory();
        $smartPhpConfigPrinter = $phpConfigPrinterFactory->create();
        $fileContent = $smartPhpConfigPrinter->printConfiguredServices($rectorClassesWithConfiguration);
        $this->smartFileSystem->dumpFile($filePath, $fileContent);
    }
    private function normalizeNewlines(string $string) : string
    {
        return \RectorPrefix2020DecSat\Nette\Utils\Strings::replace($string, '#\\r\\n|\\r|\\n#', "\n");
    }
}
