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

------------------------------------------------------------------------
Copyright 2017-2018 Bradford Knowlton

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses.
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