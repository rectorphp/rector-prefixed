<?php

declare (strict_types=1);
namespace RectorPrefix20210317\Symplify\Skipper\SkipVoter;

use RectorPrefix20210317\Symplify\PackageBuilder\Parameter\ParameterProvider;
use RectorPrefix20210317\Symplify\PackageBuilder\Reflection\ClassLikeExistenceChecker;
use RectorPrefix20210317\Symplify\Skipper\Contract\SkipVoterInterface;
use RectorPrefix20210317\Symplify\Skipper\SkipCriteriaResolver\SkippedClassResolver;
use RectorPrefix20210317\Symplify\Skipper\Skipper\OnlySkipper;
use RectorPrefix20210317\Symplify\Skipper\Skipper\SkipSkipper;
use RectorPrefix20210317\Symplify\Skipper\ValueObject\Option;
use RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo;
final class ClassSkipVoter implements \RectorPrefix20210317\Symplify\Skipper\Contract\SkipVoterInterface
{
    /**
     * @var ClassLikeExistenceChecker
     */
    private $classLikeExistenceChecker;
    /**
     * @var ParameterProvider
     */
    private $parameterProvider;
    /**
     * @var SkipSkipper
     */
    private $skipSkipper;
    /**
     * @var OnlySkipper
     */
    private $onlySkipper;
    /**
     * @var SkippedClassResolver
     */
    private $skippedClassResolver;
    /**
     * @param \Symplify\PackageBuilder\Reflection\ClassLikeExistenceChecker $classLikeExistenceChecker
     * @param \Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider
     * @param \Symplify\Skipper\Skipper\SkipSkipper $skipSkipper
     * @param \Symplify\Skipper\Skipper\OnlySkipper $onlySkipper
     * @param \Symplify\Skipper\SkipCriteriaResolver\SkippedClassResolver $skippedClassResolver
     */
    public function __construct($classLikeExistenceChecker, $parameterProvider, $skipSkipper, $onlySkipper, $skippedClassResolver)
    {
        $this->classLikeExistenceChecker = $classLikeExistenceChecker;
        $this->parameterProvider = $parameterProvider;
        $this->skipSkipper = $skipSkipper;
        $this->onlySkipper = $onlySkipper;
        $this->skippedClassResolver = $skippedClassResolver;
    }
    /**
     * @param string|object $element
     */
    public function match($element) : bool
    {
        if (\is_object($element)) {
            return \true;
        }
        return $this->classLikeExistenceChecker->doesClassLikeExist($element);
    }
    /**
     * @param string|object $element
     */
    public function shouldSkip($element, \RectorPrefix20210317\Symplify\SmartFileSystem\SmartFileInfo $smartFileInfo) : bool
    {
        $only = $this->parameterProvider->provideArrayParameter(\RectorPrefix20210317\Symplify\Skipper\ValueObject\Option::ONLY);
        $doesMatchOnly = $this->onlySkipper->doesMatchOnly($element, $smartFileInfo, $only);
        if (\is_bool($doesMatchOnly)) {
            return $doesMatchOnly;
        }
        $skippedClasses = $this->skippedClassResolver->resolve();
        return $this->skipSkipper->doesMatchSkip($element, $smartFileInfo, $skippedClasses);
    }
}
