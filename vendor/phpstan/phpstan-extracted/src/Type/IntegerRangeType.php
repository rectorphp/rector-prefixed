<?php

declare (strict_types=1);
namespace _PhpScoperb75b35f52b74\PHPStan\Type;

use _PhpScoperb75b35f52b74\PHPStan\TrinaryLogic;
use _PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantBooleanType;
use _PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType;
class IntegerRangeType extends \_PhpScoperb75b35f52b74\PHPStan\Type\IntegerType implements \_PhpScoperb75b35f52b74\PHPStan\Type\CompoundType
{
    /** @var int */
    private $min;
    /** @var int */
    private $max;
    private function __construct(?int $min, ?int $max)
    {
        \assert($min === null || $max === null || $min <= $max);
        $this->min = $min ?? \PHP_INT_MIN;
        $this->max = $max ?? \PHP_INT_MAX;
    }
    public static function fromInterval(?int $min, ?int $max, int $shift = 0) : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        $min = $min ?? \PHP_INT_MIN;
        $max = $max ?? \PHP_INT_MAX;
        if ($shift !== 0) {
            if ($shift < 0) {
                if ($max < \PHP_INT_MIN - $shift) {
                    return new \_PhpScoperb75b35f52b74\PHPStan\Type\NeverType();
                }
                if ($max !== \PHP_INT_MAX) {
                    $max += $shift;
                }
                $min = $min < \PHP_INT_MIN - $shift ? \PHP_INT_MIN : $min + $shift;
            } else {
                if ($min > \PHP_INT_MAX - $shift) {
                    return new \_PhpScoperb75b35f52b74\PHPStan\Type\NeverType();
                }
                if ($min !== \PHP_INT_MIN) {
                    $min += $shift;
                }
                $max = $max > \PHP_INT_MAX - $shift ? \PHP_INT_MAX : $max + $shift;
            }
        }
        if ($min > $max) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\NeverType();
        }
        if ($min === $max) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType($min);
        }
        if ($min === \PHP_INT_MIN && $max === \PHP_INT_MAX) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\IntegerType();
        }
        return new self($min, $max);
    }
    public function getMin() : int
    {
        return $this->min;
    }
    public function getMax() : int
    {
        return $this->max;
    }
    public function describe(\_PhpScoperb75b35f52b74\PHPStan\Type\VerbosityLevel $level) : string
    {
        if ($this->min === \PHP_INT_MIN) {
            return \sprintf('int<min, %d>', $this->max);
        }
        if ($this->max === \PHP_INT_MAX) {
            return \sprintf('int<%d, max>', $this->min);
        }
        return \sprintf('int<%d, %d>', $this->min, $this->max);
    }
    public function accepts(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $type, bool $strictTypes) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        if ($type instanceof self) {
            if ($this->min > $type->max || $this->max < $type->min) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
            }
            if ($this->min <= $type->min && $this->max >= $type->max) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createYes();
            }
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createMaybe();
        }
        if ($type instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType) {
            if ($this->min <= $type->getValue() && $type->getValue() <= $this->max) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createYes();
            }
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
        }
        if ($type instanceof parent) {
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createMaybe();
        }
        if ($type instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\CompoundType) {
            return \_PhpScoperb75b35f52b74\PHPStan\Type\CompoundTypeHelper::accepts($type, $this, $strictTypes);
        }
        return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
    }
    public function isSuperTypeOf(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $type) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        if ($type instanceof self) {
            if ($this->min > $type->max || $this->max < $type->min) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
            }
            if ($this->min <= $type->min && $this->max >= $type->max) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createYes();
            }
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createMaybe();
        }
        if ($type instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType) {
            if ($this->min <= $type->getValue() && $type->getValue() <= $this->max) {
                return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createYes();
            }
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
        }
        if ($type instanceof parent) {
            return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createMaybe();
        }
        if ($type instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\CompoundType) {
            return $type->isSubTypeOf($this);
        }
        return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
    }
    public function isSubTypeOf(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $otherType) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        if ($otherType instanceof parent) {
            return $otherType->isSuperTypeOf($this);
        }
        if ($otherType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\UnionType || $otherType instanceof \_PhpScoperb75b35f52b74\PHPStan\Type\IntersectionType) {
            return $otherType->isSuperTypeOf($this);
        }
        return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::createNo();
    }
    public function isAcceptedBy(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $acceptingType, bool $strictTypes) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        return $this->isSubTypeOf($acceptingType);
    }
    public function equals(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $type) : bool
    {
        return $type instanceof self && $this->min === $type->min && $this->max === $type->max;
    }
    public function generalize() : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        return new parent();
    }
    public function isSmallerThan(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $otherType, bool $orEqual = \false) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::extremeIdentity((new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType($this->min))->isSmallerThan($otherType, $orEqual), (new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType($this->max))->isSmallerThan($otherType, $orEqual));
    }
    public function isGreaterThan(\_PhpScoperb75b35f52b74\PHPStan\Type\Type $otherType, bool $orEqual = \false) : \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic
    {
        return \_PhpScoperb75b35f52b74\PHPStan\TrinaryLogic::extremeIdentity($otherType->isSmallerThan(new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType($this->min), $orEqual), $otherType->isSmallerThan(new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType($this->max), $orEqual));
    }
    public function toNumber() : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        return new parent();
    }
    public function toBoolean() : \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType
    {
        $isZero = (new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantIntegerType(0))->isSuperTypeOf($this);
        if ($isZero->no()) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantBooleanType(\true);
        }
        if ($isZero->maybe()) {
            return new \_PhpScoperb75b35f52b74\PHPStan\Type\BooleanType();
        }
        return new \_PhpScoperb75b35f52b74\PHPStan\Type\Constant\ConstantBooleanType(\false);
    }
    /**
     * @param mixed[] $properties
     * @return Type
     */
    public static function __set_state(array $properties) : \_PhpScoperb75b35f52b74\PHPStan\Type\Type
    {
        return new self($properties['min'], $properties['max']);
    }
}
