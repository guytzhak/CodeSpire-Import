<?php 
/*
Plugin Name: CodeSpire Import
Plugin URI: http://codespire.co.il/
Description: Import Posts to Wordpress
Author: Guy Ytzhak
Author URI: http://codespire.co.il/
Text Domain: codespire
Domain Path: /languages/
Version: 1.0
*/

define( 'CSIMPORT_VERSION', '1.0' );
define( 'CSIMPORT_PLUGIN', __FILE__ );
define( 'CSIMPORT_PLUGIN_BASENAME', plugin_basename( CSIMPORT_PLUGIN ) );
define( 'CSIMPORT_PLUGIN_NAME', trim( dirname( CSIMPORT_PLUGIN_BASENAME ), '/' ) );
define( 'CSIMPORT_PLUGIN_DIR', untrailingslashit( dirname( CSIMPORT_PLUGIN ) ) );


/**
 * Create admin Page to list unsubscribed emails.
 */
 // Hook for adding admin menus
 add_action('admin_menu', 'codespire_add_import_options');
 
 // action function for above hook
 
/**
 * Adds a new top-level page to the administration menu.
 */
function codespire_add_import_options() {
     add_menu_page(
        __( 'Hidiz Import', 'codespire' ),
        __( 'Hidiz Import','codespire' ),
        'manage_options',
        'codespire-import',
        'codespire_add_import_options_page',
        ''
    );
}
 
/**
 * Disply callback for the Unsub page.
 */
 function codespire_add_import_options_page() {
     if(get_current_screen()->id == 'toplevel_page_codespire-import') {
        function load_custom_cs_wp_admin_style() {
            wp_register_style( 'custom_wp_admin_css', plugin_dir_url( __FILE__ ) . '/css/style.css', false, '1.0.0' );
            wp_enqueue_style( 'custom_wp_admin_css' );
        }
        add_action( 'admin_enqueue_scripts', 'load_custom_cs_wp_admin_style' );
         echo "<link rel='stylesheet' id='codespire-import-css'  href='". plugin_dir_url( __FILE__ ) ."css/style.css' type='text/css' media='all' />";
         
     }
     require_once CSIMPORT_PLUGIN_DIR . '/admin/index.php';
 }

function get_images_for_import_as_array($img_name, $post_id){

    //attach the photos to the post
    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype( basename( $img_name ), null );

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();
    
    // Get File Name
    $info = pathinfo($img_name);
    $file_name =  basename($img_name,'.'.$info['extension']);
    
    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid'           => $img_name, 
        'post_mime_type' => $filetype['type'],
        'post_title'     => $file_name,
        'post_content'   => '',
        'post_status'    => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $img_name );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $img_name );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    return $attach_id;

}

function codespire_get_image_id_by_url($image_url) {
	global $wpdb;
	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    print_r($attachment);
    return $attachment[0]; 
}