<?php

namespace AmpedWeb\TwigStackExtension\Tests;

use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use AmpedWeb\TwigStackExtension\Environment;
use AmpedWeb\TwigStackExtension\StackExtension;
use AmpedWeb\TwigStackExtension\TokenParser\StackManager;

class PushStackTest extends TestCase
{
    private function assertSubstringCount(string $needle, string $haystack, int $expectedCount): void
    {
        $this->assertEquals($expectedCount, substr_count($haystack, $needle), "The string $needle did not occur the expected number of times in $haystack");
    }

    #[Test]
    public function can_push_html_onto_named_stack_in_extended_template(): void
    {
        // Arrange
        $templateToExtend = '
            {% stack teststack %}
            {% block content %}{% endblock %}
        ';
        $childTemplate = "
            {% extends 'base.twig' %}
            {% block content %}
            {% push teststack %}
                <script src='test1.js'></script>
                <script src='test2.js'></script>
            {% endpush %}
            {% endblock %}
        ";
        $twig = $this->setupTwigEnvironmentWithExtension([
            'base.twig' => $templateToExtend,
            'child.twig' => $childTemplate,
        ]);

        // Act
        $html = $twig->render('child.twig');

        // Assert
        $this->assertStringContainsString("<script src='test1.js'></script>", $html);
        $this->assertStringContainsString("<script src='test2.js'></script>", $html);
    }

    #[Test]
    public function push_once_only_displays_once_instance_of_html_in_stack(): void
    {
        // Arrange
        $templateToExtend = '
            {% stack teststack %}
            {% block content %}{% endblock %}
        ';
        $childTemplate = "
            {% extends 'base.twig' %}
            {% block content %}
                {% pushonce teststack %}
                    <script src='test1.js'></script>
                    <script src='test2.js'></script>
                {% endpushonce %}
                {% pushonce teststack %}
                    <script src='test1.js'></script>
                    <script src='test2.js'></script>
                {% endpushonce %}
            {% endblock %}
        ";
        $twig = $this->setupTwigEnvironmentWithExtension([
            'base.twig' => $templateToExtend,
            'child.twig' => $childTemplate,
        ]);

        // Act
        $html = $twig->render('child.twig');

        // Assert
        //Do we only have one instance of the html being pushed?
        $this->assertSubstringCount("<script src='test1.js'></script>", $html, 1);
        $this->assertSubstringCount("<script src='test2.js'></script>", $html, 1);
    }

    #[Test]
    public function push_throws_syntax_error_when_missing_stack_name(): void
    {
        //Assert
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Expected push to define a stack name. Unexpected token "end of statement block" ("name" expected) in "child.twig" at line 4.');
        // Arrange
        $templateToExtend = '
            {% stack teststack %}
            {% block content %}{% endblock %}
        ';
        $childTemplate = "
            {% extends 'base.twig' %}
            {% block content %}
                {% push %}
                {% endpush %}
            {% endblock %}
        ";
        $twig = $this->setupTwigEnvironmentWithExtension([
            'base.twig' => $templateToExtend,
            'child.twig' => $childTemplate,
        ]);
        // Act
        $twig->render('child.twig');
    }

    #[Test]
    public function pushonce_throws_syntax_error_when_missing_stack_name(): void
    {
        //Assert
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage('Expected pushonce to define a stack name. Unexpected token "end of statement block" ("name" expected) in "child.twig" at line 4.');
        // Arrange
        $templateToExtend = '
            {% stack teststack %}
            {% block content %}{% endblock %}
        ';
        $childTemplate = "
            {% extends 'base.twig' %}
            {% block content %}
                {% pushonce %}
                {% endpushonce %}
            {% endblock %}
        ";
        $twig = $this->setupTwigEnvironmentWithExtension([
            'base.twig' => $templateToExtend,
            'child.twig' => $childTemplate,
        ]);
        // Act
        $twig->render('child.twig');
    }

    #[Test]
    public function stack_with_no_content_does_not_leave_placeholder_behind(): void
    {
        // Arrange
        $templateToExtend = '
            <!--TestStackStart-->
            {% stack teststack %}
            <!--TestStackEnd-->
            {% block content %}{% endblock %}
        ';
        $childTemplate = "
            {% extends 'base.twig' %}
            {% block content %}

            {% endblock %}
        ";
        $twig = $this->setupTwigEnvironmentWithExtension([
            'base.twig' => $templateToExtend,
            'child.twig' => $childTemplate,
        ]);

        // Act
        $html = preg_replace('/\s+/', '', $twig->render('child.twig'));

        // Assert
        //Make sure no placeholder is left behind
        $this->assertEquals('<!--TestStackStart--><!--TestStackEnd-->', $html);
    }

    protected function setupTwigEnvironmentWithExtension(array $loaderConfig): Environment
    {
        $twig = new Environment(new ArrayLoader($loaderConfig), ['debug' => true]);
        $twig->addExtension(new StackExtension(new StackManager()));

        return $twig;
    }
}
