# 🍃 Silence Framework Monorepo

This is the monorepository for the Silence Framework - a set of modular PHP packages designed for building lightweight, flexible applications with minimal dependencies and maximum extensibility.  

Silence is based on the [Symfony DependencyInjection](https://symfony.com/doc/current/components/dependency_injection.html) container and aims to provide clean abstractions for request handling, event dispatching, and application flow.

## 📦 Framework Packages

This monorepo contains the source code for the following packages:
- [silencenjoyer/silence-kernel](https://github.com/silencenjoyer/silence-kernel) - Core of the framework (entry point, base configuring).
- [silencenjoyer/silence-kernel-extension](https://github.com/silencenjoyer/silence-kernel-extension) - Silence application kernel extension system.
- [silencenjoyer/silence-routing](https://github.com/silencenjoyer/silence-routing) - Silence application routing system.
- [silencenjoyer/silence-runtime](https://github.com/silencenjoyer/silence-runtime) - Silence application runtime (runners) system.
- [silencenjoyer/silence-config](https://github.com/silencenjoyer/silence-config) - Silence application configuration.
- [silencenjoyer/silence-collection](https://github.com/silencenjoyer/silence-collection) - A collection for storing data in a structured and typed form.
- [silencenjoyer/silence-error-handling](https://github.com/silencenjoyer/silence-error-handling) - Silence error handling system.
- [silencenjoyer/silence-event](https://github.com/silencenjoyer/silence-event) - The basis of the Silence event system.
- [silencenjoyer/silence-http-parser](https://github.com/silencenjoyer/silence-http-parser) - Component for reading HTTP headers.
- [silencenjoyer/silence-http](https://github.com/silencenjoyer/silence-http) - HTTP Silence processing system.
- [silencenjoyer/silence-http-spec](https://github.com/silencenjoyer/silence-http-spec) - Static specification of the HTTP protocol.
- [silencenjoyer/silence-view](https://github.com/silencenjoyer/silence-view) - Silence application view system.

## 🎯 Goals:
- Full modularity: use only what you need.
- Minimal boilerplate: strong defaults and clean architecture 🐝
- Separated components: follow PSR standards wherever possible.
- Good DX: built-in testing, type safety, and modern PHP.

## ⚒️ Code Quality:
- Tests: ``composer test`` 
- Static analysis: ``php vendor/bin/phpstan``
- Composer dependency analyze: ``php vendor/bin/composer-dependency-analyser``
- PSR-12 formatting

## 🔀 Package Splitting:
This repo is split into standalone packages using [danharrin/monorepo-split-github-action](https://github.com/danharrin/monorepo-split-github-action).

Changes to subdirectories are automatically pushed to read-only split repositories on each push to main.