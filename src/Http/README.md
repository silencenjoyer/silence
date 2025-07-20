# Silence HTTP Kernel

[![Latest Stable Version](https://img.shields.io/packagist/v/silencenjoyer/silence-http.svg)](https://packagist.org/packages/silencenjoyer/silence-http)
[![PHP Version Require](https://img.shields.io/packagist/php-v/silencenjoyer/silence-http.svg)](https://packagist.org/packages/silencenjoyer/silence-http)
[![License](https://img.shields.io/github/license/silencenjoyer/silence-http)](LICENSE)

The package provides HTTP kernel functionality for processing HTTP requests.
The PSR-15 Middleware chain is involved in the processing. 
The kernel triggers event so that you can subscribe to these events.

This package is part of the monorepository [silencenjoyer/silence](https://github.com/silencenjoyer/silence), but can be used independently.

## ⚙️ Installation

``
composer require silencenjoyer/silence-http
``

## 🚀 Quick start
```php
<?php
```

## 🧱 Features:
- PSR-15 Middleware chain
- Event triggering
- Injection route handler parameters
- HTTP exceptions hierarchy 

## 🧪 Testing
``
php vendor/bin/phpunit
``

## 🧩 Use in the composition of Silence
The package is the core of HTTP request processing in the Silence application.  
If you are writing your own package, you can use ``silencenjoyer/silence-http`` as basic algorithm for request handling.

## 📄 License
This package is distributed under the MIT licence. For more details, see [LICENSE](LICENSE).
