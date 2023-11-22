<?php

include dirname(__FILE__).'/class_display_objects.php';

class ewm_global_setting extends class_display_objects{

	public static function init(){

		// ewm_populate_single_sg_val
		add_action( "wp_ajax_ewm_populate_single_sg_val" , ["ewm_global_setting","ewm_populate_single_sg_val"] );
		add_action( "wp_ajax_nopriv_ewm_populate_single_sg_val" , ["ewm_global_setting","ewm_populate_single_sg_val"] );

		// ewm_remove_cat_button
		add_action( "wp_ajax_ewm_remove_cat_button" , ["ewm_global_setting","ewm_remove_cat_button"] );
		add_action( "wp_ajax_nopriv_ewm_remove_cat_button" , ["ewm_global_setting","ewm_remove_cat_button"] );

		// ewm_populate_single_sg_val
		add_action( "wp_ajax_ewm_sv_search_cats" , ["ewm_global_setting","ewm_sv_search_cats"] );
		add_action( "wp_ajax_nopriv_ewm_sv_search_cats" , ["ewm_global_setting","ewm_sv_search_cats"] );

		// ewm_sv_create_new_post
		add_action( "wp_ajax_ewm_sv_create_new_post" , ["ewm_global_setting","ewm_sv_create_new_post"] );
		add_action( "wp_ajax_nopriv_ewm_sv_create_new_post" , ["ewm_global_setting","ewm_sv_create_new_post"] );

		add_action( "wp_ajax_ewm_sv_update_single_v" , ["ewm_global_setting","ewm_sv_update_single_v"] );
		add_action( "wp_ajax_nopriv_ewm_sv_update_single_v" , ["ewm_global_setting","ewm_sv_update_single_v"] );

		add_action( "wp_ajax_ewm_sv_input_cats" , ["ewm_global_setting","ewm_sv_input_cats"] );
		add_action( "wp_ajax_nopriv_ewm_sv_input_cats" , ["ewm_global_setting","ewm_sv_input_cats"] );

		// 
		add_action( "wp_ajax_nopriv_gs_process_submission" , ["ewm_global_setting","gs_process_submission"] );
		add_action( "wp_ajax_gs_process_submission" , ["ewm_global_setting","gs_process_submission" ] );

		//
		add_action( "wp_ajax_nopriv_ewm_sv_add_cat_to_post" , ["ewm_global_setting","ewm_sv_add_cat_to_post"] );
		add_action( "wp_ajax_ewm_sv_add_cat_to_post" , ["ewm_global_setting","ewm_sv_add_cat_to_post" ] );

		//
		add_action( "wp_ajax_nopriv_ewm_delete_single_category" , ["ewm_global_setting","ewm_delete_single_category"] );
		add_action( "wp_ajax_ewm_delete_single_category" , ["ewm_global_setting","ewm_delete_single_category" ] );

		add_action( "wp_ajax_nopriv_ewm_sv_validate_that_shortcode_is_unique" , ["ewm_global_setting","check_if_shortcode_is_unique_ajax"] );
		add_action( "wp_ajax_ewm_sv_validate_that_shortcode_is_unique" , ["ewm_global_setting","check_if_shortcode_is_unique_ajax" ] );

		add_action( "wp_ajax_nopriv_gs_delete_submission" , ["ewm_global_setting","gs_delete_submission"] );
		add_action( "wp_ajax_gs_delete_submission" , ["ewm_global_setting","gs_delete_submission" ] );

		// add_action( "wp_footer" , [ "ewm_global_setting","display_data" ] );
		add_shortcode('ewm_standard_values', [ "ewm_global_setting","gs_shortcode_display" ] );
		add_action( 'init',  [ "ewm_global_setting","ewm_esv_show_preview" ] );

	}

	public static function ewm_delete_single_category(){

		$r_cat_id = wp_delete_post( $_POST['cat_id'], true );

		echo json_encode([
			'post_deleted' => $r_cat_id,
			'post' => $_POST,
		]);

		wp_die();
	}

