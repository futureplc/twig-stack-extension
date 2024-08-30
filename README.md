# Twig Stack Extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![Tests](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
<!--delete-->
---

This package introduces the `{% push %}`, `{% pushonce %}` and `{% stack %}` tags to the Twig templating engine.
They allow for the dynamic injection content into specific sections of a layout.

They allow you to "push" content from child views into a named stack, which can then be rendered in the parent view
using `{% stack %}`. This is particularly helpful for managing scripts, styles, or other elements that need to be
included in a specific order or only when certain conditions are met.

## Installation

You can install the package via composer:

```bash
composer require futureplc/twig-stack-extension
```

### Setup in your project

First, add the extension to your Twig instance:

```php
use AmpedWeb\TwigStackExtension\StackExtension;

$twig->addExtension(new StackExtension);
```
Next you will need to either, use our custom Twig Environment here:

```php
use AmpedWeb\TwigStackExtension\Environment;
```
Or, if you have already overridden your `Environment` class, then you will need to override the `render()` method and add:

```php
    /**
     * @param string|TemplateWrapper $name The template name
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render($name, array $context = []): string
    {
        $html = parent::render($name, $context);

        if ($this->hasExtension(StackExtension::class)) {
            $stackManager = $this->getExtension(StackExtension::class)->getStackManager();
            $html = $stackManager->replaceStackPlaceholdersWithStackContent($html);
        }

        return $html;
    }
```
This will ensure any `{% stack %}` tag placeholders are replaced.

## Usage

```php
$skeleton = new VendorName\Skeleton();
echo $skeleton->echoPhrase('Hello, VendorName!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
