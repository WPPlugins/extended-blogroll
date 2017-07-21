<?php
/**
 * Plugin Name:	Extended Blogroll
 * Description:	Displays the recent posts of your blogroll links via RSS Feeds in a customizable sidebar widget. It also provides a shortcode &#91;blogroll&#93; to show the blogroll where ever you want to. (based on Blogroll Widget with RSS Feeds by Crazy Girl)
 * Plugin URI:	http://blog.ppfeufer.de/wordpress-plugin-extended-blogroll/
 * Version:	1.2
 * Author:	H.-Peter Pfeufer
 * Author URI:	http://ppfeufer.de/
 */

/**
 * LICENSE
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
 */

define('EXTENDED_BLOGROLL_DONATE_FLATTR_LINK', 'https://flattr.com/thing/96891/WordPress-Plugin-Extended-Blogroll');

class Blogroll_Widget_RSS extends WP_Widget {
	function Blogroll_Widget_RSS() {
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('extended_blogroll', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages', dirname(plugin_basename(__FILE__)) . '/languages');
		}

		$widget_ops = array(
			'classname' => 'blogroll_widget_rss',
			'description' => __('The recent posts of your blogroll links', 'extended_blogroll')
		);
		$control_ops = array(
			'width' => 400
		);

		$this->WP_Widget('blogroll_widet_rss', 'Blogroll Widget with RSS Feeds', $widget_ops, $control_ops);
	}

	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		$title = (empty($instance['title'])) ? '' : apply_filters('widget_title', $instance['title']);
		if(!empty($title)) {
			echo $before_title . $title . $after_title;
		};

		$category = (isset($instance['category'])) ? $instance['category'] : false;
		$item_order = (empty($instance['item_order'])) ? 'link_name ASC' : $instance['item_order'];
		$show_image = (empty($instance['show_image'])) ? 'show-no-images' : $instance['show_image'];
		$show_link = ($instance['show_link']) ? '1' : '0';
		$show_link_nf = ($instance['show_link_nf']) ? '1' : '0';
		$shorten_feedlink = ($instance['shorten_feedlink']) ? '1' : '0';
		$feed_link_nf = ($instance['show_link_nf']) ? '1' : '0';
		$show_summary = ($instance['show_summary']) ? '1' : '0';
		$donate = ($instance['donate']) ? '1' : '0';

		if(!$show_items = (int) $instance['show_items']) {
			$show_items = -1;
		} elseif($show_items < -1) {
			$show_items = -1;
		}

		if(!$thumb_size = (int) $instance['thumb_size']) {
			$thumb_size = 50;
		} elseif($thumb_size < 10) {
			$thumb_size = 50;
		}

		if(!$feed_items = (int) $instance['feed_items']) {
			$feed_items = 1;
		} elseif($feed_items > 10) {
			$feed_items = 10;
		} elseif($feed_items < 1) {
			$feed_items = 1;
		}

		if(!$s_f_length = (int) $instance['s_f_length']) {
			$s_f_length = 20;
		} elseif($s_f_length < 1) {
			$s_f_length = 1;
		}

