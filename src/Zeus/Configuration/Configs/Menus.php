<?php

namespace GetOlympus\Zeus\Configuration\Configs;

use GetOlympus\Zeus\Configuration\Configuration;

/**
 * Menus configuration controller
 *
 * @package    OlympusZeusCore
 * @subpackage Configuration\Configs
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.2
 *
 */

class Menus extends Configuration
{
    /**
     * Add all usefull WP filters and hooks.
     */
    public function init() : void
    {
        // Check filepath
        if (empty($this->filepath)) {
            return;
        }

        // Get configurations
        $configs = include $this->filepath;

        // Check
        if (empty($configs)) {
            return;
        }

        // Iterate on configs
        foreach ($configs as $key => $description) {
            $this->addMenu($key, $description);
        }
    }

    /**
     * Register sidebar to WP.
     *
     * @param  string  $key
     * @param  string  $description
     */
    protected function addMenu($key, $description) : void
    {
        // Check description
        $description = empty($description) ? $key : $description;

        // Register menu navigation
        register_nav_menu($key, $description);
    }
}