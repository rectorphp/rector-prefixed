# Changelog

## 1.1.0 (2020-02-04)

*   Feature: Improve error reporting and add parsing error message to Exception and
    ignore `JSON_THROW_ON_ERROR` option (available as of PHP 7.3).
    (#14 by @clue)

*   Feature: Add bechmarking script and import all global function references.
    (#16 by @clue)

*   Improve documentation and add NDJSON format description and
    add support / sponsorship info.
    (#12 and #17 by @clue)

*   Improve test suite to run tests on PHP 7.4 and simplify test matrix and
    apply minor code style adjustments to make phpstan happy.
    (#13 and #16 by @clue)

## 1.0.0 (2018-05-17)

*   First stable release, now following SemVer

*   Improve documentation and usage examples

> Contains no other changes, so it's actually fully compatible with the v0.1.2 release.

## 0.1.2 (2018-05-11)

*   Feature: Limit buffer size to 64 KiB by default.
    (#10 by @clue)

*   Feature: Forward compatiblity with EventLoop v0.5 and upcoming v1.0.
    (#8 by @clue)

*   Fix: Return bool `false` if encoding fails due to invalid value to pause source.
    (#9 by @clue)

*   Improve test suite by supporting PHPUnit v6 and test against legacy PHP 5.3 through PHP 7.2.
    (#7 by @clue)

*   Update project homepage.
    (#11 by @clue)

## 0.1.1 (2017-05-22)

*   Feature: Forward compatibility with Stream v0.7, v0.6, v0.5 and upcoming v1.0 (while keeping BC)
    (#6 by @thklein)

*   Improved test suite by adding PHPUnit to `require-dev`
    (#5 by @thklein)

## 0.1.0 (2016-11-24)

*   First tagged release