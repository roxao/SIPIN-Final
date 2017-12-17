<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class submit_iin extends CI_Controller {
	public function __construct() {
		
		parent::__construct();
		/* load library dan helper*/
	   	$this->load->library('session', 'upload');
	   	$this->load->helper(array('captcha','url','form','download'));
		$this->load->model('user_model');
		$this->load->model('admin_model');
		$this->load->library('email','form_validation', 'curl');
		$this->model = $this->user_model;
        $this->load->database();
			
	}
 
	public function index(){		
		$this->load->view('header');
		$this->load->view('submit-iin');
		$this->load->view('footer');
		$this->captcha();
	}

	public function date_time_now() {
		/*
		SET TIMEZONE ASIA/JAKARTA
		*/
	    $datetime = new DateTime('Asia/Jakarta');
	    return $datetime->format('Y\-m\-d\ H:i:s');
	}

	public function captcha()
	{

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
	

	/*
	INSERT LOG
	*/
	public function log($type, $detail){
		/*Insert Log*/
		$username = $this->session->userdata('username');
		$dataLog = array(
                'detail_log' => "{$detail} : {$username}",
                'log_type' => $type,
                'created_date' => $this->date_time_now(),
                'created_by' => $username
                );
        $this->user_model->insert_log($dataLog);
	}
	


	/*
	Pengajuan Surat Permohonan Ke BSN
	@view step0.php
	*/
	public function step_0() {
		
		// $this->captcha();
		// $a = $this->session->userdata('status');

		if($this->session->userdata('status') != "login"){
			redirect(base_url(""));
		} else {

			$id_user = $this->session->userdata('id_user');
			$first_validation = $this->user_model->step_0_validation_1($id_user)->row()->totals;

			/*Validasi apakah ada applikasi yang status nya OPEN*/
			if ($first_validation == 0) {

				if($this->input->post('kirim')  == 'kirim') {

					$get_document = $this->user_model->get_applications_Status($id_user);
					$username = $this->session->userdata('username');

					if (($this->input->post('security_code') == $this->session->userdata('mycaptcha'))){

						/*
						Define id_application_status_name => x (for below validation)
						@ Validate wether new or extend application
						*/
						$id_aps = "";
						$aps_type = "";
						$process_status = "";
						$app_type = $this->input->post('no_type');

						if ( $app_type == "new" ) {
							$id_aps = "1";
						} else {
							$id_aps = "2";
						}

						$data = array(
							'id_user' => $id_user,
							'applicant' => $this->input->post('app_applicant'),
							'applicant_phone_number' => $this->input->post('app_no_applicant'),
							'application_date' => date('Y-m-j', strtotime( $this->input->post('app_date') )),
							// 'application_date' => $this->input->post('app_date'),
							'application_purpose' => $this->input->post('app_purpose'),
							'instance_name' => $this->input->post('app_instance'),
							'instance_email' => $this->input->post('app_mail'),
							'instance_phone' => $this->input->post('app_phone'),
							'instance_director' => $this->input->post('app_div'),
							'mailing_location' => $this->input->post('app_address'),
							'mailing_number' => $this->input->post('app_num'),
							'iin_status' => "OPEN",
							'application_type' => $app_type,
							'created_date' => date('Y-m-j H:i:s'),
							'created_by' => $username
						);




						/*
						insert id_application_status_name => x(defined on above validation) Pending
						*/
						// if ( is_null($get_document->row()->iin_status ) ) {
						if ( $get_document->row()->iin_status != 'OPEN' ) {
							/*Insert Pengajuan*/
							$inserted_id = $this->user_model->insert_pengajuan($data);
							
							$data1 = array(
				                // 'id_application '=> $get_document->row()->id_application,
				                'id_application '=> $inserted_id,
				                'id_application_status_name' => $id_aps,
				                'process_status' => 'PENDING',
				                'created_date' => $this->date_time_now(),
				                'created_by' => $username
			            	);
				            $this->user_model->insert_app_status($data1);
							
							/*
							AUDIT TRAIL Step 0
							*/
							$this->log("Added New Application","Created new application by");
					        
					        /*
				            	REMINDER : 
				            	At this point , user should be stuck in this page
								and waiting for admin verification
				            */
							redirect(base_url("Layanan-IIN"));
				            
						} else {
							echo "|ERR: Controller submit_iin - function step_0";
						}
						

					} else {
						$this->session->set_flashdata('validasi-captcha', 'Captcha tidak sesuai');
						echo "Tidak Sama";
						redirect(base_url("Layanan-IIN"));
					}
				} else {
					echo "Dibatalkan";
					redirect(base_url("Layanan-IIN"));
				}
			} else {
				echo "|Tidak dapat melakukan pengajuan - Masih ada aplikasi dengan iin_status 'OPEN'|";
				redirect(base_url(''));
			}
		}
	}


	public function check_app_status() {
		/*
		Get id_user from session
		*/
		$id_user = $this->session->userdata('id_user');

		/*
		Get Application Status 
		*/
		$get_app_status =  $this->user_model->get_applications_Status($id_user);

		/*
		Validate If row Exist 
		*/
		if ( !is_null($get_app_status->row()->id_application) ) {

			$iin_status = $get_app_status->row()->iin_status;
			$id_application = $get_app_status->row()->id_application;
			$created_by = $get_app_status->row()->created_by;


			$app_status = array(
	            'iin_status'=> $iin_status,
	            'id_application'=> $id_application,
	            'created_by' => $created_by
	    	);
		    
			/*
			Validate id_application_status_name  exist
			*/
			if ( !is_null($get_app_status->row()->id_application_status_name) ) {
				$id_application_status_name = $get_app_status->row()->id_application_status_name;
				$process_status = $get_app_status->row()->process_status;
				$app_status['id_application_status_name'] = $id_application_status_name;
				$app_status['process_status'] = $process_status;

			}
			
		    return $app_status;

		} else {
			echo "ERROR :: Controller submit_iin - check_app_status | id_application NOT FOUND!";
			return "x";
		}

	}
	/*
	
	*/
	public function step_1() {

		/*
		THIS METHOD USING check_app_status function 
		*
		*/

		/*
		Instantiate app_status
		*/
		$app_status = $this->check_app_status();

		/*
		Validate app_status
		@ app_status value should be an array including
		*/
		if ($app_status != 'x') {


			$step_status_name = '2';

			$id_application_status_name = $app_status['id_application_status_name'];

			if ( $id_application_status_name == $step_status_name ) {
				
				/*
				Update The Value of application_status Table
				*/

				$this->user_model->update_aplication_status('COMPLETED', $app_status['id_application'], $step_status_name, $this->session->userdata('username'));

				/*
				AUDIT TRAIL Step 1
				*/
				$this->log("New Application Verified","Application Verified | Applicant");
		        
			}

			redirect(base_url("Layanan-IIN"));

		} else {
			echo "ERROR :: Controller submit_iin - check_app_status | id_application NOT FOUND!";
		}

	}

	/*
	
	*/
	public function step_2($uploaded, $key) {

		$logMsg = "";
		$limit = count($uploaded);

		$id_application = $this->session->userdata('id_application');
		$id_application_status = $this->session->userdata('id_application_status');
		$id_application_status_name = $this->session->userdata('id_application_status_name');


		if ( $id_application_status_name == '2' ) {
			/*
			NORMAL FILE UPLOAD
			*/

			/*
			GET list of document
			@Table : document_config
			*/
			$query = $this->user_model->get_doc_user_upload($key,'','');

			for ( $i = 0; $i < $limit; $i++ ) {
				$dataFile = array(
					'id_document_config' => $query[$i]->id_document_config,
					// 'id_document_config' => $explode_str[$i],
					'id_application' =>	 $id_application,
					'path_file' => $uploaded[$i]['full_path'],
					'status' => 'ACTIVE',
		            'created_date' => date('Y-m-j'),
					'created_by' => $this->session->userdata('username')
				);

				/*
				Insert application_file Table
				@Insert New Files Uploaded by User
				*/
				$this->user_model->insert_app_file($dataFile);
			}

			$id_application_status_name = '3';

			/*
			..INSERT application_status Table..
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => $id_application_status_name,
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            // 'created_date' => date('Y-m-j'),
	            'created_by' => $this->session->userdata('username')
	    	);
			
	        $this->user_model->insert_app_status($app_status);

		} elseif ( $id_application_status_name == '4' ) {
			/*
			..REVISION FILE UPLOAD..
			*/

			$process_status = 'COMPLETED';

			/*
			Get List of Revision File
			*/
			$data = $this->session->userdata('step2_upload');
			$list_id_form_mapping = $this->session->userdata('list_id_form_mapping');


			/*
			..UPDATE application_status Table..
			@ update id_application_status_name 4 = 'COMPLETED'
			*/
	        $this->user_model->update_aplication_status('COMPLETED', $id_application, $id_application_status_name, $this->session->userdata('username'));

			$id_application_status_name = '5';

			/*
			..INSERT application_status Table..
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => $id_application_status_name,
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            'created_by' => $this->session->userdata('username')
	    	);
			
	        $inserted_id = $this->user_model->insert_app_status($app_status);

			// $app_file = array();
			foreach ($data as $index => $valIndex) {

				/*
				Insert application_file Table
				@Insert New Files Uploaded by User
				*/
				$app_file =  array(
					// 'id_document_config' => $query[$index]->id_document_config,
					'id_application' =>	 $id_application,
					'path_file' => $uploaded[$index]['full_path'],
					'status' => 'ACTIVE',
		            'created_date' => $this->date_time_now(),
					'created_by' => $this->session->userdata('username')
				);

				foreach ($valIndex as $key => $val) {
					/*
					Validate $key== id_document_config
					*/
					if ($key == 'id_document_config') {
						$app_file['id_document_config'] = $val;
					}

					/*
					Validate $key== key
					*/
					if ($key == 'key') {
						/*
						Insert application_status_form_mapping Table
						@Insert KEY of revision Files Uploaded by User
						*/
						$form_map = array(
							'id_application_status' => $inserted_id,
							'type' => 'REVISION_FILE '.$val,
							'value' => $val,
				            'created_date' => $this->date_time_now(),
							'created_by' => $this->session->userdata('username')
						);

						$this->user_model->set_app_form($form_map, $list_id_form_mapping[$index]);

						
					}
				}


				$this->user_model->insert_app_file($app_file);
			}

			$logMsg = "Revision ";
		
		}

		/*
		..INSERT log Table..
		*/
		$this->log("{$logMsg}Submit Document","Application Files Submitted by");
	}	


	/*
	
	*/
	public function step_4() {
		/*
		THIS METHOD USING check_app_status function 
		*
		*/

		/*
		Instantiate app_status
		*/
		$app_status = $this->check_app_status();

		/*
		Validate app_status
		@ app_status value should be an array including
		*/
		if ($app_status != 'x') {
			$step_status_name = '7';
			$id_application_status_name = $app_status['id_application_status_name'];

			if ( $id_application_status_name == $step_status_name ) {

				$this->user_model->update_aplication_status('COMPLETED', $app_status['id_application'], $step_status_name, $this->session->userdata('username'));

				/*
				AUDIT TRAIL Step 1
				*/
				$this->log("User Download Billing Code","Billing Code Downloaded | Applicant");
		        
			}

			redirect(base_url("Layanan-IIN"));
		}
	}
	

	/*
	
	*/
	public function step_5($uploaded, $key_arr){
		

		$logMsg = "";

		$limit = count($uploaded);

		// $id_application = $this->input->post('id_application');
		$id_application = $this->session->userdata('id_application');
		$id_application_status = $this->session->userdata('id_application_status');
		$id_application_status_name = $this->session->userdata('id_application_status_name');


		if ( $id_application_status_name == '7' ) {
			/*
			NORMAL FILE UPLOAD
			*/

			/*
			GET list of document
			@Table : document_config
			*/
			$query = $this->user_model->get_doc_user_upload($key_arr,'','');

			for ( $i = 0; $i < $limit; $i++ ) {
				$dataFile = array(
					'id_document_config' => $query[$i]->id_document_config,
					// 'id_document_config' => $explode_str[$i],
					'id_application' =>	 $id_application,
					'path_file' => $uploaded[$i]['full_path'],
					'status' => 'ACTIVE',
		            'created_date' => date('Y-m-j'),
					'created_by' => $this->session->userdata('username')
				);

				/*
				Insert application_file Table
				@Insert New Files Uploaded by User
				*/
				$this->user_model->insert_app_file($dataFile);
			}

			$id_application_status_name = '9';

			/*
			..INSERT application_status Table..
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => $id_application_status_name,
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            // 'created_date' => date('Y-m-j'),
	            'created_by' => $this->session->userdata('username')
	    	);
			
	        $this->user_model->insert_app_status($app_status);

		} elseif ( $id_application_status_name == '10' ) {
			/*
			..REVISION FILE UPLOAD..
			*/

			$process_status = 'COMPLETED';

			/*
			Get List of Revision File
			*/

			$id_doc_arr = $this->user_model->get_doc_user_upload($key_arr,'','');


			$list_id_form_mapping = $this->session->userdata('list_id_form_mapping');
			/*
			..UPDATE application_status Table..
			@ update id_application_status_name 10 = 'COMPLETED'
			*/
	        $this->user_model->update_aplication_status('COMPLETED', $id_application, $id_application_status_name, $this->session->userdata('username'));

			$id_application_status_name = '11';

			/*
			..INSERT application_status Table..
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => $id_application_status_name,
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            'created_by' => $this->session->userdata('username')
	    	);
			
	        $inserted_id = $this->user_model->insert_app_status($app_status);

			foreach ($uploaded as $index => $valIndex) {

				/*
				Insert application_file Table
				@Insert New Files Uploaded by User
				*/
				$app_file =  array(
					'id_document_config' => $id_doc_arr[$index]->id_document_config,
					'id_application' =>	 $id_application,
					'path_file' => $uploaded[$index]['full_path'],
					'status' => 'ACTIVE',
		            'created_date' => $this->date_time_now(),
					'created_by' => $this->session->userdata('username')
				);

				foreach ($valIndex as $key => $val) {
					# code...

					/*
					Validate $key== id_document_config
					*/
	
					/*
					Validate $key== key
					*/
					if ($key == 'key') {
						/*
						Insert application_status_form_mapping Table
						@Insert KEY of revision Files Uploaded by User
						*/
						$form_map = array(
							// 'id_application_status' => $inserted_id,
							'type' => 'REVISION_PAY '.$val,
							'value' => $key_arr,
				            'created_date' => $this->date_time_now(),
							'created_by' => $this->session->userdata('username')
						);



						$this->user_model->set_app_form($form_map, $list_id_form_mapping[$index]);


						/*
						..INSERT log Table..
						*/
						$this->log("Revisi Form Mapping","Revision Form Mapping Submitted by");
					}
				}


				$this->user_model->insert_app_file($app_file);
			}

			$logMsg = "Revision ";

		}
	

		/*
		..INSERT log Table..
		*/
		$this->log("{$logMsg}Submit Payment","Payment Submitted by");
	}



	public function step_6(){

		/*
		THIS METHOD USING check_app_status function 
		*
		*/
		/*
		Instantiate app_status
		*/
		$app_status = $this->check_app_status();

		/*
		Validate app_status
		@ app_status value should be an array including
		*/
		if ($app_status != 'x') {
			$step_status_name = '12';

			$id_application_status_name = $app_status['id_application_status_name'];

			if ( $id_application_status_name == $step_status_name ) {

				/*
				Update Application Status 12
				*/
				$this->user_model->update_aplication_status('COMPLETED', $app_status['id_application'], $step_status_name, $this->session->userdata('username'));


				$id_application_status_name = '14';

				/*
				..INSERT application_status Table.. (application status 14)
				*/
				$app_status = array(
		            'id_application '=> $app_status['id_application'],
		            'id_application_status_name' => $id_application_status_name,
		            'process_status' => 'PENDING',	
		            'created_date' => $this->date_time_now(),
		            'created_by' => $this->session->userdata('username')
		    	);
				
		        $this->user_model->insert_app_status($app_status);

				/*
				AUDIT TRAIL Step 1
				*/
				$this->log("User Approved Verification Team","Verification Team  Approved | Applicant");
		        
			}

			redirect(base_url("Layanan-IIN"));
		}
		



		
	}

	public function step_6_rev(){
		
		/*
		THIS METHOD USING check_app_status function 
		*
		*/
		/*
		Instantiate app_status
		*/
		$app_status = $this->check_app_status();

		/*
		Validate app_status
		@ app_status value should be an array including
		*/
		if ($app_status != 'x') {
			$id_application = $this->session->userdata('id_application');
			$id_application_status = $this->session->userdata('id_application_status');
			$id_application_status_name = $this->session->userdata('id_application_status_name');

			$data = array(
				'rev_assess_date' => date('Y-m-j', strtotime( $this->input->post('rev_assess_date')))
			);

			$rev_assess_date = date('Y-m-j', strtotime( $this->input->post('rev_assess_date')));

			/*
			..INSERT log Table..
			*/
			$this->log("Revision Assessment Date","Revision Assessment Date Submitted by");

			/*
			..UPDATE application_status Table..
			@ update id_application_status_name 12 = 'COMPLETED'
			*/
	        $this->user_model->update_aplication_status('COMPLETED', $id_application, $id_application_status_name, $this->session->userdata('username'));

			/*
			..INSERT application_status Table.. (application status 13)
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => '13',
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            'created_by' => $this->session->userdata('username')
	    	);

	        $inserted_id = $this->user_model->insert_app_status($app_status);

	        $form_map = array(
				'id_application_status' => $inserted_id,
				'type' => 'REVISION_ASSESSMENT_DATE',
				'value' => $rev_assess_date,
	            'created_date' => $this->date_time_now(),
				'created_by' => $this->session->userdata('username')
			);

			$this->user_model->set_app_form($form_map);
		}
		redirect(base_url('Layanan-IIN'));

	}

	public function step_7($uploaded, $keys){


		$limit = count($uploaded);
		$id_application = $this->session->userdata('id_application');
		$id_application_status = $this->session->userdata('id_application_status');
		$id_application_status_name = $this->session->userdata('id_application_status_name');

		if ( $id_application_status_name == '16' ) {
			/*
			..REVISION FILE UPLOAD..
			*/

			$process_status = 'COMPLETED';

			
			/*
			..UPDATE application_status Table..
			@ update id_application_status_name 16 = 'COMPLETED'
			*/
	        $this->user_model->update_aplication_status('COMPLETED', $id_application, $id_application_status_name, $this->session->userdata('username'));

			$id_application_status_name = '17';

			/*
			..INSERT application_status Table..
			*/
			$app_status = array(
	            'id_application '=> $id_application,
	            'id_application_status_name' => $id_application_status_name,
	            'process_status' => 'PENDING',	
	            'created_date' => $this->date_time_now(),
	            'created_by' => $this->session->userdata('username')
	    	);

	        $inserted_id = $this->user_model->insert_app_status($app_status);


			// $app_file = array();
			foreach ($uploaded as $index => $valIndex) {
				/*
				Insert application_file Table
				@Insert New Files Uploaded by User
				*/
				$app_file =  array(
					'id_document_config' => $keys[$index],
					'id_application' =>	 $id_application,
					'path_file' => $uploaded[$index]['full_path'],
					'status' => 'ACTIVE',
		            'created_date' => $this->date_time_now(),
					'created_by' => $this->session->userdata('username')
				);

				$this->user_model->insert_app_file($app_file);

				$form_map = array(
					'id_application_status' => $inserted_id,
					'type' => 'REVISION_ASSESSMENT_FILE',
					'value' => $keys[$index],
		            'created_date' => $this->date_time_now(),
					'created_by' => $this->session->userdata('username')
				);

				$this->user_model->set_app_form($form_map);


			}

			$this->log("Revision Document Assessment Verification","Revision Document Assessment Submitted by");
			
		}


	}

	/*
	view rejected.php
	*/
	public function step_rejected() {

		/*
		Get id_user from session
		*/
		$id_user = $this->session->userdata('id_user');
		
		/*
		Get Application Status 
		*/
		$get_app_status =  $this->user_model->get_applications_Status($id_user);

		/*
		Validate If row Exist 
		*/
		if ($get_app_status->row()->iin_status != 'NULL') {

			$id_application = $get_app_status->row()->id_application;
			$id_application_status_name = $get_app_status->row()->id_application_status_name;
			$username = $get_app_status->row()->created_by;

			/*
			Update applications Table
			*/
			$this->user_model->update_applications("CLOSED", $id_application, $username	);
			/*
			Update application_status Table
			*/
			$this->user_model->update_aplication_status("COMPLETED", $id_application, $id_application_status_name, $username);

			/*
			AUDIT TRAIL application rejected
			*/
			$this->log("New Application Closed","Application Closed by");
		}

		redirect(base_url());
	}



	/*Melakukan penarikan dokumen*/
	public function download(){
	
	if($this->session->userdata('status') != "login"){
			redirect(base_url("SipinHome"));
		}

		$image_id = $this->input->get('var1');
   		force_download($image_id, NULL);	
	}



	/*Melakukan Upload document*/
	function upload_files_assessment() {
		//STEP 7

		if($this->session->userdata('status') != "login"){
			redirect(base_url("SipinHome"));
		}
		
		// Configure upload.
	 	$this->load->library('upload');
		$this->upload->initialize(array(
			 "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
             "upload_path"   => "./upload/"
		));

		$id_application = $this->session->userdata('id_application');
		$id_application_status = $this->session->userdata('id_application_status');

		$type='REV_DOC_ASM';
		$val = $this->user_model->get_form_mapping_by_type($id_application_status, $type);


		$key3_arr = array();
		foreach ($val as $index => $valIndex) {

			switch($valIndex->type){

				case "REV_DOC_ASM": 					
					array_push($key3_arr, $valIndex->value);
					break;	 

			}

		}

		$uploaded = array();
		$key = array();


		for ($i = 0; $i < sizeof($key3_arr); $i++) {

			/*
			Define index of list file from View
			*/

			$usr_file = "file{$i}";

			/*
			File name
			*/
			$name_file = $_FILES[$usr_file]['name'];

			/*
			Validate if the file name empty
			@ In this case only upload file that already selected by user.
			@ If user didn't choose a file, error from My_upload will be 'You did not selected the file'
			*
			*/
			if ( $name_file != "") {
				$this->upload->do_upload($usr_file);

				array_push($uploaded, $this->upload->data());
				array_push($key, $key3_arr[$i]);
			} else {
				echo "|ERR : {$usr_file}";
			}
					

		}

		if ($this->input->post('upload') == "uploadstep7") {
			$this->step_7($uploaded, $key3_arr);
		}

		redirect(base_url("Layanan-IIN"),'refresh');
	}



	function upload_files() {

		if($this->session->userdata('status') != "login"){
			redirect(base_url("SipinHome"));
		}

		$id_user = $this->session->userdata('id_user');
		$get_document = $this->user_model->get_aplication($id_user);
		$username = $this->session->userdata('username');
		$query = 0;

      	// Configure upload.
	 	$this->load->library('upload');
		$this->upload->initialize(array(
			 "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
             "upload_path"   => "./upload/"
		));

		/*
		To Count List of files to be uploaded (if it isn't an array)
		*
		*/

		//STEP 5
		$no_count = $this->input->post('no_count');

		$explode_str = explode(",", $no_count);	

		$key2 = $this->input->post('key_step5');

		$key2_arr = array($key2);

		/*
		Instantiate uploaded and key files array.
		*/

		$uploaded = array();
		$key = array();


		for ($i = 0; $i < count($explode_str); $i++) {

			/*
			Define index of list file from View
			*/

			$usr_file = "file{$explode_str[$i]}";

			/*
			File name
			*/
			$name_file = $_FILES[$usr_file]['name'];

			/*
			Validate if the file name empty
			@ In this case only upload file that already selected by user.
			@ If user didn't choose a file, error from My_upload will be 'You did not selected the file'
			*
			*/
			if ( $name_file != "") {
				$this->upload->do_upload($usr_file);
				array_push($uploaded, $this->upload->data());
				array_push($key, $explode_str[$i]);
			} else {
				echo "|ERR : {$usr_file}";
			}

		}

		if ($this->input->post('upload') == "uploadstep3"){
			$this->step_2($uploaded, $key);
		} 
		else if ($this->input->post('upload') == "uploadstep5") {
			 $this->step_5($uploaded, $key2_arr);
		} 

		redirect(base_url("Layanan-IIN"),'refresh');

	}
	
	function download_iin() {

		$id_user = $this->session->userdata('id_user');


		/*
		Check Survey Status
		@ Valid if survey_status = 0
		*/
		$survey_status = $this->session->userdata('survey_status');
		if ($survey_status == '0') {
			/*
			DOWNLOAD IIN document
			@ Valid if survey_status = 0
			*/
			redirect(base_url('submit_iin/survey/vote'));

		} elseif ($survey_status == '1') { 

			/*
			DOWNLOAD IIN document
			@ Valid if survey_status = 0
			*/
			$this->download();
			// $image_id = $this->input->get('var1');
			// echo "|IMAGE ID : {$image_id}";
			redirect(base_url('Layanan-IIN'));
		}



	}


	//SURVEY

	public function set_template($view_name, $data = array()){
        $this->load->view('header', $data);
        $this->load->view($view_name, $data);
        $this->load->view('footer', $data);
        return;
    }


	public function survey( $x = null){
		switch ($x) {
			case 'vote':
				$survey = $this->model->survey('vote',null)->result_array();
				$data['survey'] = $survey[0]['id_survey_question'];
				$data['data'] = json_decode($survey['0']['question'], true);
				$this->set_template('survey',$data);
				break;

			case 'insert-survey';
				$survey_config 	 = explode('|', $this->input->post('survey'));
				$survey_question = array();
				for ($i=1; $i <= $survey_config[1] ; $i++) { 
					$answer = array(
		                'no'   		=> $i,
		                'type'   	=> $this->input->post('answer'.$i) ? 'RATING': "COMMENT",
		                'answer'   	=> $this->input->post('answer'.$i) ? $this->input->post('answer'.$i) : $this->input->post('comment'.$i)
		                );
					array_push($survey_question, $answer);
				}

				$survey_answers = array(
		                'id_user'   		=> $this->session->userdata('id_user'),
		                'answer'   			=> json_encode($survey_question),
		                'version'   		=> $survey_config[0],
		                'created_date'		=> (new DateTime('Asia/Jakarta'))->format('Y\-m\-d\ h:i:s'),
		                'created_by'		=> $this->session->userdata('username')
		                );


				$id_answer = $this->admin_model->survey('insert-answer',$survey_answers);

				/*
				| Update User Table |
				*/
				$this->model->update_survey_status_user($this->session->userdata('id_user'));
				$this->session->set_userdata('survey_status','1');

				
				// Masukan $survey_answers ke database
				// Hapus echo dibawah
				// function model sudah dibuat 

				redirect(base_url('Layanan-IIN'));
				break;
			case 'result-survey';
				// KALAU SUDAH MEMBUAT QUERY YANG JIKA DI json_encode seperti dibawah
				// HAPUS CODE DIBAWAH INI
				$data['survey'] = json_decode('{"id_survey_question":"1","version":"1","total_answer":"15","survey_questions":[{"no":"1","question":"Question number 1","average":"4","answer":{"1":"0","2":"0","3":"3","4":"3","5":"9"}},{"no":"2","question":"Question number 2","average":"4","answer":{"1":"0","2":"0","3":"3","4":"3","5":"9"}}]}',true);
				// SAMPAI CODE INI

				// LALU HAPUS COMMENT CODE DIBAWAH INI
				// $data['survey'] = $this->user_model->get-survey-result()->result_array();

				$result['survey']=$this->admin_model->survey('get-survey-result',null)->result();

				
				#level 1 array
				$ans = array();
				#level 2 array
				$survey_question = array();
				#level 3 array
				$answer = array();

				#define string
				$id_survey_question = "";
				$version = "";
				$total_answer = "";
				$average=0;

				#int stars
				$s_1 = 0;
				$s_2 = 0;
				$s_3 = 0;
				$s_4 = 0;
				$s_5 = 0;

				$avg_array = array();

				#array to populate string stars
				$arr_stars =  array();

				#array to get answer
				$arr_answer = array();

				$iter_answer = 0;
				$no = 1;
				$i =0;
				foreach ($result as $index => $valIndex) {

					foreach ($valIndex as $key => $value) {
							#value of total_answer
							$iter_answer++;
							$total_answer = (string)$iter_answer;
						foreach ($value as $keyval => $val) {
							#value of id_survey_question
							if ( $keyval == 'id_survey_question' ) $id_survey_question = $val;
							#value of version
							if ( $keyval == 'version' ) $version = $val;

							if ( $keyval == 'question' ) {
								#iterate from $val
								#decode it so it turns to array
								foreach (json_decode($val,true) as $a => $b) {
									if ( $b['type'] == 'RATING') {
										$survey_question[$a]['no'] = (string)$b['no'];
										$survey_question[$a]['question'] = (string)$b['msg'];
									}
								}
							}

							if ( $keyval == 'answer' ) {
								#iterate from $val
								#decode it so it turns to array
								foreach (json_decode($val,true) as $a => $b) {
									if ( $b['type'] == 'RATING') {
										switch ( $b['answer'] ) {
											case '1':
												$s_1 += 1;
												break;
											case '2':
												$s_2 += 1;
												break;
											case '3':
												$s_3 += 1;
												break;
											case '4':
												$s_4 += 1;
												break;
											case '5':
												$s_5 += 1;
												break;
										}
											
									$answer[$key]['1'] = (string)$s_1;
									$answer[$key]['2'] = (string)$s_2;
									$answer[$key]['3'] = (string)$s_3;
									$answer[$key]['4'] = (string)$s_4;
									$answer[$key]['5'] = (string)$s_5;

									$survey_question[$key]['answer'] = $answer[$key];

									}

								}

							#return int stars value to 0
							$s_1 = 0;
							$s_2 = 0;
							$s_3 = 0;
							$s_4 = 0;
							$s_5 = 0;
							}
						}			
						array_push($avg_array, $average);
					}

				}


				// echo json_encode($survey_question);


				$ans['id_survey_question'] = $id_survey_question;
				$ans['version'] = $version;
				$ans['total_answer'] = $total_answer;
				$ans['survey_question'] = $survey_question;
				echo json_encode($ans);


				return false;



				$this->set_template('survey-result',$data);
				break;
			default:
				redirect(base_url());
				break;
		}

	}




}