	public static function ewm_remove_cat_button(){
		
		$ewm_gs_post_list = get_post_meta( $_POST['ewm_post_id'], 'ewm_cat_list' , true );

		if( !is_array( $ewm_gs_post_list ) ){
			$ewm_gs_post_list = [];
			add_post_meta(  $_POST['ewm_post_id'], 'ewm_cat_list' , $ewm_gs_post_list, true );
		}
		$ewm_gs_post_list[ $_POST['ewm_gs_cat_id'] ] = 'false';

		$ewm_gs_post_update = update_post_meta(  $_POST['ewm_post_id'], 'ewm_cat_list' , $ewm_gs_post_list );

		echo json_encode([
			'post' => $_POST,
			'ewm_cat_list' => $ewm_gs_post_list
		]);
		
		wp_die();


	}

	public static function ewm_sv_add_post_to_cat( $args = [] ){
		// $args["ewm_gs_post_id"] // $args["ewm_gs_c_id"]
		$ewm_gs_post_list = get_post_meta( $args["ewm_gs_c_id"], 'ewm_cat_list' , true );

		if( !is_array( $ewm_gs_post_list ) ){
			$ewm_gs_post_list = [];
			add_post_meta( $args["ewm_gs_c_id"], 'ewm_cat_list' , $ewm_gs_post_list, true );
		}
		else{ // $ewm_gs_post_list
			$ewm_gs_post_list[ $args["ewm_gs_post_id"] ] = 'true';
		}
		// $ewm_gs_post_s = maybe_serialize( $ewm_gs_post_list );
		$ewm_gs_post_update = update_post_meta( $args["ewm_gs_c_id"] , 'ewm_cat_list' , $ewm_gs_post_list );
	}

	public static function ewm_sv_add_cat_to_post(){

		// delete_post_meta( $_POST['ewm_gs_post_id'] ); // $_POST['ewm_gs_c_id'] // $_POST['ewm_gs_post_id']
		$ewm_gs_post_list = get_post_meta( $_POST['ewm_gs_post_id'], 'ewm_cat_list' , true );
		// var_dump( $ewm_gs_post_list );
		if( !is_array( $ewm_gs_post_list ) ){
			$ewm_gs_post_list = [];
			add_post_meta( $_POST['ewm_gs_post_id'], 'ewm_cat_list' , $ewm_gs_post_list, true );
		}
		$ewm_gs_post_list[ $_POST['ewm_gs_c_id'] ] = 'true';

		$ewm_gs_post_update = update_post_meta( $_POST['ewm_gs_post_id'], 'ewm_cat_list' , $ewm_gs_post_list );

		$ewm_sv_add_post_to_cat = ewm_global_setting::ewm_sv_add_post_to_cat([
			"ewm_gs_post_id" => $_POST['ewm_gs_post_id'] ,
			"ewm_gs_c_id" => $_POST['ewm_gs_c_id']
		]);

		echo json_encode([
			'post' => $_POST,
			'ewm_cat_list' => $ewm_gs_post_list
		]);
		
		wp_die();

	}

	public static function check_if_shortcode_is_unique_ajax(){

		$args = ewm_global_setting::check_if_shortcode_is_unique();

		/*
		if( $args['is_unique'] == true ){
			update_post_meta( $args['post_id'] , 'ewm_data_title' , $args['ewm_shortcode'] );
			update_post_meta( $args['post_id'] , 'ewm_shortcode' , $args['ewm_shortcode'] );
		}
		*/

		echo json_encode([
			"post" => $_POST,
			"update_data" => [
				"is_unique" => $args['is_unique'],
				"message" => ''
			]
		]);
		wp_die();

	}

	public static function check_if_shortcode_is_unique( $args = [] ) {

		// check if its unique >> $_POST['update_k'] ; >> $_POST['update_v'] ; >> $_POST['post_id'] ; // $_POST['ewm_shortcode'] // $_POST['post_id']
		$is_unique = false;
		$post_data = [];

		// if( count( $args ) == 0 ){
			// $args >> // 'ewm_shortcode' $_POST['ewm_shortcode'],
			if( array_key_exists( 'post_id', $_POST ) ){
				$args['post_id'] = $_POST['post_id'];
			}
			if( array_key_exists( 'ewm_shortcode', $_POST ) ){
				$args['ewm_shortcode'] = $_POST['ewm_shortcode'];
				// var_dump( $args );
				$post_data = get_posts([
					"post_status"   => "active",
					"post_type"     => "ewmGsS",
					// "post_title"	=> $_POST['ewm_shortcode'],
					's' 			=> $_POST['ewm_shortcode'],
					/* 'meta_query'	=> [
						[
							'relation' => 'AND',
							[
								'key'     => 'ewm_data_title',
								'value'   => $args['ewm_shortcode'],
								'compare' => '=',
							],
						]
					], */
				]);
			}
		// }

		if( count($post_data) == 1 ){
			if( $post_data[0]->ID == $args['post_id'] ) {
				$is_unique = true;
			}
		}
		elseif( count($post_data) > 1 ){
			$is_unique = false;
		}
		elseif( count($post_data) == 0 ){
			$is_unique = true;
		}
		
		return [
			'is_unique' => $is_unique ,
			'post_id' => $args['post_id'],
			'ewm_shortcode' => $args['ewm_shortcode']
		];

	}

