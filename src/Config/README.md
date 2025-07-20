# Silence Config

[![Latest Stable Version](https://img.shields.io/packagist/v/silencenjoyer/silence-config.svg)](https://packagist.org/packages/silencenjoyer/silence-config)
[![PHP Version Require](https://img.shields.io/packagist/php-v/silencenjoyer/silence-config.svg)](https://packagist.org/packages/silencenjoyer/silence-config)
[![License](https://img.shields.io/github/license/silencenjoyer/silence-config)](LICENSE.md)

This package is designed to read the configuration and interact with it.

The package provides two main entities: \Silence\Config\AppConfig for working with application parameters, and \Silence\Config\AppContext for storing important application context settings: environment, locale, etc.

This package is part of the monorepository [silencenjoyer/silence](https://github.com/silencenjoyer/silence), but can be used independently.

## ⚙️ Installation

``
composer require silencenjoyer/silence-config
``

## 🚀 Quick start

```php
# Config
// main.php
<?php

return [
    'data' => ['qwerty' => 1, 'test' => 2]
];
...

use Silence\Config\AppConfigFactory;

$config = AppConfigFactory::create('dev', 'path/to', ['main']);
$data = $config->get('data'); // ['qwerty' => 1, 'test' => 2]
```

```php
# Context

<?php
use use Silence\Config\AppContextFactory;

$appContext = AppContextFactory::create('prod', true);
$appContext->getEnv(); // 'prod'
$appContext->isDebug(); // true
$appContext->getLocale(); // 'en_US' by default
```

## 🧱 Features:
- Hierarchical config access
- Escaped dots in keys
- Dynamic value setting
- Safe value retrieval
- Pure-PHP, dependency-free

## 🧪 Testing
``
php vendor/bin/phpunit
``

## 🧩 Use in the composition of Silence
The package is used to access the context and parameters of the ‘Silence’ application.  
If you are writing your own package, you can connect ``silencenjoyer/silence-config`` to store configuration parameters.

## 📄 License
This package is distributed under the MIT licence. For more details, see [LICENSE](LICENSE.md).
