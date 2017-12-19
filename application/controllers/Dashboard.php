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
        $this->load->model('admin_model', 'adm_model');
        $this->load->model('user_model', 'usr_model');
    }
    public function date_time_now() {
        $datetime = new DateTime('Asia/Jakarta');
        return $datetime->format('Y\-m\-d\ h:i:s');
    }
    public function set_template($view_name, $data = array()) {
        if (!isset($data['web_title'])) $data['web_title'] = 'Sistem Admin :: Layanan Issuer Identification Number';
        $this->load->view('admin/header', $data);
        $this->load->view($view_name, $data);
        return;
    }
    public function index() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_applications()->result();
        $data['web_title'] = 'Inbox :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/inbox', $data);
    }
    public function iinlist() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_data_history()->result();
        $data['web_title'] = 'Daftar Penerima IIN :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/iin-list', $data);
    }
    public function data_entry() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_data_history()->result();
        $data['web_title'] = 'Historical Data Entry :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/data-entry', $data);
    }
    public function data_entry_form($id_user = null) {
        $this->user('check-autho');
        if ($this->input->post('id_entry') != 'new') {
            $data['data'] = $this->admin_model->get_data_history_byprm($this->input->post('id_entry'))->result_array();
        } else {
            $data['data'] = $this->admin_model->get_applications_finish()->result();
        }
        $data['web_title'] = 'Form Historical Data Entry:: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/data-entry-form', $data);
    }
    public function complaint() {
        $this->user('check-autho');
        $data['complaint'] = $this->admin_model->get_complaint()->result_array();
        $data['web_title'] = 'Daftar Pengaduan :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/complaint', $data);
    }
    public function extend() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_applications_ext()->result();
        $data['web_title'] = 'Pengawasan IIN Lama :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/extend', $data);
    }
    public function submission() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_applications_new()->result();
        $data['web_title'] = 'Pengajuan IIN Baru :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/submission', $data);
    }
    public function report() {
        $this->user('check-autho');
        $data['applications'] = $this->admin_model->get_applications()->result_array();
        $data['web_title'] = 'Laporan :: Sistem Admin :: Layanan Issuer Identification Number';
        $this->set_template('admin/report', $data);
    }
    public function user($subparams = null) {
        switch ($subparams) {
            case 'login':
                $this->load->view('admin/login');
                break;
            case 'register':
                $this->load->view('admin/register');
                break;
            case 'logout':
                $array_items = array('id_admin', 'username', 'email', 'admin_status', 'admin_role');
                $this->session->unset_userdata($array_items);
                $this->session->sess_destroy('sipin_cookies');
                redirect(base_url('dashboard/user/login'));
                break;
            case 'authorize':
                $username = $this->input->post('username');
                $password = hash("sha256", $this->input->post('password'));
                $cek = $this->admin_model->cek_login($username, $password);
                if ($cek->num_rows() > 0) {
                    if ($cek->row()->admin_status == "INACTIVE") {
                        redirect(base_url('dashboard/user/login'));
                    } else {
                        $this->session->set_userdata(array('id_admin' => $cek->row()->id_admin, 'admin_username' => $cek->row()->username, 'admin_email' => $cek->row()->email, 'admin_status' => $cek->row()->admin_status, 'status' => 'login', 'admin_role' => $cek->row()->admin_role));
                        $cek = $this->admin_model->get_applications_for_exp()->result_array();
                        // echo json_encode($cek);
                        // echo sizeof($cek);
                        #validate size
                        if (!is_null($cek)) {
                            #iterate $cek to get id_application_status
                            for ($i = 0;$i < sizeof($cek);$i++) {
                                $id_application_status = $cek[$i]['id_application_status'];
                                $get_id_app_before = $this->admin_model->get_id_before($cek[$i]['id_application'])->result_array();
                                if (!is_null($id_application_status)) {
                                    if (isset($get_id_app_before[$i]['id_application_status'])) $id_application_status = $get_id_app_before[$i]['id_application_status'];
                                    $exp_date = $this->admin_model->form_mapping_exp_date($id_application_status)->result_array();
                                    if (!is_null($exp_date)) {
                                        $date_bill = "";
                                        foreach ($exp_date as $key) {
                                            # code...
                                            $date_bill = date_format(date_create($key['value']), 'Y\-m\-d\ h:i:s');
                                            if ($date_bill < $this->date_time_now()) {
                                                #update table 7
                                                $condition = array('id_application_status' => $cek[$i]['id_application_status']);
                                                $update_status_7 = array('id_application_status_name' => '7', 'id_application' => $cek[$i]['id_application'], 'process_status' => 'COMPLETED', 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                                                $this->admin_model->next_step($update_status_7, $condition);
                                                $insert_status_8 = array('id_application' => $cek[$i]['id_application'], 'id_application_status_name' => '8', 'process_status' => 'PENDING', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                                                $this->admin_model->insert_app_status($insert_status_8);
                                                $get_doc_bill = $this->admin_model->get_doc_bill_res()->result_array();
                                                foreach ($get_doc_bill as $doc => $bill) {
                                                    $id_application = array('id_application' => $cek[$i]['id_application']);
                                                    $data_appf = array('id_document_config' => $bill['id_document_config'], 'status' => 'INACTIVE', 'modified_by' => $this->session->userdata('admin_username'), 'modified_date' => $this->date_time_now());
                                                    #Inactive application file
                                                    $this->admin_model->exp_bill_simponi($id_application, $data_appf);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        redirect(base_url('dashboard'));
                    }
                } else {
                    redirect(base_url('dashboard/user/login'));
                }
                break;
            case 'check-autho':
                if (!($this->session->userdata('admin_status'))) {
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
        if ($id != null) {
            $data['application'] = $this->admin_model->get_application($id_status)->result() [0];
            switch ($step) {
                case 'verif_new_req':
                case 'upl_res_assess_req':
                case 'field_assess_req':
                case 'upl_bill_req':
                case 'upl_iin_doc_req':
                case 'reupl_bill_req':
                    break;
                case 'verif_pay_req':
                case 'verif_rev_pay_req':
                    $data['doc_pay'] = $this->admin_model->get_pay($id)->result();
                    $data['assessment_roles'] = $this->admin_model->get_assessment_team_title()->result();
                    break;
                case 'verif_revdoc_req':
                    $data['revdoc_user'] = $this->admin_model->application_status_form_mapping_rev_by_idapp($id, $id_status)->result();
                    break;
                case 'verif_upldoc_req':
                    $data['doc_user'] = $this->admin_model->get_doc_user($id)->result();
                    break;
                case 'rev_assess_req':
                    $data['date_req'] = $this->admin_model->get_date_rev($id_status)->result();
                    $data['assessment_roles'] = $this->admin_model->get_assessment_team_title()->result();
                    break;
                case 'verif_rev_assess_res_req':
                    $data['doc_user'] = $this->admin_model->get_revised_resuld_assessment($id, $id_status)->result();
                    break;
                case 'cra_approval_req':
                    $data['revdoc_user'] = $this->admin_model->get_doc_cra()->result();
                    break;
                default:
                    return false;
                    break;
            }
            echo json_encode($data);
        }
    }
    public function get_autocomplete($step) {
        $result = array();
        switch ($step) {
            case 'assessment_team':
                foreach ($this->admin_model->get_assessment_team($this->input->get('term'))->result_array() as $keys):
                    $result[] = array('label' => trim($keys['name']), 'id_team' => trim($keys['id_assessment_team']));
                endforeach;
                break;
            case 'doc':
                foreach ($this->admin_model->get_document_by_display($this->input->get('term'))->result_array() as $keys):
                    $result[] = array('label' => trim($keys['display_name']), 'id_doc' => trim($keys['id_document_config']));
                endforeach;
                break;
            case 'instance_name':
                foreach ($this->admin_model->get_instance_list($this->input->get('term'))->result_array() as $keys):
                    $result[] = array('label' => trim($keys['instance_name'])
                    // 'id_doc' => trim($key['id_document_config'])
                    );
                endforeach;
                break;
        }
        echo json_encode($result);
    }
    public function set_view($param = null, $subparams = null) {
        $this->user('check-autho');
        $this->load->view('admin/' . $param . '/' . $subparams);
    }
    public function settings($param = null) {
        $this->user('check-autho');
        if ($this->session->userdata('admin_role') == 'Super Admin') {
            switch ($param) {
                case 'user':
                    $data['data'] = $this->admin_model->get_user()->result_array();
                    break;
                case 'admin':
                    $data['data'] = $this->admin_model->get_admin()->result_array();
                    break;
                case 'assessment':
                    $data['data_name'] = $this->admin_model->get_assessment()->result_array();
                    $data['data_title'] = $this->admin_model->get_assessment_title()->result_array();
                    break;
                case 'document_dynamic':
                    $data['data'] = $this->admin_model->get_document_config('DYNAMIC')->result_array();
                    break;
                case 'document_static':
                    $data['data'] = $this->admin_model->get_document_config('STATIC')->result_array();
                    break;
                case 'cms':
                    $data['data'] = $this->admin_model->get_cms()->result_array();
                    break;
                case 'cms_editor':
                    if ($this->input->post('id_cms') != 'insert') {
                        $data['data'] = $this->admin_model->get_cms_by_prm($this->input->post('id_cms'))->result_array();
                    } else {
                        $data['data'] = null;
                    }
                    break;
                case 'iin':
                    $data['data'] = $this->admin_model->get_iin()->result_array();
                    break;
                case 'survey':
                    $data['data'] = $this->admin_model->question_survey_question()->result_array();
                    break;
                case 'data_entry_form':
                    if ($this->input->post('id_entry') != 'new') {
                        $data['data'] = $this->admin_model->get_data_history_byprm($this->input->post('id_entry'))->result_array();
                    } else {
                        $data['data'] = null;
                    }
                    break;
                case 'survey_form':
                    if ($this->input->post('id_surv') != 'insert') {
                        $data['data'] = $this->admin_model->get_survey_by_prm($this->input->post('id_surv'))->result_array();
                    } else {
                        $data['data'] = null;
                    }
                    break;
                case 'banner':
                    $data['data'] = $this->admin_model->get_banner()->result_array();
                    break;
                default:
                    redirect(base_url('dashboard'));
                    break;
            }
            $this->set_template('admin/settings/' . $param, $data);
        } else {
            redirect(base_url('dashboard'));
        }
    }
    public function action_update($param) {
        $this->user('check-autho');
        $direction = 'dashboard/settings/';
        switch ($param) {
            case 'admin':
                $condition = array('id_admin' => $this->input->post('id_admin'));
                if (is_null($this->input->post('password'))) {
                    $data = array('email' => $this->input->post('email'), 'username' => $this->input->post('username'), 'admin_status' => $this->input->post('admin_status'), 'admin_role' => $this->input->post('admin_role'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                } else {
                    $data = array('email' => $this->input->post('email'), 'username' => $this->input->post('username'), 'password' => hash("sha256", $this->input->post('password')), 'admin_status' => $this->input->post('admin_status'), 'admin_role' => $this->input->post('admin_role'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                }
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data Admin', 'log_type' => 'Update Data ' . $this->input->post('username'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_admin($condition, $data);
                break;
            case 'assessment':
                $condition = array('id_assessment_team' => $this->input->post('id_assessment_team'));
                $data = array('name' => $this->input->post('name'), 'status' => $this->input->post('STATUS'),);
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data assesment team', 'log_type' => 'Update Data ' . $this->input->post('name'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_assessment($condition, $data);
                break;
            case 'document':

                $this->load->library('upload');
                $this->upload->initialize(array("allowed_types" => "gif|jpg|png|jpeg|png", "upload_path" => "./upload/"));
                $this->upload->do_upload("file_url");
                $uploaded = $this->upload->data();
                $condition = array('id_document_config' => $this->input->post('id_document_config'));
                $data = array('type' => $this->input->post('type_doc'), 'keys' => $this->input->post('keys'), 'display_name' => $this->input->post('display_name'), 'file_url' => $uploaded['full_path'], 'mandatory' => $this->input->post('mandatory'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data Dokumen', 'log_type' => 'Update Data ' . $this->input->post('display_name'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_documenet_config($condition, $data);
                if($this->input->post('type_doc')=='STATIC')
                {
                    $direction = 'dashboard/settings/document_static';
                }else
                {
                    $direction = 'dashboard/settings/document_dynamic';
                }

                break;
            case 'contents':
                $url = strtolower($this->input->post('title'));
                $url = str_replace(' ', '_', $url);
                $condition = array('id_cms' => $this->input->post('id_cms'));
                $data = array('contents' => $this->input->post('contents'), 'title' => $this->input->post('title'), 'url' => substr($url, 0, 50), 'status' => $this->input->post('status'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data CMS', 'log_type' => 'Update Data ' . $this->input->post('title'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_cms($condition, $data);
                $this->admin_model->insert_log($log);
                redirect('dashboard/settings/cms');
                break;
            case 'banner':
                $condition = $this->input->post('id_banner');
                $data = array('title' => $this->input->post('title'), 'text' => $this->input->post('description'), 'path' => $this->input->post('file_name'), 'status' => $this->input->post('status'), 'url' => $this->input->post('url'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data Banner', 'log_type' => 'Update Data Banner', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_banner($condition, $data);
                $this->admin_model->insert_log($log);
                redirect('dashboard/settings/banner');
                break;
            case 'iin':
                $condition = array('id_iin' => $this->input->post('id_iin'));
                $data = array('id_user' => $this->input->post('id_user'), 'iin_number' => $this->input->post('iin_number'), 'iin_established_date' => $this->input->post('iin_established_date'), 'iin_expiry_date' => $this->input->post('iin_expiry_date'), 'modified_date' => $this->date_time_now(), 'modified_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data IIN', 'log_type' => 'Update Data ' . $this->input->post('iin_number'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_iin($condition, $data);
                break;
            default:
                break;
        }
        $this->admin_model->insert_log($log);
        redirect(base_url($direction.'/'.$param.'?state=success'));
    }
    public function action_insert($param) {
        $this->user('check-autho');
        switch ($param) {
            case 'admin':
                $data = array('email' => $this->input->post('email'), 'username' => $this->input->post('username'),
                // 'password' => $this->input->post('password'),
                'password' => hash("sha256", $this->input->post('password')), 'admin_status' => $this->input->post('admin_status'), 'admin_role' => $this->input->post('admin_role'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new admin', 'log_type' => 'added ' . $this->input->post('username'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_admin($data);
                break;
            case 'assessment':
                $data = array('name' => $this->input->post('name'), 'status' => $this->input->post('STATUS'),);
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new tim_asesment', 'log_type' => 'added ' . $this->input->post('name'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_assesment($data);
                break;
            case 'assessment_roles':
                $data = array('title' => $this->input->post('title'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new asesment title', 'log_type' => 'added ' . $this->input->post('name'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_assesment_title($data);
                break;
            case 'document':
                $this->load->library('upload');
                $this->upload->initialize(array("allowed_types" => "gif|jpg|png|jpeg|png|doc|docx|pdf", "upload_path" => "./upload/"));
                $this->upload->do_upload("file_url");
                $uploaded = $this->upload->data();
                $data = array('type' => $this->input->post('type_doc'), 'keys' => $this->input->post('keys'), 'display_name' => $this->input->post('display_name'), 'file_url' => $this->input->post('file_url'), 'mandatory' => $this->input->post('mandatory'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new doc', 'log_type' => 'added ' . $this->input->post('display_name'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_document_config($data);

                redirect(base_url('dashboard/settings/document_dynamic'));
                break;
            case 'contents':
                $url = strtolower($this->input->post('title'));
                $url = str_replace(' ', '_', $url);
                $data = array('contents' => $this->input->post('contents'), 'title' => $this->input->post('title'), 'url' => substr($url, 0, 50), 'status' => 'Y', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new cms', 'log_type' => 'added ' . $this->input->post('title'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_cms($data);
                redirect('dashboard/settings/cms');
                break;
            case 'banner':
                $data = array('title' => $this->input->post('title'), 'text' => $this->input->post('description'), 'path' => $this->input->post('file_name'), 'status' => $this->input->post('status'), 'url' => $this->input->post('url'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Insert Data Banner', 'log_type' => 'Insert Data Banner', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_banner($data);
                redirect('dashboard/settings/banner');
                break;
            case 'iin':
                $data = array('id_user' => $this->input->post('id_user'), 'iin_number' => $this->input->post('iin_number'), 'iin_established_date' => $this->input->post('iin_established_date'), 'iin_expiry_date' => $this->input->post('iin_expiry_date'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' adding new IIN', 'log_type' => 'added IIN ' . $this->input->post('iin_number'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_iin($data);
                break;
            default:
                break;
        }
        $this->admin_model->insert_log($log);
        redirect(base_url('dashboard/settings/' . $param));
    }
    public function excel() {
        $this->load->library("PHPExcel");
        $objPHPExcel = new PHPExcel();
        $c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $f = explode("||", $this->input->get('f'));
        $t = explode("||", $this->input->get('t'));
        $m = $this->input->get('m');
        $data = $this->admin_model->$m()->result_array();
        for ($i = 0;$i < count($f);$i++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($c[$i] . 1, $t[$i]);
            for ($j = 0;$j < count($data);$j++) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($c[$i] . ($j + 2), $data[$j][$f[$i]]);
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Report' . date('-Y-m-j--H-i-s'));
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="export' . date('-Y-m-j--H-i-s') . '.xls"');
        $objWriter->save("php://output");
    }
    public function upload_acceptor() {
        $imageFolder = "upload/content-file/";
        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.0 500 Invalid file name.");
                return;
            }
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.0 500 Invalid extension.");
                return;
            }
            $filename = date('ymjHis') . '-' . $temp['name'];
            $filetowrite = $imageFolder . $filename;
            move_uploaded_file($temp['tmp_name'], $filetowrite);
            $data = array('file_name' => $filename, 'path_file' => base_url() . $imageFolder, 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
            $this->admin_model->insert_cms_file($data);
            $dataL = array('detail_log' => $this->session->userdata('admin_username') . ' Upload File for CMS', 'log_type' => 'Update Data', 'created_date' => date('Y-m-j H:i:s'));
            $this->admin_model->insert_log($dataL);
            echo json_encode(array('location' => base_url() . '/' . $filetowrite));
        } else {
            header("HTTP/1.0 500 Server Error");
        }
    }
    public function insert_historical_data_entry() {
        $dataUser = array('status_user' => '0', 'survey_status' => '0', 'created_date' => $this->date_time_now(), 'created_by' => 'system');
        $id_user = $this->admin_model->insert_user($dataUser);
        $dataIin = array('iin_number' => '', 'iin_established_date' => '', 'iin_expiry_date' => '', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
        $dataApp = array('applicant' => $this->input->post('applicant'), 'applicant_phone_number' => '', 'application_date' => '', 'application_purpose' => '', 'instance_name' => '', 'instance_email' => '', 'instance_phone' => '', 'instance_director' => '', 'mailing_location' => '', 'mailing_number' => '', 'iin_status' => 'CLOSE', 'application_type' => 'extend', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
        $this->admin_model->insert_iin($dataIin);
        $this->user_model->insert_pengajuan($dataApp);
        redirect(site_url('dashboard/data_entry'));
    }
    public function historycal_data_entry($param) {
        switch ($param) {
            case 'insert':
                $dataUser = array('status_user' => '0', 'survey_status' => '0', 'created_date' => $this->date_time_now(), 'created_by' => 'system');
                $id_user = $this->admin_model->insert_user($dataUser);
                $dataIin = array('id_user' => $id_user, 'iin_number' => $this->input->post('iin_number'), 'iin_established_date' => date_format(date_create($this->input->post('iin_established_date')), 'Y-m-d'), 
                    // 'iin_expiry_date' => date_format(date_create($this->input->post('iin_expiry_date')), 'Y-m-d')
                    'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $dataApp = array('id_user' => $id_user, 'applicant' => $this->input->post('applicant'), 'applicant_phone_number' => $this->input->post('applicant_phone_number'), 'application_date' => date_format(date_create($this->input->post('application_date')), 'Y-m-d'), 'application_purpose' => 'pengawasan', 'instance_name' => $this->input->post('instance_name'), 'instance_email' => $this->input->post('instance_email'), 'instance_phone' => $this->input->post('instance_phone'), 'instance_director' => $this->input->post('instance_director'), 'mailing_location' => $this->input->post('mailing_location'), 'mailing_number' => $this->input->post('mailing_number'), 'iin_status' => 'CLOSED', 'application_type' => 'extend', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Melakukan penmbahan pada historical data entry', 'log_type' => 'data entry ', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->insert_iin($dataIin);
                $this->usr_model->insert_pengajuan($dataApp);
                $this->admin_model->insert_log($log);
                break;
            case 'update':
                $id_iin = array('id_iin' => $this->input->post('id_iin'));
                $id_application = array('id_application' => $this->input->post('id_application'));
                $dataIin = array('iin_number' => $this->input->post('iin_number'), 'iin_established_date' => date_format(date_create($this->input->post('iin_established_date')), 'Y-m-d'), 'iin_expiry_date' => date_format(date_create($this->input->post('iin_expiry_date')), 'Y-m-d'), 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $dataApp = array('applicant' => $this->input->post('applicant'), 'applicant_phone_number' => $this->input->post('applicant_phone_number'), 'application_date' => date_format(date_create($this->input->post('application_date')), 'Y-m-d'), 'application_purpose' => 'pengawasan', 'instance_name' => $this->input->post('instance_name'), 'instance_email' => $this->input->post('instance_email'), 'instance_phone' => $this->input->post('instance_phone'), 'instance_director' => $this->input->post('instance_director'), 'mailing_location' => $this->input->post('mailing_location'), 'mailing_number' => $this->input->post('mailing_number'), 'iin_status' => 'CLOSED', 'application_type' => 'extend', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $log = array('detail_log' => $this->session->userdata('admin_role') . ' Melakukan penmbahan pada historical data entry', 'log_type' => 'data entry ', 'created_date' => $this->date_time_now(), 'created_by' => $this->session->userdata('admin_username'));
                $this->admin_model->update_iin($id_iin, $dataIin);
                $this->admin_model->update_applications($dataApp, $id_application);
                break;
        }
        redirect(site_url('dashboard/data_entry'));
    }
    public function survey_question($param) {
        switch ($param) {
            case 'insert':
                $arrayFirst = array();
                for ($x = 0;sizeof($this->input->post('question_message[]')) > $x;$x++) {
                    $pertanyaan = array('no' => $x + 1, 'msg' => $this->input->post('question_message[]') [$x], 'type' => $this->input->post('question_type[]') [$x]);
                    array_push($arrayFirst, $pertanyaan);
                    // $arrayFirst['SEMPAK'] =

                }
                $dataSurvey = array('question' => json_encode($arrayFirst), 'question_status' => $this->input->post('question_status'), 'created_by' => $this->session->userdata('admin_username'), 'created_date' => $this->date_time_now());
                $idQuestionLast = $this->admin_model->insert_survey($dataSurvey);
                break;
            case 'update':
                $idQuestionLast = array('id_survey_question' => $this->input->post('id_survey_question'));
                $dataSurvey = array('question' => $this->input->post(), 'version' => $this->input->post(), 'question_status' => $this->input->post('question_status'), 'created_by' => $this->session->userdata('admin_username'), 'created_date' => $this->date_time_now());
                $this->admin_model->update_survey($idQuestionLast, $dataSurvey);
                break;
        }
        $this->admin_model->after_insert_or_update($idQuestionLast);
        redirect(site_url('dashboard/settings/survey'));
    }
    public function get_survey_by_prm() {
        $id_ques = $this->input->post('');
        $this->admin_model->get_survey_by_prm($id_ques);
    }
    public function upload_image_acceptor($path) {
        $file_path = "upload/" . $path . "/";
        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.0 500 Invalid file name.");
                return;
            }
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("jpeg", "gif", "jpg", "png"))) {
                $data = array('status' => "HTTP/1.0 500 Invalid extension.");
                echo json_encode($data);
                return;
            }
            $filename = date('ymjHis') . '-' . $temp['name'];
            $filetowrite = $file_path . $filename;
            move_uploaded_file($temp['tmp_name'], $filetowrite);
            $pathinfo = pathinfo($file_path . $filename);
            $data = array('file_name' => $pathinfo['basename'], 'path_file' => $pathinfo['dirname'], 'full_path' => base_url($file_path . $filename), 'extension' => $pathinfo['extension'], 'size' => $temp['size']);
            echo json_encode($data);
        } else {
            header("HTTP/1.0 500 Server Error");
        }
    }
    function do_upload() {
        $this->load->library('upload');
        $this->upload->initialize(array("allowed_types" => "gif|jpg|png|jpeg|pdf|doc", "upload_path" => "./upload/"));
        //Perform upload.
        if ($this->upload->do_upload("images")) {
            echo '<script>console.log(' . var_export($this->upload->data()) . ');</script>';
            $admin_name = 'Rinaldy Sam';
            $doc_step = 'verif_upldoc_req';
            $doc_step_name = 'Verifikasi Kelengkapan Dokumen';
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
    //cari iin berdasarkan id iin
    public function get_iin($prm) {
        $data['iin'] = $this->admin_model->get_iin_by_prm($prm)->result();
        $this->load->view('admin/options/iin_edit', $data);
        // echo json_encode($data);

    }
    //edit data iin
    public function iin_edit_proses() {
        $condition = array('id_iin' => $this->input->post('id_iin'));
        $data = array('id_user' => $this->input->post('id_user'), 'iin_number' => $this->input->post('iin_number'), 'iin_established_date' => date('Y-m-j H:i:s'), 'iin_expiry_date' => date('Y-m-j H:i:s'), 'modified_date' => date('Y-m-j H:i:s')
        // 'modified_by' => $this->session->userdata('username')
        );
        $dataL = array('detail_log' => $this->session->userdata('admin_role') . ' Update Data IIN', 'log_type' => 'Update Data ' . $this->input->post('iin_number'), 'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );
        $this->admin_model->insert_log($dataL);
        $this->admin_model->update_iin($condition, $data);
    }
    //tambah iin
    public function insert_iin_proses() {
        $data = array('id_user' => $this->input->post('id_user'), 'iin_number' => $this->input->post('iin_number'), 'iin_established_date' => date('Y-m-j H:i:s'), 'iin_expiry_date' => date('Y-m-j H:i:s'), 'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );
        $dataL = array('detail_log' => $this->session->userdata('admin_role') . ' adding new IIN', 'log_type' => 'added IIN ' . $this->input->post('iin_number'), 'created_date' => date('Y-m-j H:i:s')
        // 'created_by' => $this->session->userdata('username')
        );
        $this->admin_model->insert_log($dataL);
        $this->admin_model->insert_iin($data);
        $this->get_iin_data();
    }
    public function get_list_cms() {
        echo json_encode($this->admin_model->get_list_cms()->result_array());
    }
}
