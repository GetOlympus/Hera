<?php

namespace GetOlympus\Hera\Field\Controller;

/**
 * Field interface.
 *
 * @package Olympus Hera
 * @subpackage Field\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.2
 *
 */

interface FieldInterface
{
    /**
     * Build Field component.
     *
     * @param string    $id
     * @param array     $contents
     * @param array     $details
     */
    public static function build($id, $contents = [], $details = []);

    /**
     * Retrieve field value
     *
     * @param string $id
     * @param array $details
     * @param object $default
     *
     * @return string|integer|array|object|boolean|null
     */
    public static function getValue($id, $details, $default);

    /**
     * Render HTML component.
     *
     * @param array $details
     * @param boolean $renderView
     * @param string $context
     */
    public function render($details = [], $renderView = true, $context = 'field');

    /**
     * Define the right template to extend.
     *
     * @param   string  $template
     * @return  string  $extend_template
     */
    public function setExtendedTemplate($template = 'page');
}