	public static function ewm_activate_sv_post(){

		$_post_dat = [
			'ID' => $_POST['post_id'],
			'post_status' => 'active',
			"post_type" => "ewmGsS",
		];

		$_post_dat = wp_update_post( $_post_dat );

		return $_post_dat;

	}

	public static function ewm_data_title_update( $args = [] ){

		// if shortcode -> ensure it's unique -> if not do not make update
		$args = ewm_global_setting::check_if_shortcode_is_unique([
			'ewm_shortcode' => $_POST['ewm_shortcode'],
			'post_id' => $_POST['post_id']
		]);

		// $is_unique
		if( $args['is_unique'] == true ){
			$post_meta_id = update_post_meta( $_POST['post_id'], $_POST['update_k'], $_POST['update_v'] );
		}
		else{
			$message = 'The title already exists.';
			$post_meta_id = 0 ;
		}

		return [
			'post_meta_id' => $post_meta_id,
			'message' => $message
		];

	}

	public static function  ewm_data_type_update( $args = [] ){

		$post_meta_id = update_post_meta( $_POST['post_id'], $_POST['update_k'], $_POST['update_v'] );
		return [
			'post_meta_id' => $post_meta_id,
			'message' => ''
		];

	}
	
	public static function ewm_actual_data_update( $args = [] ){

		$post_meta_id = update_post_meta( $_POST['post_id'], $_POST['update_k'], $_POST['update_v'] );

		$ewmGsS_r = wp_update_post([
			'ID' => $_POST['post_id'],
			'post_content' =>  $_POST['update_v'],
			// 'post_status' => 'active',
			"post_type" => "ewmGsS",
		]);

		return [
			'post_meta_id' => $post_meta_id,
			'message' => ''
		];

	}

	public static function ewm_shortcode_update( $args = [] ){

	}

	public static function ewm_sv_update_single_v(){

		$update_data = [];

		/* "ewm_data_title" "ewm_data_type" "ewm_actual_data" "ewm_shortcode" */
		// $ewm_sv_post = ewm_global_setting::ewm_activate_sv_post();
		if($_POST["update_k"] == "ewm_data_title"){
			// $update_data = ewm_global_setting::ewm_data_title_update();
		}
		if($_POST["update_k"] == "ewm_data_type"){
			$update_data = ewm_global_setting::ewm_data_type_update();
		}
		if($_POST["update_k"] == "ewm_actual_data"){
			$update_data = ewm_global_setting::ewm_actual_data_update();
		}
		if($_POST["update_k"] == "ewm_shortcode"){
			$update_data = ewm_global_setting::ewm_shortcode_update();
		}

		ewm_global_setting::ewm_activate_sv_post();

		echo json_encode([
			'post' => $_POST,
			'update_data' => $update_data
		]);
		
		wp_die();

	}

