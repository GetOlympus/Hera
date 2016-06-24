<?php

namespace GetOlympus\Hera\Translate\Controller;

use GetOlympus\Hera\Translate\Controller\TranslateInterface;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;

/**
 * Translates typos.
 *
 * @package Olympus Hera
 * @subpackage Translate\Controller
 * @author Achraf Chouk <achrafchouk@gmail.com>
 * @since 0.0.1
 *
 */

class Translate implements TranslateInterface
{
    /**
     * @var Singleton
     */
    private static $instance;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * Constructor.
     */
    public function __construct()
    {
        // Get global local used
        $local = str_replace('-', '_', OLH_LOCAL);

        // Get all available Hera locals
        $availables = [
            'en_EN'
            // Other languages will be available soon!
        ];

        // Check local
        $lang = !in_array($local, $availables) ? $availables[0] : $local;

        // Build all YAML files to add
        $yamls = [
            OLH_PATH.S.'AdminPage'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Configuration'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Field'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Metabox'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Posttype'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Term'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'User'.S.'Resources'.S.'languages' => 'core',
            OLH_PATH.S.'Widget'.S.'Resources'.S.'languages' => 'core',
        ];

        /**
         * Add your custom languages with alias.
         *
         * @param   array $yamls
         * @return  array $yamls
         */
        $yamls = apply_filters('olh_translate_resources', $yamls);

        // Define Translator
        $this->translator = new Translator($lang, new MessageSelector(), OLH_CACHE);
        $this->translator->addLoader('yaml', new YamlFileLoader());

        // Add Hera core languages in `core` dictionary
        foreach ($yamls as $path => $package) {
            $file = $path.S.$lang.'.yaml';
            $this->translator->addResource('yaml', $file, $lang, $package);
        }
    }

    /**
     * Get singleton.
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Choice typo.
     *
     * @param   string  $message
     * @param   integer $number
     * @param   array   $args
     * @param   string  $domain
     * @param   string  $locale
     * @return  string
     */
    public static function c($message, $number, $args = [], $domain = 'core', $locale = 'en_EN')
    {
        return self::getInstance()->translator->transChoice($message, $number, $args, $domain, $locale);
    }

    /**
     * Noop typo from WordPress.
     *
     * @param   string $singular
     * @param   string $plural
     * @return  string
     */
    public static function n($singular, $plural)
    {
        return _n_noop($singular, $plural);
    }

    /**
     * Translate typo.
     *
     * @param   string  $message
     * @param   array   $args
     * @param   string  $domain
     * @param   string  $locale
     * @return  Translate
     */
    public static function t($message, $args = [], $domain = 'core', $locale = 'en_EN')
    {
        return self::getInstance()->translator->trans($message, $args, $domain, $locale);
    }
}
