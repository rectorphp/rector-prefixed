<?php

namespace _PhpScoper88fe6e0ad041\SwitchInstanceOf;

$foo = doFoo();
$bar = doBar();
$baz = doBaz();
switch (\true) {
    case $foo instanceof \_PhpScoper88fe6e0ad041\SwitchInstanceOf\Foo:
        break;
    case $bar instanceof \_PhpScoper88fe6e0ad041\SwitchInstanceOf\Bar:
        break;
    case $baz instanceof \_PhpScoper88fe6e0ad041\SwitchInstanceOf\Baz:
        die;
        break;
}
