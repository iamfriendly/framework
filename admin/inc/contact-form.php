<?php
		
	/**
	 * Has the javascript been output yet? Default to zero.
	 *
	 * @author Richard Tape
	 * @package Incipio
	 * @since 1.0
	 */
	
	$tcf_script_printed = 0;


	/**
	 * Mostly taken from Tom Braider's excellent Tiny Contact Form plugin
	 * Modified for our purposes.
	 *
	 * @author Richard Tape
	 * @package Chemistry
	 * @since 0.7
	 * @param 
	 * @return 
	 */

	class FriendlyContactForm
	{


		/**
		 * Some public class variables
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 */
		
		/* =============================================================================== */

		var $captcha;
		var $userdata;
		var $nr = 0; // form number to use more then once forms/widgets
		
		/**
		 * Constructor where we add the shortcode
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 * @param None
		 * @return None
		 */
		
		function FriendlyContactForm()
		{

			//Add the shortcode by calling $this->shortcode
			add_shortcode( 'CONTACT_FORM', array( &$this, 'shortcode' ) );

		}/* FriendlyContactForm() */


		/* =============================================================================== */
		
		
		/**
		 * Outputs the markup for our form
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 0.7
		 * @param ( array ) $params - Any params passed to the form
		 * @return ( string ) $form - Markup
		 */
		
		function showForm( $params = '' )
		{
		
			$n = ( $this->nr == 0 ) ? '' : $this->nr;
			$this->nr++;
		
			if( isset( $_POST['tcf_sender' . $n] ) )
				$result = $this->sendMail( $n, $params );
				
			$form = '<div class="contactform" id="tcform' . $n . '">';
			
			if( !empty( $result ) )
			{

				if( $result == of_get_option( 'contact_form_success_message' ) )
					// mail successfully sent, no form
					$form .= '<p class="contactform_respons">' . $result . '</p>';
				else
					// error message
					$form .= '<p class="contactform_error">' . $result . '</p>';
			}
				
			if( empty( $result ) || ( !empty( $result ) ) )
			{

				// subject from form
				if( !empty( $_POST['tcf_subject' . $n] ) )
					$tcf_subject = $_POST['tcf_subject' . $n];
				// subject from widget instance
				else if( is_array( $params ) && !empty( $params['subject'] ) )
					$tcf_subject = $params['subject'];
				// subject from URL
				else if( empty( $_POST['tcf_subject' . $n] ) && !empty( $_GET['subject'] ) )
					$tcf_subject = $_GET['subject'];
				// subject from short code
				else if( empty( $_POST['tcf_subject' . $n] ) && !empty( $this->userdata['subject'] ) )
					$tcf_subject = $this->userdata['subject'];
				else
					$tcf_subject = '';
					
				$tcf_sender =	( isset( $_POST['tcf_sender' . $n] ) ) ? $_POST['tcf_sender' . $n] : ''; 
				$tcf_email =	( isset( $_POST['tcf_email' . $n] ) ) ? $_POST['tcf_email' . $n] : '';
				$tcf_msg =		( isset( $_POST['tcf_msg' . $n] ) ) ? $_POST['tcf_msg' . $n] : '';
				
				$form .= '
					<form action="#tcform" method="post" id="tinyform' . $n . '">
					<div>
						<div class="form_inputs">
							<input name="tcf_name' . $n . '" id="tcf_name' . $n . '" value="" class="tcf_input input-text" style="display: none; visibility: hidden;" />
							<input name="tcf_sendit' . $n . '" id="tcf_sendit' . $n . '" value="1" class="tcf_input input-text" style="display: none; visibility: hidden;" />

							<p>
								<label for="tcf_sender' . $n . '" class="tcf_label">'. __( 'Name', THEMENAME ) . ':</label>
								<input type="text" name="tcf_sender' . $n . '" id="tcf_sender' . $n . '" size="30" value="' . $tcf_sender . '" class="tcf_field input-text" />
							</p>

							<p>
								<label for="tcf_email' . $n . '" class="tcf_label">'. __( 'Email', THEMENAME ) . ':</label>
								<input type="email" name="tcf_email' . $n . '" id="tcf_email' . $n . '" size="30" value="' . $tcf_email . '" class="tcf_field input-text" />
							</p>';

						$title = ( of_get_option( 'contact_form_submit_button_text' ) ) ? 'value="'.of_get_option( 'contact_form_submit_button_text' ) . '"' : '';
						$form .= '
							
							<p>
								<label for="tcf_subject' . $n . '" class="tcf_label">'. __( 'Subject', THEMENAME ) . ':</label>
								<input type="text" name="tcf_subject' . $n . '" id="tcf_subject' . $n . '" size="30" value="' . $tcf_subject . '" class="tcf_field input-text" />
							</p>
						</div>
					
						<div class="form_message">
					
							<p>
								<label for="tcf_msg' . $n . '" class="tcf_label">'. __( 'Your Message', THEMENAME ) . ':</label>
								<textarea name="tcf_msg' . $n . '" id="tcf_msg' . $n . '" class="tcf_textarea" cols="50" rows="10">' . $tcf_msg . '</textarea></p><p class="form-submit"><input type="submit" name="submit' . $n . '" id="contactsubmit' . $n . '" class="tcf_submit" ' . $title . '  onclick="return checkForm( \'' . $n . '\' );" />
							</p>

						</div>';
					
				
				$form .= '</div></form>';

			}
			
			$form .= '</div>'; 

			$form .= $this->addScript();

			return $form;

		}/* showForm() */


		/* =============================================================================== */

		
		/**
		 * adds javascript code to check the values
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 * @param None
		 * @return (string) $script -  JS Markup
		 */
		
		function addScript()
		{

			global $tcf_script_printed;
			
			if( $tcf_script_printed ) // only once
				return;
			
			$script = "<script type=\"text/javascript\">
				//<![CDATA[
				function checkForm( n )
				{
					var f = new Array();
					f[1] = document.getElementById( 'tcf_sender' + n ).value;
					f[2] = document.getElementById( 'tcf_email' + n ).value;
					f[3] = document.getElementById( 'tcf_subject' + n ).value;
					f[4] = document.getElementById( 'tcf_msg' + n ).value;
					f[5] = f[6] = f[7] = f[8] = f[9] = '-';
				";
				
			$script .= 'var msg = "";
				for ( i=0; i < f.length; i++ )
				{
					if( f[i] == "" )
						msg = "'. __( 'Please fill out all fields . ', THEMENAME ) . '\n";
				}
				if( !isEmail( f[2] ) )
					msg += "'. __( 'Incorrect Email . ', THEMENAME ) . '\n";
				if( msg != "" )
				{
					alert( msg );
					return false;
				}
			}
			function isEmail( email )
			{
				var rx = /^( [^\s@,:"<>]+ )@( [^\s@,:"<>]+\.[^\s@,:"<>.\d]{2,}|( \d{1,3}\. ){3}\d{1,3} )$/;
				var part = email.match( rx );
				if( part )
					return true;
				else
					return false
			}
			//]]>
			</script>
			';

			$tcf_script_printed = 1;
			
			return $script;

		}/* addScript() */


		/* =============================================================================== */


		/**
		 * send mail
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 * @param (string) $n - Result message
		 * @param (array) $params - Any Params
		 * @return string Result, Message
		 */
		
		function sendMail( $n = '', $params = '' )
		{
		
			$result = $this->checkInput( $n );
				
		    if( $result == 'OK' )
		    {

		    	$result = '';
		    	
		    	// or from short code
				if( !empty( $this->userdata['to'] ) )
					$to = $this->userdata['to'];
				// or default
				else
					$to = of_get_option( 'contact_form_email_address' );
				
				$from	= of_get_option( 'contact_form_email_address' );
			
				$name		= $_POST['tcf_sender' . $n];
				$email		= $_POST['tcf_email' . $n];
				$subject 	= $_POST['tcf_subject' . $n];
				$msg		= $_POST['tcf_msg' . $n];
				
				// create mail
				$headers =
				"MIME-Version: 1.0\r\n".
				"Reply-To: \"$name\" <$email>\r\n".
				"Content-Type: text/plain; charset=\"".get_option( 'blog_charset' )."\"\r\n";
				if( !empty( $from ) )
					$headers .= "From: " . get_bloginfo( 'name' ) . " - $name <$from>\r\n";
				else if( !empty( $email ) )
					$headers .= "From: " . get_bloginfo( 'name' ) . " - $name <$email>\r\n";
		
				$fullmsg =
				"Name: $name\r\n".
				"Email: $email\r\n".
				'Subject: ' . $_POST['tcf_subject' . $n] . "\r\n\r\n".
				wordwrap( $msg, 76, "\r\n" )."\r\n\r\n".
				'Referer: ' . $_SERVER['HTTP_REFERER'] . "\r\n".
				'Browser: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
				
		    	// send mail
				if( wp_mail( $to, $subject, $fullmsg, $headers ) )
					$result = of_get_option( 'contact_form_success_message' );
				else
					$result = of_get_option( 'contact_form_error_message' );

		    }

		    return $result;

		}/* sendMail() */


		/* =============================================================================== */

		
		/**
		 * Parses parameters for the shortcode
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 * @param (array) $atts - The attributes from the shortcode
		 * @return Markup by using $this->showForm()
		 * @example [CONTACT_FORM to="abc@xyz.com" subject="xyz"]
		 */
		
		function shortcode( $atts )
		{
			
			extract( shortcode_atts( array( 
				'to' => '',
				'subject' => ''
			 ), $atts ) );

			$this->userdata = array( 
				'to' => $to,
				'subject' => $subject
			 );

			return $this->showForm();

		}/* shortcode() */


		/* =============================================================================== */

		
		/**
		 * check input fields and validate
		 *
		 * @author Richard Tape
		 * @package FriendlyContactForm
		 * @since 1.0
		 * @param (string) $n - Input field data
		 * @return (string) message OK or No spam please!
		 */
		
		function checkInput( $n = '' )
		{
			// exit if no form data
			if( !isset( $_POST['tcf_sendit' . $n] ) )
				return false;
		
			// hidden field check
			if( ( isset( $_POST['tcf_sendit' . $n] ) && $_POST['tcf_sendit' . $n] != 1 )
				|| ( isset( $_POST['tcf_name' . $n] ) && $_POST['tcf_name' . $n] != '' ) )
			{
				return __( 'No Spam please!', THEMENAME );
			}
			
		
			$_POST['tcf_sender' . $n] = stripslashes( trim( $_POST['tcf_sender' . $n] ) );
			$_POST['tcf_email' . $n] = stripslashes( trim( $_POST['tcf_email' . $n] ) );
			$_POST['tcf_subject' . $n] = stripslashes( trim( $_POST['tcf_subject' . $n] ) );
			$_POST['tcf_msg' . $n] = stripslashes( trim( $_POST['tcf_msg' . $n] ) );
		
			$error = array();

			if( empty( $_POST['tcf_sender' . $n] ) )
				$error[] = __( 'Name', THEMENAME );
		    if( !is_email( $_POST['tcf_email' . $n] ) )
				$error[] = __( 'Email', THEMENAME );
		    if( empty( $_POST['tcf_subject' . $n] ) )
				$error[] = __( 'Subject', THEMENAME );
		    if( empty( $_POST['tcf_msg' . $n] ) )
				$error[] = __( 'Your Message', THEMENAME );
			if( !empty( $error ) )
				return __( 'Check these fields:', THEMENAME ) . ' ' . implode( ', ', $error );
			
			return __( 'OK', THEMENAME );

		}/* checkInput() */


	}/* class FriendlyContactForm */

	$tiny_contact_form = new FriendlyContactForm();

?>