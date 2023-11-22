<?php

class class_display_objects{

	public static function text( $args = [] ){

		return $args["ewm_actual_data"];

	}
	
	public static function img( $args = [] ){

		return '<img src="'. $args["ewm_actual_data"] . '" >';

	}
	
	public static function url( $args = [] ){ 

		return $args["ewm_actual_data"];

	}
	
	public static function number( $args = [] ){

		return $args["ewm_actual_data"];

	}
	
	public static function percentage( $args = [] ){

		return $args["ewm_actual_data"];

	}
	
	public static function decimal( $args = [] ){

		return $args["ewm_actual_data"];

	}
	
}

?>
