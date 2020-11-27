<?php

namespace Twork\Tests;

use Twork\Tests\Fixtures\TestMenu;
use WP_UnitTestCase;

/**
 * Class ThemeFunctionalTest
 *
 * Basic theme functionality test case.
 *
 * @package Twork
 */
class MenuTest extends WP_UnitTestCase
{
    protected $menu;

    public function setUp(): void
    {
        parent::setUp();

        wp_set_current_user(self::factory()->user->create([
            'role' => 'administrator',
        ]));

        update_option( 'siteurl', 'http://example.com' );

        new TestMenu();

        do_action('admin_menu');

        set_current_screen('dashboard');

        global $menu;

        $this->menu = $menu[key($menu)];
    }

    /** @test */
    public function menu_page_exists(): void
    {
        $this->assertNotEmpty(menu_page_url('test-slug', false));
    }

    /** @test */
    public function menu_position(): void
    {
        global $menu;

        $this->assertSame(1, key($menu));
    }

    /** @test */
    public function menu_title(): void
    {
        $this->assertSame('Test Menu Title', $this->menu[0]);
    }

    /** @test */
    public function page_title(): void
    {
        $this->assertSame('Test Page Title', $this->menu[3]);
    }

    /** @test */
    public function capability(): void
    {
        $this->assertSame('test-capability', $this->menu[1]);
    }

    /** @test */
    public function slug(): void
    {
        $this->assertSame('test-slug', $this->menu[2]);
    }

    /** @test */
    public function icon(): void
    {
        $this->assertSame('test-icon', $this->menu[6]);
    }
}
