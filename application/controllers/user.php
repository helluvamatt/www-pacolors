<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->title = "";
	}
	
	public function login()
	{
		$this->title = "Login";
		$this->load->library('form_validation');
		
		$redirect = $this->input->post('redirect');
		if ($redirect == 'user/login') $redirect = '';
		
		$this->form_validation->set_rules('username', 'Username', 'required|callback_verify_login[password]');
		$this->form_validation->set_rules('password', 'Password', 'required');
		if ($this->form_validation->run())
		{
			redirect(site_url($redirect));
		}
		
		$page_data['redirect'] = $redirect ? $redirect : '';
		$this->render_page('users/login', $page_data);
	}
	
	public function verify_login($username, $password_field)
	{
		$password = $this->input->post($password_field);
		if (($userid = $this->user_model->verify($username, $password)) > 0)
		{
			$this->session->set_userdata('user_id', $userid);
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('verify_login', "Invalid username or password.");
			return FALSE;
		}
	}
	
	public function logout()
	{
		// Destroy session / cookie
		$this->session->sess_destroy();
		
		// Redirect to home
		redirect(site_url());
	}
	
	public function signup()
	{
		$this->title = "Sign Up";
		$this->load->library('form_validation');
		$this->load->helper('form');
		$page_data = array();
		
		$this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password_1', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('password_2', 'Password Verification', 'required|matches[password_1]');
		if ($this->form_validation->run())
		{
			// Do signup
			$username = $this->input->post('username');
			$email = $this->input->post('email');
			$realname_first = $this->input->post('realname_first');
			$realname_last = $this->input->post('realname_last');
			$password = $this->input->post('password_1');
			$new_userid = $this->user_model->add_user($username, $email, $password, $realname_first, $realname_last);
			if ($new_userid > 0)
			{
				// Automatically log in
				$this->session->set_userdata('user_id', $new_userid);
			
				// Redirect to home page
				$this->set_flashdata_message('success', 'Sign Up Successful! Welcome!', '');
			}
			else
			{
				$page_data['error'] = "There was a problem during signup. Please try again.";
			}
			
		}
		
		// Load form
		$this->render_page('users/signup', $page_data);
	}
	
	public function colors($id = 0)
	{
		// Grab the user ID
		if ($id == 0)
		{
			$userid = $this->userid;
			$page_data['user'] = $this->user;
			$this->active = "user.colors";
		}
		else
		{
			$userid = $id;
			$page_data['user'] = $this->user_model->get_user($userid);
		}
		
		$this->title = "My Colors";
		if (isset($page_data['user']))
		{
			$this->load->model('color_model');
			$list_data['color_list'] = $this->color_model->get_list_for_user($this->userid, $userid);
			$list_data['user'] = $this->user;
			$page_data['colorsetting_list'] = $this->load_view('colors/table', $list_data);
			$this->render_page('users/colors', $page_data);
		}
		else
		{
			$page_data['error_title'] = "Invalid User";
			$page_data['error_message'] = "That user was not found in the database.";
			$this->render_page('error', $page_data);
		}
	}
	
	public function favorites($id = 0)
	{
		// Grab the user ID
		if ($id == 0)
		{
			$userid = $this->userid;
			$page_data['user'] = $this->user;
			$this->active = "user.favorites";
		}
		else
		{
			$userid = $id;
			$page_data['user'] = $this->user_model->get_user($userid);
		}
		
		$this->title = "My Favorites";
		if (isset($page_data['user']))
		{
			$this->load->model('color_model');
			$list_data['color_list'] = $this->color_model->get_list_favorites($userid);
			$list_data['user'] = $this->user;
			$page_data['colorsetting_list'] = $this->load_view('colors/table', $list_data);
			$this->render_page('users/colors', $page_data);
		}
		else
		{
			$page_data['error_title'] = "Invalid User";
			$page_data['error_message'] = "That user was not found in the database.";
			$this->render_page('error', $page_data);
		}

	}
	
}