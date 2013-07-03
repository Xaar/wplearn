<?php
/*
   Plugin Name: RomanCartWPPluginStd
   Plugin URI: http://www.davelopware.com/products/romancartwppluginstd/
   Description: Roman Cart WordPress plugin (Std).
   Version: 1.21
   Author: Dave Amphlett (Davelopware Ltd)
   Author URI: http://www.davelopware.com/
*/

/* **********************************************************************************************

   RomanCartWPPluginStd
   Copyright 2010-2012 Davelopware Ltd

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License
   version 2 as published by the Free Software Foundation.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

************************************************************************************************* */

if (!class_exists('RomanCartWPPlugin')) {
	class RomanCartWPPlugin {
		var $name = 'RomanCartWPPlugin';
		var $dirname = 'romancartwppluginstd';
		var $options_tag = 'romancartplugin';
		var $options = array();
		var $shortcode = 'romancart';
		var $settings_page_url = '';

		var $settings_menu_name = 'RomanCart'; // ProTweak - put Pro on the end here
		var $settings_page_title = 'RomanCart Options';

		function RomanCartWPPlugin()
		{
			if ($options = get_option($this->options_tag)) {
				$this->options = $options;
			}

			if (is_admin()) {
				add_action('admin_menu', array(&$this, 'admin_menu'));
				add_action('admin_init', array(&$this, 'admin_init'));
			}
			if ( function_exists('add_shortcode') ) {
			    add_shortcode($this->shortcode, array(&$this, 'shortcode_romancart'));
			}
		}
		function admin_menu()
		{
			add_options_page($this->settings_page_title, $this->settings_menu_name, 10, $this->dirname.'/settings.php');
		}
		function admin_init()
		{
			register_setting($this->options_tag.'_options', $this->options_tag);
		}
		function update_options()
		{
			update_option($this->options_tag, $this->options);
		}

		function get_storeid()
		{
			if (array_key_exists('storeid', $this->options)) {
				return $this->options['storeid'];
			} else {
				return "not set yet";
			}
		}
		function update_storeid($storeid)
		{
			$this->options['storeid'] = $storeid;
			$this->update_options();
		}

		function get_helpimprove()
		{
			if (array_key_exists('helpimprove', $this->options)) {
				return $this->options['helpimprove'];
			} else {
				return 0;
			}
		}
		function update_helpimprove($helpimprove)
		{
			$this->options['helpimprove'] = $helpimprove;
			$this->update_options();
		}

		/**
		* shortcode_romancart - produces and returns the content to replace the shortcode tag
		*
		* @param array $atts       An array of attributes passed from the shortcode [not used]
		* @param string $content   If the shortcode wraps round some html, this is passed [not used]
		*/
		function shortcode_romancart( $atts , $content = null) {
			$storeid = $this->get_storeid();
			extract(shortcode_atts(array(
				'action' => null,
				'itemcode' => null,
				'itemname' => null,
				'price' => null,
				'quantity' => null,
				'align' => 'right',
				'cssclass' => null,
				'text' => null,
				'categoryid' => null,
				'datatype' => null,
				'rddcode' => null,
			), $atts));

			if (!is_array($atts) or !array_key_exists('action', $atts)) {
				return "<b>action parameter missing from shortcode: romancart</b>";
			}

			if ($action == 'addToBasketLink') {
				$result = $this->innershortcode_add_to_basket_link($storeid, $itemcode, $itemname, $price, $quantity, $cssclass, $text);

			} else if ($action == 'addToBasketButton') {
				$result = $this->innershortcode_add_to_basket_button($storeid, $itemcode, $itemname, $price, $quantity, $align);

			} else if ($action == 'viewBasketButton') {
				$result = $this->innershortcode_view_basket_button($storeid, $align);

			} else if ($action == 'viewBasketLink') {
				$result = $this->innershortcode_view_basket_link($storeid, $cssclass, $text);

			} else if ($action == 'cartData') {
				$result = $this->innershortcode_cart_data($storeid, $datatype);

			} else if ($action == 'loadDataForCategory') {
				// StdTweak - return Pro Version required warning
				$result = "<!-- Warning - {$this->name} {$action} - Pro Version required for this action -->";
				//$result = $this->innershortcode_load_data_for_category($storeid, $categoryid);

			} else if ($action == 'showLoadedData') {
				// StdTweak - return Pro Version required warning
				$result = "<!-- Warning - {$this->name} {$action} - Pro Version required for this action -->";
				//$result = $this->innershortcode_show_loaded_data($itemcode, $datatype);

			} else if ($action == 'loadData') {
				// StdTweak - return Pro Version required warning
				$result = "<!-- Warning - {$this->name} {$action} - Pro Version required for this action -->";
				//$result = $this->innershortcode_load_data($storeid, $datatype, $itemcode, $rddcode=null);

			} else {
				$result = "<!-- {$this->name} unknown action {$action} -->";
			}
			return $result;
		}

		function innershortcode_add_to_basket_link($storeid, $itemcode, $itemname, $price, $quantity, $cssclass, $text) {
			$queryStr = "storeid={$storeid}";
			if ($itemcode != null) {
				$queryStr = $queryStr."&itemcode={$itemcode}";
			}
			if ($itemname != null) {
				$queryStr = $queryStr."&itemname={$itemname}";
			}
			if ($price != null) {
				$queryStr = $queryStr."&price={$price}";
			}
			if ($quantity != null) {
				$queryStr = $queryStr."&quantity={$quantity}";
			}
			if ($cssclass != null) {
				$cssclass = "class='{$cssclass}'";

			}
			$result = "<a {$cssclass} href='http://www.romancart.com/cart.asp?{$queryStr}'>{$text}</a>";
			return $result;
		}

		function innershortcode_add_to_basket_button($storeid, $itemcode, $itemname, $price, $quantity, $align){
			$result = "<div style='text-align:{$align}'>";
			$result = $result."<form action='http://www.romancart.com/cart.asp' method='post'>";
			/* if (array_key_exists('itemcode', $atts)) { */
			if ($itemcode != null) {
				$result = $result."<input type='hidden' name='itemcode' value='{$itemcode}'>";
			}
			if ($itemname != null) {
				$result = $result."<input type='hidden' name='itemname' value='{$itemname}'>";
			}
			if ($price != null) {
				$result = $result."<input type='hidden' name='price' value='{$price}'>";
			}
			if ($quantity!= null) {
				$result = $result."<input type='hidden' name='quantity' value='{$quantity}'>";
			}
			$result = $result."<input type='hidden' name='storeid' value='{$storeid}'>";
			$result = $result."<input type='submit' value='Add to Basket'>";
			$result = $result."</form></div>";
			return $result;
		}

		function innershortcode_view_basket_button($storeid, $align){
			$result = "<div style='text-align:{$align}'>";
			$result = $result."<form action='http://www.romancart.com/cart.asp' method='post'>";
			$result = $result."<input type='hidden' name='storeid' value='{$storeid}'>";
			$result = $result."<input type='submit' value='View Basket'>";
			$result = $result."</form></div>";
			return $result;
		}

		function innershortcode_view_basket_link($storeid, $cssclass, $text){
			if ($cssclass != null) {
				$cssclass = "class='{$cssclass}'";
			}
			$result = "<a {$cssclass} href='http://www.romancart.com/cart.asp?storeid={$storeid}'>{$text}</a>";
			return $result;
		}

		function innershortcode_cart_data($storeid, $datatype){
			if ($datatype == 'items') {
				$result = "<SCRIPT LANGUAGE='JavaScript' SRC='http://www.romancart.com/cartinfo.asp?storeid={$storeid}&type=1'></script>";
			} else if ($datatype == 'total') {
				$result = "<SCRIPT LANGUAGE='JavaScript' SRC='http://www.romancart.com/cartinfo.asp?storeid={$storeid}&type=2'></script>";
			} else if ($datatype == 'totalex') {
				$result = "<SCRIPT LANGUAGE='JavaScript' SRC='http://www.romancart.com/cartinfo.asp?storeid={$storeid}&type=3'></script>";
			} else if ($datatype == 'totaltax') {
				$result = "<SCRIPT LANGUAGE='JavaScript' SRC='http://www.romancart.com/cartinfo.asp?storeid={$storeid}&type=4'></script>";
			} else if ($datatype == 'subtotal') {
				$result = "<SCRIPT LANGUAGE='JavaScript' SRC='http://www.romancart.com/cartinfo.asp?storeid={$storeid}&type=5'></script>";
			}
			return $result;
		}


	}
	// ProTweak - always overwrite in case old std version is kicking around
	// StdTweak - never overwrite in case old pro version is installed
	if ($romancartwpplugin == null) {
		$romancartwpplugin = new RomanCartWPPlugin();
	}
}


