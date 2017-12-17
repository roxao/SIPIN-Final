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

    public function date_time_now() {
        /*
        SET TIMEZONE ASIA/JAKARTA
        */
        $datetime = new DateTime('Asia/Jakarta');
        return $datetime->format('Y\-m\-d\ h:i:s');
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
        $user = $this->input->post('created_by');
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
      
                $data = array(
                'process_status' => 'COMPLETED',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Verify Application by : '.$user,
                'log_type' => 'New Application Verification', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                'id_application'=> $this->input->post('id_application'),
                'id_application_status_name' => '2',
                'process_status ' => 'PENDING',
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            
            $this->admin_model->insert_app_status($data2);

            $data4 = array(
                    'type' => 'APPROVED',
                    'id_application_status' => $this->input->post('id_application_status'),
                    'value' => 'APPROVED',
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
            $this->admin_model->insert_app_sts_for_map($data4);

            $message = ADMNTFSTEP0;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));
             redirect(base_url('dashboard'));
            
        
    }


    //tolak pengajuan karena kesalahan sesuatu
    public function VERIF_NEW_REQ_ETC()
    {
        $user = $this->input->post('created_by');
         // ditolak dll

        //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

        
        
            $data = array(
                'process_status' => 'REJECTED',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Rejected Application by : '.$user,
                'log_type' => 'New Application Rejected', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            if('OTHER'==$this->input->post('rejectionType')){
                $data4 = array(
                    'type' => 'REJECTED',
                    'value' => $this->input->post('coment'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );

                //kirim notifikasi

                $message = ADMNTFSTEP0;
                $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            }else{
                
                $data4 = array(
                    'type' => 'REJECTED',
                    'value' => $this->input->post('iinExisting'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                $iin = array(
                    'iin_number' => $this->input->post('iinExisting'),
                    'modified_by' => $this->session->userdata('admin_username'),
                    'modified_date' => $this->date_time_now()
                    );
                $id_user = array('id_user' => $this->input->post('id_user'));
                $this->admin_model->update_iin($id_user, $iin);
            }
            $this->admin_model->insert_app_sts_for_map($data4);
                    
            //         $data5 = array(
            //             'iin_status'=> 'CLOSED');
            // $id_application = array('id_application'=> $this->input->post('id_application'));
            // $this->admin_model->update_applications($data5,$id_application);

            $this->send_mail($this->input->post('id_application'));
           redirect(base_url('dashboard'));
        
    }

























//setujui kelemngkapan document dari user
    public function VERIF_UPLDOC_REQ_PROSES_SUCCEST()
    {
        $user = $this->input->post('created_by');
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' approved new document by : '.$user,
                'log_type' => 'Document Verifyed '.$id_app->row()->applicant, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            // $data4 = array(
            //      'id_application '=> $this->input->post('id_application'),
            //     'process_status' => 'COMPLETED',
            //     'id_application_status_name' => '4',
             
            //     'created_date' => $this->date_time_now(),
            //     'created_by' => $this->session->userdata('admin_username'));

           
            // $this->admin_model->insert_app_status($data4,$condition);

            // $data5 = array(
            //      'id_application '=> $this->input->post('id_application'),
            //     'process_status' => 'COMPLETED',
            //     'id_application_status_name' => '5',
             
            //     'created_date' => $this->date_time_now(),
            //     'created_by' => $this->session->userdata('admin_username'));

           
            // $this->admin_model->insert_app_status($data5,$condition);

            $data6 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '6',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $this->admin_model->insert_app_status($data6,$condition);

                  $data2 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
           $this->admin_model->insert_app_sts_for_map($data2);

            $message = ADMNTFSTEP2;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

           $this->send_mail($this->input->post('id_application'));

           redirect(base_url('dashboard'));

    }

//revisi document untuk user
    public function VERIF_UPLDOC_REQ_PROSES_REVITIONS()
    {
        $user = $this->input->post('created_by');
            //untuk menapilkan nama applicant yang akan disimpan di tabel log
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '3',
              
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Revised Application Files by : '.$user,
                'log_type' => 'Revised Document '.$id_app->row()->applicant, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

             $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '4',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $id_app_st = $this->admin_model->insert_app_status($data4);


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
                        'type' => 'REVISED_DOC'.$doc[$i],
                        'value' => $doc[$i],
                        'id_application_status'=> $id_app_st,
                        'created_by' => $this->session->userdata('admin_username'),
                        'created_date' => $this->date_time_now()
                        );
                        //insert ke tabel application form mapping
                         $this->admin_model->insert_app_sts_for_map($data2);

                        $id_app_file = array('id_application_file' => $apf->row()->id_application_file);
                        
                        $data3 = array(
                            'status' => 'INACTIVE',
                            'modified_date' => $this->date_time_now(),
                            'modified_by' => $this->session->userdata('admin_username')
                        );
                        //update applications file untuk direfisi
                        $this->admin_model->application_file_update($id_app_file, $data3);

                    }
             }

            $message = ADMNTFSTEP2REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

             $this->send_mail($this->input->post('id_application'));

            redirect(base_url('dashboard'));
    }

   


   

//revisi dokumen disetujui
   public function VERIF_REVDOC_REQ_PROSES()
   {
            $user = $this->input->post('created_by');
            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' approve revisi dokumen by : '.$user,
                'log_type' => 'Revised Document '.$id_app->ROW()->applicant, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '6',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $this->admin_model->insert_app_status($data2,$condition);
            
             $data3 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
           $this->admin_model->insert_app_sts_for_map($data3);    

           $message = ADMNTFSTEP2REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

           $this->send_mail($this->input->post('id_application'));

            redirect(base_url('dashboard'));

   }

//revisi dokumen kembali jika ada kesalahan dokumen 
   public function VERIF_REVDOC_REQ_REVITION()
   {
            $user = $this->input->post('created_by');
            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

            $data = array(
                'process_status' => 'COMPLETED',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi dokumen by : '.$user,
                'log_type' => 'Revised Document '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

                $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '4',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('admin_username'));

           
            $id_app_sts = $this->admin_model->insert_app_status($data2,$condition);
            
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
                        'type' => 'REVISED_DOC'.$doc[$i],
                        'value' => $doc[$i],
                        'id_application_status'=> $id_app_sts,
                        'created_date' => $this->date_time_now(),
                        'created_by' => $this->session->userdata('admin_username')
                        );
                        //insert ke tabel application form mapping
                         $this->admin_model->insert_app_sts_for_map($data2);

                        $id_app_file = array('id_application_file' => $apf->row()->id_application_file);
                        
                        $data3 = array(
                            'status' => 'INACTIVE',
                            'modified_date' => date('y-m-d'),
                            'modified_by' => $this->session->userdata('admin_username')
                        );
                        //update applications file untuk direfisi
                        $this->admin_model->application_file_update($id_app_file, $data3);

                    }
             }

             
           $message = ADMNTFSTEP2REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

             $this->send_mail($this->input->post('id_application'));

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
            $user = $this->input->post('created_by');
            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

                $data = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '6',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Upload Billing Code SIMPONI for : '.$user,
                'log_type' => 'Billing Simponi ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);
            $this->admin_model->next_step($data,$condition);

             $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '7',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

            $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                'id_application' => $this->input->post('id_application'),
                'id_document_config'=> '23',
                'path_file'=> '/',
                'status' => 'ACTIVE'
                );

            // $this->admin_model->insert_application_file($data3);

            $dataCode = array(
                    'type' => 'BILLING_CODE',
                    'value' => $this->input->post('app_bill_code'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($dataCode);

            $dataDate = array(
                    'type' => 'BILLING_DATE',
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($dataDate);


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
                    'created_date'=> $this->date_time_now(),
                    'path_file' => $uploaded[$x]['full_path'],
                    'created_by' => $this->session->userdata('admin_username')
                                );

                    $uploaded = $this->upload->data();
                    $this->admin_model->insert_doc_for_user($doc);

                    $dataDoc = array(
                    'type' => 'BILLING_DOC',
                    'value' => $getDoc[$x]->keys,
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
                     $this->admin_model->insert_app_sts_for_map($dataDoc);
                   
                    

                        }

                    }
                }
           
                }
            
            $message = ADMNTFSTEP3;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

             $this->send_mail($this->input->post('id_application'));

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
        
            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '8',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

             $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Reupload Billing Code SIMPONI by : '.$this->session->userdata('admin_username'),
                'log_type' => 'Billing simponi Ulang '.$this->input->post('username'), 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '7',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

            $this->admin_model->insert_app_status($data2,$condition);



           $dataCode = array(
                    'type' => 'BILLING_CODE',
                    'value' => $this->input->post('app_bill_code'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($dataCode);

            $dataDate = array(
                    'type' => 'BILLING_DATE',
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($dataDate);



            $this->load->library('upload');
            $cek = $this->input->post("bill");
            $getDoc = $this->admin_model->get_doc_bill_res()->result();

            
                       
            for($d=0;$d < count($getDoc);$d++)
                {
                    $this->admin_model->reupl_bil_rec_update($this->input->post('id_application'),$getDoc[$d]->id_document_config);        
                }


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
                            'path_file' => $uploaded[$x]['full_path'],
                            'created_by' => $this->session->userdata('admin_username')
                            );

                            $uploaded = $this->upload->data();
                            $this->admin_model->insert_doc_for_user($doc); 

                            $dataDoc = array(
                            'type' => 'BILLING_DOC',
                            'value' => $getDoc[$x]->keys,
                            'id_application_status'=> $this->input->post('id_application_status'),
                            'created_date' => $this->date_time_now(),
                            'created_by' => $this->session->userdata('admin_username')
                            );
                            $this->admin_model->insert_app_sts_for_map($dataDoc);
                        }
                    }
                }
           
                }
            
            $message = ADMNTFSTEP3REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));

            redirect(base_url('dashboard'));
        
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
        $usr_id = $this->admin_model->get_user_application_data($this->input->post('id_application'));
        $usrnme = $this->admin_model->get_user_by_prm($usr_id->row()->id_user);
        $user = $this->input->post('created_by');
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

        

        $condition = array('id_application_status' => $this->input->post('id_application_status'));
           
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' verif bukti pembayaran by : '.$user,
                'log_type' => 'BUkti bayar ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            
        $this->admin_model->insert_log($dataL);

        $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '10',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
   
        $this->admin_model->insert_app_status($data2);

            $data3 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '11',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
        $this->admin_model->insert_app_status($data3);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '12',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
        $id_apps_last = $this->admin_model->insert_app_status($data4);

            $data5 = array(
                    'type' => 'APPROVED',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );

        $this->admin_model->insert_app_sts_for_map($data5);

            $dataDate = array(
                    'type' => 'ASSESSMENT_DATE',
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $id_apps_last,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
            $this->admin_model->insert_app_sts_for_map($dataDate);


            $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_role').' memillih team assessment by : '.$user,
                'log_type' => 'added team_assessment ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

        $this->admin_model->insert_log($dataL2);

            $data_ass_app = array(
                'id_application' => $this->input->post('id_application'),
                'assessment_date' => date_format(date_create($this->input->post('expired_date')), 'Y-m-d'),
                'assessment_status' => 'OPEN',
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

            $dataLass = array(
                'detail_log' => $this->session->userdata('admin_role').' adding new assesment_application by : '.$user,
                'log_type' => 'added '.$usrnme->row()->username, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

        $this->admin_model->insert_log($dataLass);

            $id_ass_app =  $this->admin_model->insert_assessment_application($data_ass_app);



            $team = $this->input->post('assessment_name');
            $title = $this->input->post('assessment_title');
            
            

            for($x=0;$x < count($team);$x++){
                    $dat = array(
                    'id_assessment_application' => $id_ass_app,
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );

                    $this->admin_model->insert_assessment_registered($dat);
                }
        $this->load->library('upload');
 
        //Configure upload.
        $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                 "upload_path"   => "./upload/"
                ));

            $getLetterAssigment = $this->admin_model->get_letter_of_assignment()->result();
             //Perform upload.
            if($this->upload->do_upload("images")) {
                $uploaded = $this->upload->data();
                
                for($y=0;$y < 2;$y++)
                {
                    $data6 = array(
                    'id_document_config' => $getLetterAssigment[$y]->id_document_config,
                    'id_application' => $this->input->post('id_application'),
                    'path_file' =>  $uploaded[$y]['full_path'],
                    'status' => 'ACTIVE',
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );

                    $this->admin_model->insert_application_file($data6);

                $dataDocAss = array(
                    'type' => 'ASSESSMENT_DOC',
                    'value' => $getLetterAssigment[$y]->keys,
                    'id_application_status'=> $id_apps_last,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                $this->admin_model->insert_app_sts_for_map($dataDocAss);    
                }
                

              } 

            $message = ADMNTFSTEP5;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

              $this->send_mail($this->input->post('id_application'));

            redirect(site_url('dashboard'));
    }
        

    


    //bukti pembayaran revisi
    public function VERIF_PAY_REQ_REVISI()
    {       
        
        $usr_id = $this->admin_model->get_user_application_data($this->input->post('id_application'));
        $usrnme = $this->admin_model->get_user_by_prm($usr_id->row()->id_user);
         $user = $this->input->post('created_by');

         //untuk menapilkan nama applicant yang akan disimpan di tabel log
            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));

            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Revised Billing Code SIMPONI For : '.$user,
                'log_type' => 'Revised Document ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '10',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $id_sts = $this->admin_model->insert_app_status($data2,$condition);

            //pengecekan bukti transaksi dari user
            $cek = $this->admin_model->application_file_get_transaction($this->input->post('id_application'));

            $data3 = array(
                'status' => 'INACTIVE',
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now()
                );
            $id_app_file = array('id_application_file' => $cek->row()->id_application_file);
            $this->admin_model->application_file_update($id_app_file,$data3);

            $data4 = array(
                'type' => 'REVISED_PAY',
                'value' => $this->input->post('coment'),
                'id_application_status'=> $id_sts,
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
                
            $this->admin_model->insert_app_sts_for_map($data4);
            
            $message = ADMNTFSTEP5REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));

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
             $usr_id = $this->admin_model->get_user_application_data($this->input->post('id_application'));
        $usrnme = $this->admin_model->get_user_by_prm($usr_id->row()->id_user);
        $user = $this->input->post('created_by');
        $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Verify Revision Paymen by : '.$user,
                'log_type' => 'Revised Paymen ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '12',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $id_sts = $this->admin_model->insert_app_status($data2,$condition);

            $data6 = array(
                    'type' => 'BILLING',
                    'value' => 'SUCCEST',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
            $this->admin_model->insert_app_sts_for_map($data6);

            $dataDate = array(
                    'type' => 'ASSESSMENT_DATE',
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $id_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
            $this->admin_model->insert_app_sts_for_map($dataDate);

            $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_username').' Submit Team Assessment for : '.$user,
                'log_type' => 'Added Team Assessment ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

        $this->admin_model->insert_log($dataL2);

            $data_ass_app = array(
                'id_application' => $this->input->post('id_application'),
                'assessment_date' => date_format(date_create($this->input->post('expired_date')), 'Y-m-d'),
                'assessment_status' => 'OPEN',
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

           

            $dataLass = array(
                'detail_log' => $this->session->userdata('admin_username').' Submit Assessment Applications for : '.$user,
                'log_type' => 'Submit Assessment Application', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

        $this->admin_model->insert_log($dataLass);

            $id_ass_app =  $this->admin_model->insert_assessment_application($data_ass_app);



            $team = $this->input->post('assessment_name');
            $title = $this->input->post('assessment_title');
            
            

            for($x=0;$x < count($team);$x++){
                    $dat = array(
                    'id_assessment_application' => $id_ass_app,
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );

                    $this->admin_model->insert_assessment_registered($dat);
                }
        $this->load->library('upload');
 
        //Configure upload.
        $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                 "upload_path"   => "./upload/"
                ));

            $getLetterAssigment = $this->admin_model->get_letter_of_assignment()->result();
             //Perform upload.
            if($this->upload->do_upload("images")) {
                $uploaded = $this->upload->data();
                
                for($y=0;$y < 2;$y++)
                {
                    $data6 = array(
                    'id_document_config' => $getLetterAssigment[$y]->id_document_config,
                    'id_application' => $this->input->post('id_application'),
                    'path_file' =>  $uploaded[$y]['full_path'],
                    'status' => 'ACTIVE',
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );

                    $this->admin_model->insert_application_file($data6);

                $dataDocAss = array(
                    'type' => 'ASSESSMENT_DOC',
                    'value' => $getLetterAssigment[$y]->keys,
                    'id_application_status'=> $id_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                $this->admin_model->insert_app_sts_for_map($dataDocAss);    
                }
                

              } 

              $message = ADMNTFSTEP5REV;
                $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

              $this->send_mail($this->input->post('id_application'));

            redirect(site_url('dashboard'));
    }

    //revisi bukti revisi pembayaran yg direvisi
    public function VERIF_REV_PAY_REQ_REVISI()
    {   
             $user = $this->input->post('created_by');
             $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').'  Revisi Bukti Pembayaran by : '.$user,
                'log_type' => 'Revisi Payment '.$this->input->post('username'), 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($dta,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '10',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
            $id_sts = $this->admin_model->insert_app_status($data2,$condition);

            $data6 = array(
                    'type' => 'REVISED_PAY',
                    'value' => $this->input->post('coment'),
                    'id_application_status'=> $id_sts,
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($data6);

           $message = ADMNTFSTEP5REV;
                $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));

           redirect(site_url('dashboard'));

        
    }























































































































































	

    //input dokumen penugasan tim asesment dan tgl asesment
	public function FIELD_ASSESS_REQ_SUCCEST()
	{
         $user = $this->input->post('created_by');

     	$id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
        	$data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => date('Y-m-j H:i:s'));

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' dokumen penugasan tim asesment by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added new applicant '.$id_app->row()->applicant, 
                'created_date' => date('Y-m-j H:i:s'),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

        	  $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '15',
             
                'created_date' => date('Y-m-j'),
                'created_by' => $this->session->userdata('admin_username'));
           
            $id_app_sts = $this->admin_model->insert_app_status($data2,$condition);

             $data3 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $id_app_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                     'created_date' => $this->date_time_now()
                    );
            $this->admin_model->insert_app_sts_for_map($data3);

            //upload file penugasan
            $this->load->library('upload');
 
            //Configure upload.
            $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                 "upload_path"   => "./upload/"
                ));

            //mencari documen usulan tim asessmen lapangan dan surat informasi tim asessmen
            $getLetterAssigment = $this->admin_model->get_letter_of_assignment_SPTAL()->result();
           
             //Perform upload.
            if($this->upload->do_upload("doc")) {
                $uploaded = $this->upload->data();
                
                for($y=0; $y< count($getLetterAssigment);$y++)
                {
                    $data6 = array(
                    'id_document_config' => $getLetterAssigment[$y]->id_document_config,
                    'id_application' => $this->input->post('id_application'),
                    'path_file' =>  $uploaded['full_path'],
                    'status' => 'ACTIVE',
                    'created_date' => date('y-m-d'),
                    'created_by' =>  $this->session->userdata('admin_username')
                    );
                            
                    $this->admin_model->insert_application_file($data6); 

                    $dataDocAss = array(
                    'type' => 'ASSESSMENT_DOC',
                    'value' => $getLetterAssigment[$y]->keys,
                    'id_application_status'=> $id_app_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                    $this->admin_model->insert_app_sts_for_map($dataDocAss);      
                }
                

              }

              $message = ADMNTFSTEP7;
                $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

              $this->send_mail($this->input->post('id_application'));

              redirect(site_url('dashboard')); 
   
	}












    //input revisi tim asesmen
    public function REV_ASSESS_REQ_PROSESS()
    {
         $user = $this->input->post('created_by');
         $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));

        $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());
        $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_username').' Revised Assessment Team for : '.$user,
                'log_type' => 'Assessment Team  ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

        $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '12',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));

           
        $id_app_sts_lst = $this->admin_model->insert_app_status($data2);

        $data3 = array(
                    'type' => 'ASSESSMENT_DATE',
                    'value' => $this->input->post('expired_date'),
                    'id_application_status'=> $id_app_sts_lst,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
        $this->admin_model->insert_app_sts_for_map($data3);

        $dataL2 = array(
                'detail_log' => $this->session->userdata('admin_username').' Submit Team Assessment for: '.$user,
                'log_type' => 'Added Team Assessment ', 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
        $this->admin_model->insert_log($dataL2);

        //input asessment applications

        $data_ass_app = array(
                'id_application' => $this->input->post('id_application'),
                'assessment_date' => date_format(date_create($this->input->post('expired_date')), 'Y-m-d'),
                'assessment_status' => 'OPEN',
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );

        $id_ass_app =  $this->admin_model->insert_assessment_application($data_ass_app);

        $team = $this->input->post('assessment_name');
        $title = $this->input->post('assessment_title');
        
            for($x=0;$x < count($team);$x++)
            {
                $dat = array(
                    'id_assessment_application' => $id_ass_app,
                    'id_assessment_team' => $team[$x],
                    'id_assessment_team_title' => $title[$x]
                                );
                
                $this->admin_model->insert_assessment_registered($dat);
            }

        $this->load->library('upload');
 
        //Configure upload.
        $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                 "upload_path"   => "./upload/"
                ));

        //mencari documen usulan tim asessmen lapangan dan surat informasi tim asessmen
        $getLetterAssigment = $this->admin_model->get_letter_of_assignment()->result();

         //Perform upload.
        if($this->upload->do_upload("images")) 
        {
                $uploaded = $this->upload->data();
                for ($i=0; $i < count($getLetterAssigment); $i++) 
                { 
                    # code...
                    $data6 = array(
                    'id_document_config' => $getLetterAssigment[$i]->id_document_config,
                    'id_application' => $this->input->post('id_application'),
                    'path_file' =>  $uploaded[$i]['full_path'],
                    'status' => 'ACTIVE',
                    'created_date' => $this->date_time_now(),
                    'created_by' =>  $this->session->userdata('admin_username')
                    );
                    
                    $this->admin_model->insert_application_file($data6);

                    $dataDocAss = array(
                    'type' => 'ASSESSMENT_DOC',
                    'value' => $getLetterAssigment[$i]->keys,
                    'id_application_status'=> $id_app_sts_lst,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                    $this->admin_model->insert_app_sts_for_map($dataDocAss);
                }
        }
        $message = ADMNTFSTEP6REV;
        $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

        $this->send_mail($this->input->post('id_application'));

        redirect(site_url('dashboard'));

    }

           
           

            






    public function UPL_RES_ASSESS_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('hasil_asesment_lapangan', $data);
    }

    public function UPL_RES_ASSESS_REQ_SUCCESS()
    {          
             $user = $this->input->post('created_by');

            $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' Approve Hasil Asesment by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added  '.$id_app->row()->applicant, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '16',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
            $this->admin_model->insert_app_status($data2,$condition);

             $data3 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'COMPLETED',
                'id_application_status_name' => '17',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
            $this->admin_model->insert_app_status($data3,$condition);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '18',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
           $id_apps_sts_lst = $this->admin_model->insert_app_status($data4,$condition);

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
           $this->admin_model->insert_app_sts_for_map($data5);

            $this->load->library('upload');

            
            $getDoc = $this->admin_model->get_news_for_user()->result();
            
           $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                 "upload_path"   => "./upload/"
             ));
           
            if($this->upload->do_upload('bill'))
                {
                   
                    $uploaded = $this->upload->data();     

                    
           
                    for($x=0;$x < count($getDoc);$x++)
                    {
                        
                        $doc = array(
                            'id_application' => $this->input->post('id_application'),
                            'id_document_config' => $getDoc[$x]->id_document_config,
                            'status' => 'ACTIVE',
                            'created_date'=> $this->date_time_now(),
                            'path_file' => $uploaded[$x]['full_path'],
                            'created_by' => $this->session->userdata('admin_username')
                        );
                       
                        $this->admin_model->insert_doc_for_user($doc);

                        $dataDocAss = array(
                        'type' => 'ASSESSMENT_DOC',
                        'value' => $getDoc[$x]->keys,
                        'id_application_status'=> $id_apps_sts_lst,
                        'created_by' => $this->session->userdata('admin_username'),
                        'created_date' => $this->date_time_now()
                        );
                        $this->admin_model->insert_app_sts_for_map($dataDocAss);
                  
                    }

           
                }

                $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user);

                $this->send_mail($this->input->post('id_application'));

        redirect(site_url('dashboard'));
    }

