# Xhprof support in buggregator for Laravel

Welcome to the Xhprof integration package for [buggregator](https://buggregator.dev/) in Laravel. This repository allows you to effortlessly enable Xhprof support for buggregator in your Laravel application.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- [XHProf](http://pecl.php.net/package/xhprof) ext or its fork [longxinH/xhprof](https://github.com/longxinH/xhprof).

## Installation

To get started, install the package via composer:

```bash
composer require --dev maantje/xhprof-buggregator-laravel
```

## Usage

Set the buggregator endpoint in your environment file, the displayed value is the default value:

```env
PROFILER_ENDPOINT=http://127.0.0.1:8000/api/profiler/store
```

Toggle Xhprof in your environment file as needed, but remember to disable it when not in use to avoid performance impact

```env
XHPROF_ENABLED=true
```

Alternatively, you can include the `X-Xhprof-Enabled` header in your request to explicitly enable or disable profiling for that specific call. When this header is present, it takes precedence over the environment variable.

Enabled values: `true` `1` `on` `yes`  
Disabled values: `false` `0` `off` `no`

This feature works great with a browser extension like [ModHeader](https://modheader.com/). It lets you switch profiling on and off right from your browser.

### Usage Profiler

By default, the profiler is turned on and off with each HTTP request. 
However, you may have other points where your\application starts. For example, it can be queues, commands, and so on.

In such cases, you can configure the profiler to run in the desired location yourself:

```php

use SpiralPackages\Profiler\Profiler;

class RegisterUserActionJob 
{
    public function __construct(
        public string $name,
        public string $password
    ) {
    }
    
    /**
     * Get Profiler object from Container DI
     * 
     * @param Profiler $profiler
     * @return void
     */
    public function handle(Profiler $profiler): void
    {
        try {
            $profiler->start();
            
            // code for register new user
        }
        finally {
            $profiler->end();
        }
    }
}

```

### Usage with Sail

Add the buggregator service to your docker-compose file:

```yaml
buggregator:
    image: ghcr.io/buggregator/server:dev
    ports:
        - 8000:8000
        - 1025:1025
        - 9912:9912
        - 9913:9913
    networks:
        - sail
```

Set the profiler endpoint in your environment file:

```env
PROFILER_ENDPOINT=http://buggregator:8000/api/profiler/store
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
