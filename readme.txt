=== Forms: 3rd-Party Migration ===
Contributors: zaus
Donate link: http://drzaus.com/donate
Tags: contact form, form, contact form 7, CF7, gravity forms, GF, CRM, mapping, 3rd-party service, services, remote request, migration, migrate settings
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2 or later

Import/Export 'Forms: 3rdparty Integration' settings, and upgrade from previous plugin version.

== Description ==

To upgrade deprecated Wordpress Plugin [CF7-3rdparty Integration](http://wordpress.org/plugins/contact-form-7-3rd-party-integration/) to the new version [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/), or migrate settings of either plugin between sites.

From discussion at http://wordpress.org/support/topic/how-to-upgrade-from-old-version-to-this-one?replies=1 and more recent request https://github.com/zaus/forms-3rdparty-integration/issues/17.

Your mileage may vary...

0. You should have both plugins enabled with at least one service configured and saved.  This is so you have both complete sets of options available for comparison, because you may have to add some extra properties beyond just copy/paste.
1. Upload this to your plugins folder (i.e. install)
2. Go to the Tools admin page, underneath it will be "Forms 3rdparty Migrate"
3. Choose the "mode", which corresponds to the currently selected plugin you want to work with.  When reviewing, will show that plugin's settings.  When updating, will set that plugin's settings.
4. Choose 'Review' to see the currently selected plugin settings serialized to JSON, and copy them to export.  If you are on > PHP 5.4, you'll get prettified output, otherwise...sorry.
5. Choose 'Raw Review' to see the currently selected plugin settings in a 'nicely formatted' array.
6. Choose 'Test' to see what your pasted JSON would like as a nicely formatted array (i.e. like 'Raw Review')
7. Choose 'Update' to set the currently selected plugin from the values in the textarea.  Submitted values should be JSON.
8. Select the 'Convert' option if you are migrating between CF7-3rdparty and Forms-3rdparty plugins -- this will perform minor variable renaming for you.
9. Select the 'Merge' option to combine settings with existing.  Unselect to overwrite.
9. If "upgrading" from CF7 to Forms, make sure to add any additional properties present in the 'new' format (such as labels) are added, otherwise you might get some PHP warnings when you go back to the Forms-3rdparty admin page.  This part might not be necessary if you don't have PHP warnings turned on.
10. Check the integration plugin admin page to make sure your settings are all there.
11. Try the new services.

_NOTE_ Because all of the services are serialized to a single field, there is a limit to how many services you can configure at once.


== Installation ==

1. Unzip, upload plugin folder to your plugins directory (`/wp-content/plugins/`)
2. Make sure [Forms 3rdparty Integration](http://wordpress.org/plugins/forms-3rdparty-integration/) is installed and settings have been saved at least once.
3. Activate plugin
4. Go to new admin subpage _"3rdparty Services"_ under the tools menu

== Frequently Asked Questions ==

= How do I import/export 'Forms 3rdparty Integration'? =

See _"Description"_ section for specific steps.

"Mode" = to/destination plugin, and "Convert?" = from/source plugin.

Choose 'Forms 3rdparty' for both "Mode" and "Convert?".  Copy the "Settings" from one site and paste to another.

You may choose to "Merge?" settings to keep both old and new service configurations.

= How do I upgrade/downgrade 'CF7 3rdparty Integration'? =

"Mode" = to/destination plugin, and "Convert?" = from/source plugin.

*Upgrade:* Choose 'Forms 3rdparty' for "Mode" and 'Contact Form 7' for "Convert?"
*Downgrade:* choose the reverse of the above

== Screenshots ==

N/A.

== Changelog ==

= 0.3.0 =

Moved out of gist and formalized as real plugin.

= 0.1.0 =

semi-dangerous hack, mostly proof of concept.

== Upgrade Notice ==

N/A