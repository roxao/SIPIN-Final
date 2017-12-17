<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Verifikasi_Controller extends CI_Controller 
{


	public function __construct() 
	{
		
		parent::__construct();
		$this->load->library('session', 'upload');
	    $this->load->helper(array('captcha','url','form'));
		$this->load->model('admin_model');
		$this->load->library('email');
		$this->load->helper('form'); 
		$this->load->database();
		$this->load->model('admin_model','adm_model');
        $this->load->model('user_model','usr_model');
      
	}

    //proses pertama
	public function VERIF_NEW_REQ($id_application_status)
	{

        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('admin_pengajuan_permohonan', $data);
	}

    //proses pertama setujui permohonan baru
	public function VERIF_NEW_REQ_PROSES()
	{
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
      
        		$data = array(
        		'process_status' => 'COMPLETED',
            	'created_date' => date('Y-m-j'),
      			'created_by' => $this->session->userdata('username'),
        		'last_updated_date' => date('Y-m-j H:i:s'));

        	$condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' adding new applicant',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

        	$this->admin_model->next_step($data,$condition);

                $data2 = array(
                'id_application'=> $this->input->post('id_application'),
                'id_application_status_name' => '2',
                'process_status ' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'),
                'modified_by ' => $this->session->userdata('username')
                );
            
            $this->admin_model->insert_app_status($data2);

            $data4 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED'
                    );
            $this->admin_model->insert_app_sts_for_map($data4);

            $this->get_doc($this->input->post('id_application'));

                $this->send_mail($this->input->post('id_application'));
        	 redirect(site_url('dashboard'));
        	
        
	}

    //tolak pengajuan karena sudah punya iin
    public function VERIF_NEW_REQ_HAS_IIN()
    {
            
                $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Rejected new applicant',
                'log_type' => 'reject new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            // $this->admin_model->next_step($data,$condition);

                $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'id_application_status_name' => '2',
                'process_status ' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'),
                // 'modified_by ' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_app_status($data2);

            $data4 = array(
                    'type' => 'REJECTED',
                    'value' => $this->input->post('coment')
                    );
            $this->admin_model->insert_app_sts_for_map($data4);
                    
                    $data5 = array(
                        'iin_status'=> 'CLOSED');

                    $id_application = array('id_application'=> $this->input->post('id_application'));
            // $this->admin_model->update_app($data5,$id_application);

        
    }

    //tolak pengajuan karena kesalahan sesuatu
    public function VERIF_NEW_REQ_ETC()
    {
  
         // ditolak dll

        //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
        echo $id_app->row()->applicant;
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'modified_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Rejected new applicant',
                'log_type' => 'reject new applicant '.$id_app->row()->applicant, 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'id_application_status_name' => '2',
                'process_status ' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'),
                // 'modified_by ' => $this->session->userdata('username')
                );
            $this->admin_model->insert_app_status($data2);

  

                $data4 = array(
                    'type' => 'REJECTED',
                    'value' => $this->input->post('coment'),
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
            $this->admin_model->insert_app_sts_for_map($data4);
                    
                    $data5 = array(
                        'iin_status'=> 'CLOSED');
            $id_application = array('id_application'=> $this->input->post('id_application'));
            $this->admin_model->update_applications($data5,$id_application);
           redirect(base_url('dashboard'));
        
    }
























	public function VERIF_UPLDOC_REQ($id_application_status)
	{
	    $data['aplication_setujui'] = $this->admin_model->get_application_file($id_application_status)->result();
        // echo json_encode($data);
        $this->load->view('admin_cek_upload_document', $data);
	}

//setujui kelemngkapan document dari user
	public function VERIF_UPLDOC_REQ_PROSES_SUCCEST()
	{
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
        	$data = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                // 'id_application_status_name' => '3',
              
                'created_date' => date('Y-m-j'),
                // 'modified_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

        	$condition = array('id_application_status' => $this->input->post('id_application_status'));
            echo $this->input->post('id_application_status')." = id_application_status";
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' approved new document',
                'log_type' => 'added '.$id_app->row()->applicant, 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

        	$this->admin_model->next_step($data,$condition);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '4',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data4,$condition);

        	$data5 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '5',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data5,$condition);

            $data6 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '6',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data6,$condition);

                  $data2 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data2);
           redirect(base_url('dashboard'));

	}

