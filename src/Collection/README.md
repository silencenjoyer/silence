# Silence Collection

[![Latest Stable Version](https://img.shields.io/packagist/v/silencenjoyer/silence-collection.svg)](https://packagist.org/packages/silencenjoyer/silence-collection)
[![PHP Version Require](https://img.shields.io/packagist/php-v/silencenjoyer/silence-collection.svg)](https://packagist.org/packages/silencenjoyer/silence-collection)
[![License](https://img.shields.io/github/license/silencenjoyer/silence-collection)](LICENSE.md)

A collection for storing data in a structured and typed form, supplied as a component of the Silence PHP framework.

BaseCollection class is a generic, strongly-typed collection implementation in PHP.
It provides a convenient and consistent interface for working with arrays as objects and supports array-like access, iteration, and other common operations.

This package is part of the monorepository [silencenjoyer/silence](https://github.com/silencenjoyer/silence), but can be used independently.

## ⚙️ Installation

``
composer require silencenjoyer/silence-collection
``

## 🚀 Quick start

```php
$collection = new BaseCollection();

$collection->set('test', new stdClass());
$collection->set('test_2', new stdClass());

$collection->count(); // 2
```

## 🧱 Features:
- Generic Support
- Array-Like Behavior
- Iterable
- Countable

## 🧪 Testing
``
php vendor/bin/phpunit
``

## 🧩 Use in the composition of Silence
The package is used as the strongly-typed collection with generic support within the Silence ecosystem. 
If you are writing your own package, you can connect ``silencenjoyer/silence-collection`` for storing data in a structured and typed form.

## 📄 License
This package is distributed under the MIT licence. For more details, see [LICENSE](LICENSE.md).
