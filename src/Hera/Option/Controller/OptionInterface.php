<?php

namespace GetOlympus\Hera\Option\Controller;

/**
 * Option interface.
 *
 * @package Olympus Hera
 * @subpackage Option\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.2
 *
 */

interface OptionInterface
{
    /**
     * Force add a value into options
     *
     * @param string $option
     * @param string $value
     * @param string $deprecated
     * @param string $autoload
     */
    public static function add($option, $value, $deprecated = '', $autoload = 'no');

    /**
     * Set a value into options
     *
     * @param string $option
     */
    public static function delete($option);

    /**
     * Return a value from options
     *
     * @param string $option
     * @param string $default
     * @param string $item
     * @return mixed $value
     */
    public static function get($option, $default = '', $item = '');

    /**
     * Retrieve field value
     *
     * @param array     $details
     * @param object    $default
     * @param string    $id
     * @param boolean   $multiple
     * @return mixed    $value
     */
    public static function getFieldValue($details, $default, $id = '', $multiple = false);

    /**
     * Force update a value into post options without transient
     *
     * @param string    $post_id
     * @param string    $option
     * @return mixed    $value
     */
    public static function getPostMeta($post_id, $option);

    /**
     * Force update a value into term options without transient
     *
     * @param string    $term_id
     * @param string    $option
     * @param mixed     $default
     * @return mixed    $value
     */
    public static function getTermMeta($term_id, $option, $default = '');

    /**
     * Set a value into options
     *
     * @param string    $option
     * @param string    $value
     * @param string    $type
     * @param integer   $type
     */
    public static function set($option, $value, $type = '', $id = 0);

    /**
     * Force update a value into options without transient
     *
     * @param string $option
     * @param string $value
     */
    public static function update($option, $value);

    /**
     * Force update a value into post options without transient
     *
     * @param string $post_id
     * @param string $option
     * @param string $value
     */
    public static function updatePostMeta($post_id, $option, $value);

    /**
     * Force update a value into term options without transient
     *
     * @param string $term_id
     * @param string $option
     * @param string $value
     */
    public static function updateTermMeta($term_id, $option, $value);
}