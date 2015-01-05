# axy\creator: creation of objects by parameters
 
* GitHub: [axypro/creator](https://github.com/axypro/creator)
* Composer: [axypro/creator](https://packagist.org/packages/axy/creator)

The library for creating similar objects by the specified pattern.

Apply in the following cases

* Lazy load: not all objects can be required, and the creation of an extra is overhead
* Lazy programmer: creating objects requires a lot of monotonous code

## Contents

* Examples
* Terms
* Format of context
* Format of pointer
* Class Creator
* Class Lazy
* Class Subs
* List of errors

## Callbacks

The library use [axy/callbacks](https://github.com/axypro/callbacks).
Wherever require a callback, you can use the [extended format](https://github.com/axypro/callbacks/blob/master/doc/format.md).