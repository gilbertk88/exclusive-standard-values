<style>
	.attachment-300x300{
		max-height: 120px !important;
		max-width: 120px !important;
		float: left !important;
	}
	#myprefix-preview-image{
		max-height: 120px !important;
		max-width: 120px !important;
		float: left !important;
	}
</style>

<div class="form-group-outer-glass">

	<div class="col-global-modal">

	<div class="form-group-manager-top">
		<span class="gs-close-tab" >Close[x]</span>
		<div class="gs-main-shortcode-area">
			[ewm_standard_values item="<span id="gs-input_unique_title"></span>" ]
		</div>
	</div>

	<div class="form-group-manager-top-selectors">
		<div class="form-group-field-selector">
			<div  class="ewm_t_top_d">Select an Input type</div>
			<div class="ewm_m_content">
				<select id="form-gs-choose-f" class="gs-single-setting-t" >
					<option value="choose"> Please Choose... </option>
					<option value="text"> Text </option>
					<option value="img">Image</option>
					<option value="url">URL</option>
					<option value="number">Number</option>
					<option value="percentage">Percentage</option>
					<option value="decimal">Decimal</option>
				</select>
			</div>
			<span id="form-gs-choose-f-message"></span>
		</div>
		<div class="form-group-field-selector sv_relevant_if_input_ex" >
			<div class="ewm_t_top_d">Enter unique shortcode</div>
			<div>
				<input id="form-gs-title-f" class="gs-single-setting-t" type="text" name="form-gs-title-f" placeholder="Name/ Key (must be unique)">
			</div>
			<span id="form-gs-title-f-message"></span>
		</div>
		
	</div>
	<div class="gs-main-content-main-div sv_relevant_if_input_ex_2">

		<div class="gs-main-choose">
			<center>Please Choose Input Type</center>
		</div>
		<div class="gs-main-text gs-single-setting-parent-d">

			<div class="ewm_gs_single_inputs">
				<?php
				$text= 'hh'; //get_post_meta( $post, 'SMTH_METANAME' , true );
				wp_editor( htmlspecialchars($text), 'gs-main-text_input', $settings = array('textarea_name'=>'gs-main-text_input') );
				?>
			</div>
			<div id="gs-main-text_input_message" class="gs_error_message_pop"></div>

		</div>
		<div class="gs-main-img gs-single-setting-parent-d">

			<div class="ewm_gs_single_inputs">
				<?php $image_id = get_option( 'myprefix_image_id' );
				if( intval( $image_id ) > 0 ) {
					// Change with the image size you want to use
					$image = wp_get_attachment_image( $image_id, 'medium', false, array( 'id' => 'myprefix-preview-image' ) );
				} else {
					// Some default image
					$image = '<img id="myprefix-preview-image" src="" />';
				}
				echo $image; ?>
				<input type="hidden" name="myprefix_image_id" id="myprefix_image_id" value="<?php echo esc_attr( $image_id ); ?>" class="regular-text myprefix-spacetop" />
				<input type='button' class="button-primary myprefix-spacetop" value="<?php esc_attr_e( 'Select an Image', 'mytextdomain' ); ?>" id="myprefix_media_manager"/>
				<br>
				<input type="text" class="gs-single-setting-i gs-single-setting-input-f" id="gs-main-img_input" placeholder="Enter Image URL">
			
			</div>

			<div id="gs-main-img_input_message" class="gs_error_message_pop"></div>

		</div>
		<div class="gs-main-url gs-single-setting-parent-d">
			<div class="ewm_gs_single_inputs">
				<input type="url" class="gs-single-setting-t gs-single-setting-input-f" id="gs-main-url_input" placeholder="Enter URL">
			</div>
			<div id="gs-main-url_input_message" class="gs_error_message_pop"></div>
		</div>
		<div class="gs-main-number gs-single-setting-parent-d">
			<div class="ewm_gs_single_inputs">
				<input type="number" class="gs-single-setting-t gs-single-setting-input-f" id="gs-main-number_input" placeholder="Enter Number">
			</div>
			<div id="gs-main-number_input_message" class="gs_error_message_pop"></div>
		</div>
		<div class="gs-main-percentage gs-single-setting-parent-d">
			<div class="ewm_gs_single_inputs">
				<input type="number" class="gs-single-setting-t gs-single-setting-input-f" id="gs-main-percentage_input" placeholder="Enter Percentage">
			</div>
			<div id="gs-main-percentage_input_message" class="gs_error_message_pop"></div>
		</div>
		<div class="gs-main-decimal gs-single-setting-parent-d">
			<div class="ewm_gs_single_inputs">
				<input type="number" class="gs-single-setting-t gs-single-setting-input-f" id="gs-main-decimal_input" placeholder="Enter Decimal">
			</div>
			<div id="gs-main-decimal_input_message" class="gs_error_message_pop"></div>
		</div>

		<div class="ewm_cat_sections_list">

			<div class="ewm_active_cat_list"></div>
			<div class="ewm_active_cat_list_manager">
				<input type="button" value="Add Category[+]" class="ewm_add_cat_btn">
			</div>

			<div class="ewm_cat_sections_list_t"></div>
			<div class="ewm_cat_sections_list_b"></div>
			<div class="ewm_cat_sections_list_i">
				<div class="ewm_cat_sections_list_i_t">
					<center>Enter Category</center>
				</div>
				<div class="ewm_cat_sections_list_i_m">
					<input class="ewm_cat_input_sect" type="search">
					<input class="ewm_cat_input_sect_b" type="button" value="Add">
					<span class="ewm_cat_input_sect_close">x</span>
				</div>
				<div class="ewm_cat_sections_list_option"></div>
			</div>

		</div>

		<!-- Hidden data points -->

		<input type="hidden" value="" id="gs-input_type_hidden_shortcode">

		<input type="hidden" value="" id="gs-input_type_hidden_data_type">

		<input type="hidden" value="" id="gs-main-hidden-previous-value">		

		<input type="hidden" value="" id="gs-main-hidden-new-or-update-entry">

		<input type="hidden" value="" id="gs-main-hidden-edit-id">

		<div class="gs-main-button-container">
			<center>
				<input type="button" class="gs-single-setting-button" id="gs-input_type_button" value="Add">
			</center>
		</div>
	</div>

	</div>

</div>
