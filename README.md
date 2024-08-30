# Twig Stack Extension

[![Latest Version on Packagist](https://img.shields.io/packagist/v/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![Tests](https://img.shields.io/github/actions/workflow/status/:vendor_slug/:package_slug/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/:vendor_slug/:package_slug/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
<!--delete-->
---

This package introduces the `{% push %}`, `{% pushonce %}` and `{% stack %}` tags to the Twig templating engine.
They allow for dynamic injection content into specific sections of a layout.

They allow you to "push" content from child views into a named "stack", which can then be rendered in the parent view
using `{% stack %}`. This is particularly helpful for managing scripts, styles, or other elements that need to be
included in a specific order or only when certain conditions are met. Such as when using [Twig Components](https://symfony.com/bundles/ux-twig-component/current/index.html).

## Installation

You can install the package via composer:

```bash
composer require futureplc/twig-stack-extension
```

### Setup in your project

First, add the extension to your Twig instance:

```php
use Future\TwigStackExtension\StackExtension;

$twig->addExtension(new StackExtension);
```
Next you will need to either, use our custom Twig Environment here:

```php
use Future\TwigStackExtension\Environment;
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

In Twig templates you will have three new tags available.

### Stack
```
{% stack 'stack-name' %}
```
Place this where you would like any content "pushed" to the stack to be output.

### Push
```html 
{% push 'stack-name' %}
<div>Content you would like to push</div>
{% endpush %}
```
Wrap any content you would like "pushed" to the stack when this part of the Twig template is parsed.
### Push Once

```html 
{% pushonce 'stack-name' %}
<div>Content you would like to push only once</div>
{% endpush %}
```
This works essentially the same as `{% push 'stack-name' %}`, however it will ensure that this content is only added to the stack once. Avoiding duplication.

### Basic Example of `{% pushonce %}`

### layout.html.twig (Parent Template)
The parent template includes {% stack %} directives to output any content pushed to the 'styles' and 'scripts' stacks.

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{% block title %}My Website{% endblock %}</title>

    {# Render any pushed styles #}
    {% stack 'styles' %}
</head>
<body>
    {% block content %}{% endblock %}

    {# Render any pushed scripts #}
    {% stack 'scripts' %}
</body>
</html>
```
### child.html.twig (Child Template)
This template uses the same include for the `component.html.twig` partial twice, each time with different parameters (title and content). This allows you to reuse the same partial for different sections of the page, with different content.

```html
{% extends 'base.html.twig' %}

{% block title %}Child Page Title{% endblock %}

{% block content %}
<h1>Welcome to the Child Page</h1>
<p>This is some content on the child page.</p>

{# Include the same component with different parameters #}
{% include 'partials/component.html.twig' with {'title': 'Section 1', 'content': 'Content for section 1'} %}
{% include 'partials/component.html.twig' with {'title': 'Section 2', 'content': 'Content for section 2'} %}

{% endblock %}
```
### partials/component.html.twig (Component Template)
The partial template includes `{% pushonce %}` directives to ensure that shared styles and scripts are only pushed to the stack once. Even though this partial is included multiple times with different parameters, the `{% pushonce %}` directive prevents duplicate inclusion of the assets.
```html
<div>
    <h2>{{ title }}</h2>
    <p>{{ content }}</p>
</div>

{# Push shared styles, but ensure they are only included once #}
{% pushonce 'styles' %}
<link rel="stylesheet" href="/css/shared-styles.css">
{% endpushonce %}

{# Push shared scripts, but ensure they are only included once #}
{% pushonce 'scripts' %}
<script src="/js/shared-scripts.js"></script>
{% endpushonce %}
```

So in this case, the `styles` and `scripts` stacks will only include the `<link rel="stylesheet" href="/css/shared-styles.css">` and `<script src="/js/shared-scripts.js"></script>` once respectively.


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