//revisi document untuk user
    public function VERIF_UPLDOC_REQ_PROSES_REVITIONS()
    {
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '3',
              
                'created_date' => date('Y-m-j'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Revisi Kelengkapan Dokumen',
                'log_type' => 'revisi '.$id_app->row()->applicant, 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

             $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '4',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           //dapet id pas save terus lempar ke perulangan app form mapping
            $this->admin_model->insert_app_status($data4,$condition);


           //update data

             $doc = $this->input->post("docRef");

             for ($i=0; $i < count($doc); $i++) { 
                    //untuk mendapatkan id_document_config
                    $dc = $this->admin_model->document_config_get_by_prm_key($doc[$i]);

                    //app file search dgn 2 parameter yaitu id_application dan id_document_config
                    $apf = $this->admin_model->application_file_get_by_idapp_iddc($this->input->post('id_application'),$dc->row()->id_document_config);

                    //data untuk insert ke tabel applications_form_mapping
                    if(!$doc[$i] == null)
                    {
                        $data2 = array(
                        'type' => 'REVISED_DOC '.$doc[$i],
                        'value' => $doc[$i],
                        'id_application_status'=> $this->input->post('id_application_status')
                        );
                        //insert ke tabel application form mapping
                         $this->admin_model->insert_app_sts_for_map($data2);

                        $id_app_file = array('id_application_file' => $apf->row()->id_application_file);
                        //dari user yang update setelah upload buang 
                        $data3 = array(
                            'status' => 'INACTIVE',
                            'modified_date' => date('y-m-d'),
                            'modified_by' => $this->session->userdata('username')
                        );
                        //update applications file untuk direfisi
                        $this->admin_model->application_file_update($id_app_file, $data3);

                    }
             }

            redirect(base_url('dashboard'));
    }

   


   public function VERIF_REVDOC_REQ($id_application_status)
   {
        $data['aplication_setujui'] = $this->admin_model->get_application_file($id_application_status)->result();
        // echo json_encode($data);
        $this->load->view('admin_cek_revisi_upload_document', $data);
   }

//revisi dokumen disetujui
   public function VERIF_REVDOC_REQ_PROSES()
   {
            
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'modified_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' approve revisi dokumen',
                'log_type' => 'added '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '6',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);
            
             $data3 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data3);    
            redirect(base_url('dashboard'));

   }

//revisi dokumen kembali jika ada kesalahan dokumen 
   public function VERIF_REVDOC_REQ_REVITION()
   {
        
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi dokumen',
                'log_type' => 'added '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '4',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);
            
           //update data

             $doc = $this->input->post("docRef");

             for ($i=0; $i < count($doc); $i++) { 
                    //untuk mendapatkan id_document_config
                    $dc = $this->admin_model->document_config_get_by_prm_key($doc[$i]);

                    //app file search dgn 2 parameter yaitu id_application dan id_document_config
                    $apf = $this->admin_model->application_file_get_by_idapp_iddc($this->input->post('id_application'),$dc->row()->id_document_config);

                    //data untuk insert ke tabel applications_form_mapping
                    if(!$doc[$i] == null)
                    {
                        $data2 = array(
                        'type' => 'REVISED_DOC '.$doc[$i],
                        'value' => $doc[$i],
                        'id_application_status'=> $this->input->post('id_application_status')
                        );
                        //insert ke tabel application form mapping
                         $this->admin_model->insert_app_sts_for_map($data2);

                        $id_app_file = array('id_application_file' => $apf->row()->id_application_file);
                        
                        $data3 = array(
                            'status' => 'INACTIVE',
                            'modified_date' => date('y-m-d'),
                            'modified_by' => $this->session->userdata('username')
                        );
                        //update applications file untuk direfisi
                        $this->admin_model->application_file_update($id_app_file, $data3);

                    }
             }

            redirect(base_url('dashboard')); 
            
 
   }





















	public function UPL_BILL_REQ($id_application_status)
	{
        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('admin_upload_biling_code_simponi', $data);
	}