		if(!$summary_length = (int) $instance['summary_length']) {
			$summary_length = 100;
		} elseif($summary_length < 10) {
			$summary_length = 10;
		} elseif($summary_length > 999) {
			$summary_length = 999;
		}

//		show_blogroll_widget_rss($instance);
		echo extended_blogroll_output($instance, 'widget');

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array(
			'title' => '',
			'show_items' => -1,
			'category' => false,
			'item_order' => 'link_name ASC',
			'show_image' => 'show-no-images',
			'thumb_size' => 50,
			'show_link' => 0,
			'show_link_nf' => 0,
			'feed_items' => 1,
			'shorten_feedlink' => 0,
			's_f_length' => 20,
			'feed_link_nf' => 0,
			'show_summary' => 0,
			'summary_length' => 100,
			'donate' => 0
		));

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['show_items'] = (int) $new_instance['show_items'];
		$instance['category'] = intval($new_instance['category']);
		$instance['item_order'] = htmlspecialchars($new_instance['item_order']);
		$instance['show_image'] = htmlspecialchars($new_instance['show_image']);
		$instance['thumb_size'] = (int) $new_instance['thumb_size'];
		$instance['show_link'] = $new_instance['show_link'] ? 1 : 0;
		$instance['show_link_nf'] = $new_instance['show_link_nf'] ? 1 : 0;
		$instance['feed_items'] = (int) $new_instance['feed_items'];
		$instance['shorten_feedlink'] = $new_instance['shorten_feedlink'] ? 1 : 0;
		$instance['s_f_length'] = (int) $new_instance['s_f_length'];
		$instance['feed_link_nf'] = $new_instance['feed_link_nf'] ? 1 : 0;
		$instance['show_summary'] = $new_instance['show_summary'] ? 1 : 0;
		$instance['summary_length'] = (int) $new_instance['summary_length'];
		$instance['donate'] = $new_instance['donate'] ? 1 : 0;

		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array(
			'title' => '',
			'show_items' => -1,
			'category' => false,
			'item_order' => 'link_name ASC',
			'show_image' => 'show-no-images',
			'show_link' => 0,
			'thumb_size' => 50,
			'show_link_nf' => 0,
			'feed_items' => 1,
			'shorten_feedlink' => 0,
			's_f_length' => 20,
			'feed_link_nf' => 0,
			'show_summary' => 0,
			'summary_length' => 100,
			'donate' => 0
		));

		$title = strip_tags($instance['title']);
		$link_cats = get_terms('link_category');
		$item_order = htmlspecialchars($instance['item_order']);
		$show_image = htmlspecialchars($instance['show_image']);
		$show_link = $instance['show_link'] ? 'checked="checked"' : '';
		$show_link_nf = $instance['show_link_nf'] ? 'checked="checked"' : '';
		$shorten_feedlink = $instance['shorten_feedlink'] ? 'checked="checked"' : '';
		$feed_link_nf = $instance['feed_link_nf'] ? 'checked="checked"' : '';
		$show_summary = $instance['show_summary'] ? 'checked="checked"' : '';
		$donate = $instance['donate'] ? 'checked="checked"' : '';

		if(!$show_items = (int) $instance['show_items']) {
			$show_items = -1;
		} elseif($show_items < -1) {
			$show_items = -1;
		}

		if(!$thumb_size = (int) $instance['thumb_size']) {
			$thumb_size = 50;
		} elseif($thumb_size < 10) {
			$thumb_size = 50;
		}

		if(!$feed_items = (int) $instance['feed_items']) {
			$feed_items = 1;
		} elseif($feed_items > 10) {
			$feed_items = 10;
		} elseif($feed_items < 1) {
			$feed_items = 1;
		}

		if(!$s_f_length = (int) $instance['s_f_length']) {
			$s_f_length = 20;
		} elseif($s_f_length < 1) {
			$s_f_length = 1;
		}

		if(!$summary_length = (int) $instance['summary_length']) {
			$summary_length = 100;
		} elseif($summary_length < 10) {
			$summary_length = 10;
		} elseif($summary_length > 999) {
			$summary_length = 999;
		}

		echo '<p><small>' . __('Make sure, that you have entered the right RSS Addresses to your links in the Links Subpanel. Otherwise this plugin will not work correctly. No item is shown when a wrong or no RSS Address is entered!', 'extended_blogroll') . '</small></p>';
		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Widget Settings:', 'extended_blogroll') . '</strong></p>';

		echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title:', 'extended_blogroll') . '</label>';
		echo '<input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $instance['title'] . '" size="50" /></p>';

		echo '<p><label for="' . $this->get_field_id('show_items') . '">' . __('Display items:', 'extended_blogroll') . '</label>';
		echo '<input id="' . $this->get_field_id('show_items') . '" name="' . $this->get_field_name('show_items') . '" type="text" value="' . $show_items . '" size="3" />';
		echo '<span class="description"><small>' . __('(-1 will display all items)', 'extended_blogroll') . '</small></span></p>';

		echo '<p>' . __('Display items from link category:', 'extended_blogroll');
		echo '<label for="' . $this->get_field_id('category') . '" class="screen-reader-text">' . __('Select Link Category', 'extended_blogroll') . '</label>';
		echo '<select style="width: 150px;" id="' . $this->get_field_id('category') . '" name="' . $this->get_field_name('category') . '">';
		echo '<option value="">' . __('All Links', 'extended_blogroll') . '</option>';
		foreach($link_cats as $link_cat) {
			echo '<option value="' . intval($link_cat->term_id) . '"' . ($link_cat->term_id == $instance['category'] ? ' selected="selected"' : '') . '>' . $link_cat->name . "</option>\n";
		}
		echo '</select></p>';

		$array_EntrysortOtpions = array(
			'link_name ASC' => array(
				'selection' => ($item_order === 'link_name ASC') ? ' selected="selected"' : '',
				'translation' => __('Link Name Ascending', 'extended_blogroll')
			),
			'link_name DESC' => array(
				'selection' => ($item_order === 'link_name DESC') ? ' selected="selected"' : '',
				'translation' => __('Link Name Descending', 'extended_blogroll')
			),
			'link_id ASC' => array(
				'selection' => ($item_order === 'link_id ASC') ? ' selected="selected"' : '',
				'translation' => __('Link ID Ascending', 'extended_blogroll')
			),
			'link_id DESC' => array(
				'selection' => ($item_order === 'link_id DESC') ? ' selected="selected"' : '',
				'translation' => __('Link ID Descending', 'extended_blogroll')
			),
			'rand()' => array(
				'selection' => ($item_order === 'rand()') ? ' selected="selected"' : '',
				'translation' => __('Random Order', 'extended_blogroll')
			)
		);
		echo '<p>' . __('Item order:', 'extended_blogroll');
		echo '<label for="' . $this->get_field_id('item_order') . '" class="screen-reader-text">' . __('Select Item Order', 'extended_blogroll') . '</label>';
		echo '<select id="' . $this->get_field_id('item_order') . '" name="' . $this->get_field_name('item_order') . '">';
		foreach($array_EntrysortOtpions as $arraykey => $arrayvalue) {
			echo '<option value="' . $arraykey . '"' . $arrayvalue['selection'] . '>' . $arrayvalue['translation'] . '</option>';
		}
		echo '</select>';
		echo '<span class="description" style="display:block; padding-left:10px;">';
		echo '<small>' . __('("Random Order" is recommended for less than all items)', 'extended_blogroll') . '</small>';
		echo '</span></p>';

		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Items Configuration:', 'extended_blogroll') . '</strong></p>';

		echo '<div>';
		$array_LinkImageOtpions = array(
			'show-no-images' => array(
				'selection' => ($show_image === 'show-no-images') ? ' selected="selected"' : '',
				'translation' => __('Show no images', 'extended_blogroll')
			),
			'show-my-own-images' => array(
				'selection' => ($show_image === 'show-my-own-images') ? ' selected="selected"' : '',
				'translation' => __('Show my own images', 'extended_blogroll')
			),
			'create-thumbnails' => array(
				'selection' => ($show_image === 'create-thumbnails') ? ' selected="selected"' : '',
				'translation' => __('Create and show thumbnails', 'extended_blogroll')
			)
		);
		echo '<p>';
		echo __('Show link images:', 'extended_blogroll');
		echo '<label for="' . $this->get_field_id('show_image') . '" class="screen-reader-text">' . __('Select if you want to show images', 'extended_blogroll') . '</label>';
		echo '<select id="' . $this->get_field_id('show_image') . '" name="' . $this->get_field_name('show_image') . '">';
		foreach($array_LinkImageOtpions as $arraykey => $arrayvalue) {
			echo '<option value="' . $arraykey . '"' . $arrayvalue['selection'] . '>' . $arrayvalue['translation'] . '</option>';
		}
		echo '</select>';
		echo '</p>';
		echo '<p style="padding-left: 25px;">';
		echo '<input id="' . $this->get_field_id('thumb_size') . '" name="' . $this->get_field_name('thumb_size') . '" type="text" value="' . $thumb_size . '" size="3" />';
		echo '<label for="' . $this->get_field_id('thumb_size') . '">' . __('Pixel (image size)', 'extended_blogroll') . '</label>';
		echo '</p>';
		echo '</div>';

		echo '<div><p style="margin-left: 10px; margin-right: 10px; border-bottom: 1px dotted #DFDFDF;"></p></div>';

		echo '<div style="float:left; width:55%;"><p>';
		echo '<input class="checkbox" type="checkbox" ' . $show_link . ' id="' . $this->get_field_id('show_link') . '" name="' . $this->get_field_name('show_link') . '" />';
		echo '<label for="' . $this->get_field_id('show_link') . '">' . __('Show blogroll links ?', 'extended_blogroll') . '</label>';
		echo '</p></div>';
		echo '<div style="float:left; width:45%;"><p>';
		echo '<input class="checkbox" type="checkbox" ' . $show_link_nf . ' id="' . $this->get_field_id('show_link_nf') . '" name="' . $this->get_field_name('show_link_nf') . '" />';
		echo '<label for="' . $this->get_field_id('show_link_nf') . '">' . __('Add rel="nofollow" ?', 'extended_blogroll') . '</label>';
		echo '</p></div>';

		echo '<div><p style="margin-left: 10px; margin-right: 10px; border-bottom: 1px dotted #DFDFDF; clear:both;"></p></div>';

		echo '<div style="float:left; width:55%;"><p>';
		echo '<label for="' . $this->get_field_id('feed_items') . '">' . __('Display feed post links:', 'extended_blogroll') . '</label>';
		echo '<input id="' . $this->get_field_id('feed_items') . '" name="' . $this->get_field_name('feed_items') . '" type="text" value="' . $feed_items . '" size="2" />';
		echo '<span class="description"><small>' . __('(between 1 and 10)', 'extended_blogroll') . '</small></span>';
		echo '</p></div>';
		echo '<div style="float:left; width:45%;"><p>';
		echo '<input class="checkbox" type="checkbox" ' . $feed_link_nf . ' id="' . $this->get_field_id('feed_link_nf') . '" name="' . $this->get_field_name('feed_link_nf') . '" />';
		echo '<label for="' . $this->get_field_id('feed_link_nf') . '">' . __('Add rel="nofollow" ?', 'extended_blogroll') . '</label>';
		echo '</p></div>';

		echo '<div style="float:left; width:55%; clear:both;"><p>';
		echo '<input class="checkbox" type="checkbox" ' . $shorten_feedlink . ' id="' . $this->get_field_id('shorten_feedlink') . '" name="' . $this->get_field_name('shorten_feedlink') . '" />';
		echo '<label for="' . $this->get_field_id('shorten_feedlink') . '">' . __('Shorten feed post link text ?', 'extended_blogroll') . '</label>';
		echo '</p></div>';
		echo '<div style="float:left; width:45%;"><p>';
		echo '<label for="' . $this->get_field_id('s_f_length') . '">' . __('shorten to', 'extended_blogroll') . '</label>';
		echo '<input id="' . $this->get_field_id('s_f_length') . '" name="' . $this->get_field_name('s_f_length') . '" type="text" value="' . $s_f_length . '" size="2" /> ' . __('characters', 'extended_blogroll');
		echo '</p></div>';

		echo '<div><p style="margin-left: 10px; margin-right: 10px; border-bottom: 1px dotted #DFDFDF; clear:both;"></p></div>';

		echo '<div style="float:left; width:55%; clear:both;"><p>';
		echo '<input class="checkbox" type="checkbox" ' . $show_summary . ' id="' . $this->get_field_id('show_summary') . '" name="' . $this->get_field_name('show_summary') . '" />';
		echo '<label for="' . $this->get_field_id('show_summary') . '">' . __('Show feed post excerpts ?', 'extended_blogroll') . '</label>';
		echo '</p></div>';
		echo '<div style="float:left; width:45%;"><p>';
		echo '<input id="' . $this->get_field_id('summary_length') . '" name="' . $this->get_field_name('summary_length') . '" type="text" value="' . $summary_length . '" size="3" />';
		echo '<label for="' . $this->get_field_id('summary_length') . '">' . __('Characters for excerpts', 'extended_blogroll') . '</label> <span class="description"><small>' . __('(between 10 and 999)', 'extended_blogroll') . '</small></span>';
		echo '</p></div>';

		echo '<div><p style="margin-left: 10px; margin-right: 10px; border-bottom: 1px dotted #DFDFDF; clear:both;"></p></div>';

		echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('You like this Plugin? Support the developer.', 'extended_blogroll') . '</strong></p>';
		echo '<a href="' . EXTENDED_BLOGROLL_DONATE_FLATTR_LINK . '" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>';
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Blogroll_Widget_RSS");'));

