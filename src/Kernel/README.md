# Silence Kernel

[![Latest Stable Version](https://img.shields.io/packagist/v/silencenjoyer/silence-kernel.svg)](https://packagist.org/packages/silencenjoyer/silence-kernel)
[![PHP Version Require](https://img.shields.io/packagist/php-v/silencenjoyer/silence-kernel.svg)](https://packagist.org/packages/silencenjoyer/silence-kernel)
[![License](https://img.shields.io/github/license/silencenjoyer/silence-kernel)](LICENSE.md)

The core of the **Silence** PHP framework, providing a configurable environment, basic application loading, integration with [Symfony DependencyInjection](https://symfony.com/doc/current/components/dependency_injection.html), and application lifecycle management.

This package is part of the monorepository [silencenjoyer/silence](https://github.com/silencenjoyer/silence), but can be used independently.

## ⚙️ Installation

``
composer require silencenjoyer/silence-kernel
``

## 🚀 Quick start

```php
$config = KernelConfig::withBasePath(dirname(__DIR__, 2))
    ->withExtensions([
        new RouteExtension(),
        new TwigExtension(),
    ])
;

(new Kernel($config))->run(); // launches the application
```

## 🧱 Features:
- Support for environment configurations ⚒️💼🔄
- PSR-11-compatible container (based on Symfony)
  - Simple mechanism for loading services and parameters
- Starting point for the application 🏁

## 🧪 Testing
``
php vendor/bin/phpunit
``

## 🧩 Use in the composition of Silence
The package is used as the basis for all applications and modules within the Silence ecosystem. 
If you are writing your own package, you can connect ``silencenjoyer/silence-kernel`` to manage dependencies and the environment.

## 📄 License
This package is distributed under the MIT licence. For more details, see [LICENSE](LICENSE.md).
