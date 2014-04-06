<?php
/*
Plugin Name: Forms: 3rd-Party Migrate Hack
Plugin URI: https://gist.github.com/zaus/10001727
Description: Upgrade CF7-3rdparty to Forms-3rdparty
Author: zaus
Version: 0.1
Author URI: http://drzaus.com
*/

# upgrade path? http://wordpress.org/support/topic/how-to-upgrade-from-old-version-to-this-one?replies=1
class Forms3rdpartyMigrateHack {
	// poor security, please don't leave this on longer than you need to
	var $migratekey = 'forms-3rdparty-migrate';
	// change this value to something random
	var $expected_secret = 'your-temporary-secret-plz-change-this';

	public function __construct() {
		if(isset($_REQUEST[$this->migratekey]) && $_REQUEST[$this->migratekey] == $this->expected_secret) {
			add_action('wp_footer', array(&$this, 'migrate_form')); // if this is supported by your theme, otherwise find another page hook
		}
	}

	public function migrate_form() {

		$old_plugin = get_option('Cf73rdPartyIntegration_settings', 'not-used');
		
		// save
		if(isset($_REQUEST['save']) && $_REQUEST['save'] == 'Update') {
			$new = stripslashes_deep($_REQUEST['new']);
			$new_plugin = json_decode($new, true);
			// update_option('Forms3rdPartyIntegration_settings', $new_plugin);
		}
		// review
		else {
			$new_plugin = get_option('Forms3rdPartyIntegration_settings', 'not-used');
		}

		// raw dump
		if(isset($_REQUEST['raw']) && $_REQUEST['raw']=='true') {
			?><h3>Raw Review</h3><pre><?php
			print_r(array('CF7-3rdparty' => $old_plugin, 'Forms-3rdparty' => $new_plugin));
			?></pre><?php
		}


		// form to update
		// note: pretty-print available PHP > 5.4
		?>
		<h3>Migrate Forms-3rdparty from Old Version</h3>
		<form action="?" method="post">
			<strong>CF7-3rdparty</strong>
			<textarea style="width:100%; height:20em;" name="old"><?php echo json_encode($old_plugin/*, JSON_PRETTY_PRINT*/); ?></textarea>
			<hr />
			<strong>Forms-3rdparty</strong>
			<textarea style="width:100%; height:20em;" name="new"><?php echo json_encode($new_plugin/*, JSON_PRETTY_PRINT*/); ?></textarea>
			
			<input type="hidden" name="raw" value="<?php if(isset($_REQUEST['raw'])) echo esc_attr($_REQUEST['raw']); ?>" />
			<input type="hidden" name="<?php echo esc_attr($this->migratekey) ?>" value="<?php echo esc_attr($_REQUEST[$this->migratekey]); ?>" />
			<input type="submit" name="save" value="Update" />
		</form>
		<?php

	}
}//---	class	Forms3rdpartyMigrateHack


new Forms3rdpartyMigrateHack(); // engage