//mengupload biling
	public function UPL_BILL_REQ_SUCCEST()
	{  
        
            
                $data = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '6',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Upload Billing Code SIMPONI',
                'log_type' => 'added '.$this->input->post('username'), 
                'log_type' => 'added by : ', 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);
            $this->admin_model->next_step($data,$condition);

             $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '7',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                'id_application' => $this->input->post('id_application'),
                'id_document_config'=> '23',
                'path_id'=> '/',
                'status' => 'ACTIVE'
                );

            // $this->admin_model->insert_application_file($data3);

            $data4 = array(
                    'type' => $this->input->post('app_bill_code'),
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data4);



            $this->load->library('upload');
            $cek = $this->input->post("bill");
            $getDoc = $this->admin_model->get_doc_bill_res()->result();
           

            $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                 "upload_path"   => "./upload/"
             ));

            if($this->upload->do_upload("bill"))
                {
                    $uploaded = $this->upload->data();     
           
                for($x=0;$x < count($getDoc);$x++)
                {
                    for($y=0;$y < count($getDoc);$y++)
                    {
                        if($x == $y)
                        {
                    $doc = array(
                    'id_application' => $this->input->post('id_application'),
                    'id_document_config' => $getDoc[$x]->id_document_config,
                    'status' => 'ACTIVE',
                    'created_date'=> date('y-m-d'),
                    'path_id' => $uploaded[$x]['full_path'],
                    'created_by' => $this->session->userdata('username')
                                );

                    $uploaded = $this->upload->data();
                    $this->admin_model->insert_doc_for_user($doc);
                   
                    

                        }

                    }
                }
           
                }
             
            redirect(base_url('dashboard'));       
  
    }

