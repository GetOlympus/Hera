<?php

namespace GetOlympus\Zeus\Render\Controller;

/**
 * Render interface.
 *
 * @package Olympus Zeus-Core
 * @subpackage Render\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.2
 *
 */

interface RenderInterface
{
    /**
     * Get singleton.
     */
    public static function getInstance();

    /**
     * Render assets on asked page.
     *
     * @param array $currentPage
     * @param array $fields
     */
    public static function assets($currentPage, $fields);

    /**
     * Create temporary asset accessible file.
     *
     * @param string $source
     * @param string $filename
     */
    public static function assetsInCache($source, $filename);

    /**
     * Render TWIG component.
     *
     * @param string $template
     * @param array $vars
     * @param string $context
     */
    public static function view($template, $vars, $context = 'core');
}