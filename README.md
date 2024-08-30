# Twig Stack Extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![Tests](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)

This Twig extension allows you to "push" content from child views into a named "stack" which can be rendered in another view or layout. This is perfect for many scenarios, like pushing scripts or styles used by components.

## Installation

You can install the package via Composer:

```bash
composer require futureplc/twig-stack-extension
```

### Setup in your project

First, add the extension to the Twig instance:

```php
$twig->addExtension(new \Future\TwigStackExtension\StackExtension());
```

Next, we need to modify the output of the content rendered by Twig in order to ensure the stacks are injected into the right place properly.

One way this can be done is by using the custom `Environment` class provided by the package.

```diff
- use Twig\Environment;
+ use Future\TwigStackExtension\Environment;

// ...

$twig = new Environment($loader);
```

If you don't want to override your `Environment`, you can manually call the extension on the rendered output.

```php
$result = $twig->render('view.html.twig');

$result = $twig
    ->getExtension(\Future\TwigStackExtension\StackExtension::class)
    ->getStackManager()
    ->replaceStackPlaceholdersWithStackContent($result);
```

It's all set up and ready to be used.

## Usage

In Twig templates, you will have three new tags to use, `{% push %}` and `{% pushonce %}` to push content to a named stack, and `{% stack %}` to render the named stack.

### Pushing to a stack

Wrap any content you want "pushed" to the stack each time this part of the Twig template is parsed.

```twig
<!-- partial.twig -->
{% push 'scripts' %}
    <script>console.log('Pushed script executed')</script>
{% endpush %}
```

Using `{% push %}` will push the contents to the stack _every time_ it is called.

If you want to push only the first time the code is referenced, for example, including a library needed for a component, you can use the `{% pushonce %}` tag instead.

```twig
<!-- components/datepicker.twig -->
<input type="text" class="datepicker" />

{% pushonce 'scripts' %}
    <script src="/path/to/datepicker-lib.js" />
{% endpushonce %}
```

### Rendering a stack

You can use the `{% stack %}` tag to render the contents of the stack.

```
<!-- layout.twig -->
<html>
    <head>...</head>
    <body>
        ...
        {% stack 'scripts' %}
    </body>
</html>
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

- [Chris Powell](https://github.com/ampedweb)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
