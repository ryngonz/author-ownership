<?php
/**
 * Author Ownership.
 *
 * @package   Author_Ownership
 * @author    Ryan Gonzales <ryngonz@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2013 Ryan Gonzales
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package Plugin_Name
 * @author  Your Name <email@example.com>
 */
class Author_Ownership {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * TODO - Rename "plugin-name" to the name your your plugin
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'author-ownership';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		
		add_action( 'TODO', array( $this, 'action_method_name' ) );
		add_filter( 'TODO', array( $this, 'filter_method_name' ) );
		
		add_action( 'add_meta_boxes', array( $this, 'ao_meta_box_add' ) );
		add_action( 'save_post', array( $this, 'ao_save' ) );
		
		add_action( 'user_register', array( $this, 'ao_send_ownership_list' ) );
		//add_action( 'publish_post', 'ao_notify_new_post' );
		//add_action( 'register_form', array( $this, 'ao_send_ownership_list' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
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
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		// TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}
	
	//- Add custom field metaboxes to every page and post
	public function ao_meta_box_add () {
		$screens = array( 'post', 'page' );

	    foreach ( $screens as $screen ) {
	
	        add_meta_box(
	            'ao_meta_box_'.$screen,
	            __( 'Ownership', 'myplugin_textdomain' ),
	            array( $this, 'ao_render_meta_box' ),
	            $screen,
	            'side',
	            'high'
	        );
	    }
	}
	
	//- Render the metabox
	public function ao_render_meta_box ( $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'ao_inner_custom_box', 'ao_inner_custom_box_nonce' );
		
		/*
		* Use get_post_meta() to retrieve an existing value
		* from the database and use the value for the form.
		*/
		$value = get_post_meta( $post->ID, '_ao_meta_value_key', true );
		echo '<label for="o_email">';
		   _e( "Email", 'myplugin_textdomain' );
		echo '</label> ';
		echo '<input type="text" id="o_email" name="o_email" value="' . esc_attr( $value ) . '" size="25" />';
	}
	
	public function ao_save ( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['ao_inner_custom_box_nonce'] ) )
			return $post_id;
			
		$nonce = $_POST['ao_inner_custom_box_nonce'];
		
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'ao_inner_custom_box' ) )
			return $post_id;
			
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;
		
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}
		
		// Sanitize the user input.
		$ao_email = sanitize_text_field( $_POST['o_email'] );
		
		//Check if valid email
		if ( is_email( $ao_email ) ) {
			$user = get_user_by( 'email', $ao_email );
			
			if ( $user ) {
				$this->ao_send_ownership_list($user->ID);
				// Update the meta field.
				update_post_meta( $post_id, '_ao_meta_value_key', $ao_email );
			}
		}
	}
	
	// Redefine user notification function
	function ao_send_ownership_list( $user_id = null ) {
		
		$user = new WP_User( $user_id );
		
		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
		
		$args = array( 'post_type' => 'any');
		$myposts = get_posts( $args );
		
		$message  = '<html><body>';
		$message .= '<input type="hidden" value="Login" />';
		$message .= '<h1>'.sprintf( __("Posts that needs Ownership confirmation:"), get_option('blogname')).'</h1>';
		$message .= '<table cellpadding="5" width="100%">';
		
		$index = 0;
		
		foreach ( $myposts as $post ) {
			$value = get_post_meta( $post->ID, '_ao_meta_value_key', true );
			$post_url = stripslashes(get_permalink($post->ID));
			$post_title = stripslashes($post->post_title);
			$post_link = stripslashes('<a href="'.$post_url.'">'.$post_title.'</a>');
			$plugin_url = plugin_dir_url(__FILE__).'email-confirmation/?uid='.$user_id.'&post_id='.$post->ID;
			$post_edit_link = stripslashes('<a href="'.$plugin_url.'">Confirm</a>');
			if ( $value && $value == $user_email && $user_id != $post->post_author ) {
				//$message .= '<tr><td>'.sprintf( __('Post %s: %s'), $index, $post_link ) . '</td></tr>';
				$index++;
				$message .= '<tr>';
				$message .= '<td>'.sprintf( __('%s: %s'), $index, $post_link ) . '</td>';
				$message .= '<td>'.sprintf( __('%s'), $post_edit_link ).'</td>';
				$message .= '</tr>';
			}
		}
		
		$message .= '</table></body></html>';
		
		if ( $index > 0 ) {
			add_filter( 'wp_mail_content_type', array( $this, 'ao_set_html_content_type' ) );
			
			$headers[] = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>';
			
			wp_mail(
				$user_email,
				sprintf( __('[%s] Verify Ownership'), get_option('blogname') ),
				$message,
				$headers
			);
			
			remove_filter( 'wp_mail_content_type', array( $this, 'ao_set_html_content_type' ) );
		}
		
	}
	
	function ao_set_html_content_type () {
		return 'text/html';
	}
	
	function ao_notify_new_post ( $post_id ) {
		if( ( $_POST['post_status'] == 'publish' ) && ( $_POST['original_post_status'] != 'publish' ) ) {
	        $user = get_user_by( $field, $value );
	    }
	}

}