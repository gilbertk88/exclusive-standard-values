<style>
	#wpfooter {
		position: relative !important;
	}
	.ewm_gs_cat_list_name{
		float: left;
    	font-size: larger;
	}
	.ewm_gs_cat_list_close{
		float: right;
		padding: 0px 6px;
		border: 0px solid gray;
		border-radius: 15px;
		margin-left: 10px;
		font-size: 10px;
		display:none;
		color: #ccc;
	}
	.ewm_clear_category_selection{
		float: ;
		border-radius: 35px;
		border: 1px solid #ccc8;
		cursor: pointer;
		margin-left: 5px;
		padding: 8px 15px;
		background: #fff;
		background: #aaff6d2e;
	}
</style>

<?php

$ewm_sv_v_cat = get_posts([
	"post_status"   => "active",
	"post_type"     => "ewm_sv_v_cat",
]);

?>
<div class="table-head-globalSettings">
	<div class="table-head-global-single">
		<div class="table-head-global-single-inner">
			<div class="ewm_filter_by_cat">Filter by Categories</div>
			<span class="ewm_show_hide_category_edits">Delete Categories(show/ hide)</span> 
			<span class="ewm_clear_category_selection">Clear Category Selection</span>
		</div>
		<div class="ewm_gs_top_layer">
			<?php

			foreach( $ewm_sv_v_cat as $k_n => $v_n ){
				echo '<div class="ewm_gs_cat_list ewm_gs_cat_list_'.$v_n->ID.'" data-cat-id="'.$v_n->ID.'">
					<div class="ewm_gs_cat_list_name" data-cat-id="'.$v_n->ID.'">'. $v_n->post_title .'</div>
					<div class="ewm_gs_cat_list_close" data-cat-id="'.$v_n->ID.'">
						<span class="dashicons dashicons-trash" data-raw-id=""></span>
					</div>
				</div>';
			}

			?>
			
		</div>
	</div>
</div>

<div class="ewm-form-group-gloabal-settings">

	<span class="ewmglobal_title">Add edit and delete global options</span>
	
	<span class="ewmglobal_new_button"> + New </span>

</div>

<?php

// list of object
$ewm_list_object = ewm_global_setting::get_list_of_gs();

$ewm_list_object = get_posts([
	"post_status"   	=> "active",
	"post_type"     	=> "ewmGsS",
	"posts_per_page"	=> "-1",
]);

//var_dump( get_post(2459) );
//echo '<br><br>';
//var_dump( get_post_meta(2463) );

/*
$post_data = get_posts([
	"post_status"   => "active",
	"post_type"     => "ewmGsS",
	'meta_query'	=> [
		[
			// 'relation' => 'AND',
			[
				'key'     => 'ewm_data_title',
				'value'   => 'home',
				'compare' => '=',
			],
		]
	],
]);
*/

// var_dump( $post_data );
// echo '<br><br><br>';
// var_dump( get_post_meta( $post_data[0]->ID ) );

/*
	$post_data_v = get_posts([
		"post_status"   => "active",
		"post_type"     => "ewmGsS",
		//"post_title"    
		's' => 'homer',
		'meta_query'	=> [
			[
				'relation' => 'AND',
				[
					'key'     => 'ewm_data_title',
					'value'   => $args['ewm_shortcode'],
					'compare' => '=',
				],
			]
		],
	]);

	var_dump( $post_data_v );
*/

$full_table_list = '
<input type="hidden" id="form-gs-title-hidden-f">
<div class="table-head-globalTotal"> ';
	$full_table_list .= '<div class="ewm_b_wrapper">';

	foreach( $ewm_list_object as $ewm_single_object ){
		// var_dump($ewm_single_object);
		$ewm_data_type = get_post_meta(  $ewm_single_object->ID , 'ewm_data_type' , true );
		$ewm_single_object = ewm_global_setting::get_single_of_gs([
			'ewm_sg_item_id' => $ewm_single_object->ID,
			'sg_title' => $ewm_single_object->post_title,
			'gs_preview_details' => $ewm_single_object->post_content,
			'sg_data_type' => $ewm_data_type,
			'sg_actual_data' => $ewm_single_object->post_content,
		]);

		if( strlen($ewm_single_object['sg_title']) ) {
			$ewm_s_v_t = '[ewm_standard_values item="'.$ewm_single_object['sg_title'] . '"]';
		}
		else{
			$ewm_s_v_t = 'Shortcode not set';
		}

		$ewm_ewm_cat_list = get_post_meta( $ewm_single_object["ewm_sg_item_id"], 'ewm_cat_list', true );

		$cat_class_list = '';

		if( is_array($ewm_ewm_cat_list) ){
			foreach($ewm_ewm_cat_list as $cat_k => $cat_v ){
				if($cat_v =='true') {
					$cat_class_list .= ' ewm_s_cat_' . $cat_k;
				}
			}
		}
				
		$full_table_list .= '
		<div class="ewm_single_list_item '.$cat_class_list.'" id="'.$ewm_single_object["ewm_sg_item_id"].'_single_line_id" data-ewm-single="'.$ewm_single_object["ewm_sg_item_id"].'">
			<div class="gs_data_content" id="'.$ewm_single_object["ewm_sg_item_id"].'_data_title">
				<div class="gs_data_content gs_data_content_h" id="'.$ewm_single_object["ewm_sg_item_id"].'_data_title">
					<center>'.$ewm_s_v_t.'<br><span class="ewm_data_type_t">(Type: '.$ewm_data_type.')</span></center>
				</div>
				<div class="gs_data_content gs_data_shortcode" id="'.$ewm_single_object["ewm_sg_item_id"].'_data_shortcode">
					<center>' . $ewm_single_object['gs_preview_details'] .' </center>
				</div>
				<div class = "table-head-global-single-edit" >
					<center>
						<div class = "gs_edit_single_par" id="gs_edit_single_par_'.$ewm_single_object["ewm_sg_item_id"].'">
							<span class="gs_delete_button dashicons dashicons-trash" data-d-title="'.$ewm_single_object['sg_title'] .'" data-gs-delete= "'. $ewm_single_object["ewm_sg_item_id"] .'" data-raw-id = "" ></span>
							<span class="gs_edit_button" data-gs-edit= "'. $ewm_single_object["ewm_sg_item_id"] .'" data-d-type="'. $ewm_single_object['sg_data_type'] .'" data-a-data="" data-d-title="'.$ewm_single_object['sg_title'] .'" href="#" >Open</span>
						</div>
					</center>
				</div>
			</div>
		</div> ';

	}

	$full_table_list .= '</div></div>' ;

echo $full_table_list ;

?>
</div>

<?php
	include dirname(__FILE__).'/global_popup.php';
?>
<input type="hidden" id="ewm_update_type-f">
