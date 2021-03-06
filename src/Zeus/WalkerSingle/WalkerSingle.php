<?php

namespace GetOlympus\Zeus\WalkerSingle;

use GetOlympus\Zeus\WalkerSingle\WalkerSingleInterface;

/**
 * Gets its own Walker.
 *
 * @package    OlympusZeusCore
 * @subpackage WalkerSingle
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 *
 */

if (!class_exists('Walker')) {
    include_once ABSPATH.'wp-includes'.S.'class-wp-walker.php';
}

class WalkerSingle extends \Walker implements WalkerSingleInterface
{
    /**
     * @var string
     */
    public $tree_type = 'category';

    /**
     * @var array
     */
    public $db_fields = [
        'id'     => 'term_id',
        'parent' => 'parent',
    ];

    /**
     * Starts the list before the elements are added.
     *
     * @param  string  $output
     * @param  int     $depth
     * @param  array   $args
     */
    public function start_lvl(&$output, $depth = 0, $args = []) // phpcs:ignore
    {
        $indent = str_repeat("\t", $depth);
        $output .= $indent.'<ul class="children">'."\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @param  string  $output
     * @param  int     $depth
     * @param  array   $args
     */
    public function end_lvl(&$output, $depth = 0, $args = []) // phpcs:ignore
    {
        $indent = str_repeat("\t", $depth);
        $output .= $indent.'</ul>'."\n";
    }

    /**
     * Start the element output.
     *
     * @param  string  $output
     * @param  object  $category
     * @param  int     $depth
     * @param  array   $args
     * @param  int     $id
     */
    public function start_el(&$output, $category, $depth = 0, $args = [], $id = 0) // phpcs:ignore
    {
        $taxonomy = empty($args['taxonomy']) ? 'category' : $args['taxonomy'];
        $name = 'category' === $taxonomy ? 'post_category' : 'tax_input['.$taxonomy.']';

        $args['popular_cats'] = empty($args['popular_cats']) ? [] : $args['popular_cats'];
        $class = in_array($category->term_id, $args['popular_cats']) ? ' class="popular-category"' : '';
        $args['selected_cats'] = empty($args['selected_cats']) ? [] : $args['selected_cats'];

        if (!empty($args['list_only'])) {
            $aria_cheched = 'false';
            $inner_class = 'category';

            if (in_array($category->term_id, $args['selected_cats'])) {
                $inner_class .= ' selected';
                $aria_cheched = 'true';
            }

            /**
             * @see wp-includes/category-template.php
             */
            $output .= "\n".'<li'.$class.'>';
            $output .= '<div class="'.$inner_class.'" data-term-id='.$category->term_id.' tabindex="0" ';
            $output .= 'role="checkbox" aria-checked="'.$aria_cheched.'">'.esc_html($category->name).'</div>';
        } else {
            /**
             * @see wp-includes/category-template.php
             */
            $output .= "\n".'<li id="'.$taxonomy.'-'.$category->term_id.'"'.$class.'>';
            $output .= '<label class="selectit"><input value="'.$category->term_id.'" type="radio" ';
            $output .= 'name="'.$name.'" id="in-'.$taxonomy.'-'.$category->term_id.'"';
            $output .= checked(in_array($category->term_id, $args['selected_cats']), true, false);
            $output .= disabled(empty($args['disabled']), false, false);
            $output .= ' /> '.esc_html($category->name).'</label>';
        }
    }

    /**
     * Ends the element output, if needed.
     *
     * @param  string  $output
     * @param  object  $category
     * @param  int     $depth
     * @param  array   $args
     */
    public function end_el(&$output, $category, $depth = 0, $args = []) // phpcs:ignore
    {
        $output .= '</li>'."\n";
    }
}