//yg ini belum
    public function REUPL_BILL_REQ($id_application_status)
    {
        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('admin_upload_biling_code_simponi', $data);
    }

    public function REUPL_BILL_REQ_PROSESS()
    {
        if ($this->input->post('setujui') == "setujui") {
            
            $data = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '6',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

             $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Reupload Billing Code SIMPONI',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '7',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '8',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $this->admin_model->insert_app_status($data3,$condition);

             $data4 = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '9',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $this->admin_model->insert_app_status($data4,$condition);

             $data5 = array(
                'process_status' => 'PENDING',
                'id_application_status_name' => '10',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $this->admin_model->insert_app_status($data5,$condition);

               $data3 = array(
                'id_application' => $this->input->post('id_application'),
                'id_document_config'=> '4',
                'path_id'=> '//// ini buat ganti code billingnya'
                );

            $this->admin_model->insert_application_file($data3);

            $data6 = array(
                    'type' => 'BILLING_EXPIRED',
                    'value' => 'contoh (29-08-2017)',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data6);


        }
    }















    public function VERIF_PAY_REQ($id_application_status)
    {
        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        // $this->load->view('admin_konfirmasi_assessment_lapangan', $data);
        $this->load->view('cek_bukti_transfer', $data);
    }

//bukti pembayaran diterima dan 
    public function VERIF_PAY_REQ_SUCCEST()
    {
        
        $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' verif bukti pembayaran',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_log($dataL);

            // $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '10',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            // $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '11',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            // $this->admin_model->insert_app_status($data3,$condition);

             $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '14',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            // $this->admin_model->insert_app_status($data4,$condition);

            $data5 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           // $this->admin_model->insert_app_sts_for_map($data5);

            $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_role').' memillih team assessment',
                'log_type' => 'added new team_assessment '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_log($dataL2);

           $data_ass_app = array(
            'id_application' => $this->input->post('id_application'),
            'assessment_date' => $this->input->post('expired_date'),
            'assessment_date' => '2017-12-12',
            'assessment_status' => 'OPEN',
            'created_date' => date('y-m-d'),
            'created_by' => $this->session->userdata('username')
            );

            $dataLass = array(
                'detail_log' => $this->session->userdata('admin_role').' adding new assesment_application',
                'log_type' => 'added '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_log($dataLass);

           $id_ass_app =  $this->admin_model->insert_assessment_application($data_ass_app);

            // $id_ass_app = $this->admin_model->get_assesment_application_byprm($this->input->post('id_application'));

            $team = $this->input->post('id_assessment_team');
            $title = $this->input->post('id_assessment_team_title');
            
            
            print_r($id_ass_app);
            echo "team = ".$team." . title = ".$title;

              for($x=0;$x < count($team);$x++)
                {
                    $dat = array(
                    'id_assessment_application' => $id_ass_app,
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );

                    if($this->admin_model->insert_assessment_registered($dat))
                    {
                        echo "save  Sucses";
                    }
                }

            $data6 = array(
                'id_document_config' => '26',
                'id_application' => $this->input->post('id_application'),
                'path_id' =>  $this->input->post(),
                'status' => 'ACTIVE',
                'created_date' => date('y-m-d')
                // 'created_by' => ''
                );
    }


    //bukti pembayaran revisi
    public function VERIF_PAY_REQ_REVISI()
    {       
         $dataAplicant  = $this->admin_model->get_applications();
        $Username = $this->session->userdata('username');

            //untuk menapilkan nama applicant yang akan disimpan di tabel log
            $id_app = $this->admin_model->get_applications_by_prm($dataAplicant->row()->id_application);
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' =>$dataAplicant->row()->id_application_status);

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi bukti pembayaran',
                'log_type' => 'added new applicant '.$Username, 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $Username
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $dataAplicant->row()->id_application,
                'process_status' => 'PENDING',
                'id_application_status_name' => '10',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $Username,
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);

            //pengecekan bukti transaksi dari user
            $cek = $this->admin_model->application_file_get_transaction($dataAplicant->row()->id_application);

            $data3 = array(
                'status' => 'INACTIVE',
                'modified_by' => $Username,
                'modified_date' => date('Y-m-j')
                );
            $id_app_file = array('id_application_file' => $cek->row()->id_application_file);
            $this->admin_model->application_file_update($id_app_file,$data3);

            $data4 = array(
                'type' => 'REJECTED Bukti Transfer',
                'value' => $this->input->post('coment'),
                'id_application_status'=> $dataAplicant->row()->id_application_status
                );
                
            $this->admin_model->insert_app_sts_for_map($data4);

            redirect(site_url('dashboard'));

    }
























































    public function VERIF_REV_PAY_REQ($id_application_status)
    {
        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        // $this->load->view('admin_konfirmasi_assessment_lapangan', $data);
        $this->load->view('cek_bukti_revisi_transfer', $data);
    }
//bukti revisi pembayaran di terima
    public function VERIF_REV_PAY_REQ_SUCCEST()
    {   
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Verifikasi Revisi Bukti Pembayaran',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '14',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);

            $data6 = array(
                    'type' => 'BILLING',
                    'value' => 'SUCCEST',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
            $this->admin_model->insert_app_sts_for_map($data6);

            $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_role').' memillih team assessment',
                'log_type' => 'added new team_assessment '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_log($dataL2);

            $data_ass_app = array(
            'id_application' => $this->input->post('id_application'),
            'assessment_date' => $this->input->post('expired_date'),
            'assessment_date' => '2017-12-12',
            'assessment_status' => 'OPEN',
            'created_date' => date('y-m-d'),
            'created_by' => $this->session->userdata('username')
            );

            $dataLass = array(
                'detail_log' => $this->session->userdata('admin_role').' adding new assesment_application',
                'log_type' => 'added '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataLass);

           $id_ass_app =  $this->admin_model->insert_assessment_application($data_ass_app);

            $team = $this->input->post('id_assessment_team');
            $title = $this->input->post('id_assessment_team_title');
            
            
            print_r($id_ass_app->row()->id_assessment_application);

              for($x=0;$x < count($team);$x++)
                {
                    $dat = array(
                    'id_assessment_application' => $id_ass_app.row()->id_assessment_application,
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );

                    if($this->admin_model->insert_assessment_registered($dat))
                    {
                        echo "save  Sucses";
                    }
                }
    }

    //revisi bukti revisi pembayaran yg direvisi
    public function VERIF_REV_PAY_REQ_REVISI()
    {
        
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Verifikasi Revisi Bukti Pembayaran',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '10',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);

            $data6 = array(
                    'type' => 'BILLING',
                    'value' => 'Revisi',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data6);

           echo "sukses revisi";

        
    }























































































































































	public function FIELD_ASSESS_REQ($id_application_status)
	{
        // $data['aplication_setujui'] = $this->admin_model->get_aplication_status($id_application_status)->result();
        $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        echo json_encode($data);
        $this->load->view('input_tim_asesmen', $data);
	}

    //input tim asesment dan tgl asesment
	public function FIELD_ASSESS_REQ_SUCCEST()
	{
       if($this->input->post('setujui') == "setujui")
        {	
        	$data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' tim asesment',
                'log_type' => 'added new applicant '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

        	  $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '12',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data2,$condition);

             $data3 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data3);

           // $data4 = array(
           //      'id_application' => $this->input->post('id_application'),
           //      'assessment_date' => $this->input->post('assessment_date'),
           //      'assessment_status' => 'OPEN',
           //      'created_date' => date('y-mj'),
           //      // 'created_by' => $this->session->userdata('username'),

           //  );
           //  $this->admin_model->insert_application_file($data4);


            //  $data5 = array(
            //         'id_document_config' => '',
            //         'path_id' => '///',
            //         'id_application_status'=> $this->input->post('id_application_status')
            //         );
            // $this->admin_model->insert_application_file($data5);

             $data6 = array(
                    'id_application' => $this->input->post('id_application'),
                    // 'assessment_date' => $this->input->post('assessment_date'),
                    'assessment_status'=> 'OPEN',
                    'created_date' => date('y-m-d')
                    // 'created_by' => $this->session->username('username')
                    );
                $this->admin_model->insert_assessment_application($data6);

                // $id = $this->input->post('id_application');
                // $cekApp = $this->admin_model->get_assesment_application($id); 

                // $id_ass_app = $cekApp->row()->id_assessment_application;

                // $data7 = array(
                //         'id_assessment_application' => $id_ass_app,
                //         'id_assessment_team' => $this->input->post('id_assessment_team'),
                //         'id_assessment_team_title'=> $this->input->post('id_assessment_team_title')
                //         );
                // $this->admin_model->insert_assessment_registered($data7);





        	
        }else
        {
        	echo "bukan tombol setujui";
        }
	}











