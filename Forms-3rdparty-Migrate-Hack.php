<?php
/*
Plugin Name: Forms: 3rd-Party Migrate Hack
Plugin URI: https://gist.github.com/zaus/10001727
Description: Export/Import settings for Forms-3rdparty, or migrate to/from CF7-3rdparty
Author: zaus
Version: 0.3
Author URI: http://drzaus.com
*/

# upgrade path? http://wordpress.org/support/topic/how-to-upgrade-from-old-version-to-this-one?replies=1
class Forms3rdpartyMigrateHack {

	const pluginPageTitle = 'Forms: 3rd Party Integration Migrate';
	
	const pluginPageShortTitle = 'Forms-3rdparty Migrate';
	
	/**
	 * Admin - role capability to view the options page
	 * @var string
	 */
	const adminOptionsCapability = 'manage_options';


	/**
	 * Self-reference to plugin name
	 * @var string
	 */
	private $N;
	
	/**
	 * Namespace the given key
	 * @param string $key the key to namespace
	 * @return the namespaced key
	 */
	public function N($key = false) {
		// nothing provided, return namespace
		if( ! $key || empty($key) ) { return $this->N; }
		return sprintf('%s_%s', $this->N, $key);
	}



	public function __construct() {
		$this->N = basename(__FILE__, '.php');

		add_action( 'admin_menu', array( &$this, 'admin_init' ), 20 ); // late, so it'll attach menus farther down
	}


	/**
	 * HOOK - Add the "Settings" link to the plugin list entry
	 * @param $links
	 * @param $file
	 */
	function plugin_action_links( $links, $file ) {
		if ( $file != plugin_basename( __FILE__ ) )
			return $links;
	
		$url = $this->plugin_admin_url( array( 'page' => $this->N('config') ) );
	
		$settings_link = '<a title="Capability ' . self::adminOptionsCapability . ' required" href="' . esc_attr( $url ) . '">'
			. esc_html( __( 'Settings', $this->N ) ) . '</a>';
	
		array_unshift( $links, $settings_link );
	
		return $links;
	}


	function admin_init() {
		# perform your code here
		//add_action('admin_menu', array(&$this, 'config_page'));
		
		//add plugin entry settings link
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2 );
		
