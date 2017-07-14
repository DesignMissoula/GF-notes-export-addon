<?php
/**
 * @package GF-notes-export-addon
 */
/*
Plugin Name: Gravity Forms Notes Export Addon
Plugin URI: https://github.com/DesignMissoula/GF-notes-export-addon
Description: Used by millions.
Version: 1.6.1
Author: Bradford Knowlton
Author URI: http:/bradknowlton.com/
Text Domain: gfnea
*/

define( 'GF_NOTES_EXPORT_ADDON_VERSION', '1.6.1' );

add_action( 'gform_loaded', array( 'GF_Notes_Export_AddOn_Bootstrap', 'load' ), 5 );

class GF_Notes_Export_AddOn_Bootstrap {

    public static function load() {

        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'class-gfnotesexportaddon.php' );

        GFAddOn::register( 'GFNotesExportAddOn' );
    }

}

function gf_notes_export_addon() {
    return GFNotesExportAddOn::get_instance();
}