	public static function ewm_sv_create_new_post(){
		// Create new post and return // Create Metadata
		$meta_arr_box   = '' ;
		
		$meta_key = 'gs_settings_list';
		$current_user_id = get_current_user_id() ;
		$_data_title = '' ;
		$_actual_data = '' ;

		$post_data = [
			"post_author"   => $current_user_id ,
			"post_date"     => date('Y-m-d H:i:s') ,
			"post_date_gmt" => date('Y-m-d H:i:s') , 
			"post_content"  => $_actual_data ,
			"post_title"    => $_data_title ,
			"post_excerpt"  =>  $_data_title,
			"post_status"   => "inactive",
			"comment_status"=> "open",
			"ping_status"   => "closed",
			"post_password" => "",
			"post_name"     =>  $_data_title,
			"to_ping"       => "",
			"pinged"        => "",
			"post_modified" => date('Y-m-d H:i:s'),
			"post_modified_gmt"=> date('Y-m-d H:i:s'),
			"post_content_filtered" => "",
			"post_parent"   => 0,
			"guid"          => "",
			"menu_order"    => 0,
			"post_type"     => "ewmGsS",
			"post_mime_type"=> "",
			"comment_count" => "0",
			"filter"        => "raw",
		];
	
		global $wp_error;

		$ewm_gs_global_setting_post_id = wp_insert_post( $post_data , true );
		$meta_value = [
			"ewm_data_title"	=> '' ,
			"ewm_data_type"		=> '' ,
			"ewm_actual_data"	=> '' ,
			"ewm_shortcode"		=> ''
		];

		foreach( $meta_value  as $meta_k => $meta_v ) {
			$meta_arr_box   = add_post_meta( $ewm_gs_global_setting_post_id, $meta_k, $meta_v );
		}

		$_arr_box = [
			'data' => $_POST,
			'edit_id' => $ewm_gs_global_setting_post_id
		];

		echo json_encode( $_arr_box );

		wp_die();

	}

	public static function create_val_cat( $args ){

		$current_user_id = get_current_user_id();

		// Create post
		$post_data = [
			"post_author"   => $current_user_id,
			"post_date"     => date('Y-m-d H:i:s'),
			"post_date_gmt" => date('Y-m-d H:i:s'),
			"post_content"  => $_POST['ewm_cat_title'],
			"post_title"    => $_POST['ewm_cat_title'],
			"post_excerpt"  => $_POST['ewm_cat_title'],
			"post_status"   => "active",
			"ping_status"   => "closed" ,
			"post_name"     => $_POST['ewm_cat_title'],
			"post_modified" => date('Y-m-d H:i:s') ,
			"post_modified_gmt"=> date('Y-m-d H:i:s'),
			"post_type"     => "ewm_sv_v_cat",
		];
	
		global $wp_error;

		$_post_id = wp_insert_post( $post_data , true );

		return [
			'post_id' => $_post_id,
			'title' => $_POST['ewm_cat_title']
		];

	}

	public static function ewm_sv_input_cats(){

		$title = $_POST['ewm_cat_title'];

		// search if the category exists and select it
		$args = array(
            "post_type" => "ewm_sv_v_cat",
            "post_status" => "active",
            "posts_per_page" => "-1",
            "s" => $title
        );
        $query = get_posts( $args );

		// if it doesn't exist create it
		$post_exist = 0 ;

		if( is_array( $query) ){
			$post_exist = count( $query );
		}

		$ewm_item_list = [
			'title' => '',
			'id' => ''
		];

		if( $post_exist == 0 ){
			// create it
			$ewm_g_v = ewm_global_setting::create_val_cat( $_POST );
			$create_val_cat = $ewm_g_v['post_id'];
			$ewm_item_list = [
				'title' => $ewm_g_v['title'],
				'id' => $ewm_g_v['post_id']
			];
		}
		else{
			$create_val_cat = $query['0']->ID;
			$ewm_item_list = [
				'title' => $query['0']->post_title,
				'id' => $query['0']->ID
			];
		}

		echo json_encode([
			'post' => $_POST,
			'create_val_cat' => $create_val_cat,
			'item_list' => $ewm_item_list
		]);

		wp_die();

	}

	public static function ewm_sv_search_cats(){

		$title = $_POST['search_term'];

		$args = array(
            "post_type" => "ewm_sv_v_cat",
            "post_status" => "active",
            "posts_per_page" => "-1",
            "s" => $title
        );

        $query = get_posts( $args );
		$item_list = [];

		$query_count = 0;

		foreach( $query  as $value_p ){
			$query_count =  count($query);
			$item_list[ $value_p->ID ] = [
				'title' => $value_p->post_title,
				'id' => $value_p->ID
			];
		}

		echo json_encode([
			'Post'=> $_POST,
			'query_count'=>$query_count,
			'item_list'=>$item_list
		]);

		wp_die();

	}

