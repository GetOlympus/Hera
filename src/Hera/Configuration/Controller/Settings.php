<?php

namespace GetOlympus\Hera\Configuration\Controller;

use GetOlympus\Hera\Configuration\Controller\Configuration;
use GetOlympus\Hera\Render\Controller\Render;
use GetOlympus\Hera\Translate\Controller\Translate;

/**
 * Hera Settings controller
 *
 * @package Olympus Hera
 * @subpackage Configuration\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.2
 *
 */

class Settings extends Configuration
{
    /**
     * @var array
     */
    protected $available = [
        'admin-bar',
        'admin-footer',
        'admin-scripts',
        'admin-styles',
        'admin-menu-order',
        'admin-meta-boxes',
        'clean-assets',
        'clean-headers',
        'comments-fields-order',
        'jpeg-quality',
        'login-shake',
        'login-style',
        'login-urls',
        'shutdown',
    ];

    /**
     * Add all usefull WP filters and hooks.
     */
    public function init()
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
        foreach ($configs as $key => $args) {
            if (!in_array($key, $this->available) || empty($args)) {
                continue;
            }

            $func = Render::toFunction($key).'Setting';
            $this->$func($args);
        }
    }

    /**
     * Copy a file contents from the Hera assets folder to the public dist Olympus assets folder.
     *
     * @param string $oldPath
     * @param string $newPath
     * @param string $filename
     */
    public function _copyAssetFile($oldPath, $newPath, $filename)
    {
        // Check paths
        if ($oldPath === $newPath) {
            return;
        }

        $newFilepath = $newPath.$filename;

        // Check if file exists and create it
        if (file_exists($newFilepath)) {
            return;
        }

        // Build new contents
        $oldFilepath = $oldPath.$filename;
        $ctns = '';

        // Check the old file to copy its contents
        if (file_exists($oldFilepath)) {
            $copy = copy($oldFilepath, $newFilepath);
        } else {
            file_put_contents($newFilepath, "/**\n * This file is auto-generated by the Hera package without any content.\n */\n\n");
        }
    }

    /**
     * Remove some items from WP admin bar.
     *
     * @param array $args
     */
    public function adminBarSetting($args)
    {
        add_action('wp_before_admin_bar_render', function () use ($args){
            global $wp_admin_bar;

            // Iterate on all
            foreach ($args as $item) {
                $wp_admin_bar->remove_menu($item);
            }
        });
    }

    /**
     * Update WP footer copyright.
     *
     * @param string $description
     */
    public function adminFooterSetting($description)
    {
        // Work on description in case of an array
        $desc = is_array($description) ? $description[0] : $description;

        add_filter('admin_footer_text', function () use ($desc){
            echo '<span id="footer-thankyou">'.$desc.'</span>';
        });
    }

    /**
     * Reorder WP admin main menu.
     *
     * @param array $args
     */
    public function adminMenuOrderSetting($args)
    {
        add_filter('custom_menu_order', '__return_true');
        add_filter('menu_order', function ($menu_ord) use ($args){
            return !$menu_ord ? [] : $args;
        });
    }

    /**
     * Remove some admin widgets.
     *
     * @param array $args
     */
    public function adminMetaBoxesSetting($args)
    {
        add_action('wp_dashboard_setup', function () use ($args){
            // Iterate on all
            foreach ($args as $widget) {
                if (!is_array($widget) || 3 !== count($widget)) {
                    continue;
                }

                $plugin = $widget[0];
                $page = $widget[1];
                $column = $widget[2];

                // Remove item
                remove_meta_box($plugin, $page, $column);
            }
        });
    }

    /**
     * Add some admin JS improvements.
     *
     * @param boolean $js
     */
    public function adminScriptsSetting($js)
    {
        if (!$js) {
            return;
        }

        // Copy contents
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'js'.S.'olympus-hera-core.js');

        add_action('admin_enqueue_scripts', function (){
            wp_enqueue_script('olympus-core-js', OLH_URI.'js/olympus-hera-core.js', ['jquery']);
        }, 10);
    }

    /**
     * Add some admin CSS improvements.
     *
     * @param boolean $css
     */
    public function adminStylesSetting($css)
    {
        if (!$css) {
            return;
        }

        // Copy contents
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'css'.S.'olympus-hera-core.css');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'FontAwesome.otf');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'fontawesome-webfont.eot');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'fontawesome-webfont.svg');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'fontawesome-webfont.ttf');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'fontawesome-webfont.woff');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'fonts'.S.'fontawesome-webfont.woff2');

        add_action('admin_enqueue_scripts', function (){
            wp_enqueue_style('olympus-core-css', OLH_URI.'css/olympus-hera-core.css', false);
        }, 10);
    }

    /**
     * Remove assets version.
     *
     * @param boolean $clean
     */
    public function cleanAssetsSetting($clean)
    {
        if (!$clean) {
            return;
        }

        // Remove WP Version from styles
        add_filter('style_loader_src', function ($src){
            return strpos($src, 'ver=') ? remove_query_arg('ver', $src) : $src;
        }, 9999);

        // Remove WP Version from scripts
        add_filter('script_loader_src', function ($src){
            return strpos($src, 'ver=') ? remove_query_arg('ver', $src) : $src;
        }, 9999);
    }

    /**
     * Define what to clean from the theme header frontend, via the "remove_action" hook.
     *
     * @param array $args
     */
    public function cleanHeadersSetting($args)
    {
        $available = [
            'adjacent_posts_rel_link_wp_head',
            'index_rel_link',
            'rsd_link',
            'wlwmanifest_link',
            'wp_admin_bar_init',
            'wp_dlmp_l10n_style',
            'wp_generator',
            'wp_shortlink_wp_head',
        ];

        // Iterate on all
        foreach ($args as $key) {
            if (!in_array($key, $available)) {
                continue;
            }

            if ('wp_admin_bar_init' === $key) {
                add_filter('show_admin_bar', '__return_false');
            } else {
                remove_action('wp_head', $key);
            }
        }
    }

    /**
     * Comment fields in wanted order.
     *
     * @param array $fields
     */
    public function commentsFieldsOrderSetting($fields)
    {
        add_filter('comment_form_fields', function ($comment_fields) use ($fields){
            $new_fields = [];

            // Iterate on fields
            foreach ($fields as $field) {
                if (!isset($comment_fields[$field])) {
                    continue;
                }

                $new_fields[$field] = $comment_fields[$field];
            }

            return $new_fields;
        });
    }

    /**
     * Update JPEG quality of generated images.
     *
     * @param integer $quality
     */
    public function jpegQualitySetting($quality)
    {
        // Work on quality
        $q = (integer) $quality;
        $q = 0 < $q && $q < 100 ? $q : 75;

        // Apply filter hook
        add_filter('jpeg_quality', create_function('', 'return '.$q.';'));
    }

    /**
     * Define wether if WP has to shake the login box or not.
     *
     * @param boolean $shake
     */
    public function loginShakeSetting($shake)
    {
        if ($shake) {
            return;
        }

        add_action('login_head', function (){
            remove_action('login_head', 'wp_shake_js', 12);
        });
    }

    /**
     * Define wether if WP login has to be redesigned or not.
     *
     * @param boolean $style
     */
    public function loginStyleSetting($style)
    {
        if (!$style) {
            return;
        }

        // Copy contents
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'css'.S.'olympus-hera-login.css');
        $this->_copyAssetFile(OLH_HERA_ASSETS, OLH_ASSETS, 'img'.S.'login.jpg');

        // Render assets

        add_action('login_enqueue_scripts', function (){
            wp_enqueue_style('olympus-login', OLH_URI.'css/olympus-hera-login.css', false);
        }, 10);

        add_action('login_enqueue_scripts', function (){
            //wp_enqueue_script('olympus-login', OLH_URI.'js/olympus-login.js', false);
        }, 1);

        // Change login head URL
        add_filter('login_headerurl', function ($url) {
            return OLH_HOME;
        });

        // Change login error message
        add_filter('login_errors', function (){
            return Translate::t('configuration.settings.login.error');
        });
    }

    /**
     * Hiding wp-login.php in the login and registration URLs
     *
     * @param array $args
     */
    public function loginUrlsSetting($args)
    {
        if (!$args) {
            return;
        }

        // Define defaults
        $configs = array_merge([
            'login'         => '',
            'logout'        => '',
            'lostpassword'  => '',
            'register'      => '',
        ], $args);

        // Change login URL
        add_filter('login_redirect', function ($url) use ($configs){
            return empty($configs['login']) ? $url : site_url().$configs['login'];
        });

        // Customize Site URL
        add_filter('site_url', function ($url,$path,$scheme = null) use ($configs){
            $pattern = [
                'login'         => '/wp-login.php',
                'logout'        => '/wp-login.php?action=logout',
                'lostpassword'  => '/wp-login.php?action=lostpassword',
                'register'      => '/wp-login.php?action=register',
            ];

            // Iterate on all queries and replace the current Site URL
            foreach ($pattern as $key => $query) {
                if (empty($configs[$key])) {
                    continue;
                }

                $url = str_replace($query, $configs[$key], $url);
            }

            return $url;
        }, 10, 3);

        // Make the redirection works properly
        add_filter('wp_redirect', function ($url,$status) use ($configs){
            // Check login configuration
            if (empty($configs['login'])) {
                return $url;
            }

            $triggers = [
                'wp-login.php?checkemail=confirm',
                'wp-login.php?checkemail=registered',
            ];

            foreach ($triggers as $trigger) {
                if ($url !== $trigger) {
                    continue;
                }

                return str_replace('wp-login.php', site_url().$configs['login'], $url);
            }

            return $url;
        }, 10, 2);
    }

    /**
     * Define wether if WP has to shut the DB connections off or not.
     *
     * @param array $args
     */
    public function shutdownSetting($args)
    {
        add_action('shutdown', function (){
            global $wpdb;
            unset($wpdb);
        }, 99);
    }
}
