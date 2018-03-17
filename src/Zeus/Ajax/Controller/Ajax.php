<?php

namespace GetOlympus\Zeus\Ajax\Controller;

use GetOlympus\Zeus\Ajax\Controller\AjaxInterface;
use GetOlympus\Zeus\Ajax\Model\AjaxModel;
use GetOlympus\Zeus\Base\Controller\Base;
use GetOlympus\Zeus\Hook\Controller\Hook;

/**
 * Gets its own ajax call.
 *
 * @package Olympus Zeus-Core
 * @subpackage Ajax\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.1
 *
 */

abstract class Ajax extends Base implements AjaxInterface
{
    /**
     * @var Hook
     */
    protected $hook_connected;

    /**
     * @var Hook
     */
    protected $hook_disconnected;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->model = new AjaxModel();

        // Initialize
        $this->setVars();
        $this->init();
    }

    /**
     * Initialization.
     */
    public function init()
    {
        // Build vars
        $args = $this->getModel()->getArgs();
        $handle = $this->getModel()->getHandle();
        $name = $this->getModel()->getName();

        // Check name and callback
        if (empty($handle) || empty($name)) {
            return;
        }

        // Work on args
        $args = !empty($args) ? $args : [];
        $args = is_array($args) ? $args : [$args];

        // Build options to pass through action
        $options = [
            'args'      => array_merge([
                'action'    => $name,
                'nonce'     => wp_create_nonce($name),
                'url'       => admin_url('admin-ajax.php'),
            ], $args),
            'handle'    => $handle,
            'name'      => $name,
        ];

        // Enqueue scripts
        $this->enqueueScript($options);
    }

    /**
     * Hooks and enqueue script.
     *
     * @param array $options the options
     */
    public function enqueueScript($options)
    {
        // Connected hook
        add_action('wp_ajax_'.$options['name'], [&$this, 'hookCallback']);

        // Disconnected hook
        add_action('wp_ajax_nopriv_'.$options['name'], [&$this, 'hookCallback']);

        // Action
        wp_localize_script($options['handle'], $options['name'], $options['args']);
    }

    /**
     * Hook callback method.
     */
    public function hookCallback()
    {
        $name = $this->getModel()->getName();

        // Check Ajax referer
        check_ajax_referer($name, 'nonce');

        // Call custom callback function
        $data = $this->callback();

        // Build JSON data
        wp_send_json_success($data);

        // Stops everything
        wp_die();
    }

    /**
     * Callback custom function.
     */
    abstract public function callback();

    /**
     * Prepare variables.
     */
    abstract public function setVars();
}