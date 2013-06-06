<?

/**
 * Plugin Name: Ajax Content Renderer
 * Plugin URI: http://betancourt.us/wordpress/plugins/ajax-content/
 * Description: Detects Ajax requests and returns just the formatted body of the post or page.
 * Version: 1.3.3
 * Author: Betancourt Consulting
 * Author URI: http://betancourt.us/
 * 
 * 
 * LICENSE
 * 
 * Copyright 2009-2013 Betancourt Consulting (consulting@betancourt.us)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
// Block direct requests
if (!defined('ABSPATH'))
	die('-1');

if (!class_exists('BetancourtAjaxContent')) {

	class BetancourtAjaxContent {

		/**
		 * Constructor
		 * @return void
		 */
		function BetancourtAjaxContent() {
			
		}

		function detect($a) {

			if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

				// parse post content 
				foreach ($a as $post) {
					$content = apply_filters('the_content', $post->post_content);
					echo '<h2>' . $post->post_title . '</h2>' . $content;
				}

				exit(0);
			} else {

				// return posts as-is
				return $a;
			}
		}

	}

}

if (class_exists('BetancourtAjaxContent')) {
	$betancourt_ajax_content_plugin = new BetancourtAjaxContent();
}

// action and filters
if (isset($betancourt_ajax_content_plugin)) {
	add_action('the_posts', array(&$betancourt_ajax_content_plugin, 'detect'), 0);
}