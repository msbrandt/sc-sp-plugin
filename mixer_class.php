<?php
/**
 * SoundCloud Mixer Plugin
 *
 * @package   SoundCloud Mixer
 * @author    Mikey Brandt 
 */
/**
 * Plugin class.
 */
class mixer_showcase {
	
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'mixer_showcase';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_mixer_textdomain' ) );
		// add_action( 'init', array( $this, 'convert_duration' ) );
		add_action( 'admin_menu', array( $this, 'add_mixer_admin_menu' ) );



		// Add the options page and menu item.
	wp_enqueue_script( 'soundcloud', '//connect.soundcloud.com/sdk.js' ); 
		
		add_action( 'wp_enqueue_scripts', array( $this, 'activate_mixer_jquery' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'mixer_public_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_mixer_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_mixer_admin_scripts') );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_mixer_styles') );
		add_action( 'admin_enqueue_scripts', array( $this, 'mixer_custom_media') );

		add_action( 'wp_ajax_select_songs', array($this, 'select_songs_handler') );
		add_action( 'wp_ajax_save_playlist', array($this, 'save_playlist_handler') );


	}
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate() {
		include_once('views/public.php');
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// Remove suvbc_featured_players table from db
		global $wpdb;
		$table_name = $wpdb->prefix . 'sc_sp_alpha';

		$wpdb->query("DROP TABLE IF EXISTS $table_name");



	}
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_mixer_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */

	public function mixer_public_scripts() {
			wp_enqueue_script( $this->plugin_slug . '-public-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( $this->plugin_slug . '-public-script', 'mixer', array( 'ajaxurl' => admin_url('admin-ajax.php')  ) );
			
		}

	//include jQuery
	public function activate_mixer_jquery() {
		wp_enqueue_script( 'jquery' );
	}
	//include admin scrips
	public function enqueue_mixer_admin_scripts() {
		
		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( $this->plugin_slug . '-admin-script', 'mixer', array( 'ajaxurl' => admin_url('admin-ajax.php')  ) );
		

		wp_register_style( 'admin_stylesheet', plugins_url( '/css/mixer-admin-styles.css', __FILE__ ) );
    	wp_enqueue_style( 'admin_stylesheet' );


	}

	/**
	 * Register and enqueue style sheet.
	 *
	 * @since    1.0.0
	 */

	public function enqueue_mixer_styles() {

		wp_enqueue_style( $this->plugin_slug . '-plugin-sc-styles', plugins_url( 'css/mixer_styles.css', __FILE__ ), array(), $this->version );
	}
	public function enqueue_admin_mixer_styles() {

		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/mixer-admin-styles.css', __FILE__ ), array(), $this->version );
	}


	//include media libery from forms  
	public function mixer_custom_media(){
		wp_enqueue_media();
		
	}


	//add menu button to dashboard panal  
	public function add_mixer_admin_menu(){
		add_menu_page(
			__( 'SoundCloud Mixer'),
			__( 'SoundCloud Mixer' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_mixer_admin_page'), 'dashicons-format-audio'
			);
	}
	//view for dashboard panal
	public function display_mixer_admin_page() {
		include_once( 'views/admin.php' );

	}

	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	

	public function mixer_settings() {
		register_setting( 'mixer_show_group', 'mixer_set');
	}

	public function install_sound_table(){

		global $wpdb;

		$table_name = $wpdb->prefix . 'sc_sp_alpha';

		$sql = "CREATE TABLE " . $table_name . "(
			song_id MEDIUMINT NOT NULL AUTO_INCREMENT,
			sc_id BIGINT NOT NULL,
			song_title MEDIUMTEXT NOT NULL,
			song_duration MEDIUMINT NOT NULL,
			sc_wave MEDIUMTEXT NOT NULL,
			sc_art MEDIUMINT NOT NULL,
			PRIMARY KEY  song_id (song_id)
			);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}


// function refresh_list_handler(){
// 	echo load_song_list_li();
// 	exit();
// }

// add_action( 'wp_ajax_refresh_list', 'refresh_list_handler');
// add_action( 'wp_ajax_nopriv_refresh_list', 'refresh_list_handler' );
public function convert_duration($mili){

	$input = $mili;

	$usec = $input % 1000;
	$input = floor($mili / 1000);

	$seconds = $input % 60;
	$input = floor($input / 60);

	$minutes = $input % 60;
	$input = floor($input / 60);


	$time = $minutes.':'.$seconds;
	return $time;
}
public function select_songs_handler(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'sc_sp_alpha';
	$the_file = json_decode( stripcslashes( $_REQUEST['file_lines']), true);
	
	// echo 'wehifuhewuiahfwehafuihi';

	require_once plugin_dir_path( __FILE__ ).'/Services/Soundcloud.php';
	
	$client = new Services_Soundcloud('7d9677620e4d860d055604be6c25d43a', 'ecbbaf33f2f146a8ebb92d195074e219');
	$client->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1) );
	
	foreach ($the_file as $f) {
		$seachTracks = json_decode($client->get('tracks', array('q'=> $f, 'limit'=>5)));
		echo '<tr class="search-title"><td colspan="4">'.$f.'</td></tr>';
		foreach ($seachTracks as $track) {
			$t = $track->title;
			$t_id = $track->id;
			$t_wave = $track->waveform_url;
			$t_d = $track->duration;
			$t_a = $track->artwork_url;

			$new_duration = $this->convert_duration($t_d);

			$tlb_str = '<tr data-title="'.$t.'" data-wave="'.$t_wave.'" data-duration="'.$t_d.'"data-ID="'.$t_id.'">'.
							'<td id="sc-id">'.$t_id.'</td>'.
							'<td id="t-title">'.$t.'</td>'.
							'<td id="t-duration">'.$new_duration.'</td>'.
							'<td id="t-art"><div class="artwork" style="backgorund-url: url('.$t_a.')"></div></td>'.
							'<td id="t-wave" class="hidden-data">'.$t_wave.'</td>'.
						'</tr>';

			$this_str = '<li data-title="'.$t.'" data-wave="'.$t_wave.'" data-duration="'.$t_d.'"data-ID="'.$t_id.'">'.$t.'</li>';
			// echo $this_str;
			echo $tlb_str;

		}

	}
	
	exit();	
}

