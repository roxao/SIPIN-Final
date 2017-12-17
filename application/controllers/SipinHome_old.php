<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SipinHome extends CI_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('session');
	   	$this->load->helper(array('captcha','url','form','download'));
		$this->load->model('user_model');
		$this->load->library('email','form_validation', 'curl');
		$this->model = $this->user_model;
        $this->load->database();
	}
 
	public function index(){		
		$this->load->view('header');
		$this->load->view('home');
		$this->load->view('footer');
	}


	// ALDY: FILE ISO 
	public function file_iso_7812(){		
		$data['file_iso'] = 'http://localhost:8090/BSN/assets/sample.pdf';
		$this->load->view('header');
		$this->load->view('iso7812', $data);
		$this->load->view('footer');
	}

	// ALDY: LOGIN USER
	public function user($param){
		
		$data['type']=$param;
	
		$message = $this->session->flashdata('validasi-login');
		$data['message']=$message;
		$this->load->view('login', $data);
	}

public function log($Type, $detil, $username){
		/*Insert Log*/
		$dataLog = array(
                'detail_log' => $username. $detil,
                'log_type' => $Type .$username, 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $username,
                'last_update_date' => date('Y-m-j H:i:s'),
                'modified_by' => date('Y-m-j H:i:s'),
                );
        $this->user_model->insert_log($dataLog);
	}

	/* User login function. */
	 public function login() {
     $username = $this->input->post('username');
     // $password = hash ( "sha256", $this->input->post('password'));

     $password =  $this->input->post('password');
     $cek = $this->user_model->cek_login($username, $password);
     if($cek->num_rows() > 0){
     if ($cek->row()->status_user == 0){ $this->session->set_flashdata('validasi-login', 'Anda belum melakukan Aktifasi silahkan lakukan aktifasi');
  $this->user('login');}
      else {$this->session->set_flashdata('validasi-login', 'Selamat Datang');
      $this->log("login","Login", $username);
      $id_user = $this->session->userdata('id_user');

      $cek_menu= $this->user_model->get_aplication($id_user);
      $this->index();

      $this->session->set_userdata(array(
	    'id_user'  => $cek->row()->id_user,
	    'username' => $cek->row()->username,
	    'email'  => $cek->row()->email,
	    'status_user'     => $cek->row()->status_user,
		));
	}
      }else{
      	$this->session->set_flashdata('validasi-login', 'Username/Password yang anda masukkan salah');
      $this->user('login');
  }
      }

      public function logout() {	
      	$username = $this->session->userdata('username');
      	$this->log("login","Login", $username);
		$this->session->sess_destroy();
		$data['logout'] = 'You have been logged out.';		
		$this->index();
		}

	/* register function. */
	public function register() {
		$regex = $this->regex($this->input->post('password'));
		if ($regex == "true"){
		$name = $this->input->post('fullname');
		$username = $this->input->post('username');
		$no_iin    = $this->input->post('iin-number');
		$email    = $this->input->post('email');
		$password = $this->input->post('password');
		$password_confirm = $this->input->post('retype-password');
		// $password = hash ( "sha256", $this->input->post('password'));
		
		if ($password == $password_confirm){
			$cek = $this->user_model->cek_status_user($username, $password);
	        if($cek->num_rows() > 0){
	        		$this->session->set_flashdata('validasi-login', 'Username/Email sudah terdaftar');
      $this->user('register');
	    	}else {
	    		if ($this->user_model->register_user($email ,$username, $password, $name)){
						if ($this->user_model->sendMail($email,$username, "Please click on the below activation link to verify your email address.")) {
				     
				       $this->session->set_flashdata('validasi-login', 'Anda berhasil melakukan registrasi, silahkan periksa pesan masuk email Anda untuk mengaktifkan akun yang baru Anda buat');
				       $this->log("login","Login", $username);
				         $this->user('register');
				      }else {echo  "Gagal";
				       $this->session->set_flashdata('validasi-login', 'Gagal melakukan registrasi');
				         $this->user('register'); }}}
		}else { 
	$this->session->set_flashdata('validasi-login', 'password yang anda masukkan tidak sesuai');
				         $this->user('register');}	
		} else {

	$this->session->set_flashdata('validasi-login', 'Password minimal 8 karakter dan harus huruf besar, huruf kecil, angka, dan special character (Contoh : aAz123@#');
				         $this->user('register');
				     }	
	}

	/*Forgot Password*/
	public function forgot_password() {
	$username_forgot = $this->input->post('E-mail');
	$cek = $this->user_model->forgot_password($username_forgot);
	if ($cek->num_rows() > 0){
	if ($this->user_model->sendMail($cek->row()->email, $cek->row()->name,"Please click on the below activation link to verify your email address.")) {
		$this->log("login","Login", $username_forgot );
		$this->session->set_flashdata('validasi-login', 'Berhasil melakukan reset password silahkan cek email anda');
	$this->user('forgot');
}else {
	$this->session->set_flashdata('validasi-login', 'Gagal melakukan reset password');
	$this->user('forgot');} 
	} else {$this->session->set_flashdata('validasi-login', 'Username/Email tidak ditemukan');
	$this->user('forgot'); } 
	}

	/*Verifikasi Email*/
	public function verify($hash=NULL) {
    if ($this->user_model->verifyEmail($hash)) {
      $this->session->set_flashdata(md5('sukses'), "Email sukses diverifikasi!");
      echo  "Email sukses diverifikasi!";} 
      else {$this->session->set_flashdata(md5('notification'), "Email gagal terverifikasi");
      echo  "Email gagal diverifikasi!"; // redirect('/url/register');
  		}
  	}
  	/*Regex falidasi karakter password*/
	public function regex($password){
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialcaracter    = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password);
	if(!$uppercase || !$lowercase || !$number || !$specialcaracter || strlen($password) <= 8) {
	 return false;
	} else {
		return true;
	}
	}


	public function modal_popup(){
		$this->load->view('component/modal_popup');
	}
 }
