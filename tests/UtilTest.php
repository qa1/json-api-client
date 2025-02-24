<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Swis\JsonApi\Client\Util;

class UtilTest extends TestCase
{
    public function test_lower()
    {
        $this->assertSame('foo bar baz', Util::stringLower('FOO BAR BAZ'));
        $this->assertSame('foo bar baz', Util::stringLower('fOo Bar bAz'));
    }

    public function test_snake()
    {
        $this->assertSame('laravel_p_h_p_framework', Util::stringSnake('LaravelPHPFramework'));
        $this->assertSame('laravel_php_framework', Util::stringSnake('LaravelPhpFramework'));
        $this->assertSame('laravel php framework', Util::stringSnake('LaravelPhpFramework', ' '));
        $this->assertSame('laravel_php_framework', Util::stringSnake('Laravel Php Framework'));
        $this->assertSame('laravel_php_framework', Util::stringSnake('Laravel    Php      Framework   '));
        // ensure cache keys don't overlap
        $this->assertSame('laravel__php__framework', Util::stringSnake('LaravelPhpFramework', '__'));
        $this->assertSame('laravel_php_framework_', Util::stringSnake('LaravelPhpFramework_', '_'));
        $this->assertSame('laravel_php_framework', Util::stringSnake('laravel php Framework'));
        $this->assertSame('laravel_php_frame_work', Util::stringSnake('laravel php FrameWork'));
        // prevent breaking changes
        $this->assertSame('foo-bar', Util::stringSnake('foo-bar'));
        $this->assertSame('foo-_bar', Util::stringSnake('Foo-Bar'));
        $this->assertSame('foo__bar', Util::stringSnake('Foo_Bar'));
        $this->assertSame('żółtałódka', Util::stringSnake('ŻółtaŁódka'));
    }

    public function test_studly()
    {
        $this->assertSame('LaravelPHPFramework', Util::stringStudly('laravel_p_h_p_framework'));
        $this->assertSame('LaravelPhpFramework', Util::stringStudly('laravel_php_framework'));
        $this->assertSame('LaravelPhPFramework', Util::stringStudly('laravel-phP-framework'));
        $this->assertSame('LaravelPhpFramework', Util::stringStudly('laravel  -_-  php   -_-   framework   '));

        $this->assertSame('FooBar', Util::stringStudly('fooBar'));
        $this->assertSame('FooBar', Util::stringStudly('foo_bar'));
        $this->assertSame('FooBar', Util::stringStudly('foo_bar')); // test cache
        $this->assertSame('FooBarBaz', Util::stringStudly('foo-barBaz'));
        $this->assertSame('FooBarBaz', Util::stringStudly('foo-bar_baz'));
    }

    public function test_array_except()
    {
        $testArray = [
            'first' => 'First',
            'second' => 'second',
            'third' => 'third',
            'fourth' => 'fourth',
        ];
        $this->assertArrayHasKey('first', $testArray);
        $this->assertArrayNotHasKey('first', Util::arrayExcept($testArray, ['first']));
        $this->assertArrayNotHasKey('third', Util::arrayExcept($testArray, ['third']));
        $this->assertArrayNotHasKey('fourth', Util::arrayExcept($testArray, ['second', 'fourth']));
    }
}
