<?php

/*----------------------------------------------*
 * Menus
/*----------------------------------------------*/

/**
 * Adds the 'kd Theme Options' to the 'Settings' menu in the WordPress
*/
function kd_add_options_page(){
	
	// This will introduce a new top level menu
	add_menu_page(
		'Theme Options',			// The text to be displayed in the browser title bar
		'Theme Options',			// The text to be used for the menu
		'manage_options',			// The required capability of users to access this menu
		'kd-theme-options',			// The slug by which the menu item is accessible
		'kd_theme_options_display',	// The name of the cb function used to display the page content
		''							// Provides a default icon for our menu page
		//11,						// Avoid using this as there is potential chance of replacing other menu
		//https://wordpress.org/support/topic/custom-post-type-menu-positions
	);
	
	// Introduce a submenu page specifically for the header options
	add_submenu_page(
		'kd-theme-options',					// The slug for the parent menu page to which this sub menu belongs
		'Header Options',					// The text that's rendered in the browser title bar
		'Header Options',					// The text to be rendered in the menu
		'manage_options',					// The required capability of users to access this menu
		'header-options',					// The slug by which the sub menu is identified
		'kd_theme_options_display'	//The function used to display the options for this menu page
	);
	
	// Introduce a submenu page specifically for the footer options
	add_submenu_page(
		'kd-theme-options',					// The slug for the parent menu page to which this sub menu belongs
		'Footer Options',					// The text that's rendered in the browser title bar
		'Footer Options',					// The text to be rendered in the menu
		'manage_options',					// The required capability of users to access this menu
		'footer-options',					// The slug by which the sub menu is identified
		'kd_theme_options_display'	//The function used to display the options for this menu page
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
	
	add_settings_section(
		'header_section',							// The ID to use for this section in attribute tags
		'Header Options',							// The title of the section rendered to the screen
		'kd_theme_header_description_display',		// The function used to render the options for this section
		'kd-theme-header-options'					// The ID of the page on which the section is rendered		
	);
	
	// Let's introduce a section to be rendered on the new options page
	add_settings_section(
		'footer_section',				// The ID to use for this section in attribute tags
		'Footer Options',				// The title of the section rendered to the screen
		'kd_footer_options_display',	// The function used to render the options for this section
		'kd-theme-footer-options'		// The ID of the page on which the section is rendered
	);	

	add_settings_field(
		'display_header',				// The ID (or name) of the field
		'Display Header Text',			// The text used to label the field
		'kd_header_text_display',		// The cb fn used to render the field
		'kd-theme-header-options',		// The page on which we'll be rendering this field
		'header_section'				// The section to which we are adding the field
	);
		
	// Define the settings field
	add_settings_field(
		'footer_message',				// The ID (or name) of the field
		'Theme Footer Message',			// The text used to label the field
		'kd_footer_message_display',	// The cb fn used to render the field
		'kd-theme-footer-options',		// The page on which we'll be rendering this field
		'footer_section'				// The section to which we are adding the field
	);

	register_setting(
		'header_section',				// The name of the group of the settings
		'header_options'				// The name of the actual option (or setting)
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

/**
 * Renders the content of the options page for both the header and footer options
 */
function kd_theme_options_display(){
?>
	<div class="wrap">
        <h2>Kiran Theme Options</h2>
        
        <?php settings_errors(); ?>
        
        <?php
			$active_tab = 'header-options'; // default tab
			if( isset( $_GET['page'] ) ) {
				$active_tab = $_GET['page'];
			} // end if
		?>
        
        <h2 class="nav-tab-wrapper">
        	<a href="?page=header-options" class="nav-tab <?php echo 'header-options' == $active_tab || 'kd-theme-options' == $active_tab ? 'nav-tab-active' : '';?>">Header Options</a>
            <a href="?page=footer-options" class="nav-tab <?php echo 'footer-options' == $active_tab ? 'nav-tab-active' : '';?>">Footer Options</a>
        </h2><!-- .nav-tab-wrapper -->
        
        <form method="post" action="options.php">
        	<?php
				
				if('footer-options' == $active_tab){
					
					// Renders the settings for the settings section identified as 'Footer Section'
					settings_fields('footer_section');
					// Renders all of the settings for 'kd-theme-options' section
					do_settings_sections('kd-theme-footer-options');
					
				}else{
					
					settings_fields('header_section');
					do_settings_sections('kd-theme-header-options');
					
				}
								
				// Add the submit button to serialize the options
				submit_button();
			?>
        </form>
    </div><!-- .wrap -->
<?php
} // end kd_theme_options_display

/**
 * Renders the description of the settings below the title of the header section
 * and the above the actual settings.
 */
function kd_theme_header_description_display(){
	echo "These options are designed to help you control whether or not you want to display your header.";
}

/**
* Renders the input field for the 'Header Display' setting
*/

function kd_header_text_display(){
	$options = (array)get_option('header_options');
	$display = $options['display'];
	
	$html .= '<label for="header_options[display]">';
	$html .= '<input type="checkbox" name="header_options[display]" id="header_options[display]" value="1" '.checked(1, $display, false).'';
	$html .= '&nbsp;';
	$html .= 'Display the header text.';
	$html .= '</label>';
	
	echo $html;
} // end kd_header_text_display

/**
 * Renders the description of the settings below the title of the section
 * and the above the actual settings.
 */
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