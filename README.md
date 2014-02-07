#Mahout, a modular PHP webserver interface

Mahout provides a minimal, modular and adaptable interface for developing
web applications in PHP.  By wrapping HTTP requests and responses in
the simplest way possible, it unifies and distills the API for web
servers, web frameworks, and software in between (the so-called
middleware) into a single method call.

The exact details of this are described in the Mahout specification,
which all Mahout based applications should conform to.

##Supported web servers

These web servers include Mahout handlers in their distributions:

* Tusker (TBA)

Any valid Mahout based app will run the same on all these handlers, without changing anything.

##Supported web frameworks

These frameworks include Rack adapters in their distributions:

* Currently **none**

##Available middlewares

Between the server and the framework, Mahout can be customised to your applications needs using middleware, for example:

* `\Mahout\File`, for serving static files.

All these components use the same interface, which is described in detail in the Mahout specification.  These optional components can be used in any way you wish.

##Convenience

If you want to develop outside of existing frameworks, implement your own ones, or develop middleware, Mahout provides many helpers to create Mahout applications quickly and without doing the same web stuff all
over:

* `\Mahout\Request`, which also provides query string parsing and multipart handling.
* `\Mahout\Response`, for convenient generation of HTTP replies and cookie handling.
* `\Mahout\MockRequest` and `\Mahout\MockResponse` for efficient and quick testing of Mahout application without real HTTP round-trips.

##mahout-contrib

The plethora of useful middleware created the need for a project that
collects fresh Mahout middleware.  mahout-contrib includes a variety of
add-on components for Mahout and it is easy to contribute new modules.

* http://github.com/mahout/mahout-contrib

##Quick start

Try the Elephant!

With Tusk web server:

```
tusk examples/elephant.mh.php
```

By default, the elephant is found at http://localhost:9292.

##Installing with Composer

A package of Mahout is available at packagist.org.  You can install it with:

```
php composer.phar require mahout/mahout:1.*
````

##Running the tests

Testing Mahout requires the phpUnit testing framework:

```
phpunit
```

##History

* February 10th, 2014: First public release 0.1.

##Links
| Name | Address |
|----------|--------------------------|
| Mahout | <http://phpmahout.github.io/> |
| Official Mahout repositories | <http://github.com/PHPMahout/Mahout> |
| Mahout Bug Tracking | <http://github.com/PHPMahout/Mahout/issues> |
| Packagist project | <https://packagist.org/packages/mahout/mahout> |
| Aurimas Niekis |<http://www.gcds.lt/> |