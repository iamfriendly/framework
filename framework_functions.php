<?php

	/* ======================================================================================

	These are the framework functions. All of which are pluggable (i.e. you can over-ride them 
	in a child theme. They are the white lines on the blue print that is Incipio).

	Quite a few of these are taken from the Roots theme (some are modified a wee bit)

	====================================================================================== */

	if( !function_exists( 'incipio_load_js' ) )
	{
	
		/**
		 * Correctly Load our js, as much in the footer as possible
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param None
		 * @return None
		 */
	
		function incipio_load_js()
		{
			
			$style_dir = get_template_directory_uri();
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'modernizr', $style_dir . '/framework/frontend/js/modernizr.js', 'jquery', '', false );
			
			wp_enqueue_script( 'responsiveslides', $style_dir . '/framework/frontend/js/jquery.responsiveslides.js', 'jquery', '', true );
			
			wp_enqueue_script( 'tiptip_js',  $style_dir.'/framework/frontend/js/jquery.tooltips.js', 'jquery', '', true );
			wp_enqueue_script( 'site_js', $style_dir.'/framework/frontend/js/site.js', 'jquery', '', true );
			
			if ( is_singular() )
				wp_enqueue_script( 'comment-reply' );
				
			//For IE, we need a couple of admendments. *idly stares at IE*
			global $is_IE;
			if( $is_IE )
			{
				
				wp_enqueue_script( 'respondjs',  $style_dir.'/framework/frontend/js/respond.min.js', '', '', false );
				wp_enqueue_script( 'ie7js',  'http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE7.js', '', '', false );
				wp_enqueue_script( 'html5shiv', 'http://html5shiv.googlecode.com/svn/trunk/html5.js', '', '', false );
				wp_enqueue_script( 'chromeframe',  'http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js', '', '', true );
				
			}
		
		}/* incipio_load_js() */
	
	}
	
	add_action( 'wp_enqueue_scripts', 'incipio_load_js' );


	/* =================================================================================== */

	
	if( !function_exists( 'incipio_load_css' ) )
	{
	
		/**
		 * Correctly Load our css
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param None
		 * @return None
		 */
	
		function incipio_load_css()
		{
			
			$style_dir = get_stylesheet_directory_uri();
			
			wp_enqueue_style( 'main_stylesheet', $style_dir . '/style.css', '', '', 'screen' );
		
		}/* incipio_load_css() */
	
	}
	
	add_action( 'wp_enqueue_scripts', 'incipio_load_css' );


	/* =================================================================================== */

	
	if( !function_exists( 'incipio_count_widgets_on_sidebar' ) ) :
	
		/**
		 * allow us to calculate the number of widgets assigned to a sidebar
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param $sidebar_name = the id of the sidebar to check, $just_number return just the 
		 * number of active widgets or have it prepended with the sidebar name
		 * @return (int) @number_of_widgets - the number of widgets or (string) The number of widgets
		 * prepended with "num_widgets_"
		 */
		

		function incipio_count_widgets_on_sidebar( $sidebar_name, $just_number = false )
		{
		
			$current_sidebar_widgets = get_option( 'sidebars_widgets' );
		
			if( is_array( $current_sidebar_widgets ) )
			{

				if( array_key_exists( $sidebar_name, $current_sidebar_widgets ) )
				{

					$number_of_widgets =  count( $current_sidebar_widgets[$sidebar_name] );
					
					if( $just_number === true )
						return $number_of_widgets;
					else
						return "num_widgets_".$number_of_widgets;

				}

			}
		
		}/* incipio_count_widgets_on_sidebar() */
	
	endif;


	/* =================================================================================== */

	if( !function_exists( 'incipio_convert_number_to_words' ) ) :

		/**
		 * Converts a number to it's appropriate word i.e. converts "2" into "two" and "7"
		 * into "seven"
		 *
		 * @author Karl Rixon
		 * @package Incipio
		 * @since 1.0
		 * @param $number - the number to convert
		 * @return $string - the converted number
		 * @link http://www.karlrixon.co.uk/writing/convert-numbers-to-words-with-php/
		 */
		

		function incipio_convert_number_to_words( $number )
		{

			$hyphen      = '-';
		    $conjunction = ' and ';
		    $separator   = ', ';
		    $negative    = 'negative ';
		    $decimal     = ' point ';
		    $dictionary  = array(
		        0                   => 'zero',
		        1                   => 'one',
		        2                   => 'two',
		        3                   => 'three',
		        4                   => 'four',
		        5                   => 'five',
		        6                   => 'six',
		        7                   => 'seven',
		        8                   => 'eight',
		        9                   => 'nine',
		        10                  => 'ten',
		        11                  => 'eleven',
		        12                  => 'twelve',
		        13                  => 'thirteen',
		        14                  => 'fourteen',
		        15                  => 'fifteen',
		        16                  => 'sixteen',
		        17                  => 'seventeen',
		        18                  => 'eighteen',
		        19                  => 'nineteen',
		        20                  => 'twenty',
		        30                  => 'thirty',
		        40                  => 'fourty',
		        50                  => 'fifty',
		        60                  => 'sixty',
		        70                  => 'seventy',
		        80                  => 'eighty',
		        90                  => 'ninety',
		        100                 => 'hundred',
		        1000                => 'thousand',
		        1000000             => 'million',
		        1000000000          => 'billion',
		        1000000000000       => 'trillion',
		        1000000000000000    => 'quadrillion',
		        1000000000000000000 => 'quintillion'
		    );
		    
		    if( !is_numeric( $number ) )
		        return false;
		    
		    if( ( $number >= 0 && (int) $number < 0 ) || (int) $number < 0 - PHP_INT_MAX )
		    {

		        // overflow
		        trigger_error(
		            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
		            E_USER_WARNING
		        );

		        return false;

		    }

		    if( $number < 0 )
		        return $negative . incipio_convert_number_to_words( abs ( $number ) );
		    
		    $string = $fraction = null;
		    
		    if( strpos( $number, '.' ) !== false )
		        list( $number, $fraction ) = explode( '.', $number );
		    
		    switch( true )
		    {

		        case $number < 21:
		            $string = $dictionary[$number];
		            break;

		        case $number < 100:
		            $tens   = ((int) ( $number / 10 ) ) * 10;
		            $units  = $number % 10;
		            $string = $dictionary[$tens];
		            if ( $units )
		                $string .= $hyphen . $dictionary[$units];

		            break;

		        case $number < 1000:
		            $hundreds  = $number / 100;
		            $remainder = $number % 100;
		            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
		            if( $remainder )
		                $string .= $conjunction . incipio_convert_number_to_words( $remainder );

		            break;

		        default:
		            $baseUnit = pow( 1000, floor( log( $number, 1000 ) ) );
		            $numBaseUnits = (int) ( $number / $baseUnit );
		            $remainder = $number % $baseUnit;
		            $string = incipio_convert_number_to_words( $numBaseUnits ) . ' ' . $dictionary[$baseUnit];
		            if( $remainder )
		            {

		                $string .= $remainder < 100 ? $conjunction : $separator;
		                $string .= incipio_convert_number_to_words( $remainder );

		            }
		            break;

		    }
		    
		    if( null !== $fraction && is_numeric( $fraction ) )
		    {

		        $string = 'fraction';

		    }
		    
		    return $string;

		}/* incipio_convert_number_to_words() */

	endif;


	/* =================================================================================== */


	if( !function_exists( 'incipio_correct_columns_for_widgets' ) ) :

		/**
		 * Our framework is based on 12 columns. So, if we have one widget in a row, it should
		 * by 12 columns wide. If we have 2, they should each be 6 columns. So, find out number
		 * of widgets in the sidebar, then calculate how many columns each widget should be, then
		 * convert that number into words and return that string - won't work for 5 widgets in a 
		 * row by default
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (required) $sidebar_name - the name of the sidebar (the ID)
		 * @return $num_cols - string of the amount of cols for widgets
		 */
		

		function incipio_correct_columns_for_widgets( $sidebar_name )
		{

			$num_widgets = incipio_count_widgets_on_sidebar( $sidebar_name, true );

			$num_cols_figure = 12 / $num_widgets;

			$num_cols = incipio_convert_number_to_words( $num_cols_figure );

			return $num_cols;

		}/* incipio_correct_columns_for_widgets() */

	endif;


	/* =================================================================================== */

	if( !function_exists( 'incipio_widget_params' ) ) :

		/**
		 * Each sidebar can have multiple widgets and we'll want them to stack with our framework's columns
		 * This function adds the necessary markup based on how many widgets are in the sidebar. Uses
		 * incipio_correct_columns_for_widgets() to get the right number of columns and then adds the
		 * appropriate classes to the <div> container for each widget.  
		 *
		 * @author Richard Tape
		 * @package 
		 * @since 1.0
		 * @param 
		 * @return 
		 */
		

		function incipio_widget_params( $params )
		{

			//How many columns should this widget be?
			$num_cols = incipio_correct_columns_for_widgets( $params[0]['id'] );

			//We need to add classes for each widget type so widget-text or widget-archives. The widget_id in the 
			//$params array contains the type but appends with a number - scrub the number
			$class_parts = explode( "-", $params[0]['widget_id'] );
			$widget_type = $class_parts[0];

			//Add the approproate markup to the containing element
			$params[0]['before_widget'] = '<div id="' . @$params[0]['widget_id'] . '" class="' . $num_cols . ' columns widget widget-' . $widget_type . '">';

			return $params;

		}/* incipio_widget_params() */

	endif;

	add_filter( 'dynamic_sidebar_params', 'incipio_widget_params' );


	/* =================================================================================== */


	if( !function_exists( 'incipio_widget_first_last_classes' ) ) :

		/**
		 * Add additional classes onto widgets
		 *
		 * @link http://wordpress.org/support/topic/how-to-first-and-last-css-classes-for-sidebar-widgets
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param $params - Widget params
		 * @return $params - the modified params with the new classes
		 */
		
		function incipio_widget_first_last_classes( $params )
		{

			global $my_widget_num;

			$this_id = $params[0]['id'];
			$arr_registered_widgets = wp_get_sidebars_widgets();

			if( !$my_widget_num )
				$my_widget_num = array();

			if( !isset( $arr_registered_widgets[$this_id] ) || !is_array( $arr_registered_widgets[$this_id] ) )
				return $params;

			if( isset( $my_widget_num[$this_id] ) )
				$my_widget_num[$this_id] ++;
			else
				$my_widget_num[$this_id] = 1;


			$class = 'class="widget-' . $my_widget_num[$this_id] . ' ';

			if( $my_widget_num[$this_id] == 1 )
				$class .= 'widget-first ';
			elseif( $my_widget_num[$this_id] == count( $arr_registered_widgets[$this_id] ) )
				$class .= 'widget-last ';

			$params[0]['before_widget'] = preg_replace( '/class=\"/', "$class", $params[0]['before_widget'], 1 );

			return $params;

		}/* incipio_widget_first_last_classes() */

	endif;

	add_filter( 'dynamic_sidebar_params', 'incipio_widget_first_last_classes' );


	/* =================================================================================== */


	/**
	 * Allow shortcodes in widgets
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param None
	 * @return None
	 */
	
	
	if( !is_admin() )
		add_filter( 'widget_text', 'do_shortcode', 11 );


	/* =================================================================================== */


	/**
	 * Add browser info to the body classes
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param $classes - Array of classes already being added to the body class
	 * @return $classes - our modified array
	 */
	
	if( !function_exists( 'incipio_browser_body_class' ) ) :
	
		function incipio_browser_body_class( $classes )
		{
			
			global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		
			if( $is_lynx ) $classes[] = 'lynx';
			elseif( $is_gecko ) $classes[] = 'gecko';
			elseif( $is_opera ) $classes[] = 'opera';
			elseif( $is_NS4 ) $classes[] = 'ns4';
			elseif( $is_safari ) $classes[] = 'safari';
			elseif( $is_chrome ) $classes[] = 'chrome';
			elseif( $is_IE ) $classes[] = 'ie';
			else $classes[] = 'unknown';
		
			if( $is_iphone ) $classes[] = 'iphone';
			
			/* Add OS Detection */
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			
			// detecting OS
			if( stripos( $user_agent, 'windows' ) !== false )
				$classes[] = 'win';
			elseif( stripos( $user_agent, 'macintosh' ) !== false )
				$classes[] = 'mac';
			elseif( stripos( $user_agent, 'iphone' ) !== false )
				$classes[] = 'iphone';
			
			if( is_admin() )
			{

				$classes = implode( '',$classes );
				return $classes;

			}
			else
			{

				return $classes;

			}
	
		}/* incipio_browser_body_class() */
	
	endif;

	add_filter( 'body_class','incipio_browser_body_class' );
	add_filter( 'admin_body_class', 'incipio_browser_body_class' );


	/* =================================================================================== */


	/**
	 * Clean up wp_head()
	 *
	 * Remove unnecessary <link>'s
	 * Remove inline CSS used by Recent Comments widget
	 * Remove inline CSS used by posts with galleries
	 * Remove self-closing tag and change ''s to "'s on rel_canonical()
	 *
	 * Originally from http://wpengineer.com/1438/wordpress-header/
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param None
	 * @return None
	 */

	if( !function_exists( 'incipio_head_cleanup' ) ) :
	
		function incipio_head_cleanup()
		{

			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
			remove_action( 'wp_head', 'rsd_link' );
			remove_action( 'wp_head', 'wlwmanifest_link' );
			remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
			remove_action( 'wp_head', 'wp_generator' );
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

			global $wp_widget_factory;
			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

			add_filter( 'use_default_gallery_style', '__return_null' );

			if( !class_exists( 'WPSEO_Frontend' ) )
			{

				remove_action( 'wp_head', 'rel_canonical' );
				add_action( 'wp_head', 'incipio_rel_canonical' );

			}

		}/* incipio_head_cleanup() */

	endif;


	/* =================================================================================== */


	if( !function_exists( 'incipio_rel_canonical' ) ) :

		/**
		 * Helper function to cleanup the rel_canonical() method (if WPSEO isn't activated)
		 *
		 * @author Richard Tape
		 * @package 
		 * @since 1.0
		 * @param 
		 * @return 
		 */

		function incipio_rel_canonical()
		{

			global $wp_the_query;

			if( !is_singular() )
				return;

			if( !$id = $wp_the_query->get_queried_object_id() )
				return;

			$link = get_permalink( $id );
			echo "\t<link rel=\"canonical\" href=\"$link\">\n";
			
		}/* incipio_rel_canonical() */

	endif;

	//Tie the two previous functions together on init
	add_action( 'init', 'incipio_head_cleanup' );


	/* =================================================================================== */


	/**
	 * Remove the WordPress version from RSS feeds
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 * @param None
	 * @return None
	 */
	
	add_filter( 'the_generator', '__return_false' );


	/* =================================================================================== */


	if( !function_exists( 'incipio_language_attributes' ) ) :

		/**
		 * Clean up language_attributes() used in <html> tag
		 *
		 * Change lang="en-US" to lang="en"
		 * Remove dir="ltr"
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param None
		 * @return $output - the cleaned attributes
		 */
		
		function incipio_language_attributes()
		{

			$attributes = array();
			$output = '';

			if( function_exists( 'is_rtl' ) )
			{

				if( is_rtl() == 'rtl' )
					$attributes[] = 'dir="rtl"';
			
			}

			$lang = get_bloginfo( 'language' );

			if( $lang && $lang !== 'en-US' )
				$attributes[] = "lang=\"$lang\"";
			else
				$attributes[] = 'lang="en"';

			$output = implode( ' ', $attributes );
			$output = apply_filters( 'incipio_language_attributes', $output );

			return $output;
		
		}/* incipio_language_attributes() */

	endif;

	add_filter( 'language_attributes', 'incipio_language_attributes' );


	/* =================================================================================== */


	if( !function_exists( 'incipio_clean_style_tag' ) ) :

		/**
		 * Clean up output of stylesheet <link> tags
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param $input - the <link> tag input
		 * @return (string) The modified <link> tag
		 */
		
		function incipio_clean_style_tag( $input )
		{

		  preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
		  
		  // Only display media if it's print
		  $media = $matches[3][0] === 'print' ? ' media="print"' : '';
		  
		  return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";

		}/* incipio_clean_style_tag() */

	endif;

	add_filter( 'style_loader_tag', 'incipio_clean_style_tag' );


	/* =================================================================================== */


	if( !function_exists( 'incipio_embed_wrap' ) ) :

		/**
		 * Wrap embedded media as suggested by Readability
		 *
		 * @author Richard Tape
		 * @package 
		 * @since 1.0
		 * @param $cache, $url, $attr, $post_ID
		 * @return (string) corrected markup
		 * @link https://gist.github.com/965956
	 	 * @link http://www.readability.com/publishers/guidelines#publisher
		 */
		
		function incipio_embed_wrap( $cache, $url, $attr = '', $post_ID = '' )
		{
			
			return '<div class="entry-content-asset">' . $cache . '</div>';

		}/* incipio_embed_wrap() */

	endif;

	add_filter( 'embed_oembed_html', 'incipio_embed_wrap', 10, 4 );
	add_filter( 'embed_googlevideo', 'incipio_embed_wrap', 10, 2 );


	/* =================================================================================== */


	if( !function_exists( 'incipio_attachment_link_class' ) ) :

		/**
		 * Add class="thumbnail" to attachment items
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param $html - the attachment markup
		 * @return (string) $html - the reformatted markup 
		 */
		
		function incipio_attachment_link_class( $html )
		{

			$postid = get_the_ID();
			$html = str_replace( '<a', '<a class="thumbnail"', $html );
		
			return $html;

		}/* incipio_attachment_link_class() */

	endif;

	add_filter( 'wp_get_attachment_link', 'incipio_attachment_link_class', 10, 1 );


	/* =================================================================================== */


	if( !function_exists( 'incipio_caption' ) ) :

		/**
		 * Add Bootstrap thumbnail styling to images with captions
		 * Use <figure> and <figcaption>
		 *
		 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param $output, $attr, $content
		 * @return $output - feformatted markup
		 */
		
		function incipio_caption( $output, $attr, $content )
		{

		  if (is_feed())
		    return $output;

		  $defaults = array(
		    'id' => '',
		    'align' => 'alignnone',
		    'width' => '',
		    'caption' => ''
		  );

		  $attr = shortcode_atts( $defaults, $attr );

		  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
		  if( 1 > $attr['width'] || empty($attr['caption'] ) )
		    return $content;

		  // Set up the attributes for the caption <figure>
		  $attributes  = ( !empty($attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
		  $attributes .= ' class="thumbnail wp-caption ' . esc_attr( $attr['align'] ) . '"';
		  $attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

		  $output  = '<figure' . $attributes .'>';
		  $output .= do_shortcode( $content );
		  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
		  $output .= '</figure>';

		  return $output;

		}/* incipio_caption() */

	endif;

	add_filter( 'img_caption_shortcode', 'incipio_caption', 10, 3 );


	/* =================================================================================== */

	if( !function_exists( 'incipio_gallery' ) ) :

		/**
		 * Clean up gallery_shortcode()
		 *
		 * Re-create the [gallery] shortcode and use thumbnails styling from Bootstrap
		 *
		 * @link http://twitter.github.com/bootstrap/components.html#thumbnails
		 *
		 * @author Richard Tape
		 * @package 
		 * @since 1.0
		 * @param $attr
		 * @return $output - the reformatted gallery output
		 */
		
		function incipio_gallery( $attr )
		{

			global $post, $wp_locale;

			static $instance = 0;
			$instance++;

			$output = apply_filters( 'post_gallery', '', $attr );

			if( $output != '' )
			return $output;

			if( isset( $attr['orderby'] ) )
			{

				$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
				if( !$attr['orderby'] )
					unset( $attr['orderby'] );

			}

			extract( shortcode_atts( array(
				'order'      => 'ASC',
				'orderby'    => 'menu_order ID',
				'id'         => $post->ID,
				'icontag'    => 'li',
				'captiontag' => 'p',
				'columns'    => 3,
				'size'       => 'thumbnail',
				'include'    => '',
				'exclude'    => ''
			), $attr ) );

			$id = intval( $id );

			if( $order === 'RAND' )
				$orderby = 'none';

			if( !empty( $include ) )
			{
			
				$include = preg_replace( '/[^0-9,]+/', '', $include );
				$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

				$attachments = array();
				foreach ( $_attachments as $key => $val )
				{
					$attachments[$val->ID] = $_attachments[$key];
				}
			
			}
			elseif( !empty( $exclude ) )
			{
			
				$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
				$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
			}
			else
			{
				$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
			}

			if( empty( $attachments ) )
				return '';

			if( is_feed() )
			{
			
				$output = "\n";
				foreach( $attachments as $att_id => $attachment )
					$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			
				return $output;

			}

			$captiontag = tag_escape( $captiontag );
			$columns    = intval( $columns );
			$itemwidth  = $columns > 0 ? floor( 100/$columns ) : 100;
			$float      = is_rtl() ? 'right' : 'left';
			$selector   = "gallery-{$instance}";

			$num_cols = incipio_convert_number_to_words( 12/$columns );

			$gallery_style = $gallery_div = '';

			if( apply_filters( 'use_default_gallery_style', true ) )
				$gallery_style = '';

			$size_class  = sanitize_html_class( $size );
			$gallery_div = "<ul id='$selector' class='row thumbnails gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
			$output      = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

			$i = 0;
			foreach( $attachments as $id => $attachment )
			{
			
				$link = isset( $attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );

				$output .= "
					<{$icontag} class=\"gallery-item {$num_cols} columns\">
					$link
				";
			
				if( $captiontag && trim( $attachment->post_excerpt ) )
				{
			
					$output .= "
						<{$captiontag} class=\"gallery-caption hidden\">
					" . wptexturize( $attachment->post_excerpt ) . "
						</{$captiontag}>";
			
				}
			
				$output .= "</{$icontag}>";
			
				if( $columns > 0 && ++$i % $columns == 0 )
					$output .= '';

			}

			$output .= "</ul>\n";

			return apply_filters( 'incipio_gallery_output', $output );

		}/* incipio_gallery() */

	endif;

	remove_shortcode( 'gallery' );
	add_shortcode( 'gallery', 'incipio_gallery' );


	/* =================================================================================== */


	if ( !isset( $content_width ) ) $content_width = 960;


	/* =================================================================================== */


	if( !function_exists( 'incipio_edit_search_form' ) ) :
	
		/**
		 * Edit the markup for the search form
		 *
		 * @author Richard Tape
		 * @package 
		 * @since 1.0
		 * @param $form - the search form markup
		 * @return A filtered markup
		 */

		function incipio_edit_search_form( $form )
		{
	
			$search_form_value = get_search_query();
			$search_form_value = ( $search_form_value && ( $search_form_value != "" ) ) ? $search_form_value : __( "Start typing...", THEMENAME );
	
		    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" ><div>
		    <input type="text" value="' . $search_form_value . '" name="s" id="s" onFocus="clearText(this)" onBlur="clearText(this)" />
		    <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Search', THEMENAME ) .'" />
		    </div>
		    </form>';
		
		    return apply_filters( 'incipio_search_form_markup', $form );
		
		}/* incipio_edit_search_form() */
	
	endif;

	add_filter( 'get_search_form', 'incipio_edit_search_form' );


	/* ======================================================================================

	Helper functions for our custom post types

	====================================================================================== */

	if( !function_exists( 'ff_pluralize_string' ) ) :

		/**
		 * Helper function to pluralize a CPT name neatly
		 * 
		 * @package Incipio
		 * @author Richard Tape
		 * @version 1.0
		 * @since 1.0
		 */

		function ff_pluralize_string( $string )
		{

			$last = $string[strlen( $string ) - 1];
			
			if( $last == 'y' )
			{

				$cut = substr( $string, 0, -1 );
				
				//convert y to ies
				$plural = $cut . 'ies';

			}
			else
			{

				// just attach a s
				$plural = $string . 's';

			}
			
			return $plural;

		}/* ff_pluralize_string() */

	endif;


	/* =================================================================================== */


	if( !function_exists( 'ff_beautify_string' ) ) :

		/**
		 * Beautifies a string. Capitalize words and remove underscores
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (string) $string
		 * @return string
		 */
		
		function ff_beautify_string( $string )
		{

			return ucwords( str_replace( '_', ' ', $string ) );

		}/* ff_beautify_string() */

	endif;
	

	/* =================================================================================== */


	if( !function_exists( 'ff_uglify_string' ) ) :

		/**
		 * Uglifies a string. Remove underscores and lower strings
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param string $string
		 * @return string
		 */
		

		function ff_uglify_string( $string )
		{

			return strtolower( preg_replace( '/[^A-z0-9]/', '_', $string ) );

		}/* ff_uglify_string() */

	endif;

	/* =================================================================================== */


	if( !function_exists( 'incipio_add_slug_to_menu_item' ) ) :

		/**
		 * Adds the post/page slug to the menu item classes
		 * from: http://www.wpreso.com/blog/tutorials/2011/01/04/add-pagepost-slug-class-to-menu-item-classes/
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param array $output - classes for menu items
		 * @return (array) $output - modified classes
		 */
		
		function incipio_add_slug_to_menu_item( $output )
		{

			$ps = get_option( 'permalink_structure' );
			
			if( !empty( $ps ) )
			{

				$idstr = preg_match_all( '/<li id="menu-item-(\d+)/', $output, $matches );
				
				foreach( $matches[1] as $mid )
				{

					$id = get_post_meta( $mid, '_menu_item_object_id', true );
					$slug = basename( get_permalink( $id ) );
					$output = preg_replace( '/menu-item-'.$mid.'">/', 'menu-item-' . $mid . ' menu-item-' . $slug . '">', $output, 1 );

				}

			}

			return $output;

		}/* incipio_add_slug_to_menu_item() */

	endif;

	add_filter( 'wp_nav_menu', 'incipio_add_slug_to_menu_item' );



	/* =================================================================================== */


	if( !function_exists( 'incipio_use_chosen_template_for_front_page' ) ) :

		/**
		 * Handle the redirect to the chosen "show_on_front" template if the user doesn't want to
		 * use the front-page.php template. First, see if the use_front_page_template theme option
		 * is ticked (if it is, just ignore everything and use front-page.php). If it's unticked,
		 * then we find out what is selected in Settings>Reading an honour that choice by using the
		 * relevant template.
		 *
		 * Hooks into template_redirect to do the magic
		 * Uses locate_template so it will work with child themes
		 * Uses of_get_option() to return the status of what the user wants to do from the options panel
		 * Uses get_option() to get the default WordPress options
		 *
		 * @author Richard Tape
		 * @package Autify
		 * @since 1.0
		 * @param None
		 * @return None (but includes the appropriate template and then exits)
		 */

		function incipio_use_chosen_template_for_front_page()
		{

			if( is_front_page() )
			{

				$use_front_page_php = of_get_option( 'use_front_page_template' ); //Either string "1" or bool(false)

				if( $use_front_page_php )
				{

					//The option is checked, therefore just use front-page.php as normal
					return;

				}
				else
				{

					//The user has decided they don't want to use front-page.php so retrieve what they have selected
					//to use under Settings>Reading
					$show_on_front = get_option( 'show_on_front' );  //string "posts" or "page"

					//If we are using "posts" then use the home.php template
					if( $show_on_front == "posts" )
					{
						
						include( locate_template( '/home.php' ) );
						exit;

					}
					else
					{

						//If we're using a page then we need to test if that page is using a custom template, in which
						//case we'll need to load that instead of the default which is page.php

						//First we get the 'page_on_front' WP option to determine which page is selected
						$page_on_front = get_option( 'page_on_front' ); // Will be (string) and an ID of a page i.e. "80"

						//Now we have the page ID, we need to determine which template that page is to use. "default" by default
						$template_name = get_post_meta( $page_on_front, '_wp_page_template', true ); //string of the path to template

						if( $template_name == "default" )
						{
							
							include( locate_template( '/page.php' ) );
							exit;

						}
						else
						{

							include( locate_template( '/' . $template_name ) );
							exit;

						}

					}

				}

			}

		}/* incipio_use_chosen_template_for_front_page() */

	endif;
	
	add_action( 'template_redirect', 'incipio_use_chosen_template_for_front_page' );



	/* =================================================================================== */


	if( !function_exists( 'incipio_set_current_template_name_global' ) ) :

		/**
		 * This function sets a global variable which allows us to easily debug which
		 * template is being used on a page. It's used primarily by incipio_get_current_template()
		 * below which is a template tag to be used in your theme.
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (string) $t - the current template
		 * @return (string) $t - the current template
		 * @link http://wordpress.stackexchange.com/questions/10537/get-name-of-the-current-template-file
		 */
		
		function incipio_set_current_template_name_global( $t )
		{

		    $GLOBALS['current_theme_template'] = basename( $t );
		    return $t;

		}/* incipio_set_current_template_name_global() */

	endif;

	add_filter( 'template_include', 'incipio_set_current_template_name_global', 1000 );


	if( !function_exists( 'incipio_get_current_template' ) ) :

		/**
		 * A template tag to allow us to output the current template that we're on
		 * This is mainly used for debugging and is used as the 'else' part of loops
		 * so that we can easily debug
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (bool) $echo - whether we're echoing or just returning
		 * @return the global template
		 */
		
		function incipio_get_current_template( $echo = false )
		{

		    if( !isset( $GLOBALS['current_theme_template'] ) )
		        return false;
		    if( $echo )
		        echo $GLOBALS['current_theme_template'];
		    else
		        return $GLOBALS['current_theme_template'];

		}/* incipio_get_current_template() */

	endif;

	/* =================================================================================== */


	if( !function_exists( 'incipio_debug_template_name' ) ) :

		/**
		 * This is a function which allows us to output the currently used template name
		 * so that if we get any 'no posts found' errors, we can at least know which template
		 * is being shown
		 *
		 * @author Richard Tape
		 * @package Autify
		 * @since 1.0
		 * @param None
		 * @return Outputs the template name
		 */
		
		function incipio_debug_template_name()
		{

			//Output the template name
			if( function_exists( 'incipio_get_current_template' ) )
				incipio_get_current_template( true );

		}/* incipio_debug_template_name() */

	endif;

	add_action( 'incipio_oh_no_no_posts_found', 'incipio_debug_template_name' );


	/* =================================================================================== */


	if( !function_exists( 'incipio_enqueue_theme_less_file' ) ) :

		/**
		 * We load the theme.less file if it exists. Load this with a priority of 99 so we can
		 * load other stylesheets before it in each theme
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param None
		 * @return Uses wp_enqueue_script()
		 */
		
		function incipio_enqueue_theme_less_file()
		{

			// enqueue a .less style sheet
			if ( !is_admin() )
			    wp_enqueue_style( 'lessstyle', get_template_directory_uri() . '/assets/less/theme.less' );

		}/* incipio_enqueue_theme_less_file() */

	endif;

	add_action( 'wp_enqueue_scripts', 'incipio_enqueue_theme_less_file', 99 );


	/* =================================================================================== */

	if( !function_exists( 'incipio_remove_img_dimensions' ) ) :

		/**
		* Filter out hard-coded width, height attributes on all images images in WordPress. 
		* https://gist.github.com/4557917
		*
		* This version applies the function as a filter to the_content rather than send_to_editor. 
		* Changes made by filtering send_to_editor will be lost if you update the image or associated post 
		* and you will slowly lose your grip on sanity if you don't know to keep an eye out for it. 
		* the_content applies to the content of a post after it is retrieved from the database and is "theme-safe". 
		* (i.e., Your changes will not be stored permanently or impact the HTML output in other themes.)
		*
		* Also, the regex has been updated to catch both double and single quotes, since the output of 
		* get_avatar is inconsistent with other WP image functions and uses single quotes for attributes. 
		* [insert hate-stare here]
		*
		*/

		function incipio_remove_img_dimensions( $html )
		{

			$html = preg_replace('/(width|height)=["\']\d*["\']\s?/', "", $html);
			return $html;
		
		}/* incipio_remove_img_dimensions() */

	endif;

	add_filter( 'post_thumbnail_html', 'incipio_remove_img_dimensions', 10 );
	add_filter( 'the_content', 'incipio_remove_img_dimensions', 10 );
	add_filter( 'get_avatar', 'incipio_remove_img_dimensions', 10 );


	/* =================================================================================== */


	if( !function_exists( 'incipio_limit_to_x_words' ) ) :

		/**
		 * Limit a string to a defined number of words and append an elipsis if the string
		 * is longer than x
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (string) $string - The string to chop
		 * @param (int) $x - How many words to limit by
		 * @param (bool) $ellipsis - Append an elipsis?
		 * @return (string) $string - the chopped string
		 */
		
		function incipio_limit_to_x_words( $string, $x = 20, $ellipsis = true )
		{

			$words = explode( ' ', $string );

			if( count( $words ) > $x )
			{
			
				array_splice( $words, $x );
			
				$string = implode( ' ', $words );
			
				if( is_string( $ellipsis ) )
				{
			
					$string .= $ellipsis;
			
				}
				elseif( $ellipsis )
				{
				
					$string .= '&hellip;';
			
				}

			}

			return apply_filters( 'incipio_limit_string', $string, $string );

		}/* incipio_limit_to_x_words() */

	endif;


	/* =================================================================================== */


	if( !function_exists( 'incipio_limit_to_x_chars' ) ) :

		/**
		 * Limit a string to a defined number of words and append an elipsis if the string
		 * is longer than x
		 *
		 * @author Richard Tape
		 * @package Incipio
		 * @since 1.0
		 * @param (string) $string - The string to chop
		 * @param (int) $x - How many words to limit by
		 * @param (string) $delim - What to append if the string is longer than x
		 * @return (string) $string - the chopped string
		 */
		
		function incipio_limit_to_x_chars( $string, $x = 20, $delim = '&#8230;' )
		{

			//what's the length of the string?
			$length_of_string = strlen( $string );

			if( $length_of_string > $x )
			{

				//Run a regex to split the words on a space
				preg_match( '/(.{' . $x . '}.*?)\b/', $string, $matches );

				return apply_filters( 'incipio_limit_string', rtrim( $matches[1] ) . $delim, $string );

			}
			else
			{

				//The string is shorter than x, so just return the string
				return apply_filters( 'incipio_limit_string', $string, $string );

			}

		}/* incipio_limit_to_x_chars() */

	endif;
	

?>