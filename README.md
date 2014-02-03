
============================

Introduction
------------

Setup
------

`php composer.php -self-update`

`php composer.php install`

Go to `config/autoload/` and copy `application.local.php.dist` with new name without `.dist`.

TODO
----

* Tests.
* Cache for routes.
* Cache interface according to config.
* CLI task to flush all caches, assets, etc.
* Find out why listeners are called more than one time.
* Add a new module. Load layout from Application.
