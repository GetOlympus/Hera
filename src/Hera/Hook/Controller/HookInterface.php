<?php

namespace GetOlympus\Hera\Hook\Controller;

/**
 * Hook interface.
 *
 * @package Olympus Hera
 * @subpackage Hook\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.2
 *
 */

interface HookInterface
{
    /**
     * Initialize all data.
     *
     * @param string $type
     * @param string $identifier
     * @param string|array $callback
     * @param mixed $priority
     */
    public function initialize($type, $identifier, $callback, $priority = 10);

    /**
     * Hook method.
     */
    public function callback();

    /**
     * Define hook.
     *
     * @param null|mixed $args
     * @return void
     */
    public function listen($args = null);

    /**
     * Execute hook action/filter.
     *
     * @return void
     */
    public function run();
}