public function save_playlist_handler(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'sc_sp_alpha';
	$saved_list = $_REQUEST['track_list'];
	
	foreach ($saved_list as $file) {
		var_dump($file);
		$sc_title = $file['title'];
		$sc_id = $file['id'];
		$sc_duration = $file['duration'];
		$sc_wave = $file['wave'];
		$sc_art = $file['art'];

		$wpdb->insert( 
			$table_name, 
			array(
			'song_id'=> '',
			'sc_id'=>$sc_id,
			'song_title'=>$sc_title,
			'song_duration'=>$sc_duration,
			'sc_wave'=>$sc_wave,
			'sc_art'=>$sc_art
			),
			array(
				'%d',
				'%d',
				'%s',
				'%d',
				'%s',
				'%s'
				)
			);
	}

	exit();
}

// function delete_song_handler(){
// 	global $wpdb;
// 	$table_name = $wpdb->prefix . 'sc_sp_alpha';
// 	$to_delete =  $_REQUEST['delete_data'];

// 	foreach ($to_delete as $track_id) {
// 		$wpdb->delete( $table_name, array( 'sc_id' => $track_id ) );	
// 	}
	
// 	exit();
// }
// add_action( 'wp_ajax_delete_song', 'delete_song_handler');

	public function load_song_list_tbl(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'sc_sp_alpha';
		
		$this_q = $wpdb->get_results("SELECT * FROM " . $table_name . "");
		foreach ($this_q as $s) {
			$read_duration = $this->convert_duration($s->song_duration);
			$tlb_str =  
				'<tr class="db-list" data-title="'.$s->song_title.'" data-wave="'.$s->sc_wave.'" data-duration="'.$s->song_duration.'"data-ID="'.$s->sc_id.'">'.
					'<td id="sc-id">'.$s->sc_id.'</td>'.
					'<td id="t-title">'.$s->song_title.'</td>'.
					'<td id="t-duration">'.$read_duration.'</td>'.
					'<td id="t-art"><div class="artwork" style="backgorund-url: url('.$t_a.')"></div></td>'.
					'<td id="t-wave" class="hidden-data">'.$s->sc_wave.'</td>'.
				'</tr>';
			// $the_str = '<li class="db-list" data-wave="'.$s->sc_wave.'" data-duration="'.$s->song_duration.'"data-ID="'.$s->sc_id.'">'.$s->song_title.'</li>';
			echo $tlb_str;
		}
		exit();

	}

	public function load_song_list_li(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'sc_sp_alpha';
		$this_q = $wpdb->get_results("SELECT * FROM " . $table_name . "");
		foreach ($this_q as $s) {
			$the_str = '<li class="db-list" data-wave="'.$s->sc_wave.'" data-duration="'.$s->song_duration.'"data-ID="'.$s->sc_id.'">'.$s->song_title.'</li>';
			echo $the_str;
		}
	}


}
	
?>