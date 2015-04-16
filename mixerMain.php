<?php
/**
* @package   SoundCloud Mixer
* @author    Mike Brandt 
*
*Plugin Name: SoundCloud Mixer
*Description: Turn your device into a mixer! Powered by SoundCloud.
*Version: v1.0
*Author: Mikey b
*
*/
if ( ! defined( 'WPINC' ) ) {
	die;
}

register_activation_hook( __FILE__, array( 'mixer_showcase', 'activate' ) );
require_once( plugin_dir_path( __FILE__ ) . 'mixer_class.php' );


register_deactivation_hook( __FILE__, array( 'mixer_showcase', 'deactivate' ) );
include( 'views/public.php' );


mixer_showcase::get_instance();
register_activation_hook( __FILE__, array( mixer_showcase, 'install_sound_table' ) );



?>