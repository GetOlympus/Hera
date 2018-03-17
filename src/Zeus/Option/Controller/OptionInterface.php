<?php

namespace GetOlympus\Zeus\Option\Controller;

/**
 * Option interface.
 *
 * @package Olympus Zeus-Core
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
     * Set a value into options
     *
     * @param string    $option
     * @param string    $value
     * @param string    $type
     * @param integer   $id
     */
    public static function set($option, $value, $type = '', $id = 0);

    /**
     * Force update a value into options
     *
     * @param string $option
     * @param string $value
     */
    public static function update($option, $value);

    /**
     * Clean details on value
     *
     * @param   mixed $value
     * @return  mixed $value
     */
    public static function cleanValue($value);

    /**
     * Retrieve field value
     *
     * @param string    $id
     * @param array     $details
     * @param object    $default
     * @return mixed    $value
     */
    public static function getValue($id, $details, $default);

    /**
     * Get a value from user options
     *
     * @param string    $user_id
     * @param string    $option
     * @return mixed    $value
     */
    public static function getAuthorMeta($user_id, $option);

    /**
     * Force update a value into user options
     *
     * @param string $user_id
     * @param string $option
     * @param string $value
     */
    public static function updateAuthorMeta($user_id, $option, $value);

    /**
     * Get a value from post options
     *
     * @param string    $post_id
     * @param string    $option
     * @return mixed    $value
     */
    public static function getPostMeta($post_id, $option);

    /**
     * Force update a value into post options
     *
     * @param string $post_id
     * @param string $option
     * @param string $value
     */
    public static function updatePostMeta($post_id, $option, $value);

    /**
     * Get a value from term options
     *
     * @param string    $term_id
     * @param string    $option
     * @param mixed     $default
     * @return mixed    $value
     */
    public static function getTermMeta($term_id, $option, $default = '');

    /**
     * Force update a value into term options
     *
     * @param string $term_id
     * @param string $option
     * @param string $value
     */
    public static function updateTermMeta($term_id, $option, $value);
}