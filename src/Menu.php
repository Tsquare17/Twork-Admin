<?php

namespace Twork\Admin;

/**
 * Class Menu
 * @package Twork\Admin
 */
abstract class Menu
{
    /**
     * @var string The title of the page.
     */
    protected $pageTitle;

    /**
     * @var string The title of the menu in the dashboard sidebar.
     */
    protected $menuTitle;

    /**
     * @var string The permission level required to view the page.
     */
    protected $capability = 'manage_options';

    /**
     * @var string The slug of the page.
     */
    protected $menuSlug;

    /**
     * @var string The dashicon string for the icon of the menu item.
     */
    protected $icon = 'dashicons-admin-generic';

    /**
     * @var int The position of the menu item.
     */
    protected $position = 5;

    /**
     * MenuPage constructor.
     */
    public function __construct()
    {
        $this->menuSlug = $this->menuSlug ?? str_replace(' ', '-', strtolower($this->menuTitle));

        add_action('admin_menu', [$this, 'register']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        add_action('admin_init', [$this, 'actions']);
    }

    /**
     * Register the menu page.
     */
    public function register(): void
    {
        add_menu_page(
            $this->pageTitle ?: $this->menuTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            [$this, 'view'],
            $this->icon,
            $this->position
        );

        $this->submenus();
    }

    /**
     * Display for the menu item page.
     */
    abstract public function view();

    /**
     * Actions to run on admin_init.
     */
    public function actions()
    {
    }

    /**
     * Enqueue scripts and styles.
     */
    public function enqueue()
    {
    }

    /**
     * Add a submenu.
     *
     * @param string $pageTitle
     * @param callable $viewCallback
     * @param null $position
     * @param null $menuTitle
     * @param null $capabilities
     * @param null $menuSlug
     */
    protected function addSubmenu(
        string $pageTitle,
        callable $viewCallback,
        $position = null,
        $menuTitle = null,
        $capabilities = null,
        $menuSlug = null
    ) {
        $menuTitle = $menuTitle ?? $pageTitle;

        $capabilities = $capabilities ?? $this->capability;

        $menuSlug = $menuSlug ?? strtolower(str_replace(' ', '-', $menuTitle));

        add_submenu_page($this->menuSlug, $pageTitle, $menuTitle, $capabilities, $menuSlug, $viewCallback, $position);
    }

    /**
     * Register submenus, using $this->addSubmenu();
     */
    abstract public function submenus();
}