		//needs a registered page in order for the above link to work?
		#$pageName = add_options_page("Custom Shortcodes - ABT Options", "Shortcodes -ABT", self::adminOptionsCapability, 'abt-shortcodes-config', array(&$this, 'submenu_config'));
		if ( function_exists('add_submenu_page') ){
			
			$page = add_submenu_page('tools.php', __(self::pluginPageTitle), __(self::pluginPageShortTitle), self::adminOptionsCapability, $this->N('config'), array(&$this, 'migrate_form'));
						
			//register options
			add_option( $this->N('settings'), $this->get_default_options() );
		}
		
	} // function

	function get_default_options() {
		return array(
				'mode' => self::NS_Forms3rd,
				'convert' => self::NS_Forms3rd
			);
	}


	const NS_CF7 = 'Cf73rdPartyIntegration';
	const NS_Forms3rd = 'Forms3rdPartyIntegration';

	const ACTION_GET = 'Get';
	const ACTION_GET_RAW = 'Get Raw';
	const ACTION_TEST = 'Test';
	const ACTION_SET = 'Update';


	function get_input() {

		// check-admin will die; otherwise if 'get' then show default values
		$nopost = empty( $_POST ) || !check_admin_referer( __CLASS__, __CLASS__ );
		
		$options = get_option($this->N('settings'));

		// remember last chosen mode
		if(!$nopost) {
			foreach($this->get_default_options() as $opt => $def) {
				if(isset($_REQUEST[$opt])) $options[$opt] = $_REQUEST[$opt];
			}
			update_option($this->N('settings'), $options);
		}

		// not a get request, "quit"
		if($nopost || !isset($_REQUEST['action'])) $_REQUEST['action'] = false;
		
		switch($_REQUEST['action']) {
			case self::ACTION_TEST:
				// note that update mode "reverses" the from/to input
				$setting = stripslashes_deep($_REQUEST['input']);
				$setting = json_decode($setting, true);

				$options['input'] = print_r($setting, true);
				break;
			// just show
			case self::ACTION_GET:
			case self::ACTION_GET_RAW:
			default:
				$asarray = $_REQUEST['action'] == self::ACTION_GET_RAW;

				$setting = get_option($options['mode'] . '_settings');

				$options['input'] = $asarray
						? print_r($setting, true)
						: (defined('JSON_PRETTY_PRINT')
								? json_encode($setting, JSON_PRETTY_PRINT)
								: json_encode($setting)
							);

				break;
			case self::ACTION_SET:
				// note that update mode "reverses" the from/to input
				$setting = stripslashes_deep($_REQUEST['input']);
				// "convert" between plugin variations if necessary
				if(isset($options['convert']) && $options['mode'] != $options['convert']) {
					switch($options['convert']) {
						case self::NS_Forms3rd:
							$from = '"src"';
							$to = '"cf7"';
							break;
						case self::NS_CF7:
							$from = '"cf7"';
							$to = '"src"';
							break;
					}
					if(isset($from)) $setting = str_replace($from, $to, $setting);
				}

				$newsetting = json_decode($setting, true);

				// save the new setting
				update_option($options['mode'] . '_settings', $newsetting);
				
				$options['updated'] = true;
				$options['input'] = $setting;
				break;
		}//--	switch

		return $options;
	}

	function radio_input($modes, $field, $input) {
		foreach($modes as $key => $name) {
			?>
			<div class="field">
				<label for="<?php echo $field, '-', esc_attr($key) ?>"><?php _e($name) ?></label>
				<input <?php checked($input[$field], $key) ?> type="radio" id="<?php echo $field, '-', esc_attr($key) ?>" name="<?php echo esc_attr($field) ?>" value="<?php echo esc_attr($key) ?>" />
			</div>
			<?php
		}
	}

	function show_form($input) {
		?>
		<h3>Migrate Forms-3rdparty</h3>
		<form action="" method="post">
			<?php if(isset($input['updated']) && true == $input['updated']) {
				?>
				<div class="updated">
					<p><?php _e( 'Updated!', $this->N() ); ?></p>
				</div>
				<?php
			}
			?>
			<div class="field-group">
				<strong>Mode</strong>

				<?php
				$modes = array(self::NS_CF7 => 'Contact Form 7', self::NS_Forms3rd => 'Forms 3rdparty');
				
				$this->radio_input($modes, 'mode', $input);
				?>
				<em class="description">Which plugin to export.  If migrating between plugin versions, make sure to review in one mode, then change modes before updating.</em>
			</div>

			<div class="field-group">
				<strong>Convert?</strong>

				<?php
				$this->radio_input($modes, 'convert', $input);
				?>
				<em class="description">Which plugin we're importing from.  If migrating between plugin versions, this will cause the setting to be reformatted properly.</em>
			</div>


			<div class="field">
				<label for="input">Settings</label>
				<textarea id="input" name="input"><?php echo esc_textarea($input['input']) ?></textarea>
				<p><em class="description">The current plugin settings, depending on <code>mode</code> chosen.  Serialized as JSON for portability.</em></p>
			</div>

			<hr />

			<?php
			submit_button(self::ACTION_GET, 'primary', 'action', false, array('id' => 'review', 'title' => 'Get the current settings; copy to export'));
			submit_button(self::ACTION_GET_RAW, 'secondary', 'action', false, array('id' => 'raw', 'title' => 'Get the current settings in raw array format; cannot export'));
			submit_button(self::ACTION_TEST, 'secondary update', 'action', false, array('id' => 'test', 'title' => 'View the entered settings as raw array format; cannot import'));
			submit_button(self::ACTION_SET, 'primary update', 'action', false, array('id' => 'update'));

			wp_nonce_field(__CLASS__, __CLASS__);
			?>
		</form>
		<?php
	}

	public function migrate_form() {

		$input = $this->get_input();

		$this->show_form($input);
	}
}//---	class	Forms3rdpartyMigrateHack


new Forms3rdpartyMigrateHack(); // engage