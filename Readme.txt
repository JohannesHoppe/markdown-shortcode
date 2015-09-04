=== Markdown Shortcode ===
Contributors: JHoppe
Tags: markdown, shortcode, parsedown, highlight.js
Requires at least: 4
Tested up to: 4.3
License: MIT
License URI: http://opensource.org/licenses/MIT

Damn simple [markdown]#via shortcode[/markdown] for wordpress.

== Description ==
Damn simple [markdown]#via shortcode[/markdown] for wordpress.
This plugin uses (parsedown)[http://parsedown.org/] and highlight.js (highlightjs.org). Zero configuration.

Example:

[markdown]
\#h1
\##h2
text
____source code (two ore more underscores will be replaced my empty spaces)
____source code (two ore more underscores will be replaced my empty spaces)

\```javascript
source code
\```
[/markdown]

== Installation ==
1. Upload the `markdown-shortcode` directory to the `/wp-content/plugins` directory.
2. Activate the plugin through the plugins menu in WordPress.
3. Use it by wrapping text in the shortcode

== Frequently Asked Questions ==
= Will it work in the Visual Editor? =

Yes. You can switch between Visual and Text(HTML) mode. The plugin converts &< &> back to the original chaacters.

= The WYSIWYG editor (TinyMCE) is removing empty spaces! =

Replace empty spaces with underscores __ .
They will be converted to empty spaces before markdown conversion.