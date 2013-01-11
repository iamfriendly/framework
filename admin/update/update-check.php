<?php

	/* ======================================================================================

	This file is simply a function which grabs the username and API key options from the 
	dashboard and then passes those to the update class

	====================================================================================== */

	//Load the Themeforest Envato Upgrade check
	require_once locate_template( '/framework/admin/update/class-envato-wordpress-theme-upgrader.php' );

	if( !function_exists( 'incipio_theme_update_check' ) ) :

		/**
		 * Theme update check routine using Envato API
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param None
		 * @return Uses transient and sets update as necessary
		 */
		
		function incipio_theme_update_check()
		{

			//If we have a transient set then we don't need to run the update check (until it expires)
			$transient_key = THEMENAME . '-theme-update-transient-key';
			$data = get_transient( $transient_key );

			//Update timer is run through a filter so we can adjust the frequency
			$update_frequency = 60 * 60 * 12;
			$update_frequency = apply_filters( 'incipio_theme_update_frequency', $update_frequency );

			//The transient either isn't set or it has expired. So check for a theme update.
			if( $data == '' )
			{

				//Grab the username and API Key stored in our options
				$themeforest_username = of_get_option( 'themeforest_username', '' );
				$themeforest_api_key = of_get_option( 'themeforest_api_key', '' );

				//Run the Envato upgrade check
				$upgrader = new Envato_WordPress_Theme_Upgrader( $themeforest_username , $themeforest_api_key );
				$update_available = $upgrader->check_for_theme_update();

				if( isset( $update_available->errors ) && is_array( $update_available->errors ) )
				{

					//Themeforest has reported back errors. Bad times. Set transient any way
					set_transient( $transient_key, maybe_serialize( $update_available->errors ), $update_frequency );

				}
				else
				{

					//If there's an update, run it
					if( $update_available && $update_available->updated_themes_count && $update_available->updated_themes_count > 0 )
						$upgrader->upgrade_theme();

					$data = ( $update_available && $update_available->updated_themes_count ) ? $update_available->updated_themes_count : '0' ;

					//Regardless of if there's an update or not, set a transient so we don't do it on every page load
					set_transient( $transient_key, $data, $update_frequency );

				}

			}

		}/* incipio_theme_update_check() */

	endif;

	add_action( 'admin_init', 'incipio_theme_update_check' );

?>