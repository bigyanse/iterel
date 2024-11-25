<?php
/**
 * Menu page for general page.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * General page
 */
function iterel_menu_general_cb() {
    // check user capabilities
    if(!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if (isset($_GET['settings-updated'])) {
		// add settings saved message with the class of "updated"
		add_settings_error('iterel_messages', 'iterel_message', __( 'Settings Saved', 'iterel' ), 'updated');
	}

	// show error/update messages
	settings_errors('iterel_messages');
    ?>
    <div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "iterel"
			settings_fields('iterel');
			// output setting sections and their fields
			// (sections are registered for "iterel", each field is registered to a specific section)
			do_settings_sections('iterel');
			// output save settings button
			submit_button('Save Settings');
			?>
		</form>
	</div>
    <?php
}

/**
 * General Page
 */
function iterel_menu_general_init() {
    add_menu_page('Iterel', 'Iterel', 'manage_options', 'iterel', 'iterel_menu_general_cb', 'dashicons-admin-generic', null);
}
add_action('admin_menu', 'iterel_menu_general_init');


// Setup general section
require __DIR__ . '/sections/general.php';