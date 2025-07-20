# Silence Routing

[![Latest Stable Version](https://img.shields.io/packagist/v/silencenjoyer/silence-routing.svg)](https://packagist.org/packages/silencenjoyer/silence-routing)
[![PHP Version Require](https://img.shields.io/packagist/php-v/silencenjoyer/silence-routing.svg)](https://packagist.org/packages/silencenjoyer/silence-routing)
[![License](https://img.shields.io/github/license/silencenjoyer/silence-routing)](LICENSE.md)

The package provides a routing system that allows you to bind a route or group of routes to a specific incoming request.

This package is part of the monorepository [silencenjoyer/silence](https://github.com/silencenjoyer/silence), but can be used independently.

## ⚙️ Installation

``
composer require silencenjoyer/silence-routing
``

## 🚀 Quick start
### Basic Usage
```php
<?php

declare(strict_types=1);

use Silence\Routing\Router;
use Silence\Routing\Matcher\HttpMatcher;
use Silence\Routing\Group;
use Silence\Routing\HttpRoute as Route;

$group = Group::of([
    Route::get('/', function () {
        echo 'This is success processed route.';
    }),
]);

$router = (new Router(new HttpMatcher()))
    ->registerRoutes($group->getRoutes())
;

$resolvedRoute = $router->resolve($request);
```

### Advanced Usage
```php
<?php

declare(strict_types=1);

namespace App\Routes;

use App\Http\Controllers\SiteController;
use Silence\Routing\RouteProviderInterface;
use Silence\Routing\Group;
use Silence\Routing\HttpRoute as Route;

class SiteRouteProvider implements RouteProviderInterface
{
    public function getRoutes(): array
    {
        return [
            Group::of([
                Route::get('/', function () {
                    echo 'This is success processed route.';
                }),
            ]),
        ];
    }
}
```
```php
<?php

declare(strict_types=1);

use App\Routes\SiteRouteProvider;
use Silence\Routing\RouteProviders\RouteProviderRegistry;
use Silence\Routing\Router;
use Silence\Routing\Matcher\HttpMatcher;

$router = new Router(new HttpMatcher());
$registry = new RouteProviderRegistry($router);
$registry
    ->withRoute(new SiteRouteProvider())
    ->register()
;

$resolvedRoute = $router->resolve($request);
```

## 🧱 Features:
- Support for PSR-15 middleware chains.
- Extracting route parameters from the path.
- Route resolving system.
- Named routes.
- Route groups.
- Binding routes to different HTTP request methods.

## 🧪 Testing
``
php vendor/bin/phpunit
``

## 🧩 Use in the composition of Silence
The package is used as the routing core in the Silence application.  
If you are writing your own package, you can connect ``silencenjoyer/silence-routing`` to register and resolve routes with parameter substitution.

## 📄 License
This package is distributed under the MIT licence. For more details, see [LICENSE](LICENSE.md).