	public static function ewm_populate_single_sg_val( $args = [] ){

		$ewm_gs_v_data = get_post( $_POST[ 'ewm_gs_v_id' ] );

		$ewm_gs_v_data_ID = $ewm_gs_v_data->ID;

		//get categories
		$ewm_cat_list = get_post_meta( $_POST[ 'ewm_gs_v_id' ] ,'ewm_cat_list', true );
		$ewm_data_l = [];

		if( !is_array($ewm_cat_list) ){
			$ewm_cat_list = [];
		}

		foreach( $ewm_cat_list as $ewm_k => $ewm_v ){
			if($ewm_v == 'true') {
				$post_d = get_post($ewm_k);
				if(is_object($post_d)) {
					$ewm_data_l[$post_d->ID] = [
						'id' => $post_d->ID,
						'title' => $post_d->post_title
					];
				}
			}
		}

		// var_dump($ewm_data_l);
		// end of get categories

		$final_vals = [
			'ewm_data_type' => get_post_meta( $ewm_gs_v_data_ID , "ewm_data_type" , true ),
			'ewm_data_title' => $ewm_gs_v_data->post_title, // get_post_meta( $ewm_gs_v_data_ID , "ewm_data_title" , true ),
			'ewm_post_id' => get_post_meta( $ewm_gs_v_data_ID , "ewm_post_id" , true ),
			'ewm_actual_data' => $ewm_gs_v_data->post_content, //get_post_meta( $ewm_gs_v_data_ID , "ewm_actual_data" , true ),
			'ewm_data_shortcode' => get_post_meta( $ewm_gs_v_data_ID , "ewm_data_shortcode" , true ),
			'ewm_categories' => $ewm_data_l
		];

		echo json_encode([
			'post' 	=> $_POST,
			'final' => $final_vals
		]);

		wp_die();

	}

	public static function ewm_esv_show_preview( $atts = array() )
	{
		$admin_details = strpos( $_SERVER["REQUEST_URI"], 'show-esvd-preview' );
		
		if ( $admin_details !== false ) {

			// http://workshop-1.com/show-esvd-preview
			// if filter is activated? filter : forward to google link
			include dirname(__FILE__).'/templates/ewm_show_esvd_preview.php';
		
		}

	}

	public static function gs_shortcode_display( $args = [] ) {

		// $global_settings_session = ewm_global_setting::global_settings_session();

		// if( array_key_exists( $args['item'] , $global_settings_session ) ) {

			// $gs_display_item = ewm_global_setting::gs_display_item( $global_settings_session[ $args['item'] ] );
			$gs_display_item = ewm_global_setting::gs_display_item( $args['item'] );

		// }
		// else {

			// $gs_display_item = 'The entered title is missing';

		// }

		return $gs_display_item;

	}

	public static function gs_display_item( $args = [] ){

		$ewm_list_object = get_posts([
			"post_status"   	=> "active",
			"post_type"     	=> "ewmGsS",
			"posts_per_page"	=> "-1",
			"s" => $args,
		]);

		$item_found = false;
		foreach( $ewm_list_object as $object_k => $object_v ){
			if( $object_v->post_title == $args ){
				$item_found = true;
				$ewm_data_type = get_post_meta( $object_v->ID, 'ewm_data_type', true );
				$ewm_actual_data = $object_v->post_content;
			}
		}

		$html_string = '';

		if($item_found) {
			return ewm_global_setting::{$ewm_data_type}( [ "ewm_actual_data" => $ewm_actual_data ] );
		}
		else{
			return 'Item "'. $args .'" not found.';
		}

	}

	public static function gs_delete_submission(){

		$post_id = $_POST['data_gs_delete_id'] ; // ewm_global_setting::ewm_gs_get_global_post_id() ;

		// $meta_key       	= 'gs_settings_list' ;
		// $args_previous_data = ewm_global_setting::process_previous_value( $_POST['data_previous_data'] ) ; // $_POST['data_previous_value'];
		// $serialize_data = maybe_unserialize( $args_previous_data );
		// session_start();
		// $args_previous_data = maybe_serialize( $_SESSION["ewm_gs_arr"][ $_POST['data_title_old'] ] );
		// $ewm_gs_link_list	= delete_post_meta( $post_id , $meta_key, $args_previous_data ) ;

		$deleted_post_id = wp_delete_post( $post_id ); //$meta_key, $args_previous_data );

		// var_dump( $ewm_gs_link_list );
		// wp_die();
		// ewm_global_setting::gs_clear_session_data();
	
		echo json_encode([
			'data' 		=> $_POST ,
			'return'	=> $deleted_post_id //$ewm_gs_link_list
		]);

		wp_die() ;

	}

