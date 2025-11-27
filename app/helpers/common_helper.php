<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Helper: common_helper.php
 * 
 * Automatically generated via CLI.
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ( ! function_exists('xss_clean'))
{
	function xss_clean($string)
	{
		$LAVA =& lava_instance();
		$LAVA->call->library('antixss');
		return $LAVA->antixss->xss_clean($string);
	}
}

if ( ! function_exists('set_flash_alert'))
{
	function set_flash_alert($alert, $message) {
		$LAVA =& lava_instance();
		$LAVA->session->set_flashdata(array('alert' => $alert, 'message' => $message));
	}
}

if ( ! function_exists('flash_alert'))
{
	function flash_alert()
	{
		$LAVA =& lava_instance();
		if($LAVA->session->flashdata('alert') !== NULL) {
			echo '
	        <div class="alert alert-' . $LAVA->session->flashdata('alert') . '">
	            <i class="icon-remove close" data-dismiss="alert"></i>
	            ' . $LAVA->session->flashdata('message') . '
	        </div>';
		}
			
	}
}

if ( ! function_exists('toast_alert'))
{
	function toast_alert()
	{
		$LAVA =& lava_instance();

		$alert  = $LAVA->session->flashdata('alert');
		$msg    = $LAVA->session->flashdata('message');

		if ($alert !== NULL && $msg !== NULL) {
			echo "
			<script>
				document.addEventListener('DOMContentLoaded', function() {
					if (typeof showToast === 'function') {
						showToast(" . json_encode($msg) . ", " . json_encode($alert) . ");
					}
				});
			</script>
			";
		}
	}

}

if ( ! function_exists('logged_in'))
{
	//check if user is logged in
	function logged_in() {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		if($LAVA->lauth->is_logged_in())
			return true;
	}
}

if ( ! function_exists('get_user_id'))
{
	//get user id
	function get_user_id() {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		return $LAVA->lauth->get_user_id();
	}
}

if ( ! function_exists('get_username'))
{
	//get username
	function get_username($user_id) {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		return $LAVA->lauth->get_username($user_id);
	}
}

if ( ! function_exists('get_role'))
{
	//get role
	function get_role($user_id) {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		return $LAVA->lauth->get_role($user_id);
	}
}

if ( ! function_exists('get_email'))
{
	function get_email($user_id) {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		return $LAVA->lauth->get_email($user_id);
	}
}

if ( ! function_exists('get_email_verified'))
{
	function get_email_verified($user_id) {
		$LAVA =& lava_instance();
		$LAVA->call->library('lauth');
		return $LAVA->lauth->get_email_verified($user_id);
	}
}

if ( ! function_exists('email_exist'))
{
	function email_exist($email) {
		$LAVA =& lava_instance();
		$LAVA->db->table('users')->where('email', $email)->get();
		return ($LAVA->db->row_count() > 0) ? true : false;
	}
}

function SendMail($name, $email, $subject, $message, $receiver){
		//required files
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/Exception.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/SMTP.php';

        //Create an instance; passing true enables exceptions

        $mail = new PHPMailer(true);

        //Server settings
        $mail->isSMTP();                              //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
        $mail->SMTPAuth   = true;             //Enable SMTP authentication
        $mail->Username   = 'genshinpromise@gmail.com';   //SMTP write your email
        $mail->Password   = 'dvvigwjodyetiijm';      //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
        $mail->Port       = 465;                                    

        //Recipients
        $mail->setFrom($email, $name); // Sender Email and name
        $mail->addAddress($receiver);     //Add a recipient email  
        $mail->addReplyTo($email, $name); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = $subject;   // email subject headings
        $mail->Body    = $message; //email message
        //$mail->addAttachment(ROOT_DIR.'uploads/'.$attachment);

        // Success sent message alert
        $mail->send();
}