function br_w_r_shorten($string, $length) {
	$suffix = '...';
	$short_desc = trim(str_replace(array(
		"\r",
		"\n",
		"\t"
	), ' ', strip_tags($string)));
	$desc = trim(substr($short_desc, 0, $length));
	$lastchar = substr($desc, -1, 1);

	if($lastchar == '.' || $lastchar == '!' || $lastchar == '?') {
		$suffix = '';
	}

	$desc .= $suffix;

	return $desc;
}

function br_w_r_t_shorten($string, $length) {
	$suffix = '...';
	$short_tit = trim(str_replace(array(
		"\r",
		"\n",
		"\t"
	), ' ', strip_tags($string)));
	$tit = trim(substr($short_tit, 0, $length));
	$lastchar = substr($tit, -1, 1);

	if($lastchar == '.' || $lastchar == '!' || $lastchar == '?') {
		$suffix = '';
	}

	$tit .= $suffix;

	return $tit;
}

/**
 * Ab hier die Modifikationen des eigentlichen Plugins.
 * Diese sind von H.-Peter Pfeufer erstellt und getestet auf Wordpress 3.0.1.
 *
 * Modifikationen:
 * 		Erstellen eines Shortcodes blogroll, damit nicht mehr am Theme herumgebastelkt werden muss.
 * 		Erstellen eines Backends in dem die Optionen für den Shortcode geändert werden können.
 *
 * Gewünscht von:
 * 		Chaosweib (http://www.chaosweib.com)
 * 		H.-Peter Pfeufer (http://ppfeufer.de)
 */

