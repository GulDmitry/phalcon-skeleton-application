Phalcon Skeleton Applicaton
============================

Introduction
------------
Based on https://github.com/dphn/SkeletonApplication

The new features:

* Multi language.
* Examples of cache usage.
* CLI module with ability to clear cache and show system routes.
* Layouts hierarchy and partials.
* Assets compilation.
* Twitter Bootstrap as main theme.
* Tests.

Setup
------

`php composer.php -self-update`

`php composer.php install`

Go to `config/autoload/` copy `application.local.php.dist` and rename without `.dist`.

CLI
---

CLI modules have the same structure as MVC modules with one difference - tasks instead of controllers. To create a new -
put a folder in `data/modules/` and define a name in the configs `modulesCLI` section.

To use go to the root of your project and run `php cli.php`. You will see all available routes.

Tests
-----

Install `phpunit`.
Go to `tests/` and execute the command `phpunit`.

TODO
----

* Tests.
* Cache interface that uses config.
