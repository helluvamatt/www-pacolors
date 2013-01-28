<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->title = "";
		$this->data['active'] = "user";
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
	
	}
	
}