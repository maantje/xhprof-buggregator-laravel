# Xhprof support in buggregator for Laravel

Welcome to the Xhprof integration package for buggregator in Laravel. This repository allows you to effortlessly enable Xhprof support for buggregator in your Laravel application.

## Installation

To get started, install the package via composer:

```bash
composer require --dev maantje/xhprof-buggregator-laravel
```
## Usage

Add the XhprofProfiler middleware to the middleware stack

```php
class Kernel {
    protected $middleware = [
        Maantje\XhprofBuggregatorLaravel\middleware\XhprofProfiler::class,
        ...
    ]
}
```

Set the buggregator endpoint in your environment file:

```env
PROFILER_ENDPOINT=http://buggregator:8000/api/profiler/store
```

Enable Xhprof in your environment file when needed, but remember to disable it when not in use as it can impact performance:

```env
XHPROF_ENABLED=true
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