	public static function gs_clear_session_data(){

		// [ewm_global item=”home page image"]
		//if( array_key_exists( 'ewm_gs_arr' , $_SESSION ) ){

			// unset( $_SESSION['ewm_gs_arr'] );

		//}

		// return $global_settings_session = ewm_global_setting::global_settings_session() ;

	}

	public static function display_data(){

		// $post_id        = ewm_global_setting::ewm_gs_get_global_post_id() ;

		// echo "Post Id: ";

		// var_dump( $post_id );

		// echo "<br>";

		// $gs_settings_list = get_post_meta( $post_id, 'gs_settings_list' );

		// var_dump( $gs_settings_list );

		ewm_global_setting::gs_clear_session_data();

		$global_settings_session = ewm_global_setting::global_settings_session() ;

		// var_dump( $global_settings_session );

	}

	public static function global_settings_session(){

		$gs_settings_arr = [] ;

		if( array_key_exists( 'ewm_gs_arr' , $_SESSION )){

			if( isset( $_SESSION['ewm_gs_arr'] ) ){

				$gs_settings_arr = $_SESSION['ewm_gs_arr'] ;

			}

		}
		else{

            $post_id        = ewm_global_setting::ewm_gs_get_global_post_id() ;

            $gs_settings_list = get_post_meta($post_id, 'gs_settings_list');

            $gs_settings_arr = [];

            foreach ($gs_settings_list as $key => $value) {

                $value = maybe_unserialize($value);

                $gs_settings_arr[ $value['ewm_data_title'] ] = $value ;

            }

            $_SESSION['ewm_gs_arr'] = $gs_settings_arr ;

        }

		return $gs_settings_arr;

	}

	public static function gs_preview_details( $args = [] ){

		$gs_preview_details = '';
		if( $args['ewm_data_type'] == 'img' ){
			$gs_preview_details = '<img height="120" src="'.$args['ewm_actual_data'] .'">';
		}
		elseif( $args['ewm_data_type'] == 'text' ){
			$gs_preview_details = '[Text content]';
		}
		else{
			$stl_txt = strlen( $args['ewm_actual_data'] );
			// if( $stl_txt > 250 ){
				$gs_preview_details = substr( $args[ 'ewm_actual_data' ] , 0, 250) . '...' ;
			// } else{	$gs_preview_details = $args['ewm_actual_data']; }
		}

		return $gs_preview_details ; 

	}

	public static function get_single_of_gs( $args = [] ){

		$ewm_sg_item_id = $args['ewm_sg_item_id'];
		$sg_title = $args['sg_title'];//$ewm_single_object->post_title ;
		$gs_preview_details = $args['gs_preview_details'];//$ewm_single_object->post_content ;
		$sg_data_type = get_post_meta(  $ewm_sg_item_id , 'ewm_data_type' , true );
		$sg_actual_data = $args['sg_actual_data'];

		if( strlen( $sg_title ) > 0 &&  strlen( $sg_data_type ) > 0 && strlen( $sg_actual_data ) > 0 ){
			$gs_single_unserialized = [
				"ewm_data_title" => $sg_title,
				"ewm_data_type" => $sg_data_type ,
				"ewm_actual_data" => $sg_actual_data
			];
			// $gs_preview_details = ewm_global_setting::preview_framework() ; // gs_preview_details( $gs_single_unserialized );
			$gs_preview_details = ewm_global_setting::gs_preview_details( $gs_single_unserialized );

		}
		else{

			$gs_single_unserialized = [
				"ewm_data_title" => '',
				"ewm_data_type" => '' ,
				"ewm_actual_data" => ''
			];

		}

		return [
			// 'sg_serialized_value'	=> $gs_single_v,
			'sg_title' 				=> $sg_title,
			'gs_preview_details' 	=> $gs_preview_details,
			'sg_data_type'			=> $gs_single_unserialized['ewm_data_type'],
			'sg_actual_data'		=> $gs_single_unserialized['ewm_actual_data'],
			"ewm_sg_item_id"		=> $ewm_sg_item_id//$gs_single_k,
		];

	}

