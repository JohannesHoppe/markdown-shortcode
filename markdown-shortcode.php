<?php
/*
Plugin Name: Markdown Shortcode
Description: damn simple [markdown]#via shortcode[/markdown], uses parsedown (parsedown.org) and highlight.js (highlightjs.org)
Version:     0.2.3.2
Author:      Johannes Hoppe
Author URI:  http://haushoppe-its.de
*/

add_action('plugins_loaded', array('Markdown_Shortcode_Plugin', 'get_instance'));

class Markdown_Shortcode_Plugin {

  private static $instance;

  public static function get_instance() {
    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  function __construct() {
    $this->cache = array();
    add_action('init', array(&$this, 'init'));
  }

  function init() {
    add_action('wp_enqueue_scripts', array(&$this, 'init_highlight'));
    add_shortcode('markdown', array(&$this, 'markdown_shortcode'));
    add_filter('the_content', array(&$this, 'markdown_shortcode_preprocess'), 1);
  }

  function init_highlight() {
    wp_enqueue_style("highlight", plugins_url('highlight/styles/github.css', __FILE__));
    wp_enqueue_script("highlight", plugins_url('highlight/highlight.min.js', __FILE__));
    wp_enqueue_script("highlight_init", plugins_url('init_highlight.js', __FILE__));
  }

  function markdown_shortcode($attr, $content = null) {
    require_once('parsedown/Parsedown.php');
    require_once('parsedown/ParsedownExtra.php');
    require_once('parsedown/ParsedownExtended.php');

    if (isset($this->cache[$content])) {
      $content = $this->cache[$content];
    }

    $content = html_entity_decode($content);
    $content = trim($content);
    $content = $this->underscores_to_spaces($content);

    $parser = new  markdown_shortcode\ParsedownExtended();
    $parsed_content = '<div class="markdown">' . $parser->text($content) .'</div>';

    // Sanitize the output to prevent XSS attacks
    // wp_kses_post() removes dangerous HTML tags like <script> while keeping safe HTML
    $parsed_content = wp_kses_post($parsed_content);

    return $parsed_content;
  }

  function markdown_shortcode_pre($attr, $content = null) {
    $key = sha1($content);
    $this->cache[$key] = $content;
    return "[markdown]{$key}[/markdown]";
  }

  function markdown_shortcode_preprocess($content) {
    global $shortcode_tags;

    // Backup current registered shortcodes and clear them all out
    $orig_shortcode_tags = $shortcode_tags;
    $shortcode_tags = array();

    add_shortcode('markdown', array(&$this, 'markdown_shortcode_pre'));

    // Do the shortcode (only the one above is registered)
    $content = do_shortcode($content);

    // Put the original shortcodes back
    $shortcode_tags = $orig_shortcode_tags;
    return $content;
  }

  // Replaces more than one underscore to same amount of spaces
  function underscores_to_spaces($content) {
      $content = preg_replace_callback('/_{2,}/', function ($matches) {
          return str_replace('_', ' ', $matches[0]);
      }, $content);
      return $content;
  }
}
