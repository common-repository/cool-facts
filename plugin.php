<?php
/**
 * Plugin Name: Cool Facts
 * Plugin URI: http://github.com/chrismccoy/coolfacts
 * Description: Show Some Cool Facts
 * Version: 1.0
 * Author: Chris McCoy
 * Author URI: http://github.com/chrismccoy

 * @copyright 2017
 * @author Chris McCoy
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Cool_Facts
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Cool Facts Class for scripts, shortcode, and ajax
 *
 * @since 1.0
 */

if( !class_exists( 'Cool_Facts' ) ) {

	class Cool_Facts {

		/**
 		* Hook into hooks for scripts, shortcode, and ajax
 		*
 		* @since 1.0
 		*/
		public function __construct() {

			register_activation_hook( __FILE__, array( $this, 'activation' ) );

			include(plugin_dir_path( __FILE__ ) . 'widget.php');

			add_action( 'wp_ajax_load_random_quote', array( $this, 'ajax' ) );
			add_action( 'wp_ajax_nopriv_load_random_quote', array( $this, 'ajax' ) );
			add_shortcode( 'coolfacts', array( $this, 'shortcode' ) );
			add_action( 'widgets_init', array( $this, 'register_widget' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'display_js' ) );
		}

		/**
	 	* AJAX function that shows a new quote
	 	*
 		* @since 1.0
	 	*/
		public function ajax() {

			$quotes = get_transient( '_cool_facts' );
                	$single = rand(0, sizeof( $quotes )-1);

			echo '<p id="coolwidgetquote">' . $quotes[$single] . '</p>';

			die();

		}

		/**
	 	* Shortcode to show a random fact
	 	*
	 	* @since 1.0
	 	*/
		public function shortcode( $atts ) {
			$quotes = get_transient( '_cool_facts' );
			$single = rand(0, sizeof( $quotes )-1);
			$content = '<p id="coolquote">' . $quotes[$single] . '</p>';
			return $content;
		}

		/**
	 	* Register the Cool Facts Widget
	 	*
	 	* @since 1.0
	 	*/

		public function register_widget() {
			register_widget( 'Cool_Facts_Widget' );
		}

		/**
	 	* Sets transient for cached quotes
	 	*
	 	* @since 1.0
	 	*/

		public static function activation() {
			if ( false === ( $quotes = get_transient( '_cool_facts' ) ) ) {
 				$quotes = file(plugin_dir_path( __FILE__ ) . 'inc/quotes.txt');
     				set_transient( '_cool_facts', $quotes, YEAR_IN_SECONDS );
			}
		}

		/**
	 	* Enqueue front end javascript
		*
	 	* @since 1.0
	 	*/

		public function display_js() {
			wp_enqueue_script( 'coolfacts', plugin_dir_url( __FILE__ ) .   'js/coolfacts.js', array( 'jquery' ) );
			wp_localize_script( 'coolfacts', 'Ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

		}
	}
}

new Cool_Facts();
