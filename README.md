# axy\creator

Creation of objects by parameters (PHP).

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/creator.svg?style=flat-square)](https://packagist.org/packages/axy/creator)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.4-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://img.shields.io/travis/axypro/creator/master.svg?style=flat-square)](https://travis-ci.org/axypro/creator)
[![Coverage Status](https://coveralls.io/repos/axypro/creator/badge.svg?branch=master&service=github)](https://coveralls.io/github/axypro/creator?branch=master)
[![License](https://poser.pugx.org/axy/creator/license)](LICENSE)

* The library does not require any dependencies.
* Tested on PHP 5.4+, PHP 7, HHVM (on Linux).
* Install: `composer require axy/creator`.
* License: [MIT](LICENSE).

### Documentation

The library for creating similar objects by the specified pattern.

Apply in the following cases

* Lazy load: not all objects can be required, and the creation of an extra is overhead
* Lazy programmer: creating objects requires a lot of monotonous code

## Contents

* [Examples](doc/examples.md)
* [Context and pointer](doc/terms.md)
* [Format of pointer](doc/pointer.md)
* [Format of context](doc/context.md)
* [Class Creator](doc/Creator.md)
* [Class Lazy](doc/Lazy.md)
* [Class Subs](doc/Subs.md)
* [Rules of classname resolving](doc/classname.md)
* [List of errors](doc/errors.md)

## Callbacks

The library use [axy/callbacks](https://github.com/axypro/callbacks).
Wherever require a callback, you can use the [extended format](https://github.com/axypro/callbacks/blob/master/doc/format.md).