	public static function get_list_of_gs(){

		$post_id = ewm_global_setting::ewm_gs_get_global_post_id() ;

		$gs_settings_list = get_post_meta( $post_id, 'gs_settings_list' );

		$final_arr = [];

		/*
		if( session_id() == "" ){
			session_start();
		}
		*/

		//var_dump( $_SESSION["ewm_gs_arr"]["First long text "] );
		//echo '<br><br>';

		foreach( $gs_settings_list as $gs_single_k => $gs_single_v ){

			$gs_single_unserialized = maybe_unserialize( $gs_single_v );

			// $gs_single_v = preg_replace('/"/', '\"', $gs_single_v );
			// $gs_single_v = sanitize_text_field( $gs_single_v ) ;
			// var_dump( $gs_single_v );
			// echo '</br>';
			// var_dump( $gs_single_unserialized );

			$gs_single_v = preg_replace('/\s+/', '__', $gs_single_v );

			$gs_preview_details = ewm_global_setting::gs_preview_details( $gs_single_unserialized );

			$gs_single_line = [
				'sg_serialized_value'	=> $gs_single_v,
				'sg_title' 				=> $gs_single_unserialized['ewm_data_title'],
				'gs_preview_details' 	=> $gs_preview_details,
				'sg_data_type'			=> $gs_single_unserialized['ewm_data_type'],
				'sg_actual_data'		=> $gs_single_unserialized['ewm_actual_data'],
				"ewm_sg_item_id"		=> $gs_single_k,
			];

			array_push( $final_arr, $gs_single_line );

		}

		$final_arr = array_reverse( $final_arr );

		return $final_arr;

	}

	public static function ewm_gs_get_global_post_id( $args = [] ){
	
		$current_user_id = get_current_user_id() ;

		$ewm_gs_global_setting_post_id = get_option( 'ewm_gs__id' ) ;
	
		if( strlen( $ewm_gs_global_setting_post_id) > 0 ){
			return $ewm_gs_global_setting_post_id ;
		}

		// Create post
		$post_data = [
	
			// ["ID"]=> int(1464) 
			"post_author"   => $current_user_id ,
			"post_date"     => date('Y-m-d H:i:s') ,
			"post_date_gmt" => date('Y-m-d H:i:s') , 
			"post_content"  => 'gs global setting post content' ,
			"post_title"    => 'gs global setting post title' ,
			"post_excerpt"  => 'gs global setting post excerpt' ,
			"post_status"   => "inactive" ,
			"comment_status"=> "open" ,
			"ping_status"   => "closed" ,
			"post_password" => "" ,
			"post_name"     => 'gs global setting post name' ,
			"to_ping"       => "" ,
			"pinged"        => "" ,
			"post_modified" => date('Y-m-d H:i:s') ,
			"post_modified_gmt"=> date('Y-m-d H:i:s') ,
			"post_content_filtered" => "" ,
			"post_parent"   => 0 , 
			"guid"          => "" , 
			"menu_order"    => 0 ,
			"post_type"     => "ewmGsS" ,
			"post_mime_type"=> "" ,
			"comment_count" => "0" ,
			"filter"        => "raw" ,
	
		] ;
	
		global $wp_error;

		$ewm_gs_global_setting_post_id = wp_insert_post( $post_data , true ) ;
	
		add_option( 'ewm_gs__id' ,  $ewm_gs_global_setting_post_id ) ;
		
		return $ewm_gs_global_setting_post_id ;
	
	}

