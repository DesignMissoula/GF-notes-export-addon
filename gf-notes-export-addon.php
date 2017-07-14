<?php
/**
 * @package GF-notes-export-addon
 */
/*
Plugin Name: Gravity Forms Notes Export Addon
Plugin URI: https://github.com/DesignMissoula/GF-notes-export-addon
Description: Used by millions.
Version: 1.1.5
Author: Bradford Knowlton
Author URI: http:/bradknowlton.com/
Text Domain: gfnea
*/

add_filter( 'gform_export_fields', 'add_fields', 10, 1 );
function add_fields( $form ) {
    array_push( $form['fields'], array( 'id' => 'notes', 'label' => __( 'Notes', 'gfnea' ) ) );
    
    return $form;
}

add_filter( 'gform_export_field_value', 'set_export_values', 10, 4 );
function set_export_values( $value, $form_id, $field_id, $entry ) {
	
    switch( $field_id ) {
    case 'notes' :
    	$values = array();	
    	$notes = RGFormsModel::get_lead_notes( $entry['id'] );
    			
		if ( sizeof( $notes ) > 0 && GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
			
			foreach ( $notes as $note ) {
			
				$username = $note->user_name;
				$date = GFCommon::format_date( $note->date_created, false );
				$content = $note->value;
				
				$values[] = "$date [$username] $content";
			}
		}
        if( is_array($values) ){
		    $value = join("\n\r",$values);
	    }
	    
        break;
    }
    
    return $value;
}