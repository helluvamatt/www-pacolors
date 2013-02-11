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
	
	public function signup($password)
	{
		if (isset($password)) echo $this->user_model->add_user($password);
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
			$page_data['user'] = $this->user_mode->get_user($userid);
		}
		
		$this->title = "User Details";
		if (isset($page_data['user']))
		{
			$this->load->model('color_model');
			$this->title .= ' | ' . $page_data['user']->get_display_name();
			$list_data['color_list'] = $this->color_model->get_list_for_user($userid);
			$list_data['hide_user_col'] = true;
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