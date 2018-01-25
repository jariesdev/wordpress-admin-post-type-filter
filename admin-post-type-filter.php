<?php
/**
 * @package Admin Post Type Filter
 * @version 1.0
 */
/*
Plugin Name: Admin Post Type Filter
Plugin URI: https://github.com/jariesdev/wordpress-admin-post-type-filter
Description: This plugin used to add filtering functionality easily on admin post listing. This support any custom post type that uses the default table listing of wordpress.
Author: Jay Aries
Version: 1.0
Author URI: #
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Admin_Post_Type_Filter {

	public $post_types = array();
	public $current_post_type = '';


	function __construct (){
		$this->init();
		add_filter( 'parse_query', 'aptf_posts_filter' );
		add_action( 'restrict_manage_posts', 'aptf_restrict_manage_posts' );
	}

	function init(){
		$this->post_types = array('post');
	}

	function aptf_restrict_manage_posts(){
		$post_types = $this->post_types;
		if (isset($_GET['post_type'])) {
			$type = $_GET['post_type'];
		}

		if (in_array($type, $post_types)){
			$values = array(
				'label' => 'value', 
				'label1' => 'value1',
				'label2' => 'value2',
			);
			?>
			<select name="ADMIN_FILTER_FIELD_VALUE">
				<option value=""><?php _e('Filter By ', 'wose45436'); ?></option>
				<?php
				$current_v = isset($_GET['ADMIN_FILTER_FIELD_VALUE'])? $_GET['ADMIN_FILTER_FIELD_VALUE']:'';
				foreach ($values as $label => $value) {
					printf
					(
						'<option value="%s"%s>%s</option>',
						$value,
						$value == $current_v? ' selected="selected"':'',
						$label
					);
				}
				?>
			</select>
			<?php
		}
	}

	function aptf_posts_filter( $query ){
		global $pagenow;
		$post_types = $this->post_types;
		if (isset($_GET['post_type'])) {
			$type = $_GET['post_type'];
		}
		if ( in_array($type, $post_types) && is_admin() && $pagenow=='edit.php' && isset($_GET['ADMIN_FILTER_FIELD_VALUE']) && $_GET['ADMIN_FILTER_FIELD_VALUE'] != '') {
			$query->query_vars['meta_key'] = 'META_KEY';
			$query->query_vars['meta_value'] = $_GET['ADMIN_FILTER_FIELD_VALUE'];
		}
	}

}

new Admin_Post_Type_Filter();