/**
 * Menü zum Dashboard hinzufügen
 */
function extended_blogroll_options() {
	add_menu_page('Blogroll', 'Blogroll', 8, basename(__FILE__), 'extended_blogroll_options_page');
	add_submenu_page(basename(__FILE__), __('Settings'), __('Settings'), 8, basename(__FILE__), 'extended_blogroll_options_page');
}

/**
 * Optionsseite generieren
 */
function extended_blogroll_options_page() {
	?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br /></div>
		<h2><?php _e('Settings for the &#91;blogroll&#93;-shortcode', 'extended_blogroll'); ?></h2>
		<form method="post" action="options.php">
			<?php
			if(function_exists('settings_fields')) {
				settings_fields('extended_blogroll-options');
			}
			?>
			<table class="form-table">
				<tr>
					<th scope="row" valign="top"><?php _e('Shortcode Settings:', 'extended_blogroll'); ?></th>
					<td>
						<div style="float:right; width:150px; text-align:center;">
							<p><?php _e('You like this plugin? Support me',  'extended_blogroll'); ?> :-)</p>
							<a href="<?php echo EXTENDED_BLOGROLL_DONATE_FLATTR_LINK; ?>" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a>
						</div>
						<div>
							<label for="extended_blogroll_show_items"><?php _e('Display items:', 'extended_blogroll'); ?></label>
							<input type="text" value="<?php echo get_option('extended_blogroll_show_items'); ?>" name="extended_blogroll_show_items" id="extended_blogroll_show_items" size="3"/>
							<?php _e('(-1 will display all items)', 'extended_blogroll'); ?>
						</div>
						<div>
							<label for="extended_blogroll_item_order"><?php _e('Item order:', 'extended_blogroll'); ?></label>
							<select name="extended_blogroll_item_order">
								<option <?php if(get_option('extended_blogroll_item_order') == 'link_name ASC') echo 'selected="selected"'; ?> value="link_name ASC"><?php _e('Link Name Ascending', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_item_order') == 'link_name DESC') echo 'selected="selected"'; ?> value="link_name DESC"><?php _e('Link Name Descending', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_item_order') == 'link_id ASC') echo 'selected="selected"'; ?> value="link_id ASC"><?php _e('Link ID Ascending', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_item_order') == 'link_id DESC') echo 'selected="selected"'; ?> value="link_id DESC"><?php _e('Link ID Descending', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_item_order') == 'rand()') echo 'selected="selected"'; ?> value="rand()"><?php _e('Random Order', 'extended_blogroll'); ?></option>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<th scope="row" valign="top"><?php _e('Items Configuration:', 'extended_blogroll'); ?></th>
					<td>
						<div>
							<label for="extended_blogroll_show_image"><?php _e('Show link images:', 'extended_blogroll'); ?></label>
							<select name="extended_blogroll_show_image">
								<option <?php if(get_option('extended_blogroll_show_image') == 'show-no-images') echo 'selected="selected"'; ?> value="show-no-images"><?php _e('Show no images', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_show_image') == 'show-my-own-images') echo 'selected="selected"'; ?> value="show-my-own-images"><?php _e('Show my own images', 'extended_blogroll'); ?></option>
								<option <?php if(get_option('extended_blogroll_show_image') == 'create-thumbnails') echo 'selected="selected"'; ?> value="create-thumbnails"><?php _e('Create and show thumbnails', 'extended_blogroll'); ?></option>
							</select>
						</div>
						<div>
							<label for="extended_blogroll_thumb_size"><?php _e('Thumbnail size: ', 'extended_blogroll'); ?></label>
							<input type="text" value="<?php echo get_option('extended_blogroll_thumb_size'); ?>" name="extended_blogroll_thumb_size" id="extended_blogroll_thumb_size" size="3"/> <?php _e(' Pixel', 'extended_blogroll'); ?>
						</div>
						<div>
							<div style="width: 48%; float:left;">
								<input type="checkbox" value="1" <?php if(get_option('extended_blogroll_show_link') == '1') echo 'checked="checked"'; ?> name="extended_blogroll_show_link" id="extended_blogroll_show_link" />
								<label for="extended_blogroll_show_link"><?php _e('Show blogroll links ?', 'extended_blogroll'); ?></label>
							</div>
							<div style="width: 48%; float:left;">
								<input type="checkbox" value="1" <?php if(get_option('extended_blogroll_show_link_nf') == '1') echo 'checked="checked"'; ?> name="extended_blogroll_show_link_nf" id="extended_blogroll_show_link_nf" />
								<label for="extended_blogroll_show_link_nf"><?php _e('Add rel="nofollow" ?', 'extended_blogroll'); ?></label>
							</div>
						</div>
						<div style="clear:both;">
							<div style="width: 48%; float:left;">
								<label for="extended_blogroll_feed_items"><?php _e('Show Feed Items: ', 'extended_blogroll'); ?></label>
								<input type="text" value="<?php echo get_option('extended_blogroll_feed_items'); ?>" name="extended_blogroll_feed_items" id="extended_blogroll_feed_items" size="3"/>
							</div>
							<div style="width: 48%; float:left;">
								<input type="checkbox" value="1" <?php if(get_option('extended_blogroll_feed_link_nf') == '1') echo 'checked="checked"'; ?> name="extended_blogroll_feed_link_nf" id="extended_blogroll_feed_link_nf" />
								<label for="extended_blogroll_feed_link_nf"><?php _e('Add rel="nofollow" ?', 'extended_blogroll'); ?></label>
							</div>
						</div>
						<div style="clear:both;">
							<div style="width: 48%; float:left;">
								<input type="checkbox" value="1" <?php if(get_option('extended_blogroll_shorten_feedlink') == '1') echo 'checked="checked"'; ?> name="extended_blogroll_shorten_feedlink" id="extended_blogroll_shorten_feedlink" />
								<label for="extended_blogroll_shorten_feedlink"><?php _e('Shorten Feedlink', 'extended_blogroll'); ?></label>
							</div>
							<div style="width: 48%; float:left;">
								<label for="extended_blogroll_s_f_length"><?php _e('Short Link to: ', 'extended_blogroll'); ?></label>
								<input type="text" value="<?php echo get_option('extended_blogroll_s_f_length'); ?>" name="extended_blogroll_s_f_length" id="extended_blogroll_s_f_length" size="5"/> <?php _e(' Characters', 'extended_blogroll'); ?>
							</div>
						</div>
						<div style="clear:both;">
							<div style="width: 48%; float:left;">
								<input type="checkbox" value="1" <?php if(get_option('extended_blogroll_show_summary') == '1') echo 'checked="checked"'; ?> name="extended_blogroll_show_summary" id="extended_blogroll_show_summary" />
								<label for="extended_blogroll_show_summary"><?php _e('Shorten Feedsummary', 'extended_blogroll'); ?></label>
							</div>
							<div style="width: 48%; float:left;">
								<label for="extended_blogroll_summary_length"><?php _e('Short Summary to: ', 'extended_blogroll'); ?></label>
								<input type="text" value="<?php echo get_option('extended_blogroll_summary_length'); ?>" name="extended_blogroll_summary_length" id="extended_blogroll_summary_length" size="5"/> <?php _e(' Characters', 'extended_blogroll'); ?>
							</div>
						</div>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save Changes', 'extended_blogroll'); ?>" /></p>
		</form>
	</div>
<?php
}