public function REV_ASSESS_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('input_revisi_tim_asesmen', $data);
    }
//input revisi tim asesmen
public function REV_ASSESS_REQ_PROSESS()
    {
           
                $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' tim asesment',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '12',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

           
            $this->admin_model->insert_app_status($data2,$condition);

             $data3 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data3);

           $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_role').' memillih team assessment',
                'log_type' => 'added new team_assessment '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            // $this->admin_model->insert_log($dataL2);

           $this->assesment_application();

            $this->admin_model->get_assesment_application_byprm($this->input->post('id_application'));

            $team = $this->input->post('id_assessment_team');
            $title = $this->input->post('id_assessment_team_title');
            
              for($x=0;$x < count($team);$x++)
                {
                    $dat = array(
                    'id_assessment_application' => $this->input->post('id_application'),
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );

                    if($this->admin_model->insert_assessment_registered($dat))
                    {
                        echo "save  Sucses";
                    }
                }   
      
    }


























































    public function UPL_RES_ASSESS_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('hasil_asesment_lapangan', $data);
    }
    //hasil asesment lapangan ketika sudah selesai dan benar upload 2 document
    public function UPL_RES_ASSESS_REQ_SUCCESS()
    {          
      
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Approve Hasil Asesment',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '16',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data2,$condition);

             $data3 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '17',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data3,$condition);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '18',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data4,$condition);

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data5);

            $this->load->library('upload');

            $docIn = $this->input->post("bill");
            $getDoc = $this->admin_model->get_news_for_user()->result();

           $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                 "upload_path"   => "./upload/"
             ));
           
            if($this->upload->do_upload("bill"))
                {
                    echo "sucses";
                    $uploaded = $this->upload->data();     
           
                    for($x=0;$x < count($getDoc);$x++)
                    {
                        $doc = array(
                            'id_application' => $this->input->post('id_application'),
                            'id_document_config' => $getDoc[$x]->id_document_config,
                            'status' => 'ACTIVE',
                            'created_date'=> date('y-m-d'),
                            'path_id' => $uploaded[$x]['full_path'],
                            'created_by' => $this->session->userdata('username')
                        );
                        echo json_encode($doc);
                        $this->admin_model->insert_doc_for_user($doc);
                  
                    }

           
                }
        
    }

    public function UPL_RES_ASSESS_REQ_REVISI()
    {
        if ($this->input->post('revisi') == "revisi") 
        {
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' hasil asesment',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '16',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data4,$condition);

            $data5 = array(
                    'type' => 'REVITION',
                    'value' => '(dokumen apa aja)',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data5);

        }

    }

































































    public function VERIF_REV_ASSESS_RES_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('rev_doc_asesmen', $data);
    }

    public function VERIF_REV_ASSESS_RES_REQ_PROSES()
    {
        
            
           $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' hasil asesment',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '18',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data3);

        
    }

    public function VERIF_REV_ASSESS_RES_REQ_REVISI()
    {
       
            
           $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi hasil asesment',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '16',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                    'type' => 'REJECT',
                    'value' => 'REVISI document',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data3);

        
    }














   

    public function CRA_APPROVAL_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('proses_permohonan_CRA', $data);
    }

    public function CRA_APPROVAL_REQ_PROSES()
    {
        
            
               $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' approve ',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '19',
             
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));
           
            $this->admin_model->insert_app_status($data2,$condition);

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data5);
        
    }

    public function UPL_IIN_DOC_REQ($id_application_status)
    {

         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('upload_iin', $data);
    }

    public function UPL_IIN_DOC_REQ_PROSES()
    {
         $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => date('Y-m-j'),
                // 'created_by' => $this->session->userdata('username'),
                'last_updated_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' upload IIN ',
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status')
                    );
           $this->admin_model->insert_app_sts_for_map($data5);
    }   

   




    //untuk insert document type on demend
    public function insert_doc_for_user()
    {   
        $data = array(
            'type' => $this->input->post('type'),
            'key' => $this->input->post('key'),
            'display_name' => $this->input->post('display_name'),
            'file_url' => $this->input->post('file_url'),
            'mandatory' => $this->input->post('mandatory'),
            'created_date' => $this->date('y-m-d')
            // 'created_by' => $this->session->userdata('nama')

            );
        $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Upload refisi tim asessment  ',
                'log_type' => 'added '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->insert_document_config($data);
    }

    public function send_mail($prm)
    {   
        
        echo "prm".$prm;
        $cek = $this->admin_model->get_data_for_mail($prm);
        
            // echo "jumlah ".$cek->num_rows();
            echo "email user = ".$cek->row()->email;
            echo "email kantor = ".$cek->row()->instance_email;
            echo "user name = ".$cek->row()->username;
        

       
        if($this->usr_model->sendMail($cek->row()->email,$cek->row()->username, "Please click on the below activation link to verify your email address."))
        {
             $this->usr_model->sendMail($cek->row()->instance_email,$cek->row()->username);
            echo "terkirim";
        }else
        {
            echo "tidak terkirim";
        }
    }

    public function get_report_excel()
    {
        $data['report']=$this->admin_model->get_admin()->result();
        $this->load->view('excel_import',$data);
        // echo json_encode($data);
    }

    public function get_doc($prm)
    {
        $query = $this->admin_model->get_doc_for_user()->result();
        // echo json_encode($data);
                    
        for($x = 0; $x < count($query); $x++)
        {
            $data = array(
                'id_application' => $prm,
                'id_document_config' => $query[$x]->id_document_config,
                'status' => 'ACTIVE',
                'created_date'=> date('y-m-d'),
                'created_by' => $this->session->userdata('username')
                );
            $this->admin_model->insert_doc_for_user($data);

        }
    }

    function do_upload() {
        $this->load->library('upload');
        $this->upload->initialize(array("allowed_types" => "gif|jpg|PNG|jpeg|pdf|doc", "upload_path" => "./upload/"));
        //Perform upload.
        if($this->upload->do_upload("bill")) {
            echo '<script>console.log('.var_export($this->upload->data()).');</script>';

            $admin_name     = 'Rinaldy Sam';
            $doc_step       = 'verif_upldoc_req';
            $doc_step_name  = 'Verifikasi Kelengkapan Dokumen';
            /*Insert Log document Revisi*/
            // write_log($admin_name, $doc_step, 'do upload documents');
            // $upload_data = array(
            //     'id_application '=> $get_documen->row->id_application,
            //     'id_application_status_name' => $doc_step,
            //     'process_status' => 'PENDING',
            //     'approval_date' => 'null',
            //     'created_date' => date('Y-m-j'),
            //     'created_by' => $username,
            //     'modified_by' => $username,
            //     'last_updated_date' => date('Y-m-j'));
            // $this->admin_model->insert_app_status($upload_data);
        } else {
            die('GAGAL UPLOAD');
      }
    }

}