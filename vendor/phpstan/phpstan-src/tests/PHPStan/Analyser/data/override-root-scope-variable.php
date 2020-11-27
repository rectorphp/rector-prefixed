<?php

namespace _PhpScoperbd5d0c5f7638;

use PHPStan\TrinaryLogic;
use function PHPStan\Analyser\assertType;
use function PHPStan\Analyser\assertVariableCertainty;
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createMaybe(), $foo);
/** @var Foo $foo */
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createYes(), $foo);
function () : void {
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createNo(), $foo);
    /** @var Foo $foo */
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createNo(), $foo);
};
function () : void {
    if (\rand(0, 1) === 0) {
        $foo = \_PhpScoperbd5d0c5f7638\doFoo();
    }
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createMaybe(), $foo);
    /** @var Foo $foo */
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createMaybe(), $foo);
};
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createMaybe(), $bar);
\assert($bar instanceof \_PhpScoperbd5d0c5f7638\Foo);
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createYes(), $bar);
function () : void {
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createNo(), $bar);
    \assert($bar instanceof \_PhpScoperbd5d0c5f7638\Foo);
    \PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createYes(), $bar);
};
/** @var Foo $lorem */
/** @var Bar $ipsum */
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createYes(), $lorem);
\PHPStan\Analyser\assertType('Foo', $lorem);
\PHPStan\Analyser\assertVariableCertainty(\PHPStan\TrinaryLogic::createYes(), $ipsum);
\PHPStan\Analyser\assertType('Bar', $ipsum);