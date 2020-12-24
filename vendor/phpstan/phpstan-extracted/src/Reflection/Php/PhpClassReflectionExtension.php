<?php

declare (strict_types=1);
namespace _PhpScopere8e811afab72\PHPStan\Reflection\Php;

use _PhpScopere8e811afab72\PhpParser\Node;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Declare_;
use _PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_;
use _PhpScopere8e811afab72\PHPStan\Analyser\NodeScopeResolver;
use _PhpScopere8e811afab72\PHPStan\Analyser\ScopeContext;
use _PhpScopere8e811afab72\PHPStan\Analyser\ScopeFactory;
use _PhpScopere8e811afab72\PHPStan\Parser\Parser;
use _PhpScopere8e811afab72\PHPStan\PhpDoc\PhpDocInheritanceResolver;
use _PhpScopere8e811afab72\PHPStan\PhpDoc\ResolvedPhpDocBlock;
use _PhpScopere8e811afab72\PHPStan\PhpDoc\StubPhpDocProvider;
use _PhpScopere8e811afab72\PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension;
use _PhpScopere8e811afab72\PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension;
use _PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\FunctionVariantWithPhpDocs;
use _PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\MethodsClassReflectionExtension;
use _PhpScopere8e811afab72\PHPStan\Reflection\Native\NativeMethodReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\Native\NativeParameterWithPhpDocsReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\PropertiesClassReflectionExtension;
use _PhpScopere8e811afab72\PHPStan\Reflection\PropertyReflection;
use _PhpScopere8e811afab72\PHPStan\Reflection\ReflectionProvider;
use _PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\FunctionSignature;
use _PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\ParameterSignature;
use _PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\SignatureMapProvider;
use _PhpScopere8e811afab72\PHPStan\TrinaryLogic;
use _PhpScopere8e811afab72\PHPStan\Type\ArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType;
use _PhpScopere8e811afab72\PHPStan\Type\ErrorType;
use _PhpScopere8e811afab72\PHPStan\Type\FileTypeMapper;
use _PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper;
use _PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeMap;
use _PhpScopere8e811afab72\PHPStan\Type\MixedType;
use _PhpScopere8e811afab72\PHPStan\Type\NeverType;
use _PhpScopere8e811afab72\PHPStan\Type\Type;
use _PhpScopere8e811afab72\PHPStan\Type\TypehintHelper;
use _PhpScopere8e811afab72\PHPStan\Type\TypeUtils;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Adapter\ReflectionMethod;
use _PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Adapter\ReflectionProperty;
class PhpClassReflectionExtension implements \_PhpScopere8e811afab72\PHPStan\Reflection\PropertiesClassReflectionExtension, \_PhpScopere8e811afab72\PHPStan\Reflection\MethodsClassReflectionExtension
{
    /** @var ScopeFactory */
    private $scopeFactory;
    /** @var NodeScopeResolver */
    private $nodeScopeResolver;
    /** @var \PHPStan\Reflection\Php\PhpMethodReflectionFactory */
    private $methodReflectionFactory;
    /** @var \PHPStan\PhpDoc\PhpDocInheritanceResolver */
    private $phpDocInheritanceResolver;
    /** @var \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension */
    private $annotationsMethodsClassReflectionExtension;
    /** @var \PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension */
    private $annotationsPropertiesClassReflectionExtension;
    /** @var \PHPStan\Reflection\SignatureMap\SignatureMapProvider */
    private $signatureMapProvider;
    /** @var \PHPStan\Parser\Parser */
    private $parser;
    /** @var \PHPStan\PhpDoc\StubPhpDocProvider */
    private $stubPhpDocProvider;
    /** @var bool */
    private $inferPrivatePropertyTypeFromConstructor;
    /** @var \PHPStan\Reflection\ReflectionProvider */
    private $reflectionProvider;
    /** @var FileTypeMapper */
    private $fileTypeMapper;
    /** @var string[] */
    private $universalObjectCratesClasses;
    /** @var \PHPStan\Reflection\PropertyReflection[][] */
    private $propertiesIncludingAnnotations = [];
    /** @var \PHPStan\Reflection\Php\PhpPropertyReflection[][] */
    private $nativeProperties = [];
    /** @var \PHPStan\Reflection\MethodReflection[][] */
    private $methodsIncludingAnnotations = [];
    /** @var \PHPStan\Reflection\MethodReflection[][] */
    private $nativeMethods = [];
    /** @var array<string, array<string, Type>> */
    private $propertyTypesCache = [];
    /** @var array<string, true> */
    private $inferClassConstructorPropertyTypesInProcess = [];
    /**
     * @param \PHPStan\Analyser\ScopeFactory $scopeFactory
     * @param \PHPStan\Analyser\NodeScopeResolver $nodeScopeResolver
     * @param \PHPStan\Reflection\Php\PhpMethodReflectionFactory $methodReflectionFactory
     * @param \PHPStan\PhpDoc\PhpDocInheritanceResolver $phpDocInheritanceResolver
     * @param \PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension
     * @param \PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension $annotationsPropertiesClassReflectionExtension
     * @param \PHPStan\Reflection\SignatureMap\SignatureMapProvider $signatureMapProvider
     * @param \PHPStan\Parser\Parser $parser
     * @param \PHPStan\PhpDoc\StubPhpDocProvider $stubPhpDocProvider
     * @param \PHPStan\Reflection\ReflectionProvider $reflectionProvider
     * @param FileTypeMapper $fileTypeMapper
     * @param bool $inferPrivatePropertyTypeFromConstructor
     * @param string[] $universalObjectCratesClasses
     */
    public function __construct(\_PhpScopere8e811afab72\PHPStan\Analyser\ScopeFactory $scopeFactory, \_PhpScopere8e811afab72\PHPStan\Analyser\NodeScopeResolver $nodeScopeResolver, \_PhpScopere8e811afab72\PHPStan\Reflection\Php\PhpMethodReflectionFactory $methodReflectionFactory, \_PhpScopere8e811afab72\PHPStan\PhpDoc\PhpDocInheritanceResolver $phpDocInheritanceResolver, \_PhpScopere8e811afab72\PHPStan\Reflection\Annotations\AnnotationsMethodsClassReflectionExtension $annotationsMethodsClassReflectionExtension, \_PhpScopere8e811afab72\PHPStan\Reflection\Annotations\AnnotationsPropertiesClassReflectionExtension $annotationsPropertiesClassReflectionExtension, \_PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\SignatureMapProvider $signatureMapProvider, \_PhpScopere8e811afab72\PHPStan\Parser\Parser $parser, \_PhpScopere8e811afab72\PHPStan\PhpDoc\StubPhpDocProvider $stubPhpDocProvider, \_PhpScopere8e811afab72\PHPStan\Reflection\ReflectionProvider $reflectionProvider, \_PhpScopere8e811afab72\PHPStan\Type\FileTypeMapper $fileTypeMapper, bool $inferPrivatePropertyTypeFromConstructor, array $universalObjectCratesClasses)
    {
        $this->scopeFactory = $scopeFactory;
        $this->nodeScopeResolver = $nodeScopeResolver;
        $this->methodReflectionFactory = $methodReflectionFactory;
        $this->phpDocInheritanceResolver = $phpDocInheritanceResolver;
        $this->annotationsMethodsClassReflectionExtension = $annotationsMethodsClassReflectionExtension;
        $this->annotationsPropertiesClassReflectionExtension = $annotationsPropertiesClassReflectionExtension;
        $this->signatureMapProvider = $signatureMapProvider;
        $this->parser = $parser;
        $this->stubPhpDocProvider = $stubPhpDocProvider;
        $this->reflectionProvider = $reflectionProvider;
        $this->fileTypeMapper = $fileTypeMapper;
        $this->inferPrivatePropertyTypeFromConstructor = $inferPrivatePropertyTypeFromConstructor;
        $this->universalObjectCratesClasses = $universalObjectCratesClasses;
    }
    public function hasProperty(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $propertyName) : bool
    {
        return $classReflection->getNativeReflection()->hasProperty($propertyName);
    }
    public function getProperty(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $propertyName) : \_PhpScopere8e811afab72\PHPStan\Reflection\PropertyReflection
    {
        if (!isset($this->propertiesIncludingAnnotations[$classReflection->getCacheKey()][$propertyName])) {
            $this->propertiesIncludingAnnotations[$classReflection->getCacheKey()][$propertyName] = $this->createProperty($classReflection, $propertyName, \true);
        }
        return $this->propertiesIncludingAnnotations[$classReflection->getCacheKey()][$propertyName];
    }
    public function getNativeProperty(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $propertyName) : \_PhpScopere8e811afab72\PHPStan\Reflection\Php\PhpPropertyReflection
    {
        if (!isset($this->nativeProperties[$classReflection->getCacheKey()][$propertyName])) {
            /** @var \PHPStan\Reflection\Php\PhpPropertyReflection $property */
            $property = $this->createProperty($classReflection, $propertyName, \false);
            $this->nativeProperties[$classReflection->getCacheKey()][$propertyName] = $property;
        }
        return $this->nativeProperties[$classReflection->getCacheKey()][$propertyName];
    }
    private function createProperty(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $propertyName, bool $includingAnnotations) : \_PhpScopere8e811afab72\PHPStan\Reflection\PropertyReflection
    {
        $propertyReflection = $classReflection->getNativeReflection()->getProperty($propertyName);
        $propertyName = $propertyReflection->getName();
        $declaringClassName = $propertyReflection->getDeclaringClass()->getName();
        $declaringClassReflection = $classReflection->getAncestorWithClassName($declaringClassName);
        if ($declaringClassReflection === null) {
            throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException(\sprintf('Internal error: Expected to find an ancestor with class name %s on %s, but none was found.', $declaringClassName, $classReflection->getName()));
        }
        $deprecatedDescription = null;
        $isDeprecated = \false;
        $isInternal = \false;
        if ($includingAnnotations && $this->annotationsPropertiesClassReflectionExtension->hasProperty($classReflection, $propertyName)) {
            $hierarchyDistances = $classReflection->getClassHierarchyDistances();
            $annotationProperty = $this->annotationsPropertiesClassReflectionExtension->getProperty($classReflection, $propertyName);
            if (!isset($hierarchyDistances[$annotationProperty->getDeclaringClass()->getName()])) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            $distanceDeclaringClass = $propertyReflection->getDeclaringClass()->getName();
            $propertyTrait = $this->findPropertyTrait($propertyReflection);
            if ($propertyTrait !== null) {
                $distanceDeclaringClass = $propertyTrait;
            }
            if (!isset($hierarchyDistances[$distanceDeclaringClass])) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            if ($hierarchyDistances[$annotationProperty->getDeclaringClass()->getName()] < $hierarchyDistances[$distanceDeclaringClass]) {
                return $annotationProperty;
            }
        }
        $docComment = $propertyReflection->getDocComment() !== \false ? $propertyReflection->getDocComment() : null;
        $declaringTraitName = null;
        $phpDocType = null;
        $resolvedPhpDoc = $this->stubPhpDocProvider->findPropertyPhpDoc($declaringClassName, $propertyReflection->getName());
        $stubPhpDocString = null;
        if ($resolvedPhpDoc === null) {
            if ($declaringClassReflection->getFileName() !== \false) {
                $declaringTraitName = $this->findPropertyTrait($propertyReflection);
                $constructorName = null;
                if (\method_exists($propertyReflection, 'isPromoted') && $propertyReflection->isPromoted()) {
                    if ($declaringClassReflection->hasConstructor()) {
                        $constructorName = $declaringClassReflection->getConstructor()->getName();
                    }
                }
                if ($constructorName === null) {
                    $resolvedPhpDoc = $this->phpDocInheritanceResolver->resolvePhpDocForProperty($docComment, $declaringClassReflection, $declaringClassReflection->getFileName(), $declaringTraitName, $propertyName);
                } elseif ($docComment !== null) {
                    $resolvedPhpDoc = $this->fileTypeMapper->getResolvedPhpDoc($declaringClassReflection->getFileName(), $declaringClassName, $declaringTraitName, $constructorName, $docComment);
                }
                $phpDocBlockClassReflection = $declaringClassReflection;
            }
        } else {
            $phpDocBlockClassReflection = $declaringClassReflection;
            $stubPhpDocString = $resolvedPhpDoc->getPhpDocString();
        }
        if ($resolvedPhpDoc !== null) {
            $varTags = $resolvedPhpDoc->getVarTags();
            if (isset($varTags[0]) && \count($varTags) === 1) {
                $phpDocType = $varTags[0]->getType();
            } elseif (isset($varTags[$propertyName])) {
                $phpDocType = $varTags[$propertyName]->getType();
            }
        }
        if ($phpDocType === null) {
            if (isset($constructorName) && $declaringClassReflection->getFileName() !== \false) {
                $constructorDocComment = $declaringClassReflection->getConstructor()->getDocComment();
                $nativeClassReflection = $declaringClassReflection->getNativeReflection();
                $positionalParameterNames = [];
                if ($nativeClassReflection->getConstructor() !== null) {
                    $positionalParameterNames = \array_map(static function (\ReflectionParameter $parameter) : string {
                        return $parameter->getName();
                    }, $nativeClassReflection->getConstructor()->getParameters());
                }
                $resolvedPhpDoc = $this->phpDocInheritanceResolver->resolvePhpDocForMethod($constructorDocComment, $declaringClassReflection->getFileName(), $declaringClassReflection, $declaringTraitName, $constructorName, $positionalParameterNames);
                $paramTags = $resolvedPhpDoc->getParamTags();
                if (isset($paramTags[$propertyReflection->getName()])) {
                    $phpDocType = $paramTags[$propertyReflection->getName()]->getType();
                }
            }
        }
        if ($resolvedPhpDoc !== null) {
            if (!isset($phpDocBlockClassReflection)) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            $phpDocType = $phpDocType !== null ? \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveTemplateTypes($phpDocType, $phpDocBlockClassReflection->getActiveTemplateTypeMap()) : null;
            $deprecatedDescription = $resolvedPhpDoc->getDeprecatedTag() !== null ? $resolvedPhpDoc->getDeprecatedTag()->getMessage() : null;
            $isDeprecated = $resolvedPhpDoc->isDeprecated();
            $isInternal = $resolvedPhpDoc->isInternal();
        }
        if ($phpDocType === null && $this->inferPrivatePropertyTypeFromConstructor && $declaringClassReflection->getFileName() !== \false && $propertyReflection->isPrivate() && (!\method_exists($propertyReflection, 'hasType') || !$propertyReflection->hasType()) && $declaringClassReflection->hasConstructor() && $declaringClassReflection->getConstructor()->getDeclaringClass()->getName() === $declaringClassReflection->getName()) {
            $phpDocType = $this->inferPrivatePropertyType($propertyReflection->getName(), $declaringClassReflection->getConstructor());
        }
        $nativeType = null;
        if (\method_exists($propertyReflection, 'getType') && $propertyReflection->getType() !== null) {
            $nativeType = $propertyReflection->getType();
        }
        $declaringTrait = null;
        if ($declaringTraitName !== null && $this->reflectionProvider->hasClass($declaringTraitName)) {
            $declaringTrait = $this->reflectionProvider->getClass($declaringTraitName);
        }
        return new \_PhpScopere8e811afab72\PHPStan\Reflection\Php\PhpPropertyReflection($declaringClassReflection, $declaringTrait, $nativeType, $phpDocType, $propertyReflection, $deprecatedDescription, $isDeprecated, $isInternal, $stubPhpDocString);
    }
    public function hasMethod(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $methodName) : bool
    {
        return $classReflection->getNativeReflection()->hasMethod($methodName);
    }
    public function getMethod(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $methodName) : \_PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection
    {
        if (isset($this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$methodName])) {
            return $this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$methodName];
        }
        $nativeMethodReflection = new \_PhpScopere8e811afab72\PHPStan\Reflection\Php\NativeBuiltinMethodReflection($classReflection->getNativeReflection()->getMethod($methodName));
        if (!isset($this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$nativeMethodReflection->getName()])) {
            $method = $this->createMethod($classReflection, $nativeMethodReflection, \true);
            $this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$nativeMethodReflection->getName()] = $method;
            if ($nativeMethodReflection->getName() !== $methodName) {
                $this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$methodName] = $method;
            }
        }
        return $this->methodsIncludingAnnotations[$classReflection->getCacheKey()][$nativeMethodReflection->getName()];
    }
    public function hasNativeMethod(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $methodName) : bool
    {
        $hasMethod = $this->hasMethod($classReflection, $methodName);
        if ($hasMethod) {
            return \true;
        }
        if ($methodName === '__get' && \_PhpScopere8e811afab72\PHPStan\Reflection\Php\UniversalObjectCratesClassReflectionExtension::isUniversalObjectCrate($this->reflectionProvider, $this->universalObjectCratesClasses, $classReflection)) {
            return \true;
        }
        return \false;
    }
    public function getNativeMethod(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, string $methodName) : \_PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection
    {
        if (isset($this->nativeMethods[$classReflection->getCacheKey()][$methodName])) {
            return $this->nativeMethods[$classReflection->getCacheKey()][$methodName];
        }
        if ($classReflection->getNativeReflection()->hasMethod($methodName)) {
            $nativeMethodReflection = new \_PhpScopere8e811afab72\PHPStan\Reflection\Php\NativeBuiltinMethodReflection($classReflection->getNativeReflection()->getMethod($methodName));
        } else {
            if ($methodName !== '__get' || !\_PhpScopere8e811afab72\PHPStan\Reflection\Php\UniversalObjectCratesClassReflectionExtension::isUniversalObjectCrate($this->reflectionProvider, $this->universalObjectCratesClasses, $classReflection)) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            $nativeMethodReflection = new \_PhpScopere8e811afab72\PHPStan\Reflection\Php\FakeBuiltinMethodReflection($methodName, $classReflection->getNativeReflection());
        }
        if (!isset($this->nativeMethods[$classReflection->getCacheKey()][$nativeMethodReflection->getName()])) {
            $method = $this->createMethod($classReflection, $nativeMethodReflection, \false);
            $this->nativeMethods[$classReflection->getCacheKey()][$nativeMethodReflection->getName()] = $method;
        }
        return $this->nativeMethods[$classReflection->getCacheKey()][$nativeMethodReflection->getName()];
    }
    private function createMethod(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $classReflection, \_PhpScopere8e811afab72\PHPStan\Reflection\Php\BuiltinMethodReflection $methodReflection, bool $includingAnnotations) : \_PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection
    {
        if ($includingAnnotations && $this->annotationsMethodsClassReflectionExtension->hasMethod($classReflection, $methodReflection->getName())) {
            $hierarchyDistances = $classReflection->getClassHierarchyDistances();
            $annotationMethod = $this->annotationsMethodsClassReflectionExtension->getMethod($classReflection, $methodReflection->getName());
            if (!isset($hierarchyDistances[$annotationMethod->getDeclaringClass()->getName()])) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            $distanceDeclaringClass = $methodReflection->getDeclaringClass()->getName();
            $methodTrait = $this->findMethodTrait($methodReflection);
            if ($methodTrait !== null) {
                $distanceDeclaringClass = $methodTrait;
            }
            if (!isset($hierarchyDistances[$distanceDeclaringClass])) {
                throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException();
            }
            if ($hierarchyDistances[$annotationMethod->getDeclaringClass()->getName()] < $hierarchyDistances[$distanceDeclaringClass]) {
                return $annotationMethod;
            }
        }
        $declaringClassName = $methodReflection->getDeclaringClass()->getName();
        $declaringClass = $classReflection->getAncestorWithClassName($declaringClassName);
        if ($declaringClass === null) {
            throw new \_PhpScopere8e811afab72\PHPStan\ShouldNotHappenException(\sprintf('Internal error: Expected to find an ancestor with class name %s on %s, but none was found.', $declaringClassName, $classReflection->getName()));
        }
        if ($this->signatureMapProvider->hasMethodSignature($declaringClassName, $methodReflection->getName())) {
            $variantNumbers = [];
            $i = 0;
            while ($this->signatureMapProvider->hasMethodSignature($declaringClassName, $methodReflection->getName(), $i)) {
                $variantNumbers[] = $i;
                $i++;
            }
            $stubPhpDocString = null;
            $variants = [];
            $reflectionMethod = null;
            if ($classReflection->getNativeReflection()->hasMethod($methodReflection->getName())) {
                $reflectionMethod = $classReflection->getNativeReflection()->getMethod($methodReflection->getName());
            } elseif (\class_exists($classReflection->getName(), \false)) {
                $reflectionClass = new \ReflectionClass($classReflection->getName());
                if ($reflectionClass->hasMethod($methodReflection->getName())) {
                    $reflectionMethod = $reflectionClass->getMethod($methodReflection->getName());
                }
            }
            foreach ($variantNumbers as $variantNumber) {
                $methodSignature = $this->signatureMapProvider->getMethodSignature($declaringClassName, $methodReflection->getName(), $reflectionMethod, $variantNumber);
                $phpDocParameterNameMapping = [];
                foreach ($methodSignature->getParameters() as $parameter) {
                    $phpDocParameterNameMapping[$parameter->getName()] = $parameter->getName();
                }
                $stubPhpDocReturnType = null;
                $stubPhpDocParameterTypes = [];
                $stubPhpDocParameterVariadicity = [];
                $phpDocParameterTypes = [];
                $phpDocReturnType = null;
                if (\count($variantNumbers) === 1) {
                    $stubPhpDocPair = $this->findMethodPhpDocIncludingAncestors($declaringClass, $methodReflection->getName(), \array_map(static function (\_PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\ParameterSignature $parameterSignature) : string {
                        return $parameterSignature->getName();
                    }, $methodSignature->getParameters()));
                    if ($stubPhpDocPair !== null) {
                        [$stubPhpDoc, $stubDeclaringClass] = $stubPhpDocPair;
                        $stubPhpDocString = $stubPhpDoc->getPhpDocString();
                        $templateTypeMap = $stubDeclaringClass->getActiveTemplateTypeMap();
                        $returnTag = $stubPhpDoc->getReturnTag();
                        if ($returnTag !== null) {
                            $stubPhpDocReturnType = \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveTemplateTypes($returnTag->getType(), $templateTypeMap);
                        }
                        foreach ($stubPhpDoc->getParamTags() as $name => $paramTag) {
                            $stubPhpDocParameterTypes[$name] = \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveTemplateTypes($paramTag->getType(), $templateTypeMap);
                            $stubPhpDocParameterVariadicity[$name] = $paramTag->isVariadic();
                        }
                    } elseif ($reflectionMethod !== null && $reflectionMethod->getDocComment() !== \false) {
                        $filename = $reflectionMethod->getFileName();
                        if ($filename !== \false) {
                            $phpDocBlock = $this->fileTypeMapper->getResolvedPhpDoc($filename, $declaringClassName, null, $reflectionMethod->getName(), $reflectionMethod->getDocComment());
                            $returnTag = $phpDocBlock->getReturnTag();
                            if ($returnTag !== null) {
                                $phpDocReturnType = $returnTag->getType();
                            }
                            foreach ($phpDocBlock->getParamTags() as $name => $paramTag) {
                                $phpDocParameterTypes[$name] = $paramTag->getType();
                            }
                            $signatureParameters = $methodSignature->getParameters();
                            foreach ($reflectionMethod->getParameters() as $paramI => $reflectionParameter) {
                                if (!\array_key_exists($paramI, $signatureParameters)) {
                                    continue;
                                }
                                $phpDocParameterNameMapping[$signatureParameters[$paramI]->getName()] = $reflectionParameter->getName();
                            }
                        }
                    }
                }
                $variants[] = $this->createNativeMethodVariant($methodSignature, $stubPhpDocParameterTypes, $stubPhpDocParameterVariadicity, $stubPhpDocReturnType, $phpDocParameterTypes, $phpDocReturnType, $phpDocParameterNameMapping);
            }
            if ($this->signatureMapProvider->hasMethodMetadata($declaringClassName, $methodReflection->getName())) {
                $hasSideEffects = \_PhpScopere8e811afab72\PHPStan\TrinaryLogic::createFromBoolean($this->signatureMapProvider->getMethodMetadata($declaringClassName, $methodReflection->getName())['hasSideEffects']);
            } else {
                $hasSideEffects = \_PhpScopere8e811afab72\PHPStan\TrinaryLogic::createMaybe();
            }
            return new \_PhpScopere8e811afab72\PHPStan\Reflection\Native\NativeMethodReflection($this->reflectionProvider, $declaringClass, $methodReflection, $variants, $hasSideEffects, $stubPhpDocString);
        }
        $declaringTraitName = $this->findMethodTrait($methodReflection);
        $resolvedPhpDoc = null;
        $stubPhpDocPair = $this->findMethodPhpDocIncludingAncestors($declaringClass, $methodReflection->getName(), \array_map(static function (\ReflectionParameter $parameter) : string {
            return $parameter->getName();
        }, $methodReflection->getParameters()));
        $phpDocBlockClassReflection = $declaringClass;
        if ($stubPhpDocPair !== null) {
            [$resolvedPhpDoc, $phpDocBlockClassReflection] = $stubPhpDocPair;
        }
        $stubPhpDocString = null;
        if ($resolvedPhpDoc === null) {
            if ($declaringClass->getFileName() !== \false) {
                $docComment = $methodReflection->getDocComment();
                $positionalParameterNames = \array_map(static function (\ReflectionParameter $parameter) : string {
                    return $parameter->getName();
                }, $methodReflection->getParameters());
                $resolvedPhpDoc = $this->phpDocInheritanceResolver->resolvePhpDocForMethod($docComment, $declaringClass->getFileName(), $declaringClass, $declaringTraitName, $methodReflection->getName(), $positionalParameterNames);
                $phpDocBlockClassReflection = $declaringClass;
            }
        } else {
            $stubPhpDocString = $resolvedPhpDoc->getPhpDocString();
        }
        $declaringTrait = null;
        if ($declaringTraitName !== null && $this->reflectionProvider->hasClass($declaringTraitName)) {
            $declaringTrait = $this->reflectionProvider->getClass($declaringTraitName);
        }
        $templateTypeMap = \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeMap::createEmpty();
        $phpDocParameterTypes = [];
        $phpDocReturnType = null;
        $phpDocThrowType = null;
        $deprecatedDescription = null;
        $isDeprecated = \false;
        $isInternal = \false;
        $isFinal = \false;
        if ($methodReflection instanceof \_PhpScopere8e811afab72\PHPStan\Reflection\Php\NativeBuiltinMethodReflection && $methodReflection->isConstructor() && $declaringClass->getFileName() !== \false) {
            foreach ($methodReflection->getParameters() as $parameter) {
                if (!\method_exists($parameter, 'isPromoted') || !$parameter->isPromoted()) {
                    continue;
                }
                if (!$methodReflection->getDeclaringClass()->hasProperty($parameter->getName())) {
                    continue;
                }
                $parameterProperty = $methodReflection->getDeclaringClass()->getProperty($parameter->getName());
                if (!\method_exists($parameterProperty, 'isPromoted') || !$parameterProperty->isPromoted()) {
                    continue;
                }
                if ($parameterProperty->getDocComment() === \false) {
                    continue;
                }
                $propertyDocblock = $this->fileTypeMapper->getResolvedPhpDoc($declaringClass->getFileName(), $declaringClassName, $declaringTraitName, $methodReflection->getName(), $parameterProperty->getDocComment());
                $varTags = $propertyDocblock->getVarTags();
                if (isset($varTags[0]) && \count($varTags) === 1) {
                    $phpDocType = $varTags[0]->getType();
                } elseif (isset($varTags[$parameter->getName()])) {
                    $phpDocType = $varTags[$parameter->getName()]->getType();
                } else {
                    continue;
                }
                $phpDocParameterTypes[$parameter->getName()] = $phpDocType;
            }
        }
        if ($resolvedPhpDoc !== null) {
            $templateTypeMap = $resolvedPhpDoc->getTemplateTypeMap();
            foreach ($resolvedPhpDoc->getParamTags() as $paramName => $paramTag) {
                if (\array_key_exists($paramName, $phpDocParameterTypes)) {
                    continue;
                }
                $phpDocParameterTypes[$paramName] = $paramTag->getType();
            }
            foreach ($phpDocParameterTypes as $paramName => $paramType) {
                $phpDocParameterTypes[$paramName] = \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveTemplateTypes($paramType, $phpDocBlockClassReflection->getActiveTemplateTypeMap());
            }
            $nativeReturnType = \_PhpScopere8e811afab72\PHPStan\Type\TypehintHelper::decideTypeFromReflection($methodReflection->getReturnType(), null, $declaringClass->getName());
            $phpDocReturnType = $this->getPhpDocReturnType($phpDocBlockClassReflection, $resolvedPhpDoc, $nativeReturnType);
            $phpDocThrowType = $resolvedPhpDoc->getThrowsTag() !== null ? $resolvedPhpDoc->getThrowsTag()->getType() : null;
            $deprecatedDescription = $resolvedPhpDoc->getDeprecatedTag() !== null ? $resolvedPhpDoc->getDeprecatedTag()->getMessage() : null;
            $isDeprecated = $resolvedPhpDoc->isDeprecated();
            $isInternal = $resolvedPhpDoc->isInternal();
            $isFinal = $resolvedPhpDoc->isFinal();
        }
        return $this->methodReflectionFactory->create($declaringClass, $declaringTrait, $methodReflection, $templateTypeMap, $phpDocParameterTypes, $phpDocReturnType, $phpDocThrowType, $deprecatedDescription, $isDeprecated, $isInternal, $isFinal, $stubPhpDocString);
    }
    /**
     * @param FunctionSignature $methodSignature
     * @param array<string, Type> $stubPhpDocParameterTypes
     * @param array<string, bool> $stubPhpDocParameterVariadicity
     * @param Type|null $stubPhpDocReturnType
     * @param array<string, Type> $phpDocParameterTypes
     * @param Type|null $phpDocReturnType
     * @param array<string, string> $phpDocParameterNameMapping
     * @return FunctionVariantWithPhpDocs
     */
    private function createNativeMethodVariant(\_PhpScopere8e811afab72\PHPStan\Reflection\SignatureMap\FunctionSignature $methodSignature, array $stubPhpDocParameterTypes, array $stubPhpDocParameterVariadicity, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $stubPhpDocReturnType, array $phpDocParameterTypes, ?\_PhpScopere8e811afab72\PHPStan\Type\Type $phpDocReturnType, array $phpDocParameterNameMapping) : \_PhpScopere8e811afab72\PHPStan\Reflection\FunctionVariantWithPhpDocs
    {
        $parameters = [];
        foreach ($methodSignature->getParameters() as $parameterSignature) {
            $type = null;
            $phpDocType = null;
            $phpDocParameterName = $phpDocParameterNameMapping[$parameterSignature->getName()] ?? $parameterSignature->getName();
            if (isset($stubPhpDocParameterTypes[$parameterSignature->getName()])) {
                $type = $stubPhpDocParameterTypes[$parameterSignature->getName()];
                $phpDocType = $stubPhpDocParameterTypes[$parameterSignature->getName()];
            } elseif (isset($phpDocParameterTypes[$phpDocParameterName])) {
                $phpDocType = $phpDocParameterTypes[$phpDocParameterName];
            }
            $parameters[] = new \_PhpScopere8e811afab72\PHPStan\Reflection\Native\NativeParameterWithPhpDocsReflection($parameterSignature->getName(), $parameterSignature->isOptional(), $type ?? $parameterSignature->getType(), $phpDocType ?? new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(), $parameterSignature->getNativeType(), $parameterSignature->passedByReference(), $stubPhpDocParameterVariadicity[$parameterSignature->getName()] ?? $parameterSignature->isVariadic(), null);
        }
        $returnType = null;
        if ($stubPhpDocReturnType !== null) {
            $returnType = $stubPhpDocReturnType;
            $phpDocReturnType = $stubPhpDocReturnType;
        }
        return new \_PhpScopere8e811afab72\PHPStan\Reflection\FunctionVariantWithPhpDocs(\_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeMap::createEmpty(), null, $parameters, $methodSignature->isVariadic(), $returnType ?? $methodSignature->getReturnType(), $phpDocReturnType ?? new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(), $methodSignature->getNativeReturnType());
    }
    private function findPropertyTrait(\ReflectionProperty $propertyReflection) : ?string
    {
        if ($propertyReflection instanceof \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Adapter\ReflectionProperty) {
            $declaringClass = $propertyReflection->getBetterReflection()->getDeclaringClass();
            if ($declaringClass->isTrait()) {
                if ($propertyReflection->getDeclaringClass()->isTrait() && $propertyReflection->getDeclaringClass()->getName() === $declaringClass->getName()) {
                    return null;
                }
                return $declaringClass->getName();
            }
            return null;
        }
        $declaringClass = $propertyReflection->getDeclaringClass();
        $trait = $this->deepScanTraitsForProperty($declaringClass->getTraits(), $propertyReflection);
        if ($trait !== null) {
            return $trait;
        }
        return null;
    }
    /**
     * @param \ReflectionClass<object>[] $traits
     * @param \ReflectionProperty $propertyReflection
     * @return string|null
     */
    private function deepScanTraitsForProperty(array $traits, \ReflectionProperty $propertyReflection) : ?string
    {
        foreach ($traits as $trait) {
            $result = $this->deepScanTraitsForProperty($trait->getTraits(), $propertyReflection);
            if ($result !== null) {
                return $result;
            }
            if (!$trait->hasProperty($propertyReflection->getName())) {
                continue;
            }
            $traitProperty = $trait->getProperty($propertyReflection->getName());
            if ($traitProperty->getDocComment() === $propertyReflection->getDocComment()) {
                return $trait->getName();
            }
        }
        return null;
    }
    private function findMethodTrait(\_PhpScopere8e811afab72\PHPStan\Reflection\Php\BuiltinMethodReflection $methodReflection) : ?string
    {
        if ($methodReflection->getReflection() instanceof \_PhpScopere8e811afab72\_HumbugBox221ad6f1b81f\Roave\BetterReflection\Reflection\Adapter\ReflectionMethod) {
            $declaringClass = $methodReflection->getReflection()->getBetterReflection()->getDeclaringClass();
            if ($declaringClass->isTrait()) {
                if ($methodReflection->getDeclaringClass()->isTrait() && $declaringClass->getName() === $methodReflection->getDeclaringClass()->getName()) {
                    return null;
                }
                return $declaringClass->getName();
            }
            return null;
        }
        $declaringClass = $methodReflection->getDeclaringClass();
        if ($methodReflection->getFileName() === $declaringClass->getFileName() && $methodReflection->getStartLine() >= $declaringClass->getStartLine() && $methodReflection->getEndLine() <= $declaringClass->getEndLine()) {
            return null;
        }
        $declaringClass = $methodReflection->getDeclaringClass();
        $traitAliases = $declaringClass->getTraitAliases();
        if (\array_key_exists($methodReflection->getName(), $traitAliases)) {
            return \explode('::', $traitAliases[$methodReflection->getName()])[0];
        }
        foreach ($this->collectTraits($declaringClass) as $traitReflection) {
            if (!$traitReflection->hasMethod($methodReflection->getName())) {
                continue;
            }
            if ($methodReflection->getFileName() === $traitReflection->getFileName() && $methodReflection->getStartLine() >= $traitReflection->getStartLine() && $methodReflection->getEndLine() <= $traitReflection->getEndLine()) {
                return $traitReflection->getName();
            }
        }
        return null;
    }
    /**
     * @param \ReflectionClass $class
     * @return \ReflectionClass[]
     */
    private function collectTraits(\ReflectionClass $class) : array
    {
        $traits = [];
        $traitsLeftToAnalyze = $class->getTraits();
        while (\count($traitsLeftToAnalyze) !== 0) {
            $trait = \reset($traitsLeftToAnalyze);
            $traits[] = $trait;
            foreach ($trait->getTraits() as $subTrait) {
                if (\in_array($subTrait, $traits, \true)) {
                    continue;
                }
                $traitsLeftToAnalyze[] = $subTrait;
            }
            \array_shift($traitsLeftToAnalyze);
        }
        return $traits;
    }
    private function inferPrivatePropertyType(string $propertyName, \_PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection $constructor) : ?\_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        $declaringClassName = $constructor->getDeclaringClass()->getName();
        if (isset($this->inferClassConstructorPropertyTypesInProcess[$declaringClassName])) {
            return null;
        }
        $this->inferClassConstructorPropertyTypesInProcess[$declaringClassName] = \true;
        $propertyTypes = $this->inferAndCachePropertyTypes($constructor);
        unset($this->inferClassConstructorPropertyTypesInProcess[$declaringClassName]);
        if (\array_key_exists($propertyName, $propertyTypes)) {
            return $propertyTypes[$propertyName];
        }
        return null;
    }
    /**
     * @param \PHPStan\Reflection\MethodReflection $constructor
     * @return array<string, Type>
     */
    private function inferAndCachePropertyTypes(\_PhpScopere8e811afab72\PHPStan\Reflection\MethodReflection $constructor) : array
    {
        $declaringClass = $constructor->getDeclaringClass();
        if (isset($this->propertyTypesCache[$declaringClass->getName()])) {
            return $this->propertyTypesCache[$declaringClass->getName()];
        }
        if ($declaringClass->getFileName() === \false) {
            return $this->propertyTypesCache[$declaringClass->getName()] = [];
        }
        $fileName = $declaringClass->getFileName();
        $nodes = $this->parser->parseFile($fileName);
        $classNode = $this->findClassNode($declaringClass->getName(), $nodes);
        if ($classNode === null) {
            return $this->propertyTypesCache[$declaringClass->getName()] = [];
        }
        $methodNode = $this->findConstructorNode($constructor->getName(), $classNode->stmts);
        if ($methodNode === null || $methodNode->stmts === null) {
            return $this->propertyTypesCache[$declaringClass->getName()] = [];
        }
        $classNameParts = \explode('\\', $declaringClass->getName());
        $namespace = null;
        if (\count($classNameParts) > 1) {
            $namespace = \implode('\\', \array_slice($classNameParts, 0, -1));
        }
        $classScope = $this->scopeFactory->create(\_PhpScopere8e811afab72\PHPStan\Analyser\ScopeContext::create($fileName), \false, [], $constructor, $namespace)->enterClass($declaringClass);
        [$templateTypeMap, $phpDocParameterTypes, $phpDocReturnType, $phpDocThrowType, $deprecatedDescription, $isDeprecated, $isInternal, $isFinal] = $this->nodeScopeResolver->getPhpDocs($classScope, $methodNode);
        $methodScope = $classScope->enterClassMethod($methodNode, $templateTypeMap, $phpDocParameterTypes, $phpDocReturnType, $phpDocThrowType, $deprecatedDescription, $isDeprecated, $isInternal, $isFinal);
        $propertyTypes = [];
        foreach ($methodNode->stmts as $statement) {
            if (!$statement instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Expression) {
                continue;
            }
            $expr = $statement->expr;
            if (!$expr instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Assign) {
                continue;
            }
            if (!$expr->var instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\PropertyFetch) {
                continue;
            }
            $propertyFetch = $expr->var;
            if (!$propertyFetch->var instanceof \_PhpScopere8e811afab72\PhpParser\Node\Expr\Variable || $propertyFetch->var->name !== 'this' || !$propertyFetch->name instanceof \_PhpScopere8e811afab72\PhpParser\Node\Identifier) {
                continue;
            }
            $propertyType = $methodScope->getType($expr->expr);
            if ($propertyType instanceof \_PhpScopere8e811afab72\PHPStan\Type\ErrorType || $propertyType instanceof \_PhpScopere8e811afab72\PHPStan\Type\NeverType) {
                continue;
            }
            $propertyType = \_PhpScopere8e811afab72\PHPStan\Type\TypeUtils::generalizeType($propertyType);
            if ($propertyType instanceof \_PhpScopere8e811afab72\PHPStan\Type\Constant\ConstantArrayType) {
                $propertyType = new \_PhpScopere8e811afab72\PHPStan\Type\ArrayType(new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(\true), new \_PhpScopere8e811afab72\PHPStan\Type\MixedType(\true));
            }
            $propertyTypes[$propertyFetch->name->toString()] = $propertyType;
        }
        return $this->propertyTypesCache[$declaringClass->getName()] = $propertyTypes;
    }
    /**
     * @param string $className
     * @param \PhpParser\Node[] $nodes
     * @return \PhpParser\Node\Stmt\Class_|null
     */
    private function findClassNode(string $className, array $nodes) : ?\_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_
    {
        foreach ($nodes as $node) {
            if ($node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Class_ && $node->namespacedName->toString() === $className) {
                return $node;
            }
            if (!$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Namespace_ && !$node instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\Declare_) {
                continue;
            }
            $subNodeNames = $node->getSubNodeNames();
            foreach ($subNodeNames as $subNodeName) {
                $subNode = $node->{$subNodeName};
                if (!\is_array($subNode)) {
                    $subNode = [$subNode];
                }
                $result = $this->findClassNode($className, $subNode);
                if ($result === null) {
                    continue;
                }
                return $result;
            }
        }
        return null;
    }
    /**
     * @param string $methodName
     * @param \PhpParser\Node\Stmt[] $classStatements
     * @return \PhpParser\Node\Stmt\ClassMethod|null
     */
    private function findConstructorNode(string $methodName, array $classStatements) : ?\_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod
    {
        foreach ($classStatements as $statement) {
            if ($statement instanceof \_PhpScopere8e811afab72\PhpParser\Node\Stmt\ClassMethod && $statement->name->toString() === $methodName) {
                return $statement;
            }
        }
        return null;
    }
    private function getPhpDocReturnType(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $phpDocBlockClassReflection, \_PhpScopere8e811afab72\PHPStan\PhpDoc\ResolvedPhpDocBlock $resolvedPhpDoc, \_PhpScopere8e811afab72\PHPStan\Type\Type $nativeReturnType) : ?\_PhpScopere8e811afab72\PHPStan\Type\Type
    {
        $returnTag = $resolvedPhpDoc->getReturnTag();
        if ($returnTag === null) {
            return null;
        }
        $phpDocReturnType = $returnTag->getType();
        $phpDocReturnType = \_PhpScopere8e811afab72\PHPStan\Type\Generic\TemplateTypeHelper::resolveTemplateTypes($phpDocReturnType, $phpDocBlockClassReflection->getActiveTemplateTypeMap());
        if ($returnTag->isExplicit() || $nativeReturnType->isSuperTypeOf($phpDocReturnType)->yes()) {
            return $phpDocReturnType;
        }
        return null;
    }
    /**
     * @param ClassReflection $declaringClass
     * @param string $methodName
     * @param array<int, string> $positionalParameterNames
     * @return array{\PHPStan\PhpDoc\ResolvedPhpDocBlock, ClassReflection}|null
     */
    private function findMethodPhpDocIncludingAncestors(\_PhpScopere8e811afab72\PHPStan\Reflection\ClassReflection $declaringClass, string $methodName, array $positionalParameterNames) : ?array
    {
        $declaringClassName = $declaringClass->getName();
        $resolved = $this->stubPhpDocProvider->findMethodPhpDoc($declaringClassName, $methodName, $positionalParameterNames);
        if ($resolved !== null) {
            return [$resolved, $declaringClass];
        }
        if (!$this->stubPhpDocProvider->isKnownClass($declaringClassName)) {
            return null;
        }
        $ancestors = $declaringClass->getAncestors();
        foreach ($ancestors as $ancestor) {
            if ($ancestor->getName() === $declaringClassName) {
                continue;
            }
            if (!$ancestor->hasNativeMethod($methodName)) {
                continue;
            }
            $resolved = $this->stubPhpDocProvider->findMethodPhpDoc($ancestor->getName(), $methodName, $positionalParameterNames);
            if ($resolved === null) {
                continue;
            }
            return [$resolved, $ancestor];
        }
        return null;
    }
}
