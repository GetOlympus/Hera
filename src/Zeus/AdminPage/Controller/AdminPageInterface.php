<?php

namespace GetOlympus\Zeus\AdminPage\Controller;

/**
 * AdminPage interface.
 *
 * @package Olympus Zeus-Core
 * @subpackage AdminPage\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.7
 *
 */

interface AdminPageInterface
{
    /**
     * Build AdminPageModel and initialize admin pages.
     */
    public function init();

    /**
     * Initialize assets in admin pages.
     */
    public function initAssets();

    /**
     * Add root admin page.
     */
    public function addRootPage();

    /**
     * Add root admin bar page.
     */
    public function addRootAdminBar();

    /**
     * Add child admin page.
     *
     * @param string    $slug
     * @param array     $options
     */
    public function addChild($slug, $options);

    /**
     * Add child admin bar page.
     *
     * @param string    $slug
     * @param array     $options
     */
    public function addChildAdminBar($slug, $options);

    /**
     * Hook callback.
     */
    public function callback();
}