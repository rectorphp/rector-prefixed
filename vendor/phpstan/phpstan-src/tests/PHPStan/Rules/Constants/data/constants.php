<?php

namespace _PhpScoper88fe6e0ad041\Constants;

use const _PhpScoper88fe6e0ad041\OtherConstants\BAZ_CONSTANT;
echo FOO_CONSTANT;
echo BAR_CONSTANT;
echo \_PhpScoper88fe6e0ad041\OtherConstants\BAZ_CONSTANT;
echo NONEXISTENT_CONSTANT;
function () {
    echo DEFINED_CONSTANT;
    \define('DEFINED_CONSTANT', \true);
    echo DEFINED_CONSTANT;
    if (\defined('DEFINED_CONSTANT_IF')) {
        echo DEFINED_CONSTANT_IF;
    }
    echo DEFINED_CONSTANT_IF;
    if (!\defined("OMIT_INDIC_FIX_1") || OMIT_INDIC_FIX_1 != 1) {
        // ...
    }
};
const CONSTANT_IN_CONST_ASSIGN = 1;
echo CONSTANT_IN_CONST_ASSIGN;