/**
 * Changelog bei Pluginupdate ausgeben.
 *
 * @since 0.1
 */
if(!function_exists('extended_blogroll_update_notice')) {
	function extended_blogroll_update_notice() {
		$array_EBR_Data = get_plugin_data(__FILE__);
		$var_sUserAgent = 'Mozilla/5.0 (X11; Linux x86_64; rv:5.0) Gecko/20100101 Firefox/5.0 WorPress Plugin Extended Blogroll (Version: ' . $array_EBR_Data['Version'] . ') running on: ' . get_bloginfo('url');
		$url_readme = 'http://plugins.trac.wordpress.org/browser/2-click-socialmedia-buttons/trunk/readme.txt?format=txt';
		$data = '';

		if(ini_get('allow_url_fopen')) {
			$data = file_get_contents($url_readme);
		} else {
			if(function_exists('curl_init')) {
				$cUrl_Channel = curl_init();
				curl_setopt($cUrl_Channel, CURLOPT_URL, $url_readme);
				curl_setopt($cUrl_Channel, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($cUrl_Channel, CURLOPT_USERAGENT, $var_sUserAgent);
				$data = curl_exec($cUrl_Channel);
				curl_close($cUrl_Channel);
			} // END if(function_exists('curl_init'))
		} // END if(ini_get('allow_url_fopen'))

		if($data) {
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote($array_EBR_Data['Version']) . '\s*=|$)~Uis';

			if(preg_match($regexp, $data, $matches)) {
				$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

				echo '</div><div class="update-message" style="font-weight: normal;"><strong>What\'s new:</strong>';
				$ul = false;
				$version = 99;

				foreach($changelog as $index => $line) {
					if(version_compare($version, $array_EBR_Data['Version'], ">")) {
						if(preg_match('~^\s*\*\s*~', $line)) {
							if(!$ul) {
								echo '<ul style="list-style: disc; margin-left: 20px;">';
								$ul = true;
							} // END if(!$ul)

							$line = preg_replace('~^\s*\*\s*~', '', $line);
							echo '<li>' . $line . '</li>';
						} else {
							if($ul) {
								echo '</ul>';
								$ul = false;
							} // END if($ul)

							$version = trim($line, " =");
							echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
						} // END if(preg_match('~^\s*\*\s*~', $line))
					} // END if(version_compare($version, $array_EBR_Data['Version'],">"))
				} // END foreach($changelog as $index => $line)

				if($ul) {
					echo '</ul><div style="clear: left;"></div>';
				} // END if($ul)


				echo '</div>';
			} // END if(preg_match($regexp, $data, $matches))
		} else {
			/**
			 * Returning if we can't use file_get_contents or cURL
			 */
			return;
		} // END if($data)
	} // END function extended_blogroll_update_notice()
} // END if(!function_exists('extended_blogroll_update_notice'))

function extended_blogroll_css() {
	echo'
	<style type="text/css">
	ul#extended-blogroll li.blogrollitem {list-style-type: none; margin-bottom:15px;}
	ul#extended-blogroll li.blogrollitem p {}
	ul#extended-blogroll div.blogrollthumbnail {float:left; margin:5px 5px 0 0;}
	ul#extended-blogroll a.blogrollbloglink {font-weight: bold; font-size:125%;}
	ul#extended-blogroll a.blogrollfeedlink {}
	</style>
	';
}

/**
 * Variablen registrieren
 * Plugin initialisieren
 */
function extended_blogroll_init() {
	if(function_exists('register_setting')) {
		register_setting('extended_blogroll-options', 'extended_blogroll_show_items');
		register_setting('extended_blogroll-options', 'extended_blogroll_item_order');
		register_setting('extended_blogroll-options', 'extended_blogroll_show_image');
		register_setting('extended_blogroll-options', 'extended_blogroll_thumb_size');
		register_setting('extended_blogroll-options', 'extended_blogroll_show_link');
		register_setting('extended_blogroll-options', 'extended_blogroll_show_link_nf');
		register_setting('extended_blogroll-options', 'extended_blogroll_feed_items');
		register_setting('extended_blogroll-options', 'extended_blogroll_shorten_feedlink');
		register_setting('extended_blogroll-options', 'extended_blogroll_s_f_length');
		register_setting('extended_blogroll-options', 'extended_blogroll_feed_link_nf');
		register_setting('extended_blogroll-options', 'extended_blogroll_show_summary');
		register_setting('extended_blogroll-options', 'extended_blogroll_summary_length');
	}

	/**
	 * Sprachdatei wählen
	 */
	if(function_exists('load_plugin_textdomain')) {
		load_plugin_textdomain('extended_blogroll', false, dirname(plugin_basename( __FILE__ )) . '/languages/');
	}
}

/**
 * Standardwerte setzen
 */
function extended_blogroll_activate() {
	add_option('extended_blogroll_show_items', -1);
	add_option('extended_blogroll_category', false);
	add_option('extended_blogroll_item_order', 'link_name ASC');
	add_option('extended_blogroll_show_image', 'create-thumbnails');
	add_option('extended_blogroll_thumb_size', 100);
	add_option('extended_blogroll_show_link', 1);
	add_option('extended_blogroll_show_link_nf', 0);
	add_option('extended_blogroll_feed_items', 1);
	add_option('extended_blogroll_shorten_feedlink', 0);
	add_option('extended_blogroll_s_f_length', 250);
	add_option('extended_blogroll_feed_link_nf', 0);
	add_option('extended_blogroll_show_summary', 1);
	add_option('extended_blogroll_summary_length', 250);
}

if(is_admin()) {
	add_action('admin_menu', 'extended_blogroll_options');
	add_action('admin_init', 'extended_blogroll_init');

	// Updatemeldung
	if(ini_get('allow_url_fopen') || function_exists('curl_init')) {
		add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'extended_blogroll_update_notice');
	}
}

