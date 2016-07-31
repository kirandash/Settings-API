<?php

/**
* Registers a new settings field on the 'General settings' page of the WordPress dashboard.
*/

function kd_initialize_theme_options(){
	
	// Define the settings field
	add_settings_field(
		'footer_field',					// The ID (or name) of the field
		'Theme Footer Message',			// The text used to label the field
		'kd_footer_message_display',	// The cb fn used to render the field
		'general'						// The section to which we are adding the field
	);
	
	// Register the 'footer_message' setting with the 'General' section
	register_setting(
		'general',
		'footer_message'
	);
} // end kd_initialize_theme_options

add_action('admin_init','kd_initialize_theme_options');

/**
* Renders the input field for the 'Footer message' setting in the 'General settings' section
*/

function kd_footer_message_display(){
	echo '<input type="text" name="footer_message" id="footer_message" value="'.get_option('footer_message').'" />';
} // end kd_footer_message_display
?>