<?php

namespace _PhpScoperbd5d0c5f7638\ExplodeFunction;

function (string $delimiter, $mixed) {
    /** @var string $str */
    $str = doFoo();
    $sureArray = \explode(' ', $str);
    $sureFalse = \explode('', $str);
    $arrayOrFalse = \explode($delimiter, $str);
    $emptyOrComma = '';
    if (doFoo()) {
        $emptyOrComma = ',';
    }
    $anotherArrayOrFalse = \explode($emptyOrComma, $str);
    $benevolentArrayOrFalse = \explode($mixed, $str);
    die;
};