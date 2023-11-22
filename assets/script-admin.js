jQuery(document).ready(function($) {

	$('#form-gs-choose-f').change( function(e) {

		if( $( this ).val() !== 'choose' ){
			$('.sv_relevant_if_input_ex').show();
		}
		else{
			$('.sv_relevant_if_input_ex').hide();
			$('.sv_relevant_if_input_ex_2').hide();
		}

		form_gs_choose_f = [ ".gs-main-choose",".gs-main-text",".gs-main-img",".gs-main-url",".gs-main-number",".gs-main-percentage",".gs-main-decimal" ];

		$.each( form_gs_choose_f , function( index, value ) {
			$(value).hide();
		});

		$( ".gs-main-"+this.value ).show();
		$('.gs-single-setting-input-f').val('');
		$("#gs-input_type_hidden_data_type").val( this.value );

		// update choice form-gs-choose-f	
		ewm_sv_choose_v = $( this ).val();
		ewm_sv_update_choice_v({
			'update_k' : 'ewm_data_type',
			'update_v' : ewm_sv_choose_v
		});

	});

	var ewm_cat_reset_clear = function () {
		$('.ewm_cat_input_sect').val('');
		$('.ewm_cat_sections_list_option').html('');
		$('.ewm_active_cat_list').html('');
	}

	function gs_close_modal_popup(){

		form_gs_choose_f = [ ".gs-main-choose",".gs-main-text",".gs-main-img",".gs-main-url",".gs-main-number",".gs-main-percentage",".gs-main-decimal" ];

		$.each( form_gs_choose_f , function( index, value ) {
			$(value).hide();
			$(value).val('');
		});

		form_input_fs = [ "#gs-main-text_input", "#gs-main-img_input", "#gs-main-url_input", "#gs-main-number_input", "#gs-main-percentage_input", "#gs-main-decimal_input" ];

		$.each( form_input_fs , function( index, value ) {
			$(value).val('');
		});

		$('#form-gs-title-f').val('');
		$('#form-gs-choose-f').val('choose');
		$(".gs-main-choose" ).show();
		$('.form-group-outer-glass').hide();
		$('#gs-input_type_button').val('Add');
		$('#gs-input_unique_title').html('');
		$('#myprefix-preview-image').attr('src', '');
		$('#myprefix-preview-image').attr('srcset', '');
		$('#myprefix-preview-image').attr('width', '0px');
		$('#myprefix-preview-image').attr('height', '0px');
		ewm_cat_reset_clear();

		location.reload();

	}

	$('.gs-close-tab').click(function(){
		gs_close_modal_popup();
	});

	$('.ewm_gs_cat_list').click(function(){

		ewm_cat_id = $( this ).data('cat-id');
		$('.ewm_gs_cat_list').css({'border':'2px solid #ccc4'});
		$( this ).css({'border':'2px solid #333'});
		$('.ewm_single_list_item').hide();
		$( '.ewm_s_cat_' + ewm_cat_id ).show();

	})

	$('.ewm_clear_category_selection').click(function(){
		$('.ewm_single_list_item').show();
		$('.ewm_gs_cat_list').css({'border':'2px solid #ccc4'});
	})

	$('.ewm_show_hide_category_edits').click( function(){
		$( '.ewm_gs_cat_list_close' ) .toggle();
	});

	var ewm_replace_values_pop =  function( args = {} ){
		/*
		args.ewm_data_type
		args.ewm_data_title
		args.ewm_post_id
		args.ewm_actual_data
		args.ewm_data_shortcode
		args.ewm_categories
		*/

		$('#form-gs-title-f').val( args.ewm_data_title ); //>> Title
		$('#form-gs-title-hidden-f').val( args.ewm_data_title );

		$('#form-gs-choose-f').val( args.ewm_data_type ); //>> Set data type
		$('#form-gs-choose-f option[value=' + args.ewm_data_type + ']').attr('selected','selected');
		$('#form-gs-choose-f').attr('disabled', 'disabled');

		if( args.ewm_data_type == 'img' ){
			$('#myprefix-preview-image').attr( 'src', args.ewm_actual_data );
			$('#myprefix_image_id').val( args.ewm_actual_data );
		}

		tinymce.get( 'gs-main-text_input' ).setContent( args.ewm_actual_data ) ;

		// args.ewm_data_type
		$('#gs-main-' + args.ewm_data_type + '_input').val( args.ewm_actual_data );
		$('#gs-input_unique_title').html( args.ewm_data_title );
		$('#gs-main-hidden-edit-id').val( args.ewm_post_id ); // >> Set the line id (for updates)

		form_gs_choose_f = [ ".gs-main-choose",".gs-main-text",".gs-main-img",".gs-main-url",".gs-main-number",".gs-main-percentage",".gs-main-decimal" ];

		$.each( form_gs_choose_f , function( index, value ) {
			$(value).hide();
		} ) ;

		$( ".gs-main-" + args.ewm_data_type ).show();

		// hide all until input has been entered.
		$('.sv_relevant_if_input_ex').show();
		$('.sv_relevant_if_input_ex_2').show();

		Object.entries( args.ewm_categories ).forEach(([key,keyword_value]) => {
			$('.ewm_active_cat_list').append('<div class="ewm_sv_cat_l ewm_sv_cat_l_'+keyword_value.id+'" data-category-id="'+keyword_value.id+'"> '+ keyword_value.title + ' <span class="ewm_remove_cat_button" data-category-id="'+keyword_value.id+'">X</span></div>');
		});

		ewm_listen_cat_list();

	}

	var ewm_listen_cat_list =  function(){
		$('.ewm_remove_cat_button').click(function () {
			
			ewm_category_id = $( this ).data('category-id');
			// remove cat on list display
			$('.ewm_sv_cat_l_' + ewm_category_id ).remove();

			// remove cat on server 
			ewm_remove_cat_button( ewm_category_id );
		});
	}

	var ewm_remove_cat_button = function( cat_id ) {

		// args.edit_id; // ewm_replace_values({ }); // e.preventDefault();
        var form_data = new FormData() ;
        form_data.append( 'action', 'ewm_remove_cat_button' );
		form_data.append( 'ewm_gs_cat_id' , cat_id );
		form_data.append( 'ewm_post_id', $('#gs-main-hidden-edit-id').val() );

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ){
				console.log( 'ewm_remove_cat_button' );
				console.log( response );
				response = JSON.parse( response );
				// ewm_replace_values_pop( response.final );
            },
            error: function ( response ) {
                console.log( response ) ;
            }
        });

	}

	$('.ewm_remove_cat_button').click(function(e) {
		ewm_category_id = $( this ).data('category-id');
		// ewm_category_id
		ewm_remove_cat_button( ewm_category_id );
	});

	var ewm_replace_values =  function( args = {} ){

		$('#form-gs-title-f').val( $( this ).data('d-title') ); //>> Title
		$('#form-gs-title-hidden-f').val( $( this ).data('d-title') );

		$('#form-gs-choose-f').val( $( this ).data('d-type') ); //>> Set data type
		$('#form-gs-choose-f option[value=' + $( this ).data('d-type') + ']').attr('selected','selected');

		if( $('#form-gs-choose-f').val() == 'img' ){
			$('#myprefix-preview-image').attr( 'src', $( this ).data('a-data') );
		}

		tinymce.get( 'gs-main-text_input' ).setContent( $( this ).data('a-data') ) ;

		$('#gs-main-'+ $( this ).data('d-type') +'_input').val( $( this ).data('a-data') ) ; //>> Set main data
		$("#gs-input_type_hidden_shortcode").val('[ewm_standard_values item="'+$( this ).data('d-title') +'" ]');
		$('#gs-input_unique_title').html($( this ).data('d-title') );
		// $("#gs-input_type_hidden_data_type").val($( this ).data('d-type') ); //>> Set specific data type 
		$('#gs-main-hidden-previous-value').val( $( this ).data('previous-values') ); //>> Set the previous value to search it in meta data
		$('#gs-main-hidden-new-or-update-entry').val('update'); //>> Set if this is a new entry or an update
		$('#gs-main-hidden-edit-id').val( $( this).data('gs-edit') ); //>> Set the line id (for updates)

		form_gs_choose_f = [ ".gs-main-choose",".gs-main-text",".gs-main-img",".gs-main-url",".gs-main-number",".gs-main-percentage",".gs-main-decimal" ];

		$.each( form_gs_choose_f , function( index, value ) {
			$(value).hide();
		} ) ;

		$( ".gs-main-" + $( this ).data('d-type') ).show();

		// Send data
		// Update the display

	}

	var ewm_update_single_popup =  function ( args = {} ){

		// args.edit_id; // ewm_replace_values({ }); // e.preventDefault();
        var form_data = new FormData() ;
        form_data.append( 'action', 'ewm_populate_single_sg_val' );
		form_data.append( 'ewm_gs_v_id' , args.edit_id );

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ){
				console.log(ewm_update_single_popup);
				console.log( response ) ;
				response = JSON.parse( response );
				ewm_replace_values_pop( response.final );
            },
            error: function ( response ) {
                console.log( response ) ;
            }
        });

	}

	function gs_validate_entries(){

		its_valid = true;

		// title/ key
		if( $("#form-gs-title-f").val() == "" ){
			$('#form-gs-title-f-message').html('Required field');
			$("#form-gs-title-f").css( { "border":"2px solid red" } );
			its_valid = false;
		}
		else{
			$('#form-gs-title-f-message').html('');
			$("#form-gs-title-f").css( { "border":"1px solid #80808036" } );
		}

		// choice
		if( typeof $("#form-gs-choose-f").val() == "undefined" || $("#form-gs-choose-f").val() == "choose" ){
			$('#form-gs-choose-f-message').html('Required field');
			$("#form-gs-choose-f").css( { "border":"2px solid red" } );
			its_valid = false;
		}
		else{
			$('#form-gs-choose-f-message').html('');
			$("#form-gs-choose-f").css( { "border":"1px solid #80808036" } );
		}

		gs_data_type = $("#gs-input_type_hidden_data_type").val();

		// main data
		if( $("#gs-main-"+gs_data_type +"_input").val() == '' ){
			$('#gs-main-'+gs_data_type+'_input_message').html('Required field');
			$("#gs-main-"+gs_data_type+"_input").css( { "border":"2px solid red" } );
			its_valid = false;
		}
		else{
			$('#gs-main-'+gs_data_type+'_input_message').html('');
			$("#gs-main-"+gs_data_type+"_input").css( { "border":"1px solid #80808036" } );
		}

		$(".gs-single-setting-t").change(function() {
			$( this ).css( { "border":"2px solid #ccc" } );
			$('#gs-main-'+gs_data_type+'_input_message').html('');
		})

		return its_valid ;

	}

	//console.log(wp.editor);
	$('.gs_edit_button').click( function(e){

		e.preventDefault();

		if( $('#form-gs-choose-f').val() == 'choose' ){
			$('.sv_relevant_if_input_ex').hide();
			$('.sv_relevant_if_input_ex_2').hide();
			$('.ewm_cat_sections_list_i').hide();
		}

		$('#ewm_update_type-f').val('edit');

		// Show modal dialog
		$('.form-group-outer-glass').show();
		$('#gs-input_type_button').val('Update');

		// Update HTML input fields
		ewm_d_title = '' ;
		ewm_d_type = '';
		ewm_a_data = '' ;

		ewm_update_single_popup( { 'edit_id' : $( this).data('gs-edit') } );

	} );

	$('.ewm_add_cat_btn').click(function(){
		$('.ewm_cat_sections_list_i').toggle();
	});

	$('.ewm_cat_input_sect_close').click(function(){
		$('.ewm_cat_sections_list_i').hide();
		$('.ewm_cat_sections_list_option').html('');
		$('.ewm_cat_input_sect').val('');
	})

	function ewm_gs_add_new_line( response ){

		gs_close_modal_popup();

		ewm_display_content_data = ewm_display_content( response );
 
		new_line = '<tr class="ewm_single_list_item" id="1_single_line_id">\
			<td class="gs_data_content">'+ response.data_title +'</td>\
			<td class="gs_data_content"> <center>'+ ewm_display_content_data +' </center></td>\
			<td class="table-head-global-single">\
				<a class="gs_edit_button" data-gs-edit="1" data-previous-values="``" data-d-type="percentage" data-a-data="34%" data-d-title="second" href="#">Edit</a> \
				<a class="gs_delete_button" data-gs-delete="1" href="#" data-previous-values="``" data-raw-id=""> Delete </a>\
			</td>\
		</tr>';

		$( new_line ).insertAfter( '.table-head-globalSettings' );

	}

	function ewm_display_content( response ){

		response_html = response.actual_data;
		if( response.data_type == 'img' ){
			response_html = '<center> <img width="100" src="'+ response.actual_data +'"> </center>';
		}

		return response_html;
		
	}

	function ewm_gs_update_line( response ){

		gs_close_modal_popup();
		location.reload();
		ewm_display_content_data = ewm_display_content( response );
		$( '#' + response.data_edit_id +'_data_shortcode').html( '<center>' + ewm_display_content_data + '</center>' ) ;
		$( '#' + response.data_edit_id +'_data_title').html( response.data_title ) ;
	
	}

	$('#gs-input_type_button').click(function(){

		if( gs_validate_entries() == false ){
			return false;
		}
		$('#gs-input_type_button').val( 'Saving...' );

        var form_data = new FormData();
        form_data.append( 'action', 'gs_process_submission' );
		form_data.append( 'ewm_data_type',  $("#form-gs-choose-f").val() );
        form_data.append( 'ewm_data_title', $('#form-gs-title-f').val() ); //form-gs-title-f
		form_data.append( 'ewm_post_id', $('#gs-main-hidden-edit-id').val() );
		// $("#gs-main-hidden-new-or-update-entry").val( 'update' );
		// Pre actual value
		pre_actual_value = '';
		if(  $("#form-gs-choose-f").val()  == 'text' ){
			pre_actual_value = tinymce.get( 'gs-main-text_input' ).getContent() ;
		}
		else{
			pre_actual_value = $('#gs-main-' + $("#form-gs-choose-f").val() + '_input').val() ;
		}
		form_data.append( 'ewm_actual_data', pre_actual_value );
		// form_data.append( 'ewm_data_new_or_update', $("#gs-main-hidden-new-or-update-entry").val() );
		form_data.append( 'ewm_data_shortcode', $("#gs-input_type_hidden_shortcode").val() ) ;
		/*
		if( $('#gs-main-hidden-new-or-update-entry').val() == 'update' ){
			str = $("#gs-main-hidden-previous-value").val();
			str = JSON.stringify( str );
			form_data.append( 'data_edit_id', $("#gs-main-hidden-edit-id").val() ) ;
		}
		*/
        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log('gs_process_submission');
				console.log( response );
				response = JSON.parse( response );

				ewm_gs_update_line( response.data );

				/*
				if( response.data.data_new_or_update == 'new' ){
					ewm_gs_add_new_line( response.data );
				}
				else if( response.data.data_new_or_update == 'update'){
					
				}
				*/

            },
            error: function (response) {
                console.log( response ) ;
            }

        }) ;

	})

	var ewm_sv_add_cat_to_post = function ( ewm_cat_id ) {
		// 2372
		var form_data = new FormData();

        form_data.append( 'action', 'ewm_sv_add_cat_to_post' );
		form_data.append( 'ewm_gs_c_id' , ewm_cat_id );
		form_data.append( 'ewm_gs_post_id' , $('#gs-main-hidden-edit-id').val() );

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ){
				console.log('ewm_sv_add_cat_to_post');
				console.log( response );
				response = JSON.parse( response );
				// ewm_replace_values( response.final );
            },
            error: function ( response ) {
                console.log( response );
            }
        });
	}

	var ewm_sv_activate_single_cat = function (response) {

		$('.ewm_sv_single_cat').click(function (e) {
			var ewm_cat_id = $( this ).data('cat-id');
			var ewm_cat_html = $( this ).html();
			// get the category id and name. // add to main category list
			ewm_long_html = '<div class="ewm_sv_cat_l ewm_sv_cat_l_' + ewm_cat_id + '" data-category-id="' + ewm_cat_id + '">' + ewm_cat_html + ' <span class="ewm_remove_cat_button" data-category-id="' + ewm_cat_id + '">X</span></div>';
			$('.ewm_active_cat_list').append( ewm_long_html );
			ewm_listen_cat_list();
			$('.ewm_cat_input_sect').val('');
			$('.ewm_cat_sections_list_option').html('');
			$('.ewm_cat_sections_list_i').hide();

			// clear list and input	
			ewm_sv_add_cat_to_post( ewm_cat_id );
		})

	}

	$('.gs_delete_button').click(function( e ){

		e.preventDefault();
        var form_data = new FormData() ;

        form_data.append( 'action', 'gs_delete_submission' );
		form_data.append( 'data_new_or_update', 'delete' );
		// form_data.append( 'data_row_id', $( this ).data( 'gs-delete' ) );
		// form_data.append( 'data_title_old' , $( this ).data( 'd-title' ) );
		form_data.append( 'data_gs_delete_id' , $( this ).data( 'gs-delete' ) );

		// str = $( this ).data( 'previous-values') ;
		// str = JSON.stringify( str );
		// form_data.append( 'data_previous_data', str );

        jQuery.ajax( {
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log('gs_delete_button');
				console.log( response ) ;
				response = JSON.parse( response );
                $( '#' + response.data.data_gs_delete_id + '_single_line_id' ).remove();
				// alert('Standard value has been deleted.');
            },
            error: function ( response ) {
                console.log( response ) ;
            }
        } );

	});

	$('.ewm_single_list_item').hover(function () {
		d_ewm_single = $(this).data('ewm-single');
		$( '#gs_edit_single_par_' + d_ewm_single ).show();
	})

	$('.ewm_cat_input_sect_b').click(function () {

		ewm_cat_input_sect = $('.ewm_cat_input_sect').val();
		var form_data = new FormData();
        form_data.append( 'action', 'ewm_sv_input_cats' );
		form_data.append( 'ewm_cat_title', ewm_cat_input_sect );
		
        jQuery.ajax( {
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log( 'ewm_cat_input_sect_b' );
				console.log( response );
				response = JSON.parse( response );
				// console(response.item_list);
				ewm_list_cat_list_added( response.item_list );
            },
            error: function ( response ) {
                console.log( response );
            }
        } ) ;
		
	})

	var ewm_list_cat_list_added = function( args ) {
		var ewm_cat_id = args.id ;
		var ewm_cat_html = args.title ;
			
		// get the category id and name. // add to main category list
		ewm_long_html = '<div class="ewm_sv_cat_l ewm_sv_cat_l_' + ewm_cat_id + '" data-category-id="' + ewm_cat_id + '">' + ewm_cat_html + ' <span class="ewm_remove_cat_button" data-category-id="' + ewm_cat_id + '">X</span></div>';
		$('.ewm_active_cat_list').append( ewm_long_html );
		ewm_listen_cat_list();
		$('.ewm_cat_input_sect').val('');
		$('.ewm_cat_sections_list_option').html('');
		$('.ewm_cat_sections_list_i').hide();
		ewm_sv_add_cat_to_post(ewm_cat_id);

	}

	var ewm_list_cat_list = function( args ) {
		$('.ewm_cat_sections_list_option').html('');
		Object.entries( args ).forEach(([key,keyword_value]) => {
			$('.ewm_cat_sections_list_option').append('<div data-cat-id = "'+keyword_value.id+'" class="ewm_sv_single_cat"> '+ keyword_value.title + '</div>' );
		});
	}

	var ewm_sv_search_categories = function( args ){
        var form_data = new FormData() ;

        form_data.append( 'action', 'ewm_sv_search_cats' );
		form_data.append( 'search_term', args.search_term );
		
        jQuery.ajax( {
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {

				console.log('ewm_sv_search_categories');
				console.log( response );
				response = JSON.parse( response );
				// console(response.item_list);
				ewm_list_cat_list( response.item_list );
				ewm_sv_activate_single_cat();

            },
            error: function ( response ) {
                console.log( response );
            }
        } ) ;

	}

	var ewm_sv_do_error_message = function (){}

	var ewm_sv_update_choice_v = function ( args ){

		/* "ewm_data_title" "ewm_data_type" "ewm_actual_data" "ewm_shortcode" */
		var form_data = new FormData() ;

        form_data.append( 'action', 'ewm_sv_update_single_v' );
		form_data.append( 'update_k', args.update_k );
		form_data.append( 'update_v', args.update_v );
		form_data.append( 'post_id', $('#gs-main-hidden-edit-id').val() );
		
        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log( 'ewm_sv_update_choice_v' );
				console.log( response );
				response = JSON.parse( response );
				if( response.update_data.message.length > 0 ){
					ewm_sv_do_error_message();
				}
            },
            error: function ( response ) {
                console.log( response );
            }
        });

	}
	
	$('#form-gs-title-f').focus(function() {
		$('#form-gs-choose-f').attr('disabled', 'disabled');
		$('.sv_relevant_if_input_ex_2').show();
		$('.ewm_cat_sections_list_i').hide()
	});

	// update popups /* "ewm_data_title" >> "ewm_data_type" >> "ewm_actual_data" >> "ewm_shortcode" */
	// update title/ shortcode >> // on keyup validate shortcode is unique. - on keyup. 

	var ewm_manage_title_shortcode_field = function( args ){

		$("#gs-input_unique_title").html( args.ewm_sv_title_v );
		$("#gs-input_type_hidden_shortcode").val('[ewm_standard_values item="' + args.ewm_sv_title_v + '"]' );
		$(".gs-main-shortcode-area").html( '[ewm_standard_values item="' + args.ewm_sv_title_v + '"]' );
		
		ewm_sv_v_s_is_unique = ewm_sv_validate_shortcode_is_unique({
			'ewm_sv_title_v': args.ewm_sv_title_v
		});

	}

	$('#form-gs-title-f').change(function ( event ) {
		ewm_sv_title_v = $(this).val();
		ewm_manage_title_shortcode_field({
			'ewm_sv_title_v': ewm_sv_title_v
		});
	} );

	$('.gs-single-setting-input-f').change(function () {

		ewm_sv_actual_v = $(this).val();
		ewm_sv_update_choice_v({
			'update_k' : 'ewm_actual_data',
			'update_v' : ewm_sv_actual_v
		});

	});

	var ewm_sv_validate_that_shortcode_is_unique = function ( args ){

		var form_data = new FormData() ;

        form_data.append( 'action', 'ewm_sv_validate_that_shortcode_is_unique' );
		// form-gs-title-f
		$('#form-gs-title-f').val();
		// form_data.append( 'ewm_post_id', args.update_k );
		form_data.append( 'ewm_shortcode', $('#form-gs-title-f').val() );
		form_data.append( 'post_id', $('#gs-main-hidden-edit-id').val() );
		
        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log('ewm_sv_validate_that_shortcode_is_unique');
				console.log( response );
				response = JSON.parse( response );
				if( response.update_data.is_unique == false ) {
					// ewm_sv_do_error_message();
					$('#form-gs-title-f-message').html('Shortcode needs to be unique');
				}

				// ewm_sv_v_s_is_unique = ;
				/*
				if( ewm_sv_v_s_is_unique == true ){
					ewm_sv_update_choice_v({
						'update_k' : 'ewm_data_title',
						'update_v' : ewm_sv_title_v
					});
				}
				else{
					$('#form-gs-title-f-message').html('Shortcode needs to be unique');
				}
				*/
            },
            error: function ( response ) {
                console.log( response );
            }
        });

	}

	var ewm_sv_validate_shortcode_is_unique = function (shortcode) {

		// search on the db for shortcode.
		// if not entered, focus the cursor to the box.
		if( $('#form-gs-title-f').val() == '' ){
			$('#form-gs-title-f').focus();
			$('#form-gs-title-f-message').html( 'Please add a unique name here' );
			$('#form-gs-title-f').css({ "border":"1px solid red" });
		}
		else{
			$('#form-gs-title-f-message').html('');
			$('#form-gs-title-f').css({ "border":"1px solid #ccc" });
		}

		ewm_sv_validate_that_shortcode_is_unique();
		
	}

	// on focus of value confirm that the shortcode value is correct.
	$('.sv_relevant_if_input_ex_2').click(function(){
		// validate that the shortcode is cool
		// ewm_sv_validate_shortcode_is_unique();
		// if not cool -> focus on the unique shortcode.
	});

	$('.ewm_cat_input_sect').click(function(e) {

		// console.log( typeof $( this ).val() );
		if( $( this ).val() == '' ){
			$('.ewm_cat_sections_list_option').html('');
		}

	});

	$('.ewm_cat_input_sect').keyup(function(e) {
		ewm_cat_val = $( this ).val();
		ewm_sv_search_categories({
			'search_term':ewm_cat_val
		});
	});

	$('.ewm_single_list_item').mouseleave(function () {
		$('.gs_edit_single_par').hide();
	})

	var ewm_sv_create_new_post = function(){

		// 
		var form_data = new FormData();
        form_data.append( 'action', 'ewm_sv_create_new_post' );
		// form_data.append( 'search_term', args.search_term );
		
        jQuery.ajax( {
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log('ewm_sv_create_new_post');
				console.log( response );
				response = JSON.parse( response );
				$('#gs-main-hidden-edit-id').val(response.edit_id);
            },
            error: function ( response ) {
                console.log( response );
            }
        });

	}

	var ewm_delete_single_category = function( cat_id ){
		// 
		var form_data = new FormData();
        form_data.append( 'action', 'ewm_delete_single_category' );
		form_data.append( 'cat_id', cat_id );
		
        jQuery.ajax( {
            url: ajax_object.ajaxurl,
            type: 'post',
            contentType: false,
            processData: false,
            data: form_data,
            success: function ( response ) {
				console.log('ewm_delete_single_category');
				console.log( response );
				// response = JSON.parse( response );
				// $('#gs-main-hidden-edit-id').val(response.edit_id);
            },
            error: function ( response ) {
                console.log( response );
            }
        });

	}

	$('.ewm_gs_cat_list_close').click(function () {

		cat_id = $( this ).data('cat-id');
		// remove cat from display
		$('.ewm_gs_cat_list_' + cat_id ).remove();
		// remove cat from server
		ewm_delete_single_category( cat_id );

	})

	$('.ewmglobal_new_button').click(function(){

		$('.form-group-outer-glass').show();
		$('#gs-main-hidden-new-or-update-entry').val('new');
		// tinymce.get( 'gs-main-text_input' ).setContent('Enter text here');

		// make choice active
		$('#form-gs-choose-f').removeAttr('disabled');

		// hide all until input has been entered.
		$('.sv_relevant_if_input_ex').hide();
		$('.sv_relevant_if_input_ex_2').hide();

		// create new post
		ewm_sv_create_new_post();
		$('#ewm_update_type-f').val('new');

	});

	// console.log( $('#myprefix-preview-image').val() );

	jQuery('input#myprefix_media_manager').click(function(e) {

		e.preventDefault();

		var image_frame;

		if( image_frame ){

			image_frame.open();

		}

		// Define image_frame as wp.media object
		image_frame = wp.media({
					title: 'Select Media',
					multiple : false,
					library : {
						type : 'image',
					}
				});

				image_frame.on('close',function() {
					// On close, get selections and save to the hidden input
					// plus other AJAX stuff to refresh the image preview
					var selection =  image_frame.state().get('selection');
					var gallery_ids = new Array();
					var my_index = 0;
					selection.each(function(attachment) {
						gallery_ids[my_index] = attachment['id'];
						my_index++;
					});
					var ids = gallery_ids.join(",");
					if(ids.length === 0) return true;//if closed withput selecting an image
					jQuery('input#myprefix_image_id').val(ids);
					Refresh_Image(ids);
				});

				image_frame.on('open',function() {
				// On open, get the id from the hidden input
				// and select the appropiate images in the media manager
				var selection =  image_frame.state().get('selection');
				var ids = jQuery('input#myprefix_image_id').val().split(',');
				ids.forEach(function(id) {
					var attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add( attachment ? [ attachment ] : [] );
				});

				});

			image_frame.open();

	});
	

});

		// Ajax request to refresh the image preview
	function Refresh_Image(the_id){

		var data = {
		action: 'myprefix_get_image',
		id: the_id
		};

		jQuery.get( ajaxurl, data, function( response ) {

		if( response.success === true ) {

			// console.log( response ) ;
			jQuery('#myprefix-preview-image').replaceWith( response.data.image );
			jQuery('#gs-main-img_input').val( response.data.full_width_image );

		} } );
	}