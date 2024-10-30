<?php
/*
Plugin Name: Custom Post Taxonomy
Description: Custom Post Taxonomy to create new post type.
Tags: Custom Post Taxonomy, post, Taxonomy, slug
Author URI: http://www.hurtigmums.dk
Author: Kjeld Hansen
Text Domain: custom-post-taxonomy
Requires at least: 4.0
Tested up to: 4.6
Version: 1.0
*/


 if ( ! defined( 'ABSPATH' ) ) exit; 
add_action('admin_menu','cuptaxn_custom_post_admin_menu');
function cuptaxn_custom_post_admin_menu() { 
    add_menu_page(
		"Custom Post taxonomy",
		" Taxonomy",
		8,
		__FILE__,
		"cuptaxn_custom_post_admin_menu_list",
		plugins_url( 'images/plugin-icon.png', __FILE__) 
	); 
}

function cuptaxn_custom_post_admin_menu_list(){
	wp_enqueue_script( 'ricftx_script', plugin_dir_url( __FILE__ ) . 'js/ricf.js' );
	include 'cuptaxna-dmin.php';
}

add_action( 'admin_enqueue_scripts', 'cuptaxnf_custom_post_admin_css' );
function cuptaxnf_custom_post_admin_css(){
	wp_register_style( 'cuptaxnf_custom_post_admin_wp_admin_css', plugins_url( '/css/admin.css', __FILE__), false, '1.0.0' );
    wp_enqueue_style( 'cuptaxnf_custom_post_admin_wp_admin_css' );	
}

add_action( 'init', 'codex_cuptaxn_custom_post_type_init' );
function codex_cuptaxn_custom_post_type_init() {
	
	
	if(get_option( 'cuptaxnf_custom_post_opt' )){
		$cuptaxnf_custom_posts_disp = unserialize(get_option( 'cuptaxnf_custom_post_opt' ));
			if($cuptaxnf_custom_posts_disp && sizeof($cuptaxnf_custom_posts_disp)>0){
			foreach($cuptaxnf_custom_posts_disp as $cuptaxnf_custom_post_disp){
				if($cuptaxnf_custom_post_disp)
				foreach($cuptaxnf_custom_post_disp as $slug=>$field){
					
					$cuptaxnslug = $field['type'];
					$cuptaxnsing = $field['ph'];
					$cuptaxnplu = $field['label'];
					$cuptaxnpty = explode(', ',$field['ritaxpt']);
					
					$labels = array(
						'name'              => _x( $cuptaxnplu, 'taxonomy general name', 'custom-post-taxonomy'.$cuptaxnslug ),
						'singular_name'     => _x( $cuptaxnsing, 'taxonomy singular name', 'custom-post-taxonomy'.$cuptaxnslug ),
						'search_items'      => __( 'Search '.$cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
						'all_items'         => __( 'All Genres', 'custom-post-taxonomy'.$cuptaxnslug ),
						'parent_item'       => __( 'Parent '.$cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
						'parent_item_colon' => __( 'Parent '.$cuptaxnsing.':', 'custom-post-taxonomy'.$cuptaxnslug ),
						'edit_item'         => __( 'Edit '.$cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
						'update_item'       => __( 'Update '.$cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
						'add_new_item'      => __( 'Add New '.$cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
						'new_item_name'     => __( 'New '.$cuptaxnsing.' Name', 'custom-post-taxonomy'.$cuptaxnslug ),
						'menu_name'         => __( $cuptaxnsing, 'custom-post-taxonomy'.$cuptaxnslug ),
					);
				
					$args = array(
						'hierarchical'      => true,
						'labels'            => $labels,
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => true,
						'rewrite'           => array( 'slug' => 'cuptaxn'.$cuptaxnslug ),
					);
				
					register_taxonomy( 'cuptaxn'.$cuptaxnslug, $cuptaxnpty, $args );
					
					
				}
			}
		}
	}
	
}