	public static function 	add_global_settings( $args = [] ){

		// Create Metadata
		$meta_arr_box   = '' ;
		// $post_id        = ewm_global_setting::ewm_gs_get_global_post_id() ;
		$meta_key       = 'gs_settings_list';
		$current_user_id = get_current_user_id() ;

		$post_data = [
			// ["ID"]=> int(1464)
			"post_author"   => $current_user_id ,
			"post_date"     => date('Y-m-d H:i:s') ,
			"post_date_gmt" => date('Y-m-d H:i:s') , 
			"post_content"  => $_POST["actual_data"] ,
			"post_title"    => $_POST["data_title"] ,
			"post_excerpt"  =>  $_POST["data_title"],
			"post_status"   => "inactive" ,
			"comment_status"=> "open" ,
			"ping_status"   => "closed" ,
			"post_password" => "" ,
			"post_name"     =>  $_POST["data_title"] ,
			"to_ping"       => "" ,
			"pinged"        => "" ,
			"post_modified" => date('Y-m-d H:i:s') ,
			"post_modified_gmt"=> date('Y-m-d H:i:s') ,
			"post_content_filtered" => "" ,
			"post_parent"   => 0 , 
			"guid"          => "" , 
			"menu_order"    => 0 ,
			"post_type"     => "ewmGsS" ,
			"post_mime_type"=> "" ,
			"comment_count" => "0" ,
			"filter"        => "raw" ,
	
		] ;
	
		global $wp_error;

		$ewm_gs_global_setting_post_id = wp_insert_post( $post_data , true ) ;
		$meta_value = [
			"ewm_data_title"	=> trim($_POST["data_title"]) ,
			"ewm_data_type"		=> trim($_POST["data_type"]) ,
			"ewm_actual_data"	=> trim($_POST["actual_data"]),
			"ewm_shortcode"		=> trim($_POST["data_shortcode"])
		];

		foreach( $meta_value  as $meta_k => $meta_v ) {
			$meta_arr_box   = add_post_meta( $ewm_gs_global_setting_post_id, $meta_k, $meta_v );
		}

		return [
			'data' 		=> $_POST,
			'response'	=> $meta_arr_box
		];

	}

	public static function process_previous_value( $args = '' ){

		$args = str_replace('__',' ', $args );

		$args  = trim( stripslashes( $args ) );

		$args = preg_replace('/\\\/', '', $args );

		$args = substr( $args, 2 );

		$args = mb_substr( $args , 0, -2); //substr($str, -2 );

		return $args;

	}

	public static function 	edit_global_settings( $args = [] ){

		$array_list = [
			'ewm_data_type' => $_POST['ewm_data_type'],
			'ewm_data_title' => $_POST['ewm_data_title'],
			'ewm_post_id' => $_POST['ewm_post_id'],
			'ewm_actual_data' => $_POST['ewm_actual_data'],
			'ewm_data_shortcode' => $_POST['ewm_data_shortcode'],
		];

		$meta_arr_box = [];

		foreach( $array_list as $g_k => $g_v ){

			$g_k_meta = get_post_meta( $_POST['ewm_post_id'] , $g_k, true );
			$m_id = '';
			if( is_array( $g_k_meta ) ){
				$m_id = update_post_meta( $_POST['ewm_post_id'] , $g_k, $g_v );
			}
			else{
				$m_id = add_post_meta( $_POST['ewm_post_id'] , $g_k, $g_v, true );
			}
			$meta_arr_box[$g_k] = [
				'm_id' => $m_id,
			];

		}

		$ewm_p_d =[
			'ID' 			=> $_POST['ewm_post_id'] ,
			'post_title' 	=> $_POST['ewm_data_title'],
			'post_content' 	=> $_POST['ewm_actual_data'],
			'post_status' 	=> 'active',
		];

		wp_update_post( $ewm_p_d );

		return [
			'data' 		=> $_POST,
			'response'	=> $meta_arr_box
		] ;
		
	}

	public static function save_values( $args = [] ){

		// return $_POST; update_type
		// If it's new, create a new global setting // else edit a new global setting
		$return_value = [];

		/*if($_POST['data_new_or_update'] == 'new'){
            $return_value = ewm_global_setting::add_global_settings( $args );
        }
		elseif( $_POST['data_new_or_update'] == 'update'){
			*/
        $return_value = ewm_global_setting::edit_global_settings( $args );
        //}

		// ewm_global_setting::gs_clear_session_data();

		return $return_value;
	
	}

	public static function gs_process_submission(){

		$gs_process_submission = ewm_global_setting::save_values(  $_POST ) ;

		echo json_encode([
			'gs_process_submission' => $gs_process_submission,
			'post' => $_POST
		]);

		wp_die();

	}

}

ewm_global_setting::init();