//revisi untuk hasil asessment lapangan
    public function UPL_RES_ASSESS_REQ_REVISI()
    {
         $user = $this->input->post('created_by');
             $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
            $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi hasil asesment by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added  '.$id_app->row()->applicant, 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data4 = array(
                 'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '16',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
            $id_app_sts = $this->admin_model->insert_app_status($data4,$condition);

            $display_name_doc = $this->input->post('doc');

            
            for($x=0;$x < count($this->input->post("doc"));$x++)
            {
                

                $cek_doc = $this->admin_model->get_doc_conf_by_name($display_name_doc[$x]);

                
                if($cek_doc->num_rows() == 0)
                {   

                    $data_doc = array(
                        'type'=> 'TRANSACTIONAL',
                        'keys'=> $this->gen_uuid(),
                        'display_name'=> $display_name_doc[$x],
                        'created_date'=> $this->date_time_now(),
                        'created_by'=> $this->session->userdata('admin_username'));
                    $id_doc_new = $this->admin_model->insert_document_config($data_doc);

                    $data_doc_l = array(
                        'detail_log' => $this->session->userdata('admin_role').' input document config '.$display_name_doc[$x].' by : '.$this->session->userdata('admin_username'),
                        'log_type' => 'added  doc '.$display_name_doc[$x], 
                        'created_date' => $this->date_time_now(),
                        'created_by' => $this->session->userdata('admin_username')
                        );
                    $this->admin_model->insert_log($data_doc_l);

                    $data5 = array(
                    'type' => 'REV_DOC_ASM',
                    'value' => $id_doc_new,
                    'id_application_status'=> $id_app_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                    
                    $this->admin_model->insert_app_sts_for_map($data5);

                }else
                {
                    
                    $data5 = array(
                    'type' => 'REV_DOC_ASM',
                    'value' => $cek_doc->row()->id_document_config,
                    'id_application_status'=> $id_app_sts,
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                
                    $this->admin_model->insert_app_sts_for_map($data5);

                    $app_file_data = array(
                        'status' => 'INACTIVE',
                        'modified_date' => $this->date_time_now(),
                        'modified_by' => $this->session->userdata('admin_username')
                        );

                    //app file search dgn 2 parameter yaitu id_application dan id_document_config
                    $apf = $this->admin_model->application_file_get_by_idapp_iddc($this->input->post('id_application'),$cek_doc->row()->id_document_config);
                    $id_app_file = array('id_application_file'=> $apf->row()->id_application_file);
                    //update applications file untuk direfisi                    
                    $this->admin_model->application_file_update($id_app_file, $app_file_data);
                }

            }

            $message = ADMNTFSTEP7REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));

             redirect(site_url('dashboard'));

    }


    function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

































































    public function VERIF_REV_ASSESS_RES_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('rev_doc_asesmen', $data);
    }

    public function VERIF_REV_ASSESS_RES_REQ_PROSES()
    {
        
             $user = $this->input->post('created_by');
             $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
           $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' hasil asesment by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '18',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
          $id_app_sts_lst = $this->admin_model->insert_app_status($data2,$condition);

            $data3 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
           $this->admin_model->insert_app_sts_for_map($data3);

           

            $this->load->library('upload');
           $getDoc = $this->admin_model->get_news_for_user()->result();
            
           $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                 "upload_path"   => "./upload/"
             ));
           
            if($this->upload->do_upload('bill'))
                {
                   
                    $uploaded = $this->upload->data();     

                    
           
                    for($x=0;$x < count($getDoc);$x++)
                    {
                        
                        $doc = array(
                            'id_application' => $this->input->post('id_application'),
                            'id_document_config' => $getDoc[$x]->id_document_config,
                            'status' => 'ACTIVE',
                            'created_date'=> $this->date_time_now(),
                            'path_file' => $uploaded[$x]['full_path'],
                            'created_by' => $this->session->userdata('admin_username')
                        );
                       
                        $this->admin_model->insert_doc_for_user($doc);

                        $dataDocAss = array(
                        'type' => 'ASSESSMENT_DOC',
                        'value' => $getDoc[$x]->id_document_config,
                        'id_application_status'=> $id_app_sts_lst,
                        'created_by' => $this->session->userdata('admin_username'),
                        'created_date' => $this->date_time_now()
                        );
                        $this->admin_model->insert_app_sts_for_map($dataDocAss);
                  
                    }

           
                }


           
            $message = ADMNTFSTEP8;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

           $this->send_mail($this->input->post('id_application'));

           redirect (site_url('dashboard'));

        
    }

    public function VERIF_REV_ASSESS_RES_REQ_REVISI()
    {
       
             $user = $this->input->post('created_by');
             $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
           $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' revisi hasil asesment by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '16',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
            $id_app_sts = $this->admin_model->insert_app_status($data2,$condition);



           //id_document config dari inputan
           $doc = $this->input->post("docRef");

             for ($i=0; $i < count($doc); $i++) { 

                    //app file search dgn 2 parameter yaitu id_application dan id_document_config
                    $apf = $this->admin_model->application_file_get_by_idapp_iddc($this->input->post('id_application'),$doc[$i]);
                    
                    //data untuk insert ke tabel applications_form_mapping
                    if(!$doc[$i] == null)
                    {
                        $data2 = array(
                        'type' => 'REV_DOC_ASM',
                        'value' => $doc[$i],
                        'id_application_status'=> $id_app_sts,
                        'created_by' =>  $this->session->userdata('admin_username'),
                        'created_date' => $this->date_time_now()
                        );
                        //insert ke tabel application form mapping
                         $this->admin_model->insert_app_sts_for_map($data2);

                        $id_app_file = array('id_application_file' => $apf->row()->id_application_file);
                        
                        $data3 = array(
                            'status' => 'INACTIVE',
                            'modified_date' => $this->date_time_now(),
                            'modified_by' => $this->session->userdata('admin_username')
                        );
                        //update applications file untuk direfisi
                        $this->admin_model->application_file_update($id_app_file, $data3);

                    }
             }

             $message = ADMNTFSTEP7REV;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

             $this->send_mail($this->input->post('id_application'));

             redirect(site_url('dashboard'));

        
    }














   

    public function CRA_APPROVAL_REQ($id_application_status)
    {
         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('proses_permohonan_CRA', $data);
    }

    public function CRA_APPROVAL_REQ_PROSES()
    {
                $user = $this->input->post('created_by');
                $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
               $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' CRA by : '.$user,
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $data2 = array(
                'id_application '=> $this->input->post('id_application'),
                'process_status' => 'PENDING',
                'id_application_status_name' => '19',
             
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username'));
           
            $id_app_sts_lst = $this->admin_model->insert_app_status($data2,$condition);

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
           $this->admin_model->insert_app_sts_for_map($data5);

            $id_doc_conf = $this->admin_model->get_doc_cra();
            $doc = $this->input->post('doc');
            $this->load->library('upload');
            $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                "upload_path"   => "./upload/",
                "max_size"      => "10000"
             ));

            if($this->upload->do_upload("doc")){

                    $uploaded = $this->upload->data(); 

             for($x=0; $x < $id_doc_conf->num_rows(); $x++) { 
                
                        $data6 = array(
                            'id_application'=> $this->input->post('id_application'),
                            'id_document_config' => $id_doc_conf->row($x)->id_document_config,
                            'status' => 'ACTIVE',
                            'created_date' => $this->date_time_now(),
                            // 'path_file' => $doc[$y],uploaded
                            'path_file' => $uploaded[$x]['full_path'],
                            'created_by' => $this->session->userdata('admin_username')
                        );
                        //insert applications file untuk surat cra
                        $this->admin_model->insert_application_file($data6);  

                        $dataDocAss = array(
                        'type' => 'CRA_DOC_'.$id_doc_conf->row($x)->display_name,
                        'value' => $id_doc_conf->row($x)->keys,
                        'id_application_status'=> $id_app_sts_lst,
                        'created_by' => $this->session->userdata('admin_username'),
                        'created_date' => $this->date_time_now()
                        );
                        $this->admin_model->insert_app_sts_for_map($dataDocAss);

                }
                        
             }

            $message = ADMNTFSTEP9PEND;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

             $this->send_mail($this->input->post('id_application'));

            redirect(base_url('dashboard'));


        
    }

    public function UPL_IIN_DOC_REQ($id_application_status)
    {

         $data['aplication_setujui'] = $this->admin_model->get_application($id_application_status)->result();
        
        $this->load->view('upload_iin', $data);
    }

    public function UPL_IIN_DOC_REQ_PROSES()
    {
         $user = $this->input->post('created_by');
         $id_app = $this->admin_model->get_applications_by_prm($this->input->post('id_application'));
         $application_type = $this->input->post('application_type');

         $data = array(
                'process_status' => 'COMPLETED',
                
                'modified_by' => $this->session->userdata('admin_username'),
                'modified_date' => $this->date_time_now());

            $condition = array('id_application_status' => $this->input->post('id_application_status'));
            $dataL = array(
                'detail_log' => $this->session->userdata('admin_role').' upload IIN by : '.$this->session->userdata('admin_username'),
                'log_type' => 'added  '.$this->input->post('username'), 
                'created_date' => $this->date_time_now(),
                'created_by' => $this->session->userdata('admin_username')
                );
            

            

            $data5 = array(
                    'type' => 'APPROVAL_STATUS',
                    'value' => 'APPROVED',
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
           

            if($application_type == 'new')
            {
                    $data6 = array(
                    'id_user' => $this->input->post('id_user_iin'),
                    'iin_number' => $this->input->post('iin_number'),
                    'iin_established_date' => date_format(date_create($this->input->post('iin_established_date')), 'Y-m-d'),
                    'iin_expiry_date' => date_format(date_create($this->input->post('iin_expiry_date')), 'Y-m-d'),
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );

                    
                $id_iin = $this->admin_model->insert_iin($data6);

            }else
            {
                    $idUser = array('id_user' => $this->input->post('id_user_iin'));

                    $data6 = array(
                    'id_user' => $this->input->post('id_user_iin'),
                    'iin_number' => $this->input->post('iin_number'),
                    'iin_established_date' => date_format(date_create($this->input->post('iin_established_date')), 'Y-m-d'),
                    'iin_expiry_date' => date_format(date_create($this->input->post('iin_expiry_date')), 'Y-m-d'),
                    'modified_date' => $this->date_time_now(),
                    'modified_by' => $this->session->userdata('admin_username')
                    );

                    
                $this->admin_model->update_iin($idUser,$data6);                    

            }

            
            $doc_iin = $this->admin_model->get_doc_iin();
            
            $this->load->library('upload');
            $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|pdf|doc|docx",
                "upload_path"   => "./upload/",
                "max_size"      => "10000"
             ));

            if($this->upload->do_upload("doc")){

                $uploaded = $this->upload->data(); 

                $data7 = array(
                    'id_document_config' => $doc_iin->row()->id_document_config,
                    'id_application' => $this->input->post('id_application'),
                    'path_file'=> $uploaded['full_path'],
                    'status' => 'ACTIVE',
                    'created_date' => $this->date_time_now(),
                    'created_by' => $this->session->userdata('admin_username')
                    );
            
            $this->admin_model->insert_application_file($data7);

                $dataDocAss = array(
                    'type' => 'DOC_IIN',
                    'value' => $doc_iin->row()->keys,
                    'id_application_status'=> $this->input->post('id_application_status'),
                    'created_by' => $this->session->userdata('admin_username'),
                    'created_date' => $this->date_time_now()
                    );
                    $this->admin_model->insert_app_sts_for_map($dataDocAss);            

            }

            $this->admin_model->insert_log($dataL);

            $this->admin_model->next_step($data,$condition);

            $this->admin_model->insert_app_sts_for_map($data5);



            $message = ADMNTFSTEP9;
            $this->send_notif($id_app->row()->id_application,$id_app->row()->id_user,$message);

            $this->send_mail($this->input->post('id_application'));

            redirect(base_url('dashboard'));

    }   

        //belum fix
    public function send_notif($id_application,$id_user,$message)
    {
        $data = array(
            'notification_type' => $id_user,
            'notification_owner' => 'admin',
            // 'message' => 'Silahkan mengecek proses',
            'message' => $message,
            'Status' => 'ACTIVE',
            'notification_url' => 'Layanan-IIN'
        );
        $this->admin_model->insert_notif($data);
    }
   




    //untuk insert document type on demend
    public function insert_doc_for_user()
    {   
        $data = array(
            'type' => $this->input->post('type'),
            'keys' => $this->input->post('keys'),
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
                // 'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_log($dataL);

            $this->admin_model->insert_document_config($data);
    }

    public function send_mail($prm)
    {   
        
        
        $cek = $this->admin_model->get_data_for_mail($prm);
        
        $email = "";
        $username = "";
        $pesan = " Silahkan Klik Link di Bawah ini untuk melanjutkan proses permohonan atau pengawasan IIN Anda";

       
        if($this->usr_model->stepMailAdmin($cek->row()->email,$cek->row()->username, $pesan))
        {
             $this->usr_model->stepMailAdmin($cek->row()->instance_email,$cek->row()->username, $pesan);
            
        }

        
    }



    public function get_doc($prm)
    {
        $query = $this->admin_model->get_doc_for_user()->result();
        
                    
        for($x = 0; $x < count($query); $x++)
        {
            $data = array(
                'id_application' => $prm,
                'id_document_config' => $query[$x]->id_document_config,
                'status' => 'ACTIVE',
                'created_date'=> date('y-m-d'),
                'created_by' => $this->session->userdata('admin_username')
                );
            $this->admin_model->insert_doc_for_user($data);

        }
    }

    public function do_upload_initialize() {
        $this->upload->initialize(array(
                "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                 "upload_path"   => "./upload/"
                ));
    }



}