register_activation_hook(__FILE__, 'extended_blogroll_activate');

/**
 * Shortcode blogroll
 * @param array $atts
 * @param string $content
 */
if (!is_admin()) {
	/**
	 * CSS in Wordpress einbinden
	 */
// 	$css_url = plugins_url(basename(dirname(__FILE__)) . '/css/extended-blogroll.css');
// 	wp_register_style('extended-blogroll', $css_url, array(), EXTENDED_BLOGROLL_VERSION, 'screen');
// 	wp_enqueue_style('extended-blogroll');
	add_action('wp_head', 'extended_blogroll_css');

	function extended_blogroll_output($args = array(), $position) {
		$return = '';
		$default_args = array(
			'show_items' => -1,
			'category' => false,
			'item_order' => 'link_name ASC',
			'show_image' => 'show-no-images',
			'thumb_size' => 50,
			'show_link' => 0,
			'show_link_nf' => 0,
			'feed_items' => 1,
			'shorten_feedlink' => 0,
			's_f_length' => 20,
			'feed_link_nf' => 0,
			'show_summary' => 0,
			'summary_length' => 100,
			'donate' => 0
		);

		$args = wp_parse_args($args, $default_args);
		extract($args);

		$show_items = (int) $show_items;
		$thumb_size = (int) $thumb_size;
		$show_link = (int) $show_link;
		$show_link_nf = (int) $show_link_nf;
		$feed_items = (int) $feed_items;
		$shorten_feedlink = (int) $shorten_feedlink;
		$s_f_length = (int) $s_f_length;
		$feed_link_nf = (int) $feed_link_nf;
		$show_summary = (int) $show_summary;
		$summary_length = (int) $summary_length;
		$donate = (int) $donate;

		global $wpdb;

		if($category != 0) {
			$qu_cat_t = " AND tt.term_id = $category ";
		}

		$queryString = "
			SELECT * FROM $wpdb->links
			INNER JOIN $wpdb->term_relationships AS tr ON ($wpdb->links.link_id = tr.object_id)
			INNER JOIN $wpdb->term_taxonomy as tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
			WHERE $wpdb->links.link_visible = 'Y'
			AND $wpdb->links.link_rss != ''
			AND tt.taxonomy = 'link_category'
			$qu_cat_t
			Order by $item_order";

		if($show_items != -1) {
			$queryString .= " LIMIT $show_items";
		}

		$blbm_links = $wpdb->get_results($queryString);

		if(empty($blbm_links)) {
			$return .= '<ul><li>';
			$return .= __('No RSS Addresses are entered to your links in the Links SubPanel, therefore no items can be shown!', 'extended_blogroll');
			$return .= '</li></ul>';
		} else {
			add_filter('wp_feed_cache_transient_lifetime', create_function('$a', 'return 1800;'));

			include_once (ABSPATH . WPINC . '/feed.php');
			$return .= '<ul id="extended-blogroll">';

			foreach($blbm_links as $blbm_link) {
				$return .= '<li class="blogrollitem">';

				if($show_image != "show-no-images") {
					$thumb_h = $thumb_size / 4;
					$thumb_height = $thumb_h * 3;

					if($show_image == "show-my-own-images" && $blbm_link->link_image != "") {
						$return .= '<div class="blogrollthumbnail">';
						$return .= '<img style="width:' . $thumb_size . 'px;" src="' . $blbm_link->link_image . '" alt="' . $blbm_link->link_name . '" title="' . $blbm_link->link_name . '" />';
						$return .= '</div>';
					}

					if($show_image == "create-thumbnails") {
						$return .= '<div class="blogrollthumbnail">';
						$return .= '<img style="width:' . $thumb_size . 'px; height:' . $thumb_height . 'px;" src="http://www.m-software.de/screenshot/Screenshot.png?url=' . $blbm_link->link_url . '&commingsoonimg=http%3A%2F%2Fwww.m-software.de%2Fuploads%2Fcommingsoon.png" alt="' . $blbm_link->link_name . '" title="' . $blbm_link->link_name . '"/>';
						$return .= '</div>';
					}
				}

				$blbm_target = $blbm_link->link_target;

				if($show_link) {
					$target = '';
					$rel = '';

					if($blbm_target) {
						$target = ' target="' . $blbm_target . '"';
					}

					if($show_link_nf) {
						$rel = ' rel="nofollow"';
					}

					$return .= '<a class="blogrollbloglink" href="' . $blbm_link->link_url . '"' . $target . $rel . '>' . $blbm_link->link_name . '</a>';
				}

				$blbm_url = esc_attr($blbm_link->link_rss);
				$blbm_rss = fetch_feed($blbm_url);

				if(is_wp_error($blbm_rss)) {
					$filestring = @file_get_contents($blbm_url);
					$startpos = 0;
					while($pos = strpos($filestring, "application/rss+xml", $startpos)) {
						$string = substr($filestring, $pos, strpos($filestring, "/>", $pos + 1) - $pos);
						$startpos = $pos + 1;
					}

					$startpos = 0;
					while($pos = strpos($string, 'href="', $startpos)) {
						$blbm_url = substr(substr($string, $pos + 6), 0, strpos(substr($string, $pos + 6), '"'));
						$startpos = $pos + 1;
					}

					$blbm_rss = fetch_feed($blbm_url);

					if(is_wp_error($blbm_rss)) {
						if($show_image != "show-no-images") {
							$return .= '<div style="clear:both; margin-bottom:3px;"></div>';
						}

						$return .= '</li>';

						unset($blbm_rss);

						continue;
					}
				}

				if(!is_wp_error($blbm_rss)) {
					if($feed_items < 1) {
						$feed_items = 1;
					}

					$blbm_rss_items = $blbm_rss->get_items(0, $blbm_rss->get_item_quantity($feed_items));

					foreach($blbm_rss_items as $item) {
						$tit_l = $item->get_title();
						$tit_c = strlen($tit_l);
						$target = '';
						$rel = '';
						$cite = '';

						/**
						 * Link des RSS-Eintrags zusammensetzen
						 */
						if($blbm_target) {
							$target = ' target="' . $blbm_target . '"';
						}

						if($feed_link_nf) {
							$rel = ' rel="nofollow"';
						}

						if($shorten_feedlink && $tit_c > $s_f_length) {
							$tit = br_w_r_t_shorten($item->get_title(), $s_f_length);
							$link = 'href="' . $item->get_permalink() . '">' . $tit . '</a>';
						} else {
							$link = ' href="' . $item->get_permalink() . '">' . $item->get_title() . '</a>';
						}

						if($show_summary) {
							$desc = br_w_r_shorten($item->get_description(), $summary_length);
							$cite = '<br /><cite>' . $desc . '</cite>';
						}

						/**
						 * RSS-Eintrag ausgeben
						 */
						if ($position == 'shortcode') {
							$margin = $thumb_size + 6;
						}
						$return .= '<p style="margin-left:' . $margin . 'px;"><a class="blogrollfeedlink"' . $target . $rel . $link . $cite . '</p>';

					}

					if($show_image != "show-no-images") {
						$return .= '<div style="clear:both;"></div>';
					}

					$return .= '</li>';
				}
			}

			$return .= '</ul>';

			/**
			 * Hinweis anzeigen wo die Thumbnails erstellt wurden
			 */
			if($show_image == "create-thumbnails") {
				$return .= '<div align="center"><small>';
				$return .= '<a target="_blank" href="http://www.m-software.de/thumbshots.html">Thumbnails by M-Software.de</a>';
				$return .= '</small></div>';
			}

			/**
			 * Speicher freigeben
			 */
			unset($blbm_rss);

			/**
			 * Filter entfernen
			 */
			remove_filter('wp_feed_cache_transient_lifetime', create_function('$a', 'return 1800;'));
			return $return;
		}
	}

	function extended_blogroll_shortcode() {
		$args = array(
			'show_items' => get_option('extended_blogroll_show_items'), // Number of items to show (-1 shows all items)
			'category' => get_option('extended_blogroll_category'), // false for all links, otherwise the number of the respective link category id
			'item_order' => get_option('extended_blogroll_item_order'), // either link_name ASC, or link_name DESC, or link_id ASC, or link_id DESC, or rand()
			'show_image' => get_option('extended_blogroll_show_image'), // either show-no-images, or show-my-own-images, or create-thumbnails
			'thumb_size' => get_option('extended_blogroll_thumb_size'), // any number greater than 10 defines the pixel size of the images
			'show_link' => get_option('extended_blogroll_show_link'), // 0 = no blogroll links, 1 = display blogroll links
			'show_link_nf' => get_option('extended_blogroll_show_link_nf'), // 0 = no 'rel=nofollow' attribute is added to blogroll links, 1 = adds 'rel=nofollow' attribute to blogroll links
			'feed_items' => get_option('extended_blogroll_feed_items'), // any number between 1 and 10 defines how many feed post links are displayed
			'shorten_feedlink' => get_option('extended_blogroll_shorten_feedlink'), // 0 no feed post link text shortening, 1 = shorten the feed post link text
			's_f_length' => get_option('extended_blogroll_s_f_length'), // number of characters of feed post link text
			'feed_link_nf' => get_option('extended_blogroll_feed_link_nf'), // no 'rel=nofollow' attribute is added to feed post links, 1 = adds 'rel=nofollow' attribute to feed post links
			'show_summary' => get_option('extended_blogroll_show_summary'), // no feed post excerpts, 1 = display feed post excerpts
			'summary_length' => get_option('extended_blogroll_summary_length') // any number between 10 and 999 defines how many characters of the feed post excerpts are displayed
		);

		return extended_blogroll_output($args, 'shortcode');
	}

	add_shortcode('blogroll', 'extended_blogroll_shortcode');
}
?>