# Enhanced presenter factory for Nette Framework

[![Build Status](https://travis-ci.org/mrtnzlml/presenter-factory.svg?branch=master)](https://travis-ci.org/mrtnzlml/presenter-factory)

Nette Framework does have very simple presenter factory which helps you to map namespaces of presenters to the presenter name and vice versa.
Unfortunately it's not possible to map two namespaces under one module section:

```php
application:
  mapping:
    Module: App\Presenters\*Presenter
```

But with this package it is possible:

```php
application:
  mapping:
    Module:
      - App\Presenters\*Presenter
      - Bpp\Controllers\*Controller
```

In this case Nette is going to look for presenters in two namespaces. This is extremely useful if you have a lot of bundles
separated by functionality. For example you have API module with this mapping:

`['Api' => 'Ant\ApiModule\Presenters\*Presenter']`

Then you have Assets bundle.
In this bundle you can setup mapping for presenters under Assets package but related to the API like this:

`['Api' => 'Ant\Assets\ApiModule\Presenters\*Presenter']`

I found this very useful.
