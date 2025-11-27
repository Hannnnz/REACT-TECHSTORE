<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Controller: Auth
 * 
 * Automatically generated via CLI.
 */
class Auth extends Controller {
    public function __construct()
    {
        parent::__construct();
        if(segment(2) != 'logout') {
            $id = $this->lauth->get_user_id();
            if(logged_in() && $this->lauth->get_role($id) == "admin") {
                redirect();
            }
            else if(logged_in() && $this->lauth->get_role($id) == "user") {
                redirect('pos');
            }
        }
    }

    public function index() {
        $this->call->view('auth/login');
    }  

    public function login() {
        if($this->form_validation->submitted()) {
            $email = $this->io->post('email');
			$password = $this->io->post('password');
            $data = $this->lauth->login($email, $password);
            if(empty($data)) {
				$this->session->set_flashdata('alert', 'error');
                $this->session->set_flashdata('message', 'These credentials do not match our records. ');
			} else {
                $row = $this->db
						->table('users')				
    					->where('id', $data)
    					->limit(1)
    					->get();
                if($row['email_verified_at'] === NULL){
                    $this->session->set_flashdata('alert', 'error');
                    $this->session->set_flashdata('message', 'Please verify your email first. ');
                    $this->lauth->set_logged_out();
                    redirect('auth/login');
                }
                else if($row['role'] == "unverified"){
                    $this->session->set_flashdata('alert', 'info');
                    $this->session->set_flashdata('message', 'Account under review. Please wait for approval in your email. ');
                    $this->lauth->set_logged_out();
                    redirect('auth/login');
                }
                else if($row['role'] == "declined"){
                    $this->session->set_flashdata('alert', 'info');
                    $this->session->set_flashdata('message', 'Your request is declined by the admin. ');
                    redirect('auth/login');
                }
                else{
                    $this->lauth->set_logged_in($data);
                }
			}
            redirect('auth/login');
        } else {
            $this->call->view('auth/login');
        }
        
    }

    public function register() {

        if($this->form_validation->submitted()) {
            $username = $this->io->post('username');
            $email = $this->io->post('email');
			$email_token = bin2hex(random_bytes(50));
            $this->lauth->delete_unverified($email);
            $this->form_validation
                ->name('username')
                    ->required()
                    ->is_unique('users', 'username', $username, 'Username was already taken.')
                    ->min_length(5, 'Username name must not be less than 5 characters.')
                    ->max_length(20, 'Username name must not be more than 20 characters.')
                    ->alpha_numeric_dash('Special characters are not allowed in username.')
                ->name('password')
                    ->required()
                    //->min_length(8, 'Password must not be less than 8 characters.')
                ->name('password_confirmation')
                    ->required()
                    //->min_length(8, 'Password confirmation name must not be less than 8 characters.')
                    ->matches('password', 'Passwords did not match.')
                ->name('email')
                    ->required()
                    ->is_unique('users', 'email', $email, 'Email was already taken.');
                if($this->form_validation->run()) {
                    if($this->lauth->register($username, $email, $this->io->post('password'), $email_token)) {
                        $this->send_token_to_email($email, $email_token);
                        $this->session->set_flashdata('alert', 'success');
                        $this->session->set_flashdata('message', 'Account created! Please check your email to verify your account. ');
                        redirect('auth/login');
                    } else {
                        set_flash_alert('danger', config_item('SQLError'));
                        redirect('auth/register');
                    }
                }  else {
                    set_flash_alert('danger', $this->form_validation->errors()); 
                    redirect('auth/register');
                }
        } else {
            $this->call->view('auth/register');
        }
        
    }

