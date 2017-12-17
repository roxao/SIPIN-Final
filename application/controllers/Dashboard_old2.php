<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {

    var $params = null;
    var $subparams = null;

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('admin_model');
        $this->load->library('email');
        $this->load->helper('form'); 
        $this->load->database();
        $this->model = $this->admin_model;
    }

    public function index(){
        $this->user('check-autho');
        $this->load->view('admin/header');
        $data['applications'] = $this->admin_model->get_applications()->result();
        // echo json_encode($data);
        $this->load->view('admin/inbox', $data);
    }

    public function registered_iin(){
        $this->user('check-autho');
        $this->load->view('admin/header');
        $data['applications'] = $this->admin_model->get_applications()->result();
        $this->load->view('admin/registered_iin', $data);
    }

     public function report(){
        $this->user('check-autho');
        $this->load->view('admin/header');
        $data['applications'] = $this->admin_model->get_applications()->result_array();
        $this->load->view('admin/report', $data);
    }

    public function user($subparams = null) {
        switch ($subparams) {
            case 'login':
                print_r($this->session->userdata('admin_status'));
                $this->load->view('admin/login');
                break;
            case 'register':
                $this->load->view('admin/register');
                break;
            case 'logout':
                $array_items = array('id_admin','username','email','admin_status','admin_role');
                $this->session->unset_userdata($array_items);
                $this->session->sess_destroy('sipin_cookies');
                redirect(base_url('dashboard/user/login'));
                break;
            case 'authorize':
                $username = $this->input->post('username');
                $password = hash ( "sha256", $this->input->post('password'));
                $cek = $this->admin_model->cek_login($username,$password);
                if($cek->num_rows() > 0){
                    if ($cek->row()->admin_status == "INACTIVE"){ 
                        redirect(base_url('dashboard/user/login'));
                       
                    } else {
                        $this->session->set_userdata(array(
                            'id_admin'      => $cek->row()->id_admin,
                            'username'      => $cek->row()->username,
                            'email'         => $cek->row()->email,
                            'admin_status'  => $cek->row()->admin_status,
                            'admin_role'    => $cek->row()->admin_role));
                        redirect(base_url('dashboard'));
                    }
                }
                else{
                    redirect(base_url('dashboard/user/login'));
                }
                break;
            case 'check-autho':
                if (!($this->session->userdata('admin_status'))){
                    redirect(base_url('dashboard/user/login'));
                }
                return false;
                break;
            case null:
                redirect(base_url('dashboard/user/login'));
                break;       
        }
    }

    public function get_app_data() {    
        $this->user('check-autho');
        $id = $this->input->post('id_app');
        $id_status = $this->input->post('id_status');
        $step = $this->input->post('step');
        if($id!=null){
            switch ($step) {
                case 'verif_new_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    echo json_encode($data);
                    break;
                case 'verif_upldoc_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['doc_user'] = $this->admin_model->get_doc_user($id)->result();
                    echo json_encode($data);
                    break;
                case 'verif_revdoc_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['revdoc_user'] = $this->admin_model->application_status_form_mapping_rev_by_idapp5($id,$id_status)->result();
                    echo json_encode($data);
                    break;  
                case 'upl_bill_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    echo json_encode($data);
                    break;  
                case 'reupl_bill_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    echo json_encode($data);
                    break;  
                case 'verif_pay_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['doc_pay'] = $this->admin_model->get_pay($id)->result();
                    $data['assessment_list'] = $this->admin_model->get_assessment()->result();
                    $data['assessment_roles'] = $this->admin_model->get_assessment_team_title()->result();
                    echo json_encode($data);
                    break;
                case 'verif_rev_pay_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['doc_pay'] = $this->admin_model->get_pay($id)->result();
                    $data['assessment_list'] = $this->admin_model->get_assessment()->result();
                    $data['assessment_roles'] = $this->admin_model->get_assessment_team_title()->result();
                    echo json_encode($data);    
                    break;
                case 'rev_assess_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['assessment_list'] = $this->admin_model->get_assessment()->result();
                    $data['assessment_roles'] = $this->admin_model->get_assessment_team_title()->result();
                    echo json_encode($data);
                    break;  
                case 'field_assess_req':
                    echo json_encode($data);break;  
                case 'upl_res_assess_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    echo json_encode($data);
                    break;
                case 'verif_rev_assess_res_req':
                    echo json_encode($data);break;  
                case 'cra_approval_req':
                    $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    $data['revdoc_user'] = $this->admin_model->get_doc_cra()->result();
                    echo json_encode($data);break;  
                case 'upl_iin_doc_req':
                $data['application'] = $this->admin_model->get_application($id_status)->result()[0];
                    echo json_encode($data);break;  
            }
        }
    }

    public function set_view($param = null, $subparams = null) {
        $this->load->view('admin/'.$param.'/'.$subparams);
    }

    public function settings($param = null){
        switch ($param) {
            case 'user': 
                $data['data'] = $this->admin_model->get_user()->result_array(); break;
            case 'admin': 
                $data['data'] = $this->admin_model->get_admin()->result_array(); break;
            case 'assessment': 
                $data['data'] = $this->admin_model->get_assessment()->result_array(); break;
                $data['data_title'] = $this->admin_model->get_assessment_title()->result_array(); break;
            case 'assessment_roles': 
                
            case 'document': 
                $data['data'] = $this->admin_model->get_document()->result_array(); break;
            case 'cms': $data['data'] = 
                $this->admin_model->get_cms()->result_array(); break;
            case 'iin': 
                $data['data'] = $this->admin_model->get_iin()->result_array(); break;
            case 'survey': 
                $data['data'] = $this->admin_model->question_survey_question()->result_array(); break;
            default: 
                redirect(base_url()); break;
        }
        $this->load->view('admin/header');
        $this->load->view('admin/settings/'.$param ,$data);
    }

    public function action_update($param){
        switch ($param) {
            case 'user':
                $condition = array('id_user' => $this->input->post('id_user'));
                $data = array(  'email' => $this->input->post('email'),
                                'username' => $this->input->post('username'),
                                'password' => $this->input->post('password'),
                                'name' => $this->input->post('name'),
                                'status_user' => $this->input->post('status_user'),
                                'survey_status' => $this->input->post('survey_status'),
                                'modified_date' => date('Y-m-j H:i:s')
                                // 'modified_by' => 'Admin'                
                                // 'modified_by' => $this->session->userdata('username')                
                );
                $log = array(   'detail_log' => $this->session->userdata('admin_role').' Update Data user',
                                'log_type' => 'Update Data user '.$this->input->post('name'), 
                                'created_date' => date('Y-m-j H:i:s')
                                // 'created_by' => 'Admin'
                                // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_user($condition,$data);
                break;
            case 'admin':
                $condition = array('id_admin' => $this->input->post('id_admin'));
                $data = array(
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('username'),
                    // 'password' => $this->input->post('password'),
                    'password' => hash ( "sha256", $this->input->post('password')),
                    'admin_status' => $this->input->post('admin_status'),
                    'admin_role' => $this->input->post('admin_role'),
                    'modified_date' => date('Y-m-j'),
                    // 'modified_by' => $this->session->userdata('username')                
                );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' Update Data Admin',
                    'log_type' => 'Update Data '.$this->input->post('username'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_admin($condition,$data);
                break;
            case 'assessment':
                $condition = array('id_assessment_team' => $this->input->post('id_assessment_team'));
                $data = array(
                    'name' => $this->input->post('name'),
                    'status' => $this->input->post('status'),               
                );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' Update Data assesment team',
                    'log_type' => 'Update Data '.$this->input->post('name'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );

                $this->admin_model->update_assessment($condition,$data);
                break;
            case 'assessment_roles':
                $condition = array('id_assessment_team_title' => $this->input->post('id_assessment_team_title'));
                $data = array(
                    'title' => $this->input->post('title')          
                );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' Update Data assesment team title',
                    'log_type' => 'Update Data '.$this->input->post('title'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_assessment_team_title($condition,$data);
                break;
            case 'document':
                $this->load->library('upload');
                $this->upload->initialize(array(
                    "allowed_types" => "gif|jpg|png|jpeg|png",
                    "upload_path"   => "./upload/"
                ));
                
                $this->upload->do_upload("file_url");
                $uploaded = $this->upload->data();
            
                $condition = array('id_document_config' => $this->input->post('id_document_config'));
                $data = array(
                    'type' => $this->input->post('type'),
                    'key' => $this->input->post('key'),
                    'display_name' => $this->input->post('display_name'),
                    'file_url' => $uploaded['full_path'],
                    'mandatory' => $this->input->post('mandatory'),
                    'modified_date' => date('Y-m-j H:i:s'),
                    // 'modified_by' => $this->session->userdata('username')                
                );
                $log = array(
                'detail_log' => $this->session->userdata('admin_role').' Update Data Dokumen',
                'log_type' => 'Update Data '.$this->input->post('display_name'), 
                'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_documenet_config($condition,$data);
                break;
            case 'contents':
                $condition = array('id_cms' => $this->input->post('id_cms'));
                $data = array(
                    'content' => $this->input->post('content'),
                    'title' => $this->input->post('title'),
                    'url' => $this->input->post('url'),
                    'modified_date' => date('Y-m-j H:i:s'),
                    // 'modified_by' => $this->session->userdata('username')                
                );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' Update Data CMS',
                    'log_type' => 'Update Data '.$this->input->post('title'), 
                    'created_date' => date('Y-m-j H:i:s')
                // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_cms($condition,$data);
                break;
            case 'iin':
                $condition = array('id_iin' => $this->input->post('id_iin'));
                $data = array(
                    'id_user' => $this->input->post('id_user'),
                    'iin_number' => $this->input->post('iin_number'),
                    'iin_established_date' => date('Y-m-j H:i:s'),
                    'iin_expiry_date' => date('Y-m-j H:i:s'),
                    'modified_date' => date('Y-m-j H:i:s')
                    // 'modified_by' => $this->session->userdata('username')     
                );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' Update Data IIN',
                    'log_type' => 'Update Data '.$this->input->post('iin_number'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->update_iin($condition,$data);
                break;
            default:
                break;
        }
        $this->admin_model->insert_log($log);
        redirect(base_url('dashboard/settings/'.$param));
    }

    public function action_insert($param){
        switch ($param) {
            case 'admin':
                $data = array(
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('username'),
                    // 'password' => $this->input->post('password'),
                    'password' => hash ( "sha256", $this->input->post('password')),
                    'admin_status' => $this->input->post('admin_status'),
                    'admin_role' => $this->input->post('admin_role'),
                    'created_date' => date('Y-m-j H:i:s'),
                    'created_by' => $this->session->userdata('username')             
                    );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new admin',
                    'log_type' => 'added '.$this->input->post('username'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->insert_admin($data);
                break;
            case 'assessment':
                $data = array(
                    'name' => $this->input->post('name'),
                    'status' => $this->input->post('status'),
                    );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new tim_asesment',
                    'log_type' => 'added '.$this->input->post('name'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                    );
                $this->admin_model->insert_assesment($data);
                break;
            case 'assessment_roles':
                $data = array(
                    'title' => $this->input->post('title')
                    );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new asesment title',
                    'log_type' => 'added '.$this->input->post('name'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                    );
                $this->admin_model->insert_assesment_title($data);    
                break;
            case 'document':
                $this->load->library('upload');
                $this->upload->initialize(array(
                    "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
                    "upload_path"   => "./upload/"
                    ));   
                $this->upload->do_upload("file_url");
                $uploaded = $this->upload->data();

                $data = array(
                    'type' => $this->input->post('type'),
                    'key' => $this->input->post('key'),
                    'display_name' => $this->input->post('display_name'),
                    'file_url' => $this->input->post('file_url'),
                    'mandatory' => $this->input->post('mandatory'),
                    'created_date' => date('Y-m-j H:i:s'),
                    // 'created_by' => $this->session->userdata('username')             
                    );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new doc',
                    'log_type' => 'added '.$this->input->post('display_name'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                );
                $this->admin_model->insert_document_config($data);
                break;
            case 'contents':
                $data = array(
                    'content' => $this->input->post('content'),
                    'title' => $this->input->post('title'),
                    'url' => $this->input->post('url'),
                    'created_date' => date('Y-m-j H:i:s'),
                    // 'created_by' => $this->session->userdata('username')             
                    );
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new cms',
                    'log_type' => 'added '.$this->input->post('title'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                    );
                $this->admin_model->insert_cms($data); 
                break;
            case 'iin':
                $data = array(
                    'id_user' => $this->input->post('id_user'),
                    'iin_number' => $this->input->post('iin_number'),
                    'iin_established_date' => date('Y-m-j H:i:s'),
                    'iin_expiry_date' => date('Y-m-j H:i:s'),
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')             
                    );  
                $log = array(
                    'detail_log' => $this->session->userdata('admin_role').' adding new IIN',
                    'log_type' => 'added IIN '.$this->input->post('iin_number'), 
                    'created_date' => date('Y-m-j H:i:s')
                    // 'created_by' => $this->session->userdata('username')
                    );
                $this->admin_model->insert_iin($data);
                break;
            default:
                break;
        }
        $this->admin_model->insert_log($log);
        redirect(base_url('dashboard/settings/'.$param));
    }





























    
















    

    function do_upload() {
        $this->load->library('upload');
        $this->upload->initialize(array("allowed_types" => "gif|jpg|png|jpeg|pdf|doc", "upload_path" => "./upload/"));
        //Perform upload.
        if($this->upload->do_upload("images")) {
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















//pengaturan 
     //untuk menuju form isian data tim asesment
    public function insert_tim_asesment() 
    {
        $this->load->view('admin/options/asesment_team_insert');
    }

    //insert tim asesmen 
    public function insert_tim_asesment_proses()
    {      
        $data = array(
        'name' => $this->input->post('name'),
        'status' => $this->input->post('status'),
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' adding new tim_asesment',
        'log_type' => 'added '.$this->input->post('name'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->insert_assesment($data);     
        $this->read_tim_asesment();
    }

    //get assesmen team by id assemsent team
    public function get_tim_asesment_by_prm($prm) 
    {
        // echo $prm;
        $data['data_asesment'] = $this->admin_model->get_assessment_byid($prm)->result();
        $this->load->view('admin/options/asesment_team_edit', $data);
        // echo json_encode($data);
    }

    //edit data asesment
    public function tim_asesment_edit_proses(){
        $condition = array('id_assessment_team' => $this->input->post('id_assessment_team'));
        $data = array(
        'name' => $this->input->post('name'),
        'status' => $this->input->post('status'),               
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data assesment team',
        'log_type' => 'Update Data '.$this->input->post('name'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_assessment($condition,$data);
    }





     //menampilkan data tim asesment title
    public function read_tim_asesment_title() 
    {

        $this->load->view('admin/options/asesment_title_insert',$data);
        // echo json_encode($data);
    }

    public function read_tim_asesment_title_byprm($prm) 
    {
        $data['data_asesment_title'] = $this->admin_model->get_assessment_title_byprm($prm)->result();
        $this->load->view('admin/options/asesment_title_edit', $data);
        // echo json_encode($data);
    }

    //edit data asesment
    public function tim_asesment_title_edit_proses()
    {
        $condition = array('id_assessment_team_title' => $this->input->post('id_assessment_team_title'));
        $data = array(
        'title' => $this->input->post('title')          
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data assesment team title',
        'log_type' => 'Update Data '.$this->input->post('title'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_assessment_team_title($condition,$data);
    }

    //untuk menuju form isian data asesment_title
    public function insert_tim_asesment_title() 
    {
        $this->load->view('admin/options/asesment_title_insert');
    }

    //insert tim asesmen 
    public function insert_tim_asesment_title_proses()
    {      
        $data = array(
        'title' => $this->input->post('title')
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' adding new asesment title',
        'log_type' => 'added '.$this->input->post('name'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->insert_assesment_title($data);    
        $this->read_tim_asesment_title();
    }






    //menampilkan data admin (admin dan super admin)
    public function read_admin() 
    {

        // $this->load->view('admin/data_asesment', $data);
        $this->load->view('admin/options/admin_insert', $data);
        // echo json_encode($data);
    }

    //untuk menuju form isian data tambah admin
    public function insert_admin() 
    {
        $this->load->view('admin/options/admin_insert');
    }

    //tambah admin proses
    public function insert_admin_proses()
    {      
        $data = array(
        'email' => $this->input->post('email'),
        'username' => $this->input->post('username'),
        'password' => $this->input->post('password'),
        'admin_status' => $this->input->post('admin_status'),
        'admin_role' => $this->input->post('admin_role'),
        'created_date' => date('Y-m-j H:i:s'),
        // 'created_by' => $this->session->userdata('username')             
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' adding new admin',
        'log_type' => 'added '.$this->input->post('username'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->insert_admin($data);
        $this->read_admin();
    }

    //cari admin berdasarkan id admin
    public function get_admin_byprm($prm) 
    {
        $data['data_admin'] = $this->admin_model->get_admin_byprm($prm)->result();
        $this->load->view('admin/options/admin_edit', $data);
        // echo json_encode($data);
    }

    //edit data admin
    public function admin_edit_proses()
    {
        $condition = array('id_admin' => $this->input->post('id_admin'));
        $data = array(
            'email' => $this->input->post('email'),
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'admin_status' => $this->input->post('admin_status'),
            'admin_role' => $this->input->post('admin_role'),
            'modified_date' => date('Y-m-j'),
            // 'modified_by' => $this->session->userdata('username')                
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data Admin',
        'log_type' => 'Update Data '.$this->input->post('username'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        print_r($data);

        // $this->admin_model->insert_log($dataL);
        // if($this->admin_model->update_admin($condition,$data))
        // {
        //     echo "suses";
        // }else{ echo "gagal";}
        $this->admin_model->update_admin($condition,$data);

    }







     //cari admin berdasarkan id dokumen
    public function get_document_config($prm) 
    {
        $data['document'] = $this->admin_model->get_document_by_prm($prm)->result();
        $this->load->view('admin/options/edit_doc_conf', $data);
        // echo json_encode($data);
    }

     //edit data dokumen
    public function document_config_edit_proses()
    {

        $this->load->library('upload');
        $this->upload->initialize(array(
            "allowed_types" => "gif|jpg|png|jpeg|png",
            "upload_path"   => "./upload/"
        ));
        
        $this->upload->do_upload("file_url");
        $uploaded = $this->upload->data();
    
        $condition = array('id_document_config' => $this->input->post('id_document_config'));
        $data = array(
            'type' => $this->input->post('type'),
            'key' => $this->input->post('key'),
            'display_name' => $this->input->post('display_name'),
            'file_url' => $uploaded['full_path'],
            'mandatory' => $this->input->post('mandatory'),
            // 'modified_date' => date('Y-m-j H:i:s'),
            // 'modified_by' => $this->session->userdata('username')                
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data Dokumen',
        'log_type' => 'Update Data '.$this->input->post('display_name'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_documenet_config($condition,$data);
        
        

    }

     //untuk menuju form isian data tambah doc
    public function insert_doc() 
    {
        $this->load->view('');
    }

    //tambah admin doc
    public function insert_doc_proses(){   
        $this->load->library('upload');
 
      //Configure upload.
        $this->upload->initialize(array(
            "allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf",
            "upload_path"   => "./upload/"
        ));   
        $this->upload->do_upload("file_url");
            $uploaded = $this->upload->data();
            print_r($uploaded) ;

        $data = array(
            'type' => $this->input->post('type'),
            'key' => $this->input->post('key'),
            'display_name' => $this->input->post('display_name'),
            'file_url' => $this->input->post('file_url'),
            'mandatory' => $this->input->post('mandatory'),
            'created_date' => date('Y-m-j H:i:s'),
            // 'created_by' => $this->session->userdata('username')             
        );
        $dataL = array(
            'detail_log' => $this->session->userdata('admin_role').' adding new doc',
            'log_type' => 'added '.$this->input->post('display_name'), 
            'created_date' => date('Y-m-j H:i:s')
            // 'created_by' => $this->session->userdata('username')
        );

          $this->admin_model->insert_log($dataL);
          $this->admin_model->insert_document_config($data);
          

    }

    public function get_cms_insert() 
    {
$this->load->view('admin/options/cms_insert');
    }

    //cari admin berdasarkan id cms
    public function get_cms($prm) 
    {

        $data['cms'] = $this->admin_model->get_cms_by_prm($prm)->result();
        $this->load->view('admin/options/cms_insert',$data);
        echo json_encode($data);
    }

    //edit data cms
    public function cms_edit_proses()
    {
        $condition = array('id_cms' => $this->input->post('id_cms'));
        $data = array(
        'content' => $this->input->post('content'),
        'title' => $this->input->post('title'),
        'url' => $this->input->post('url'),
        'modified_date' => date('Y-m-j H:i:s'),
        // 'modified_by' => $this->session->userdata('username')                
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data CMS',
        'log_type' => 'Update Data '.$this->input->post('title'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_cms($condition,$data);

    }

    //untuk menuju form isian data tambah cms
    public function insert_cms() 
    {   
        // $data['cms'] = $this->admin_model->get_cms()->result();
        // $this->load->view('admin/options/cms_view_all',$data);
    }

    public function get_all_cms() 
    {   
        $data['cms'] = $this->admin_model->get_cms()->result();
        $this->load->view('admin/options/cms_view_all',$data);
    }

    //tambah cms
    public function insert_cms_proses()
    {      
        $data = array(
        'content' => $this->input->post('content'),
        'title' => $this->input->post('title'),
        'url' => $this->input->post('url'),
        'created_date' => date('Y-m-j H:i:s'),
        // 'created_by' => $this->session->userdata('username')             
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' adding new cms',
        'log_type' => 'added '.$this->input->post('title'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );
        $this->admin_model->insert_log($dataL);
        $this->admin_model->insert_cms($data);    
    }







    // CARI USER BERDASARKAN ID
    public function get_user($prm){
        $data['data_user'] = $this->admin_model->get_user_by_prm($prm)->result();
        print_r($data['data_user']);
        // $this->load->view('admin/options/user_edit', $data);
    }











    //cari iin berdasarkan id iin
    public function get_iin($prm) 
    {
        $data['iin'] = $this->admin_model->get_iin_by_prm($prm)->result();
        $this->load->view('admin/options/iin_edit', $data);
        // echo json_encode($data);
    }

    //edit data iin
    public function iin_edit_proses()
    {
        $condition = array('id_iin' => $this->input->post('id_iin'));
        $data = array(
        'id_user' => $this->input->post('id_user'),
        'iin_number' => $this->input->post('iin_number'),
        'iin_established_date' => date('Y-m-j H:i:s'),
        'iin_expiry_date' => date('Y-m-j H:i:s'),
        'modified_date' => date('Y-m-j H:i:s')
        // 'modified_by' => $this->session->userdata('username')                
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' Update Data IIN',
        'log_type' => 'Update Data '.$this->input->post('iin_number'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_iin($condition,$data);

    }

    //untuk menuju form isian data tambah iiin
    public function insert_iin() 
    {
        $this->load->view('');
    }

    //tambah iin
    public function insert_iin_proses()
    {      
        $data = array(
        'id_user' => $this->input->post('id_user'),
        'iin_number' => $this->input->post('iin_number'),
        'iin_established_date' => date('Y-m-j H:i:s'),
        'iin_expiry_date' => date('Y-m-j H:i:s'),
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')             
        );
        $dataL = array(
        'detail_log' => $this->session->userdata('admin_role').' adding new IIN',
        'log_type' => 'added IIN '.$this->input->post('iin_number'), 
        'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );

          $this->admin_model->insert_log($dataL);
          $this->admin_model->insert_iin($data);
          $this->get_iin_data();    
    }

    public function all_data()
    {
        $iin['dat'] = $this->admin_model->get_iin()->result();
        $usr['user'] = $this->admin_model->get_user()->result();
        $cms['cms'] = $this->admin_model->get_cms()->result();
        $ass['data_asesment'] = $this->admin_model->get_assessment()->result();
        $ast['data_asesment_title'] = $this->admin_model->get_assessment_title()->result();
        $doc['document']    = $this->admin_model->get_document()->result();
// $doc['document']    = $this->admin_model->all_dat()->result();
        
    }





    //menampilkan user yang komplain
    public function get_complain_data()
    {
        $data['compalin'] = $this->admin_model->get_conplain()->result();
        echo json_encode($data);
    }

    public function get_app_data_etc() {

        $data['pengawasan'] = $this->admin_model->get_applications_ext()->result();
        echo json_encode($data);
    }    

}
