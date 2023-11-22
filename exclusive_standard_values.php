<?php

/**
 * Plugin Name: Exclusive Standard Values (Premium)
 * Plugin URI: https://woocommercechild.com/exclusive-global-settings
 * Description: Helps to set up standard values from your wordpress dashboard.
 * Version: 1.0.35
 * Update URI: https://api.freemius.com
 * Author: Exclusive Web Marketing
 * Author URI: https://exclusivemarketing.com/
 * Text Domain: exclusive-global-settings
 * Domain Path: /languages/
 * License: GPLv2 or any later version
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or later, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package WPBDP
*/

if ( !function_exists( 'exc_fs_gs' ) ) {
    // Create a helper function for easy SDK access.
    function exc_fs_gs()
    {
        global  $exc_fs_gs ;
        
        if ( !isset( $exc_fs_gs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $exc_fs_gs = fs_dynamic_init( array(
                'id'              => '10545',
                'slug'            => 'ExclusiveStandardValues',
                'premium_slug'    => 'exclusive-standard-values-premium',
                'type'            => 'plugin',
                'public_key'      => 'pk_2c95a8ade96771cdcf88cc44f9bed',
                'is_premium'      => true,
                'is_premium_only' => false,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'trial'           => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'            => array(
                'slug'    => 'exclusive_global_settings',
                'support' => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $exc_fs_gs;
    }
    
    // Init Freemius.
    exc_fs_gs();
    // Signal that SDK was initiated.
    do_action( 'exc_fs_gs_loaded' );
}

include dirname( __FILE__ ) . '/class_ewm_global_setting.php';
function ewm_global_settings_load_admin_resources( $options )
{
    wp_enqueue_script( 'jquery' );
    wp_enqueue_media();
    wp_enqueue_script( 'ewm-svs-main-lib-uploader-js', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/script-admin.js', 'jquery' ) );
    wp_localize_script( 'ewm-svs-main-lib-uploader-js', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_style( 'ewm-svs-style_admin', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/style-admin.css' ) );
}

add_action( 'admin_enqueue_scripts', 'ewm_global_settings_load_admin_resources' );
function ewm_global_settings()
{
    ob_start();
    include dirname( __FILE__ ) . '/templates/global_settings_main.php';
    return ob_get_clean();
}

add_action( 'wp_enqueue_scripts', 'ewm_global_settings_load_public_resources' );
function ewm_global_settings_load_public_resources()
{
    wp_enqueue_script( 'jquery' );
    wp_enqueue_media();
    wp_enqueue_script( 'ewm-gs-main-lib-uploader-js', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/script-public.js', 'jquery' ) );
    wp_localize_script( 'ewm-gs-main-lib-uploader-js', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_script( 'ewm-gs-main-lib-textfit-js', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/jquery.fittext.js', 'jquery' ) );
    wp_localize_script( 'ewm-gs-main-lib-textfit-js', 'ajax_object', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    ) );
    wp_enqueue_style( 'ewm-gs-style_admin', plugins_url( basename( dirname( __FILE__ ) ) . '/assets/style-public.css' ) );
}

// add new dashboard widgets
function ewm_gs_dashboard_widget()
{
    wp_add_dashboard_widget( 'wptutsplus_dashboard_welcome', 'Standard Values', 'wptutsplus_add_welcome_widget' );
}

function wptutsplus_add_welcome_widget()
{
    echo  ewm_global_settings() ;
}

add_action( 'wp_dashboard_setup', 'ewm_gs_dashboard_widget' );
// Ajax action to refresh the user image
add_action( 'wp_ajax_myprefix_get_image', 'ewm_gs_get_image' );
function ewm_gs_get_image()
{
    
    if ( isset( $_GET['id'] ) ) {
        $image = wp_get_attachment_image(
            filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ),
            array( '300', '300' ),
            false,
            array(
            'id' => 'myprefix-preview-image',
        )
        );
        $full_width_image = wp_get_attachment_url( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ) );
        $data = array(
            'image'            => $image,
            'full_width_image' => $full_width_image,
        );
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }

}

function ewm_gs_single_screen_columns( $columns )
{
    $columns['dashboard'] = 1;
    return $columns;
}

add_filter( 'screen_layout_columns', 'ewm_gs_single_screen_columns' );
function single_screen_dashboard()
{
    return 1;
}

add_filter( 'get_user_option_screen_layout_dashboard', 'single_screen_dashboard' );
// Allow SVG
/*
	add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
		$filetype = wp_check_filetype( $filename, $mimes );
		return [
			'ext'             => $filetype['ext'],
			'type'            => $filetype['type'],
			'proper_filename' => $data['proper_filename']
		];
	  
	  }, 10, 4 );
	  
	  function cc_mime_types( $mimes ){
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	  }
	  add_filter( 'upload_mimes', 'cc_mime_types' );
	  
	  function fix_svg() {
		echo '<style type="text/css">
			  .attachment-266x266, .thumbnail img {
				   width: 100% !important;
				   height: auto !important;
			  }
			  </style>';
	  }
	  add_action( 'admin_head', 'fix_svg' );
*/
function ewm_gs_upload_svg_files( $allowed )
{
    if ( !current_user_can( 'manage_options' ) ) {
        return $allowed;
    }
    $allowed['svg'] = 'image/svg+xml';
    return $allowed;
}

add_filter( 'upload_mimes', 'ewm_gs_upload_svg_files' );
// New company
function exclusive_global_settings_my_edit_admin_menu()
{
    add_menu_page(
        __( 'Standard Values', 'exclusive-standardvalues' ),
        __( 'Standard Values', 'exclusive-standardvalues' ),
        'manage_options',
        'exclusive_global_settings',
        'exclusive_global_settings_my_admin_page_new_contents',
        'dashicons-database-view',
        3
    );
}

add_action( 'admin_menu', 'exclusive_global_settings_my_edit_admin_menu' );
function exclusive_global_settings_my_admin_page_new_contents()
{
    echo  '<div id="ewm_sv_setting_p"> <center> Please manage the Standard Values <a href="' . admin_url( 'index.php' ) . '"> <spam> Here </span> </a> </center> </div>' ;
}
