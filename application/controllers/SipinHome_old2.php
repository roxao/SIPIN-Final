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
        $this->load->model('admin_model','adm_model');
	}
 
	public function index() {		
		$data['banner'] = $this->model->get_banner_active()->result_array();
		$this->set_template('home', $data);
	}


	public function set_template($view_name, $data = array()){
		$data['cms_name'] = $this->model->get_cms_by_name()->result_array();
		if(!isset($data['web_title'])) $data['web_title'] = 'Layanan Issuer Identification Number';
        $this->load->view('header', $data);
        $this->load->view($view_name, $data);
        $this->load->view('footer', $data);
        return;
    }

	public function captcha() {

		$this->load->helper('captcha');
 		
		$vals = array(
			//'word' => 'Random word',
			'img_path' => './captcha/',
			'img_url' => base_url().'captcha/',
			'img_width'	=> '200',
			'img_height' => 32,
			'border' => 0,
			'expiration' => 7200,
			'word_length' => 6,
			'font_size' => 20,
			//'img_id' => 'Imageid',
			//'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
			
			// White background and border, black text and red grid
			
			'colors' => array(
				'background' => array(255, 255, 255),
				'border' => array(255, 255, 255),
				'text' => array(0, 0, 0),
				'grid' => array(255, 200, 200)
			)
		);
		$cap = create_captcha($vals);
		$this->session->set_userdata('mycaptcha', $cap['word']);
		$this->session->set_userdata('myimgcaptcha', $cap['image']);
		 $data['image'] = $cap['image'];
		return $data;
	}

	public function date_time_now() {
		/*
		SET TIMEZONE ASIA/JAKARTA
		*/
	    $datetime = new DateTime('Asia/Jakarta');
	    return $datetime->format('Y\-m\-d\ H:i:s');
	}

	public function tanggal_indo($tanggal, $cetak_hari = false)
	{
		$hari = array ( 1 =>    'Senin',
					'Selasa',
					'Rabu',
					'Kamis',
					'Jumat',
					'Sabtu',
					'Minggu'
				);
				
		$bulan = array (1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
				);
		$split 	  = explode('-', $tanggal);
		$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
		
		if ($cetak_hari) {
			$num = date('N', strtotime($tanggal));
			return $hari[$num] . ', ' . $tgl_indo;
		}
		return $tgl_indo;
	}

	// ALDY: FILE ISO
	// public function iso_document(){ 
	public function file_iso_7812() {
		$data['web_title'] = 'Dokumen ISO 7812 :: Layanan Issuer Identification Number';
		$data['file_iso'] = $this->user_model->get_file_iso()->result();
		$this->set_template('iso-document-view', $data);
	}

	// ALDY: LOGIN USER
	public function user($param) {
		$this->captcha();
		$data['type']=$param;
		$message = $this->session->flashdata('validasi-login');
		$data['message']=$message;
		$this->load->view('login', $data);
	}


	/*
	Insert Log Function
	@array dataLog
	@var detail_log
	@var log_type
	@var created_date
	@var created_by
	*/
	public function log($Type, $detil, $username) {
		$dataLog = array(
                'detail_log' => $username. $detil,
                'log_type' => $Type .$username, 
                'created_date' => $this->date_time_now(),
                'created_by' => $username
            );
        $this->user_model->insert_log($dataLog);
	}


    public function logout() {	
      	$username = $this->session->userdata('username');
      	if ($username != '') {
      		$this->log("logout","logout", $username);
      	}
		$this->session->sess_destroy($_SESSION=[]);	
		redirect(base_url());
	}

	/* 
	Register function. 
	@function captcha()
	@var name
	@var username
	@var no_iin
	@var email
	@var password
	@var password_confirm
	
	*/
	public function register() {
		/*
		Password Validation
		*/
		$regex = $this->regex($this->input->post('password'));
		if ($regex == "true"){

			$name = $this->input->post('fullname');
			$username = $this->input->post('username');
			$no_iin    = $this->input->post('iin-number');
			$email    = $this->input->post('email');
			$password = hash ( "sha256", $this->input->post('password'));
			$password_confirm = hash ( "sha256", $this->input->post('retype-password'));
			
			/*
			Captcha Validation
			*/
			if (($this->input->post('security_code') == $this->session->userdata('mycaptcha'))){
				if ($password == $password_confirm){

					/*
					User Status Validation
					*/		
					$cek = $this->user_model->cek_status_user($username);
			        if($cek->num_rows() > 0){
		        		$this->session->set_flashdata('validasi-login', 'Username/Email sudah terdaftar');
		  				$this->user('register');
			    	} else {

			    		if ($no_iin != "" ) {
			    			$get_passw = $this->model->get_user_password($no_iin);
				    		if ($get_passw->row()->iin_number == $no_iin) {
				    			$this->user_model->update_user_has_iin($email ,$username, $password, $name, $get_passw->row()->id_user);
						    } else {
						    	$this->user_model->register_user($email ,$username, $password, $name);
						    }
			    		} else {
					    	$this->user_model->register_user($email ,$username, $password, $name);
					    }


					    if ($this->user_model->sendMail($email,$username, "Please click on the below activation link to verify your email address.")) {
					
							$this->session->set_flashdata('validasi-login', 'Anda berhasil melakukan registrasi, silahkan periksa pesan masuk email Anda, untuk mengaktifkan akun yang telah Anda buat');
							$this->log("login","Login", $username);

					    } else {
							$this->session->set_flashdata('validasi-login', 'Gagal melakukan registrasi');
						}

					}

					/*
					User Status Validation
					*/		
					// $cek = $this->user_model->cek_status_user($username);
			  //       if($cek->num_rows() > 0){
		   //      		$this->session->set_flashdata('validasi-login', 'Username/Email sudah terdaftar');
		  	// 			$this->user('register');
			  //   	} else {
			   //  		if ($this->user_model->register_user($email ,$username, $password, $name)){
						// 	if ($this->user_model->sendMail($email,$username, "Please click on the below activation link to verify your email address.")) {
					
						// 		$this->session->set_flashdata('validasi-login', 'Anda berhasil melakukan registrasi, silahkan periksa pesan masuk email Anda, untuk mengaktifkan akun yang telah Anda buat');
						// 		$this->log("login","Login", $username);

						//     } else {
						// 		$this->session->set_flashdata('validasi-login', 'Gagal melakukan registrasi');
						// 	}
						// }
					// }


				} else { 
					$this->captcha();
					$this->session->set_flashdata('validasi-login', 'password yang anda masukkan tidak sesuai');
				}	
			} else {
				$this->captcha();
				$this->session->set_flashdata('validasi-login', 'Captcha tidak sesuai');
			}	
		} else {
			$this->captcha();
			$this->session->set_flashdata('validasi-login', 'Password minimal 8 karakter dan harus huruf besar, huruf kecil, angka, dan special character (Contoh : aAz123@#');
		}
		redirect(base_url('registrasi'));
	}

	/*
	Forgot Password
	@var username_forgot
	@cek username_forgot
	@model user_model
	*/
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
			$this->user('forgot'); 
		} 
	}


	/*
	Verifying User Activation Link
	@var link
	@array link_array 
	@var enc
	@model user_model
		@function verifyEmail
	*/
  	public function verify() {
		$link = $_SERVER['REQUEST_URI'];
    	$link_array = explode('/',$link);
    	$enc = end($link_array);

    	/*Calling user_model->verifyEmail to verify activation link*/
    	$this->user_model->verifyEmail($enc);

        /*Get Registration Message on Current Session*/
	 	echo $this->session->flashdata('regis_msg');
		redirect(base_url());
  	}


  	/*Regex validasi karakter password*/
	public function regex($password){
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);
		$specialcaracter    = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password);
		if(!$uppercase || !$lowercase || !$number || !$specialcaracter || strlen($password) < 8) {
			return false;
		} else {
			return true;
		}
	}


	/*
	Login Function
	@var username
	@var password
	*/
    public function login() {

	    $username = $this->input->post('username');
	    $password = hash ( "sha256", $this->input->post('password'));

	    /*
	    Validate User Login
	    */

	    #this is the default
	    #$cek = $this->user_model->cek_login($username, $password);

	  //   if (!is_null($cek->row()->status_user)) {
			//     if ($cek->row()->status_user == 0){ 
			//      	$this->session->set_flashdata('validasi-login', 'Anda belum melakukan Aktivasi silahkan lakukan aktivasi');
			// 		$this->user('login');
			// 	} else {

			// 		/*
			// 		Already have IIN
			// 		*/
			// 		if (empty($cek->row()->iin_number)) {
			// 			// echo "|Do not have IIN|";
			// 			$have_iin = "N";
			// 		} else {
			// 			// echo "|Already have IIN|";
			// 			$have_iin = "Y";
			// 		}
			// 		$this->session->set_userdata('have_iin');

			// 		//date_default_timezone_get()
			// 		$this->session->set_flashdata('validasi-login', 'Selamat Datang');
			// 		$this->log("login","Login", $username);
			// 		$id_user = $this->session->userdata('id_user');


			// 		$this->session->set_userdata(array(
			// 			'id_user'  		=> $cek->row()->id_user,
			// 			'username' 		=> $cek->row()->username,
			// 			'email' 		=> $cek->row()->email,
			// 			'status_user'   => $cek->row()->status_user,
			// 			'survey_status'   => $cek->row()->survey_status,
			// 			'iin_status'   => $cek->row()->iin_status,
			// 			'status' => "login",
			// 			'have_iin' => $have_iin
			// 		));

			// 		/*
			// 		Any Open Application?
			// 		*/
			// 		if ($cek->row()->iin_status == 'OPEN') {
			// 			// echo "|Active application|";

			// 			/*
			// 			Application Type?
			// 			*/
			// 			$this->session->set_userdata('application_type',$cek->row()->application_type);

			// 			if ($cek->row()->application_type == 'new') {
			// 				redirect(base_url("Layanan-IIN/{$url}"));

			// 			} else {
			// 				redirect(base_url("Layanan-IIN", $base_url));
			// 			}

			// 		} else {
			// 			// echo "|NO active application|";
			// 			redirect(base_url());
			// 		}

			// 	}
				
			// } else {
			//     $this->session->set_flashdata('validasi-login', 'Username/Password yang anda masukkan salah');
			//     // $this->user('login');
			//     redirect(base_url("user/login"));
			// }

	    #get password to validate username/email/iin
	    $get_passw = $this->model->get_user_password($username);

	    #get login data 
	    $cek = $this->model->get_login_data($get_passw->row()->id_user);
echo "status = ".$cek->row()->status_user;
return false;
	    #if user login using old IIN, and havn't registered yet
	    if (is_null($get_passw->row()->password)) {
	    	redirect(base_url('registrasi'));
	    } else {

	    	#password don't match with database
		    if ($get_passw->row()->password != $password) {
		    	$this->session->set_flashdata('validasi-login', 'Username/Password yang anda masukkan salah');
				    redirect(base_url("login"));
		    } else {

	    		#login success
		    	if ($cek->row()->status_user == 0){ 
			     	$this->session->set_flashdata('validasi-login', 'Anda belum melakukan Aktivasi silahkan lakukan aktivasi');
					$this->user('login');
				} else {

					/*
					Already have IIN
					*/
					if (empty($cek->row()->iin_number)) {
						// echo "|Do not have IIN|";
						$have_iin = "N";
					} else {
						// echo "|Already have IIN|";
						$have_iin = "Y";
					}
					$this->session->set_userdata('have_iin');

					//date_default_timezone_get()
					$this->session->set_flashdata('validasi-login', 'Selamat Datang');
					$this->log("login","Login", $username);
					$id_user = $this->session->userdata('id_user');


					$this->session->set_userdata(array(
						'id_user'  		=> $cek->row()->id_user,
						'username' 		=> $cek->row()->username,
						'email' 		=> $cek->row()->email,
						'status_user'   => $cek->row()->status_user,
						'survey_status'   => $cek->row()->survey_status,
						'iin_status'   => $cek->row()->iin_status,
						'status' => "login",
						'have_iin' => $have_iin
					));

					/*
					Any Open Application?
					*/
					if ($cek->row()->iin_status == 'OPEN') {
						// echo "|Active application|";

						/*
						Application Type?
						*/
						$this->session->set_userdata('application_type',$cek->row()->application_type);

						if ($cek->row()->application_type == 'new') {
							redirect(base_url("Layanan-IIN/{$url}"));

						} else {
							redirect(base_url("Layanan-IIN", $base_url));
						}

					} else {
						// echo "|NO active application|";
						redirect(base_url());
					}

				}


		    }

	    }




    }

	/*
	Render submit-iin view
	@var id_user
	@var get_app_status
	@var iin_status
	@var id_application_status_name
	@var process_status
	*/
	public function submit_application() {
		$this->captcha();
		$userIdReq = $this->input->get('userIdSelected', TRUE);

		if(null == $userIdReq || "" == $userIdReq){
			$id_user = $this->session->userdata('id_user');
		} else {
			$id_user=$userIdReq;
		}

		/*
		Get Application Status last step 15
		*/


		$get_app_status =  $this->user_model->get_applications_Status($id_user);

		/*
		Validate If row Exist 
		*/
		$iin_status ="";

		// if ( !is_null($get_app_status->row()->id_application) ) {
		if ( sizeof($get_app_status->result()) != '0' ) {
			$iin_status = $get_app_status->row()->iin_status;
			$id_application = $get_app_status->row()->id_application;
			$id_application_status = $get_app_status->row()->id_application_status;
			$id_application_status_name = $get_app_status->row()->id_application_status_name;
			$process_status = $get_app_status->row()->process_status;
			$application_type = $get_app_status->row()->application_type;
			// echo "|get_app_status : ";
			// print_r($get_app_status);

			$this->session->set_userdata('id_application',$id_application);
			$this->session->set_userdata('id_application_status',$id_application_status);
			$this->session->set_userdata('id_application_status_name',$id_application_status_name);
			$this->session->set_userdata('application_type',$application_type);

			// echo "|APP TYPE : {$application_type}";
			/*
			Instantiate arr $data
			*/
			$data = array(
				'id_application_status_name' => $id_application_status_name,
			);
		}
				

		/*
		Default Var
		@page
		*/
		$page = '0';

		/*
		Instantiate arr $data
		*/
		$data = array(
			'title' => '',
			'text' => '',
			'reject_msg' => '',
			'adm_pay_msg' => '',
			'state0' => '0',
			'state2' => '',
			'state3' => '',
			'state4' => '',
			'state5' => '',
			'state6' => '',
			'state8' => '',
			'state7' => ''
		);

		$have_iin = $this->session->userdata('have_iin');

		$data['app_type'] = APPTYPENEW;	
		$data['title_iin0'] = "Pengajuan Surat Permohonan IIN Baru";
		/*
		if iin_status = 'CLOSED'
		@THIS IS AN ACTIVE APPLICATION (New Application)
		*/
		if ( $iin_status == 'CLOSED' ) {
			if ( $have_iin=='Y' ) {
				$data['app_type'] = APPTYPEEXT;
				$data['title_iin0'] = "Pengajuan Surat Pengawasan IIN Lama";

				$input_field = $this->user_model->step_0_get_application_extend($id_user);
				$data['applicant'] = $input_field->row()->applicant;
				$data['applicant_phone_number'] = $input_field->row()->applicant_phone_number;
				$data['application_date'] = $input_field->row()->application_date;
				$data['application_purpose'] = $input_field->row()->application_purpose;
				$data['instance_name'] = $input_field->row()->instance_name;
				$data['instance_email'] = $input_field->row()->instance_email;
				$data['instance_phone'] = $input_field->row()->instance_phone;
				$data['instance_director'] = $input_field->row()->instance_director;
				$data['mailing_location'] = $input_field->row()->mailing_location;
				$data['mailing_number'] = $input_field->row()->mailing_number;

			}
		}
				        		
		/*
		if iin_status = 'OPEN'
		@THIS IS AN ACTIVE APPLICATION (New Application , type : new/extend)
		*/
		if ( $iin_status == 'OPEN' ) {



			if ( $id_application_status_name >= '1' ) {
				
				$data['step1_next'] = "";
				$data['btn_step0'] = "";
				/*
				Validate StepId (step0)
				*/
				if ( $id_application_status_name == '1' and $process_status == 'PENDING' ) {
					$data['state0'] = STATEPROCESS;
					$data['title'] = "Menunggu Hasil Verifikasi Status Permohonan";
					$data['text'] = "Dokumen yang anda unggah sudah <b>BERHASIL</b> masuk ke dalam database <b>SIPIN</b>. Silakan menunggu hasil verifikasi dan validasi pengajuan surat permohonan anda.";	
				} elseif ( $id_application_status_name == '1' and $process_status == 'REJECTED' ) {
					$reject_msg = "";

	        		$type = "REJECTED";
	        		$val = $this->user_model->get_form_mapping_by_type($id_application_status, $type);
	        		foreach ($val as $index => $valIndex) {
						foreach ($valIndex as $key => $val) {
							if ($key == 'value') {
					       		$reject_msg = "Keterangan : {$val}";
							}
						}
					}

					$data['reject_msg'] = $reject_msg;

					$data['state0'] = "rejected";
					$data['title'] = "Hasil Verifikasi Status Permohonan";
					$data['text'] = "Mohon Maaf Status Permohonan IIN anda telah di verifikasi dan telah ditolak. Silakan klik tombol di bawah ini untuk mengakhiri proses permohonan IIN baru.";
				} else {
					// $data['app_type'] = "new";
					$data['btn_step0'] = "hide";
					$data['step1_download'] = $this->user_model->get_doc_statis($id_user);
					$data['state0'] = "0";
					$page = '1';
					$input_field = $this->user_model->step_0_get_application($id_user);
					$data['applicant'] = $input_field->row()->applicant;
					$data['applicant_phone_number'] = $input_field->row()->applicant_phone_number;
					$data['application_date'] = $input_field->row()->application_date;
					$data['application_purpose'] = $input_field->row()->application_purpose;
					$data['instance_name'] = $input_field->row()->instance_name;
					$data['instance_email'] = $input_field->row()->instance_email;
					$data['instance_phone'] = $input_field->row()->instance_phone;
					$data['instance_director'] = $input_field->row()->instance_director;
					$data['mailing_location'] = $input_field->row()->mailing_location;
					$data['mailing_number'] = $input_field->row()->mailing_number;
				}

				if ( $id_application_status_name >= '2' ) {
					$page = '2';
					$data['state2'] = "2";
					$data['upload_status'] = '';
	        		$data['title_iin'] = "Submit Kelengkapan Dokumen Permohonan IIN";
	        		$data['text_iin'] = "Silakan mengunggah dokumen-dokumen yang sudah dilengkapi dan dipersiapkan ke dalam berdasarkan urutan di bawah ini.";

					
					switch (  $id_application_status_name ) {
						
					    case '2':
							/*
							Default List of Files
							*/
							switch ($process_status) {
								case 'COMPLETED':
									# code...
									$data['step2_upload']	= $this->user_model->get_doc_user_upload('','','');
									break;

								case 'PENDING':
									$data['step1_next'] = "";
									$page = '1';
									$data['state2'] = "";
									break;
							}


							
					        break;

					    case '3':

					        switch ( $process_status ) {
					        	
					        	case 'PENDING':
									// $page = '2';
					        		$data['state2'] = "process";
					        		$data['title'] = "Submit Kelengkapan Dokumen Permohonan IIN";
									$data['text'] = "Kelengkapan Dokumen anda telah masuk ke database sistem SIPIN, mohon menunggu verifikasi admin";
					        		break;
					        }

					        break;

					    case '4':

					         switch ( $process_status ) {
					        	
					        	case 'PENDING':
					        		# code...
					        		$data['title_iin'] = "Revisi Kelengkapan Dokumen Permohonan IIN";
					        		$data['text_iin'] = "Silakan mengunggah dokumen-dokumen yang sudah di revisi dan dipersiapkan ke dalam berdasarkan urutan di bawah ini.";

					        		/*
									Get List of Key 
									@From application_status_form_mapping Table
					        		*/
					        		$type = "REVISED_DOC";
					        		$val = $this->user_model->get_form_mapping_by_type($id_application_status, $type);
					        		
					        		/*
									Instantiate Array $key
					        		*/
					        		$keys = array();
					        		$list_id_form_mapping = array();
									foreach ($val as $index => $valIndex) {

										foreach ($valIndex as $key => $val) {

											if ($key == 'value') {
									       		array_push($keys, $val);
											}
											if ($key == 'id_application_status_form_mapping') {
									       		array_push($list_id_form_mapping, $val);
											}
										}
									}

					        		$data['step2_upload']	= $this->user_model->get_rev_doc_user_upload($keys);
					        		$this->session->set_userdata('step2_upload', $data['step2_upload'] );
					        		$this->session->set_userdata('list_id_form_mapping', $list_id_form_mapping );

					        		break;
					        }

					        break;

					    case '5':

					         switch ( $process_status ) {
					        	case 'PENDING':
					        		# code...
									// $page = '2';
					        		$data['state2'] = "process";
					        		$data['title'] = "Proses Verifikasi dan Validasi";
									$data['text'] = "Kelengkapan Dokumen anda telah masuk ke database sistem SIPIN, mohon menunggu verifikasi admin";
					        		break;
					        }

					        break;

					    case '6':

					        switch ( $process_status ) {
					        	case 'PENDING':

			        				$page = '3';

			        				$data['upload_status'] = 'success';
					        		$data['title_iin'] = "Kelengkapan Dokumen Permohonan IIN Anda";
					        		$data['text_iin'] = "Dokumen-dokumen di bawah ini telah diverifikasi. Berikut ini daftar dokumen terkait penerbitan IIN Anda :";
									/*
									Only Files that already uploaded By User
									*/

									$data['step2_upload']	= $this->user_model->get_doc_user_upload('',$id_application,'');
									$this->session->set_userdata('step2_upload', $data['step2_upload'] );

									$data['state3'] = "process";
					        		$data['title'] = "Proses Verifikasi dan Validasi";
									$data['text'] = "Berdasarkan permohonan yang telah anda ajukan, saat ini permohonan IIN anda sudah memasuki tahapan Verifikasi dan Validasi. Pada tahapan ini membutuhkan waktu kurang lebih selama 3 hari.";


					        		break;

							}

				       		break;

					   
					    default:
			        		# code...
			        		break;
			        }

			        if ( $id_application_status_name >= '7' ) {

			        	$page = '4';
	        			$data['state3'] = "3";
						$data['upload_status'] = 'success';
						$data['upload_status2'] = 'success';
						$data['upload_status3'] = '';
		        		$data['title_iin'] = "Kelengkapan Dokumen Permohonan IIN Anda";
		        		$data['text_iin'] = "Dokumen-dokumen di bawah ini telah diverifikasi. Berikut ini daftar dokumen terkait penerbitan IIN Anda :";


						$prev_id_app_status_name = "6";
		        		$query = $this->user_model->id_application_status_step_n($id_application, $prev_id_app_status_name);
						        		
		        		$id_application_status_step4 = "";
		        		foreach ($query as $key => $value) {
		        			# code...
		        			$id_application_status_step4 = $value->id_application_status;
		        		}

						$type = "";
		        		$val = $this->user_model->get_form_mapping_by_type($id_application_status_step4, $type);


		        		$bill_code = "";
		        		$bill_date = "";
		        		$id_keys = array();
						foreach ($val as $index => $valIndex) {

							switch($valIndex->type){
								case "BILLING_CODE":
									 $bill_code = $valIndex->value;
									 break;
								case "BILLING_DATE":
									 $bill_date = $valIndex->value;
									 break;	 

								case "BILLING_DOC":
									 array_push($id_keys, $valIndex->value);
									 break;	 

							}

						}



		        		$data['bill_code'] = $bill_code;
		        		$data['bill_date'] = $bill_date;
						$data['bill_doc']	= $this->user_model->get_doc_user_upload('',$id_application,$id_keys);
						/*
						Only Files that already uploaded By User
						*/

						$data['step2_upload']	= $this->user_model->get_doc_user_upload('',$id_application,'');
						$this->session->set_userdata('step2_upload', $data['step2_upload'] );

			        	switch (  $id_application_status_name ) { 

					       	case '7':
					       		$page = '4';
								$data['upload_status2'] = '';
				        		$data['state4'] = "4";

								switch ( $process_status ) {
						        	case 'COMPLETED':
				       					$page = '5';
				        				$data['state5'] = "5";
						        		$data['title_iin2'] = "Submit Bukti Transfer Pembayaran";
										$data['text_iin2'] = "Silakan mengunggah bukti transfer yang telah anda lakukan melalui SIMPONI :";
						        		break;
								}


				        		break;

				        	case '8':
					       		$page = '5';


				        		break;

				        	case '9':
					       		$page = '5';

				        		$data['state5'] = "process";
				        		$data['title'] = "Submit Bukti Transfer Pembayaran";
								$data['text'] = "Terima kasih atas pembayaran yang sudah Anda lakukan melalui SIMPONI. Silakan menunggu maksimal 6 hari kerja setelah pembayaran untuk melakukan persetujuan verifikasi lapangan oleh Sekretariat Layanan.";
								


				        		break;

				        	case '10':
					       		$page = '5';

				        		$data['state5'] = "5";
				        		$data['title_iin2'] = "[Revisi] Submit Bukti Transfer Pembayaran";
								$data['text_iin2'] = "Silakan mengunggah bukti transfer yang telah anda lakukan melalui SIMPONI :";
								
								switch ( $process_status ) {
						        	case 'PENDING':

						        		$adm_pay_msg = "";

						        		$type = "REVISED_PAY";
						        		$val = $this->user_model->get_form_mapping_by_type($id_application_status, $type);
						        		foreach ($val as $index => $valIndex) {
											foreach ($valIndex as $key => $val) {
												if ($key == 'value') {
										       		$adm_pay_msg = "Keterangan : {$val}";
												}
											}
										}

										$data['adm_pay_msg'] = $adm_pay_msg;

						        		break;
								}


				        		break;

				        	case '11':
					       		$page = '5';

				        		$data['state5'] = "process";
				        		$data['title'] = "[Revisi] Submit Bukti Transfer Pembayaran";
											$data['text'] = "Terima kasih atas pembayaran yang sudah Anda lakukan melalui SIMPONI. Silakan menunggu maksimal 6 hari kerja setelah pembayaran untuk melakukan persetujuan verifikasi lapangan oleh Sekretariat Layanan.";
				        		break;

			        	}


			        	if ( $id_application_status_name >= '12' ) {

			        		$page = '6';
			        		$data['state6'] = "6";
							$data['upload_status3'] = 'success';
			        		
			        		$data['state5'] = "5";
			        		$data['title_iin2'] = "Bukti Transfer Pembayaran";
							$data['text_iin2'] = "Terima kasih atas pembayaran yang sudah Anda lakukan melalui SIMPONI. Bukti pembayaran anda telah sukses diverifikasi dan divalidasi.";

			        		switch (  $id_application_status_name ) { 

					       		case '12':
					       			switch ( $process_status ) {
					       				case 'PENDING':
					       					/*
											RENDER PAGE 6 TEAM
					       					*/
											$id_arr = $this->user_model->get_id_assessment_application($this->session->userdata('id_user'));

											$id_assessment_application = "";
											foreach ($id_arr as $val) {
												$id_assessment_application = $val->id_assessment_application;
											}

											$data['step6_listTeam']  = $this->user_model->get_assesment_team($id_assessment_application);
											
											/*
											RENDER PAGE 6 DATE AND DOCUMENTS
					       					*/
							        		$val = $this->user_model->get_form_mapping_by_type($id_application_status, '');
							        		$team_date = "";
							        		$verif_date = "";
							        		$id_keys = array();
											foreach ($val as $index => $valIndex) {

												switch($valIndex->type){
													case "ASSESSMENT_DATE":
														// $team_date = $valIndex->value;
														$team_date = new DateTime($valIndex->value);
														$team_date = date_format($team_date, 'Y-m-d');
														$team_date = $this->tanggal_indo($team_date, true);


														$verif_date = new DateTime($valIndex->value);
														$verif_date = date_format($verif_date, 'Y-m-d');
														$verif_date = $this->tanggal_indo($verif_date);
														 break;

													case "ASSESSMENT_DOC":
														array_push($id_keys, $valIndex->value);
														break;	 

												}

											}

							        		$data['team_date'] = $team_date;

							        		$data['verif_date'] = $verif_date;

											$data['team_doc']	= $this->user_model->get_assessment_team_doc($id_application_status,$id_keys);
											

								}	
				       				break;

				       			case '13':
					       			switch ( $process_status ) {
					       				case 'PENDING':

			        						$data['state6'] = "process";
							        		$data['title'] = "Tim Verifikasi Lapangan";
											$data['text'] = "Permohonan pemindahan jadwal Assessment Lapangan yang telah Anda ajukan sedang dalam proses. Mohon menunggu proses verifikasi dari Sekretariat Layanan IIN.";
							        		break;

						       			case 'COMPLETED':
							        		break;
					       			}
								
				       				break;

				       			case '14':
					       			switch ( $process_status ) {
					       				case 'PENDING':
		        						$data['state6'] = "process";
						        		$data['title'] = "Konfirmasi Tim Verifikasi Lapangan";
										$data['text'] = "Persetujuan  jadwal Assessment Lapangan yang telah Anda ajukan sedang dalam proses. Mohon menunggu konfirmasi dari Sekretariat Layanan IIN.";
							        		break;

					       			}
								
				       				break;
					       	}


					       	if ( $id_application_status_name >= '15' ) {
								/*
								RENDER STEP 6
								*/
								$id_assessment_application = "";
								$id_arr = $this->user_model->get_id_assessment_application($this->session->userdata('id_user'));

								foreach ($id_arr as $val) {
									$id_assessment_application = $val->id_assessment_application;
								}
								/*
								RENDER STEP 6 Team
								*/

								$data['step6_listTeam']  = $this->user_model->get_assesment_team($id_assessment_application);
								/*
								RENDER PAGE 6 DATE AND DOCUMENTS
		       					*/

								$prev_id_app_status_name = "12";
								$prev_id_app_status = "";
				        		$prev_id_app_status_arr = $this->user_model->id_application_status_step_n($id_application, $prev_id_app_status_name);

				        		foreach ($prev_id_app_status_arr as $value) {
				        			$prev_id_app_status = $value->id_application_status;
				        		}

				        		$val = $this->user_model->get_form_mapping_by_type($prev_id_app_status, '');

				        		$team_date = "";
				        		$verif_date = "";
				        		$id_keys = array();
								foreach ($val as $index => $valIndex) {

									switch($valIndex->type){
										case "ASSESSMENT_DATE":
											$team_date = new DateTime($valIndex->value);
											$team_date = date_format($team_date, 'Y-m-d');
											$team_date = $this->tanggal_indo($team_date, true);


											$verif_date = new DateTime($valIndex->value);
											$verif_date = date_format($verif_date, 'Y-m-d');
											$verif_date = $this->tanggal_indo($verif_date);
											 break;

										case "ASSESSMENT_DOC":
											array_push($id_keys, $valIndex->value);
											break;	 

									}

								}

				        		$data['team_date'] = $team_date;

				        		$data['verif_date'] = $verif_date;

								$data['team_doc']	= $this->user_model->get_assessment_team_doc($id_application_status,$id_keys);





		       					/*
								RENDER STEP 7
		       					*/
								$app_st7 = $id_application_status;
								if ( $id_application_status_name != '15' ) {
									$prev_id_app_status_name = "15";

									$app_st7 = "";
					        		$prev_id_app_status_arr = $this->user_model->id_application_status_step_n($id_application, $prev_id_app_status_name);


					        		foreach ($prev_id_app_status_arr as $value) {
					        			# code...
					        			$app_st7 = $value->id_application_status;
					        		}

								} 


								$val = $this->user_model->get_form_mapping_by_type($app_st7, '');
		       					$id_keys = array();
								foreach ($val as $index => $valIndex) {

									switch($valIndex->type){
										case "ASSESSMENT_DOC":
											array_push($id_keys, $valIndex->value);
											break;	 

									}

								}
								

		       					$data['assess_lap']	= $this->user_model->get_assessment_team_doc($app_st7,$id_keys);



								$page = '7';
								$data['upload_status4'] = 'success';
								$data['upload_status5'] = '';
								$data['state7'] = "7";

								switch ( $id_application_status_name ) {
									
					       			case '16':
						       			switch ( $process_status ) {
						       				case 'PENDING':
												$data['state7'] = "assessment_rev";
												$data['upload_status6'] = '';

												/*
												RENDER STEP 7 REVISION
												*/
								        		$id_document_config = array();
												foreach ($val as $index => $valIndex) {


													if ( $valIndex->type == 'REV_DOC_ASM' ) {
														array_push($id_document_config, $valIndex->value);
													}

												}

								        		/*STEP 7*/
												$data['assessment_rev_doc']	= $this->user_model->get_assessment_rev_list($id_document_config);
												
								        		break;
						       			}
									
					       				break;


					       			case '17':
						       			switch ( $process_status ) {
						       				case 'PENDING':
												$data['state7'] = "process";
								        		$data['title'] = "[Revisi] Assessment Lapangan";
												$data['text'] = "Revisi dokumen terkait dengan hasil  Assessment Lapangan yang Anda ajukan sedang memasuki tahapan verifikasi dan validasi. Mohon menunggu paling lambat .... hari kerja.";

								        		break;
						       			}
									
					       				break;		

								}

								

								if ( $id_application_status_name >= '18' ) {
									$page = '8';	
									$data['state8'] = "8";
									if ( $id_application_status_name == '18' and $process_status = 'PENDING' ) {
										

									}	
									switch ( $process_status ) {
					       				case 'PENDING':
					       					$data['state8'] = "process";
							        		$data['title'] = "Proses Permohonan ke CRA";
											$data['text'] = "Berdasarkan permohonan yang telah anda ajukan, saat ini permohonan IIN anda sudah memasuki tahapan permohonan ke CRA. Silakan tunggu selama kurang lebih 3 hari untuk proses pada tahapan ini.";
							        		break;
					       			}


									if ( $id_application_status_name == '19' ) {
										
										switch ( $process_status ) {
							       			case 'COMPLETED':
								        	$id_keys = array('IIN');

											$data['iin_download']	= $this->user_model->get_assessment_team_doc($id_application_status,$id_keys);
											// echo "IIN DOWNLOAD :".json_encode($data['iin_download']);
						       				$page = '9';
								  	      		break;
					       				}
									}
								}
			       			}
					    
					    }
			        }

			    }

			}

		} 
				
		/*
		Define BOX status Value
		@ Completed or Pending
		*/
		for ($i = 0; $i <= 9; $i++) {
			
			$string_status = "box_status_";
			$string_status .= $i;

			if ($i == $page) {
				$data[$string_status] = "PENDING";
				// if ($page == '9') $data[$string_status] = "COMPLETED";
			} else if ($i < $page){
				$data[$string_status] = "COMPLETED";
			} else {
				$data[$string_status] = "";
			}
		}

		/*
		Define value of which view to load
		*/
		$data['page'] = $page;

		/*
		Passing $data from Controller to View
		*/
		$data['web_title'] = 'Layanan IIN :: Layanan Issuer Identification Number';
		$this->set_template('submit-iin', $data);

	}

	public function modal_popup(){
		$this->load->view('component/modal_popup');
	}

	public function contact_us(){	
		$data['web_title'] = 'Hubungi Kami :: Layanan Issuer Identification Number';
		$this->set_template('contact-us', $data = null);
	}

	public function send_complaint(){	
		$cek = $this->user_model->get_user_by_prm($this->session->userdata('id_user'))->result_array();
		$data = array(
                'id_user' => $cek[0]['id_user'],
                'complaint_details' => $this->input->post('message'),
                'created_date' => $this->date_time_now(),
                'created_by' => $cek[0]['username']
            );
		$this->user_model->insert_complaint($data);
		redirect(base_url('informasi-iin/pengaduan'));
	}

	public function complaint(){
		$data['web_title'] = 'Layanan Pengaduan :: Layanan Issuer Identification Number';
		$this->set_template('complaint',$data = null);
	}

	public function cms_post($prm){
		$data['cms'] = $this->model->get_cms_by_prm($prm)->result_array();
		$this->set_template('cms-post-view',$data);
	}

	public function contact_us_prossess(){	
		$email = $this->input->post('email');
		$name = $this->input->post('name');
		$message = $this->input->post('message');
		$this->user_model->sendMail($email,$name, $message);
		redirect(base_url('contact-us'));
	}

	public function message($data = array()){	
		$this->load->view('message', $data);
	}

	public function iin_list(){
		$val = $this->session->userdata();
		if($val['have_iin']=='Y') 
			$data['download_iin'] = $this->user_model->get_iin_download($val['id_application_status'], 'IIN')->result_array();
		else 
			$data['download_iin'] = null;

		$data['iin'] = $this->user_model->get_iin()->result();
		$data['web_title'] = 'Daftar Penerima IIN :: Layanan Issuer Identification Number';
		$this->set_template('iin-list-view',$data);
	}

 }
