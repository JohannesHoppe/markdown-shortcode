<?php
/*
Plugin Name: Markdown Shortcode
Description: damn simple [markdown]#via shortcode[/markdown], uses parsedown (parsedown.org) and highlight.js (highlightjs.org)
Version:     0.1.2
Author:      Johannes Hoppe
Author URI:  http://haushoppe-its.de
*/

include('parsedown/Parsedown.php');
include('parsedown/ParsedownExtra.php');

function markdown_shortcode($attr, $content = null) {

    $content = undo_html_entities($content);
    $content = trim($content);
    $content = underscores_to_spaces($content);

    $extra = new markdown_shortcode\ParsedownExtra();
    $parsed_content = $extra->text($content);
    
    return $parsed_content;
}
add_shortcode('markdown', 'markdown_shortcode');

// Reverts changes that were applied by the Visual editor
function undo_html_entities($content){
    $content = str_replace("&lt;", "<", $content);
    $content = str_replace("&gt;", ">", $content);
    $content = str_replace("&amp;", "&", $content);
    return $content;
}

// Replaces more than one underscore to same amount of spaces
function underscores_to_spaces($content) {
    $content = preg_replace_callback('/_{2,}/', function ($matches) {
        return str_replace('_', ' ', $matches[0]);
    }, $content);
    return $content;
}

function init_highlight() {
    wp_enqueue_style("highlight",  plugin_dir_url(__FILE__) . 'highlight/styles/github.css');
    wp_enqueue_script("highlight",  plugin_dir_url(__FILE__) . 'highlight/highlight.min.js');
    wp_enqueue_script("highlight_init",  plugin_dir_url(__FILE__) . 'init_highlight.js');
}
add_action('init', 'init_highlight');


// Removing wpautop() To Content Only In Shortcodes
//
// We first remove the wpautop() function and then re-add it with a different priority,
// now we can apply the shortcode_unautop() after the wpautop() function.
// Now all the code inside of the shortcodes will not have a trailing paragraph tag and
// you will no longer have line breaks at the end of your code snippets.
// --> as seen here http://www.paulund.co.uk/remove-line-breaks-in-shortcodes
remove_filter( 'the_content', 'wpautop' );
add_filter('the_content', 'wpautop' , 99);
add_filter('the_content', 'shortcode_unautop',100 );

// Stop WordPress converting quotes to pretty quotes (nobody will miss them)
remove_filter('the_content', 'wptexturize');


