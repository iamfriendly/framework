<?php

	/* ======================================================================================

	This file runs a setup routine when someone activates the theme. It may not contain 
	anything for some themes, but for others it may do several things such as install widgets
	onto sidebars, put up warning messages, adjust the welcome screen etc.

	====================================================================================== */

	//When we first activate the theme (or update) we should redirect to the welcome screen
	if( file_exists( locate_template( '/framework/admin/install/welcome_screen.php', false  ) ) ) :
		
		include locate_template( '/framework/admin/install/welcome_screen.php' );

	endif;

	//Check if we have any recommended or required plugins, if so load the class
	if( file_exists( locate_template( '/dropins/plugins.php', false  ) ) ) :
		
		include locate_template( '/framework/admin/install/class.plugin_activation.php' );
		include locate_template( '/dropins/plugins.php' );

	endif;

?>