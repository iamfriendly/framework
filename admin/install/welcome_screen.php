<?php

	// Naughty Naughty
	if ( ! defined( 'ABSPATH' ) ) exit;


	/**
	 * Replicate the WP welcome screen to greet users when they install or update
	 * Quite a lot of this is from EDD by PippinsPlugins which he got from bbPress
	 * Thanks Pippin and JJJ :)
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 0.7
	 * @param None
	 * @return None
	 */

	class IncipioWelcome
	{

		/**
		 * What capbility is required to see the welcome screen?
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 */
		
		public $minimum_capability = 'manage_options';


		/**
		 * Get things started
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */
		
		public function __construct()
		{

			add_action( 'admin_menu', array( $this, 'admin_menus') );
			add_action( 'admin_head', array( $this, 'admin_head' ) );
			add_action( 'admin_init', array( $this, 'welcome'    ) );

		}/* __construct() */

		/* =============================================================================== */

		/**
		 * Register dashboard pages.
		 *
		 * Later they are hidden in 'admin_head'.
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */

		public function admin_menus()
		{

			// About Page
			add_dashboard_page(
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				$this->minimum_capability,
				'incipio-welcome',
				array( $this, 'welcome_screen' )
			);

			// About Page
			add_dashboard_page(
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				$this->minimum_capability,
				'incipio-about',
				array( $this, 'about_screen' )
			);

			// Help Page
			add_dashboard_page(
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				__( 'Welcome to ' . THEMENAME, THEMENAME ),
				$this->minimum_capability,
				'incipio-help',
				array( $this, 'help_screen' )
			);

		}/* admin_menus() */

		/* =============================================================================== */

		/**
		 * Hide Individual Dashboard Menus
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */
		public function admin_head()
		{

			remove_submenu_page( 'index.php', 'incipio-welcome' );
			remove_submenu_page( 'index.php', 'incipio-about' );
			remove_submenu_page( 'index.php', 'incipio-help' );

			// Badge for welcome page
			$badge_url = get_template_directory_uri() . '/framework/chemistry/admin/assets/images/logo-transparent-black-themeists.png';
			
			?>

			<style type="text/css" media="screen">
			/*<![CDATA[*/
			.edd-badge {
				padding-top: 150px;
				height: 52px;
				width: 185px;
				margin: 0 -5px;
				background: url('<?php echo $badge_url; ?>') no-repeat;
			}

			.about-wrap .edd-badge {
				position: absolute;
				top: 0;
				right: 0;
			}

			.videoWrapper {
				position: relative;
				padding-bottom: 51.25%; /* 16:9 */
				padding-top: 25px;
				height: 0;
			}
			.videoWrapper iframe {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}

			/*]]>*/
			</style>

			<?php

		}/* admin_head() */

		/* =============================================================================== */

		/**
		 * Render the initial, welcome screen
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */
		
		public function welcome_screen()
		{

			list( $display_version ) = explode( '-', THEMEVERSION ); ?>
			
			<div class="wrap about-wrap">
				
				<?php ?>

				<h1><?php printf( __( 'Welcome to %s %s', THEMENAME ), THEMENAME, $display_version ); ?></h1>
				<div class="about-text"><?php printf( __( 'Thank you for installing one of our themes. We really hope you enjoy using it as much as we enjoyed making it. You will only see this screen directly after you install or update.', THEMENAME ) ); ?></div>
				
				<div class="edd-badge"></div>

				<h2 class="nav-tab-wrapper">
					
					<a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-welcome' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Welcome', THEMENAME ); ?>
					</a>
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-about' ), 'index.php' ) ) ); ?>">
						<?php _e( 'What Now?', THEMENAME ); ?>
					</a>
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-help' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Help', THEMENAME ); ?>
					</a>

				</h2>

				<p class="about-description"><?php _e( 'We have produced a useful screencast which walks you through exactly how to go about installing your new theme. We show you in about 10 minutes exactly how to reproduce everything we have on our demo as well as some help hints with regards to the different functionality in your theme.', THEMENAME ); ?></p>

				<div id="install_screencast" class="videoWrapper">
					<iframe src="http://player.vimeo.com/video/56465477?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff" id="" width="720" height="383" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
				</div>

				<div class="return-to-dashboard">
					<a href="<?php echo esc_url( 'http://docs.themeists.com/' ); ?>"><?php _e( 'Go to online help documentation', THEMENAME ); ?></a> or <a href="<?php echo esc_url( 'http://support.themeists.com/' ); ?>"><?php _e( 'Go to support forums', THEMENAME ); ?></a>
				</div>

			</div>

			<?php

		}/* welcome_screen */

		/* =============================================================================== */

		/**
		 * Render About Screen
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */

		public function about_screen()
		{

			list( $display_version ) = explode( '-', THEMEVERSION ); ?>
			
			<div class="wrap about-wrap">
				
				<h1><?php printf( __( 'Welcome to %s %s', THEMENAME ), THEMENAME, $display_version ); ?></h1>
				
				<div class="about-text"><?php printf( __( 'Thank you for installing one of our themes. We really hope you enjoy using it as much as we enjoyed making it. You will only see this screen directly after you install or update.', THEMENAME ) ); ?></div>
				
				<div class="edd-badge"></div>

				<h2 class="nav-tab-wrapper">
					
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-welcome' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Welcome', THEMENAME ); ?>
					</a>
					<a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-about' ), 'index.php' ) ) ); ?>">
						<?php _e( 'What Now?', THEMENAME ); ?>
					</a>
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-help' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Help', THEMENAME ); ?>
					</a>

				</h2>

				<div class="changelog">

					<h3><?php _e( '01 - Install plugins', THEMENAME ); ?></h3>

					<p><?php _e( 'We use a few plugins to great effect on our demo. You don\'t <strong>need</strong> them but we recommend the following: ', THEMENAME ); ?></p>

					<div class="feature-section">
						<h4><?php _e( 'Themeists Custom Post Types', THEMENAME ); ?></h4>
						<p><?php _e( 'We know you value your content. That means you shouldn\'t be tied to your theme because, if it were, you would instantly lose access to that data if you changed theme. That\'s why we keep our custom post types in a plugin.', THEMENAME ); ?></p>
						<p><?php _e( 'So if you install this plugin you will gain access to the post types that this theme supports. If, in the future, you change themes - specifically away from a Themeists theme - then you will still have those post types and therefore access to your data. Just the way it should be.', THEMENAME ); ?></p>
					</div>

					<div class="feature-section">
						<h4><?php _e( 'Themeists Custom Sidebars', THEMENAME ); ?></h4>
						<p><?php _e( 'Just like post types, we think that the ability to have infinite sidebars on your site should not be limited to your theme. this means that on <strong>every</strong> single post or page on your site you are able to have a unique sidebar.', THEMENAME ); ?></p>
					</div>

					<div class="feature-section">
						<h4><?php _e( 'Themeists Widgets', THEMENAME ); ?></h4>
						<p><?php _e( 'We have produced several widgets which add extra functionality to your site. Again, these shouldn\'t be locked into your theme. After you install this plugin you will have access to several new widgets in the Appearance > Widgets screen (where you will find all of the available widget areas)', THEMENAME ); ?></p>
					</div>

					<div class="feature-section">
						<h4><?php _e( 'Themeists Easy Share', THEMENAME ); ?></h4>
						<p><?php _e( 'Folks love being able to easily share your content with their friends and colleagues. That\'s why we made the Easy Share plugin which gives you the ability to easily add the URLs of your social networks and it will show their icons. Easy, fast and useful.', THEMENAME ); ?></p>
					</div>

					<div class="feature-section">
						<h4><?php _e( 'Themeists Like This', THEMENAME ); ?></h4>
						<p><?php _e( 'We use this plugin to allow you to give your visitors the ability to "like" your posts, pages, work etc. It places a little &heart; symbol which people can click on and keeps a record of how many people have done so.', THEMENAME ); ?></p>
					</div>

					<div class="feature-section">
						<h4><?php _e( 'WordPress SEO By Yoast', THEMENAME ); ?></h4>
						<p><?php _e( 'Whilst we are (told we are) pretty good at what we do (which is design and build WordPress themes) we are not SEO Experts. Fortunately, a guy who goes by the name of Yoast <em>is</em> is a SEO Expert and he\'s written a truly brilliant plugin which gives you absolute control over what search engines see on your site. If you want traffic on your website (and it\'s highly likely you do) then install this plugin and set it up. Job. Done.', THEMENAME ); ?></p>
					</div>

				</div>

				<div class="changelog">

					<h3><?php _e( '02 - Set up widgets', THEMENAME ); ?></h3>

					<p><?php _e( 'The header and footer on your site are all widget-ready sidebars. That gives you the most amount of control and ease of access. Simply pop on over to Appearance > Widgets to add different widgets to your sidebars.', THEMENAME ); ?></p>

				</div>

				<div class="changelog">

					<h3><?php _e( '03 - Theme Options', THEMENAME ); ?></h3>

					<p><?php _e( 'Your theme options panel is easy-to-use and comprehensive. Each option has lots of help information beside it and gives you complete control over how your website appears and functions.', THEMENAME ); ?></p>

				</div>

				<div class="return-to-dashboard">
					<a href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'options-framework' ), 'themes.php' ) ) ); ?>"><?php _e( 'Go to your theme option panel', THEMENAME ); ?></a>
				</div>
			</div>

			<?php

		}/* about_screen() */

		/* =============================================================================== */

		/**
		 * Render Help Screen
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */

		public function help_screen()
		{

			list( $display_version ) = explode( '-', THEMEVERSION ); ?>
			
			<div class="wrap about-wrap">
				
				<?php ?>

				<h1><?php printf( __( 'Welcome to %s %s', THEMENAME ), THEMENAME, $display_version ); ?></h1>
				<div class="about-text"><?php printf( __( 'Thank you for installing one of our themes. We really hope you enjoy using it as much as we enjoyed making it. You will only see this screen directly after you install or update.', THEMENAME ) ); ?></div>
				
				<div class="edd-badge"></div>

				<h2 class="nav-tab-wrapper">
					
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-welcome' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Welcome', THEMENAME ); ?>
					</a>
					<a class="nav-tab" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-about' ), 'index.php' ) ) ); ?>">
						<?php _e( 'What Now?', THEMENAME ); ?>
					</a>
					<a class="nav-tab nav-tab-active" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'incipio-help' ), 'index.php' ) ) ); ?>">
						<?php _e( 'Help', THEMENAME ); ?>
					</a>

				</h2>

				<p class="about-description"><?php _e( 'We have produced beautiful and easy-to-understand help documentation which you will have found in the zip file you downloaded. We also have online help documentation which includes video screencasts of how to accomplish most things on your site.', THEMENAME ); ?></p>

				<p class="about-description"><?php _e( 'Also, we have support forums which are free to use for everyone who purchases one of our themes (or for our free themes).', THEMENAME ); ?></p>

				<div class="return-to-dashboard">
					<a href="<?php echo esc_url( 'http://docs.themeists.com/' ); ?>"><?php _e( 'Go to online help documentation', THEMENAME ); ?></a> or <a href="<?php echo esc_url( 'http://support.themeists.com/' ); ?>"><?php _e( 'Go to support forums', THEMENAME ); ?></a>
				</div>

			</div>

			<?php

		}/* help_screen() */

		/* =============================================================================== */

		/**
		 * Sends user to the welcome page on first activation
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 0.7
		 * @param None
		 * @return None
		 */

		public function welcome()
		{

			if( isset( $_GET['activated'] ) )
			{

				// Bail if activating from network, or bulk
				if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
					return;

				wp_safe_redirect( admin_url( 'index.php?page=incipio-welcome' ) ); exit;

			}
			
		}/* welcome() */

	}/* class IncipioWelcome */

	new IncipioWelcome();

?>