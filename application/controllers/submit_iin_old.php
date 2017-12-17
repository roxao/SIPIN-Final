<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class submit_iin extends CI_Controller {
	public function __construct() {
		
		parent::__construct();
		/* load library dan helper*/
	   	$this->load->library('session', 'upload');
	   	$this->load->helper(array('captcha','url','form','download'));
		$this->load->model('user_model');
		$this->load->library('email','form_validation', 'curl');
		$this->model = $this->user_model;
        $this->load->database();
		
	}
 
	public function index(){		
		$this->load->view('header');
		$this->load->view('submit-iin');
		$this->load->view('footer');
	}
	public function log($Type, $detil){
		/*Insert Log*/
		$username = $this->session->userdata('username');
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
	/*Melakukan penyimpanan form step ke 0*/ 
	public function insert_letter_submission(){
		
	$id_user = $this->session->userdata('id_user');
	$get_document = $this->user_model->get_applications_Status($id_user);
	$username = $this->session->userdata('username');
	if($this->input->post('kirim') == "kirim"){
		$data = array(
		'id_user' => $id_user,
		/*id_admin yg update nanti dari sisi admin makanya di isi Null*/ 
		'id_admin' => "NULL",
		'applicant' => $this->session->userdata('username'),
		'applicant_phone_number' => "085725725725",
		'application_date' => $this->input->post('app_date'),
		'instance_name' => $this->input->post('app_instance'),
		'instance_email' => $this->input->post('app_mail'),
		'instance_phone' => $this->input->post('app_phone'),
		'instance_director' => $this->input->post('app_div'),
		'mailing_location' => $this->input->post('app_address'),
		'mailing_number' => $this->input->post('app_num'),
		'iin_status' => "OPEN",
		'application_type' => "NULL",
		'created_date' => date('Y-m-j H:i:s'),
		'created_by' => $username,
		'modified_date' => date('Y-m-j H:i:s'),
		'modified_by' =>$username);
		$this->log("added new applicant","Created new application");
        /*Inser Pengajuan*/
		$cekini = $this->user_model->insert_pengajuan($data);
		// print_r($cekini);
		/*insert Status 1 Pending*/
		if ($get_document->num_rows() > -1){
				$data1 = array(
                'id_application '=> $cekini,//$get_document->row()->id_application,
                'id_application_status_name' => '1',
                'process_status' => 'PENDING',	
                'created_date' => date('Y-m-j'),
                'created_by' => $username,
                'modified_by' => $username,
                'last_updated_date' => date('Y-m-j'));
            $this->user_model->insert_app_status($data1);
		}
	} else {
		echo "Dibatalkan";
	}	
	}
	/*Melkukan penarikan dokumen*/
	public function download(){
	
	$iamge_id = $this->input->get('var1');
   	force_download($iamge_id, NULL);	
	}
	function  step_tiga_upload (){
	$id_user = $this->session->userdata('id_user');
	$get_status = $this->user_model->get_applications_Status($id_user);
	$username = $this->session->userdata('username');
		 /*insert Status*/
		// if ($get_document->num_rows() > 0){
		
		if ($get_status->row()->id_application_status_name =="4"){
				if ($get_status->row()->id_application_status_name =="PENDING"){
					$this->log("Revisi document","Revisi step3");
					$this->user_model->update_aplication_status("COMPLETED", $get_status->row()->id_application, "4", $username);
					$data5 = array(
                'id_application '=> $get_status->row()->id_application,
                'id_application_status_name' => '5',
                'process_status' => 'PENDING',
                'created_date' => date('Y-m-j'),
                'created_by' => $username,
                'modified_by' => $username,
                'last_updated_date' => date('Y-m-j'));
                $this->user_model->insert_app_status($data5);
				}
		} 
		else {
			// if  ($get_status->row()->id_application_status_name =="3"){ 

			$data1 = array(
                'id_application '=> $get_status->row()->id_application,
                'id_application_status_name' => '3',
                'process_status' => 'PENDING',	
                'created_date' => date('Y-m-j'),
                'created_by' => $username,
                'modified_by' => $username,
                'last_updated_date' => date('Y-m-j'));
            $this->user_model->insert_app_status($data1);



					// if ($get_document->row()->id_application_status_name =="PENDING"){
					// 	$this->log("New document","New step3");
					// $this->user_model->update_aplication_status("COMPLETED", $get_document->row()->id_application, "3", $username);
					// }
		}
	// }
	}
	function  step_enam_upload (){
	$id_user = $this->session->userdata('id_user');
	$get_status  = $this->user_model->get_applications_Status($id_user);
	$username = $this->session->userdata('username');
		 /*insert Status*/
		if ($get_status->num_rows() > 0){

			$this->user_model->update_aplication_status("COMPLETED", $get_status->row()->id_application, "7", $username);
		
				$this->log("Upload new document","Upload new document step6");
				$data3 = array(
                'id_application '=> $get_status->row()->id_application,
                'id_application_status_name' => '9',
                'process_status' => 'PENDING',
                'created_date' => date('Y-m-j'),
                'created_by' => $username,
                'modified_by' => $username,
                'last_updated_date' => date('Y-m-j'));
                $this->user_model->insert_app_status($data3);
                 
                 
			
            // 7 Belum dirubah jadi update
		}
	}
function  step_tujuh_team (){
	$id_user = $this->session->userdata('id_user');
	$get_status  = $this->user_model->get_applications_Status($id_user);
	$username = $this->session->userdata('username');
		 /*insert Status*/
		if ($get_status->num_rows() > 0){
		
				$this->log("Upload confirmation payment","Upload nconfirmation payment");
				$data3 = array(
                'id_application '=> $get_status->row()->id_application,
                'id_application_status_name' => '14',
                'process_status' => 'PENDING',
                'created_date' => date('Y-m-j'),
                'created_by' => $username,
                'modified_by' => $username,
                'last_updated_date' => date('Y-m-j'));
                $this->user_model->insert_app_status($data3);
                 $this->user_model->update_aplication_status("COMPLETED", $get_status->row->id_application, "12", $username);
			
            
		}
	}
	
/*Melakukan Upload document*/
	 function do_upload() {
	$id_user = $this->session->userdata('id_user');
	$get_document = $this->user_model->get_aplication($id_user);
	$username = $this->session->userdata('username');
	$query = 0;
	 	 $this->load->library('upload');
 
      //Configure upload.
             $this->upload->initialize(array(
   "allowed_types" => "gif|jpg|png|jpeg",
                 "upload_path"   => "./upload/"
             ));
             //Perform upload.
             if($this->upload->do_upload("images")) {
                 $uploaded = $this->upload->data();
                
            if ($this->input->post('upload') == "uploadstep3"){
		   $query = $this->user_model->getdocument_aplication_forUpload($id_user, "document_config.type", "DYNAMIC", "ACTIVE");
			} else if ($this->input->post('upload') == "uploadstep6") {
				 $query = $this->user_model->getdocument_aplication_forUpload($id_user, "document_config.key", "BT PT", "ACTIVE");
			}


		   /*Qwery Di Looping Menggunakan Buble Short Supaya mudah di pahami*/
		   for ($j = 0; $j < count($query); $j++){
		   	/*Array Image di parsing*/
			for ($i = 0; $i < count($uploaded); $i++) {
				/*Disamain Indexnnya Setelah Index Sama Baru di Insert ke DB*/
				 	if ($j == $i){
				 		/*Qwery Insert FilePathnya ke DB*/
				 		
				 		
				if ($this->input->post('upload') == "uploadstep6"){
					$this->user_model->update_document( $query[$j]->id_application, $query[$j]->id_application_file, $query[$j]->id_document_config, $uploaded['full_path'], $username);
				} else if ($this->input->post('upload') == "uploadstep3"){
					$this->user_model->update_document( $query[$j]->id_application, $query[$j]->id_application_file, $query[$j]->id_document_config, $uploaded[$i]['full_path'], $username);
				}

			 		}
				}
			}
			  } else{
   die('GAGAL UPLOAD');
      } 
     
			      if ($this->input->post('upload') == "uploadstep3"){
					    $this->step_tiga_upload();
			} 
			else if ($this->input->post('upload') == "uploadstep6") {
				 $this->step_enam_upload();
			}
  } 
 
    


	public function captcha()
	{
		$vals = array(
			//'word' => 'Random word',
			'img_path' => './captcha/',
			'img_url' => base_url().'captcha/',
			//'font_path' => './path/to/fonts/texb.ttf',
			'img_width'	=> '120',
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
		$data = $cap['image'];
		return $data;
	}
	
 }