    private function send_token_to_email($email, $token) {
		$template = file_get_contents(ROOT_DIR.PUBLIC_DIR.'/templates/email_verify.html');
		$search = array('{token}', '{base_url}');
		$replace = array($token, base_url());
		$template = str_replace($search, $replace, $template);

        //required files
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/Exception.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/SMTP.php';

        $email = $_POST['email'];

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
        $mail->setFrom("genshinpromise@gmail.com", "TechStore"); // Sender Email and name
        $mail->addAddress($email);     //Add a recipient email  
        $mail->addReplyTo("genshinpromise@gmail.com", "TechStore"); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Account Creation";   // email subject headings
        $mail->Body    = $template; //email message

        // Success sent message alert
        $mail->send();
	}

    public function verify_email() {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            set_flash_alert('error', 'Missing verification token. ');
            redirect('auth/login');
            return;
        }

        if (! $this->lauth->get_verify_account_token($token)) {
            set_flash_alert('error', 'Invalid verification token. ');
            redirect('auth/login');
            return;
        }

        if ($this->lauth->verify_account_now($token)) {
            set_flash_alert('success', 'Email verified. Please wait for admin approval in your email. ');
        } else {
            set_flash_alert('error', 'Unable to verify email. Please try again. ');
        }

        redirect('auth/login');
	}

    private function send_password_token_to_email($email, $token) {
		$template = file_get_contents(ROOT_DIR.PUBLIC_DIR.'/templates/reset_password_email.html');
		$search = array('{token}', '{base_url}');
		$replace = array($token, base_url());
		$template = str_replace($search, $replace, $template);

        //required files
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/Exception.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require ROOT_DIR.'vendor/phpmailer/phpmailer/src/SMTP.php';

        $email = $_POST['email'];

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
        $mail->setFrom("genshinpromise@gmail.com", "TechStore"); // Sender Email and name
        $mail->addAddress($email);     //Add a recipient email  
        $mail->addReplyTo("genshinpromise@gmail.com", "TechStore"); // reply to sender email

        //Content
        $mail->isHTML(true);               //Set email format to HTML
        $mail->Subject = "Reset Password";   // email subject headings
        $mail->Body    = $template; //email message

        // Success sent message alert
        $mail->send();
	}

	public function password_reset() {
		if($this->form_validation->submitted()) {
			$email = $this->io->post('email');
			$this->form_validation
				->name('email')->required()->valid_email();
			if($this->form_validation->run()) {
				if($token = $this->lauth->reset_password($email)) {
					$this->send_password_token_to_email($email, $token);
                    $this->session->set_flashdata(['alert' => 'is-valid']);
				} else {
					$this->session->set_flashdata(['alert' => 'is-invalid']);
				}
			} else {
				set_flash_alert('danger', $this->form_validation->errors());
			}
		}
		$this->call->view('auth/password_reset');
	}

    public function set_new_password() {
        if($this->form_validation->submitted()) {
            $token = $this->io->post('token');
			if(isset($token) && !empty($token)) {
				$password = $this->io->post('password');
				$this->form_validation
					->name('password')
						->required()
						->min_length(8, 'New password must be atleast 8 characters.')
					->name('re_password')
						->required()
						->min_length(8, 'Retype password must be atleast 8 characters.')
						->matches('password', 'Passwords did not matched.');
						if($this->form_validation->run()) {
							if($this->lauth->reset_password_now($token, $password)) {
								set_flash_alert('success', 'Password was successfully updated. ');
                                $token = "";
                                redirect('auth/login');
							} else {
								set_flash_alert('danger', config_item('SQLError'));
                                redirect('auth/set-new-password/?token='.$token);
							}
						} else {
							set_flash_alert('danger', $this->form_validation->errors());
                            redirect('auth/set-new-password/?token='.$token);
						}
			} else {
				set_flash_alert('danger', 'Reset token is missing.');
			}
        } else {
             $token = $_GET['token'] ?? '';
            if(! $this->lauth->get_reset_password_token($token) && (! empty($token) || ! isset($token))) {
                set_flash_alert('danger', 'Invalid password reset token.');
            }
            $this->call->view('auth/new_password');
        }
		
	}

    public function logout() {
        if($this->lauth->set_logged_out()) {
            redirect('auth/login');
        }
    }
}