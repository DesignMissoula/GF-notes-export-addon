<?php

GFForms::include_addon_framework();

class GFNotesExportAddOn extends GFAddOn {

    protected $_version = GF_NOTES_EXPORT_ADDON_VERSION;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'gfnea';
    protected $_path = 'gf-notes-export-addon/gf-notes-export-addon.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms Notes Export Add-On';
    protected $_short_title = 'Notes Export';

    private static $_instance = null;

    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new GFNotesExportAddOn();
        }

        return self::$_instance;
    }

    public function init() {
        parent::init();
        add_filter( 'gform_export_fields', array( $this, 'add_fields' ), 10, 1 );
        add_filter( 'gform_export_field_value', array( $this, 'set_export_values'), 10, 4 );
    }

    public function plugin_settings_fields() {
        return array(
            array(
                'title'  => esc_html__( 'Notes Export Add-On Settings', 'gfnea' ),
                'fields' => array(
                    array(
                        'name'              => 'exportformat',
                        'tooltip'           => esc_html__( 'Use: <table><tbody>
												<tr><td>[date]</td><td>for date</td></tr>
												<tr><td>[user]</td><td>for user name</td></tr>
												<tr><td>[email]</td><td>for email address</td></tr>
												<tr><td>[name]</td><td>for display name</td></tr>
												<tr><td>[userid]</td><td>for user id</td></tr>
												<tr><td>[note]</td><td>for note content</td></tr>
												<tr><td>[noteid]</td><td>for note id</td></tr>
                        </tbody></table>', 'gfnea' ),
                        'label'             => esc_html__( 'Format for Notes Export', 'gfnea' ),
                        'type'              => 'text',
                        'class'             => 'small',
                        'feedback_callback' => array( $this, 'is_valid_setting' ),
                        'default_value'		=> '[date] [user] [note]',
                        'after_input'		=> '',
                    ),
                    array(
                        'name'              => 'exportseparator',
                        'tooltip'           => esc_html__( 'Use: \n or |', 'gfnea' ),
                        'label'             => esc_html__( 'Separator for Notes Export', 'gfnea' ),
                        'type'              => 'text',
                        'class'             => 'small',
                        'feedback_callback' => array( $this, 'is_valid_setting' ),
                        'default_value'		=> '|',
                        'after_input'		=> '',
                    )
                )
            )
        );
    }

    public function is_valid_setting( $value ) {
        return strlen( $value ) < 100;
    }
    
    public function add_fields( $form ) {
	    array_push( $form['fields'], array( 'id' => 'notes', 'label' => __( 'Notes', 'gfnea' ) ) );
	    
	    return $form;
	}
	
	public function set_export_values( $value, $form_id, $field_id, $entry ) {
	    switch( $field_id ) {
	    case 'notes' :
	    	$values = array();	
	    	$notes = RGFormsModel::get_lead_notes( $entry['id'] );
	    	
	    	$format = $this->get_plugin_setting( 'exportformat' );
			$separator = $this->get_plugin_setting( 'exportseparator' );
			
			if( !isset( $separator ) || '' == $separator ){
		        	$separator = '|';
	        	}
					
			if ( sizeof( $notes ) > 0 && GFCommon::current_user_can_any( 'gravityforms_edit_entry_notes' ) ) {
			
				foreach ( $notes as $note ) {
				
					$username = $note->user_name;
					$emailaddress = $note->user_email;					
					$date = GFCommon::format_date( $note->date_created, false );
					$content = $note->value;
					$userid = $note->user_id;
					$noteid = $note->id;
					$author = get_user_by('id', $note->user_id );
					$name = $author->first_name . ' ' . $author->last_name;
					
					if ( isset( $format ) && '' != $format ) {
						$find = array('[date]','[user]','[email]','[note]', '[userid]', '[noteid]', '[name]');
						$replace = array('%1$s','%2$s','%3$s','%4$s','%5$d','%6$d', '%7$s');						
						$format = str_replace($find,$replace,$format);    
			        }else{
				    	$format = '%1$s %2$s %4$s';    
			        }
					
					$values[] = sprintf($format, $date, $username, $emailaddress, $content, $userid, $noteid, $name);    
					
				}
			}
			
	        if( is_array($values) ){
	        	$value = join( stripcslashes( $separator ),$values);
		    }
		    
	        break;
	    }
	    
	    return $value;
	}
	
	public function render_uninstall() {
	}

} // end class GFNotesExportAddOn