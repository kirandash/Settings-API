<?php

/*----------------------------------------------*
 * Menus
/*----------------------------------------------*/

/**
 * Adds the 'kd Theme Options' to the 'Settings' menu in the WordPress
*/
function kd_add_options_page(){
	add_options_page(
		'KD Theme Options',			// The text to be displayed in the browser title bar
		'KD Theme Options',			// The text to be used for the menu
		'manage_options',			// The required capability of users to access this menu
		'kd-theme-options',			// The slud by which the menu item is accessible
		'kd_theme_options_display'	// The name of the cb function used to display the page content
	);
}

add_action( 'admin_menu', 'kd_add_options_page' );

/*----------------------------------------------*
 * Sections, Settings, and Fields
/*----------------------------------------------*/

/**
 * Registers a new settings field on the 'General settings' page of the WordPress dashboard.
*/

function kd_initialize_theme_options(){

	// Let's introduce a section to be rendered on the new options page
	add_settings_section(
		'footer_section',				// The ID to use for this section in attribute tags
		'Footer Options',				// The title of the section rendered to the screen
		'kd_footer_options_display',	// The function used to render the options for this section
		'kd-theme-options'				// The ID of the page on which the section is rendered
	);	
	
	// Define the settings field
	add_settings_field(
		'footer_message',				// The ID (or name) of the field
		'Theme Footer Message',			// The text used to label the field
		'kd_footer_message_display',	// The cb fn used to render the field
		'kd-theme-options',				// The page on which we'll be rendering this field
		'footer_section'				// The section to which we are adding the field
	);
	
	// Register the 'footer_message' setting with the 'General' section
	register_setting(
		'footer_section',				// The name of the group of the settings
		'footer_options'				// The name of the actual option (or setting)
	);
} // end kd_initialize_theme_options

add_action('admin_init','kd_initialize_theme_options');

/*----------------------------------------------*
 * Callbacks
/*----------------------------------------------*/

function kd_theme_options_display(){
?>
	<div class="wrap">
        <h2>Kiran Theme Options</h2>
        <form method="post" action="options.php">
        	<?php
				
				// Renders the settings for the settings section identified as 'Footer Section'
				settings_fields('footer_section');
				
				// Renders all of the settings for 'kd-theme-options' section
				do_settings_sections('kd-theme-options');
				
				// Add the submit button to serialize the options
				submit_button();
			?>
        </form>
    </div><!-- .wrap -->
<?php
} // end kd_theme_options_display

function kd_footer_options_display(){
	echo "These options are designed to help you control what's displayed in your footer.";
} // end kd_footer_options_display

/**
* Renders the input field for the 'Footer message' setting in the 'General settings' section
*/

function kd_footer_message_display(){
	
	$options = (array)get_option('footer_options');
	$message = $options['message'];
	
	echo '<input type="text" name="footer_options[message]" id="footer_options_message" value="'.$message.'" />';
} // end kd_footer_message_display

// https://codex.wordpress.org/Function_Reference/add_settings_section
?>