<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
    parent::__construct();
    $this->load->database();
        
    }

    //query untuk login berdasarkan username atau email dan pasword sha256
    public function cek_login($username,$password) {  
        $this->db->where('password', $password);
        $this->db->where('username', $username)
                ->or_where('email', $username);        
        return  $this->db->get(TbadmiN);   
    }

    public function insert_log($data){
         $this->db->insert(TbloG, $data);
    }

    public function insert_log2($detail, $type){
        $data = array(
            'detail_log' => $detail,
            'log_type' => $type,
            'created_date' => (new DateTime('Asia/Jakarta'))->format('Y\-m\-d\ h:i:s'),
            'created_by' => $this->session->userdata('admin_username')
            );
         $this->db->insert(TbloG, $data);
    }

    public function get_admin(){
       return $this->db->get(TbadmiN);
    }

    public function get_admin_byprm($prm){
       $this->db->select('*');
        $this->db->from(TbadmiN);
        $this->db->where('id_admin', $prm);
        return $this->db->get();
    }

    public function update_admin($condition,$data){
        $this->db->where($condition);
        $this->db->update(TbadmiN,$data);
    }

    public function insert_admin ($data){
        $this->db->insert(TbadmiN, $data);
    }


    // ALL GET TABLE
    public function get_assessment(){
        return $this->db->get(Tbasmtm);
    }

    public function get_assessment_byid($data){
        $this->db->select('*');
        $this->db->from(Tbasmtm);
        $this->db->where('id_assessment_team', $data);
        return $this->db->get();
    }

    public function get_assessment_by_prm($data){
        $this->db->select('*');
        $this->db->from(Tbasmtm);
        $this->db->like('name', $data);
        return $this->db->get();
    }

    public function update_assessment($condition,$data){
        $this->db->where($condition);
        $this->db->update(Tbasmtm,$data);
    }

    public function insert_assesment($data){
        $this->db->insert(Tbasmtm, $data);
    }

    public function get_assessment_title(){
        return $this->db->get(Tbasmtt);
    }

    public function get_assessment_title_byprm($prm){
        $this->db->select('*');
        $this->db->from(Tbasmtt);
        $this->db->where('id_assessment_team_title', $prm);
        return $this->db->get();
    }

    public function insert_assesment_title($data){
        $this->db->insert(Tbasmtt, $data);
    }

    public function get_user(){
        return $this->db->get(TbuseR);
    }

    public function get_user_by_prm($id){
        $this->db->select('*');
        $this->db->from(TbuseR);
        $this->db->where('id_user',$id);
        return $this->db->get();
    }

    public function update_user($condition,$data){
        $this->db->where($condition);
        $this->db->update(TbuseR,$data);
    }
    //untuk menampilkan data pengajuan iin
    public function get_applications(){
        $this->db->select('*');
        $this->db->from(Tbappst);
        $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        $where = ("applications.iin_status = "."'OPEN'"." and application_status.id_application_status in (select max(id_application_status) from application_status group by id_application)");
        $this->db->where($where);
        $this->db->order_by('application_status.id_application_status','DESC');
        // $this->db->where('application_type','new')
        return $this->db->get();
    }
    public function get_applications_new(){
        $this->db->select('*');
        $this->db->from(Tbappst);
        $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        $where = ("applications.iin_status = "."'OPEN'"." and application_status.id_application_status in (select max(id_application_status) from application_status group by id_application)");
        $this->db->where($where);
        $this->db->where('application_type','new');
        return $this->db->get();
    }
    //untuk menampilkan data pengawasan iin lama
    public function get_applications_ext(){
        $this->db->select('*');
        $this->db->from(Tbappst);
        $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        $where = ("applications.iin_status = "."'OPEN'"." and application_status.id_application_status in (select max(id_application_status) from application_status group by id_application)");
        $this->db->where($where);
        $this->db->where('application_type','extend');

        return $this->db->get();
    }

        //untuk menampilkan data pengawasan yang sudah memiliki IIN
    public function get_applications_finish(){
        $this->db->select('*');
        $this->db->from(Tbappst);
        $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        $where = ("applications.iin_status = "."'OPEN'"." and application_status.id_application_status in (select max(id_application_status) from application_status group by id_application)");
        $this->db->join(TbuseR,Tbuser.'.id_user=applications.id_user');
        $this->db->join(TbiiN,'iin.id_user='.Tbuser.'.id_user');
        $this->db->where($where);
        $this->db->where('application_status.id_application_status_name','19');
        $this->db->where('application_status.process_status','COMPLETED');

        return $this->db->get();
    }

    public function get_data_history()
    {
        $this->db->select('u.id_user, a.applicant, a.instance_email, a.instance_name, a.mailing_location, u.username, i.id_iin, i.iin_established_date, i.iin_expiry_date, i.iin_number, a.id_application');
        $this->db->from(TbiiN.' i');
        $this->db->join(TbuseR.' u', 'u.id_user=i.id_user');
        $this->db->join('applications a', 'u.id_user=a.id_user');
        // $this->db->group_by('u.id_user, i.id_iin, i.iin_established_date, i.iin_expiry_date, i.iin_number');
        $where = ('a.id_application IN (SELECT MAX(id_application) FROM applications)');
        $this->db->where($where);
        
        
        return $this->db->get();
    }

    public function get_data_history_byprm($id_user)
    {
        $this->db->select('*');
        $this->db->from(TbiiN);
        $this->db->join(TbuseR, Tbuser.'.id_user=iin.id_user');
        $this->db->join('applications',Tbuser.'.id_user=applications.id_user');
        $this->db->where('iin.id_user',$id_user);
        return $this->db->get();
    }


    public function get_application($id_application_status){
        $this->db->select("*");
        $this->db->from("application_status")
        ->join('applications','application_status.id_application = applications.id_application');
        $this->db->where('id_application_status', $id_application_status); 
        return $this->db->get();
    }

    public function get_doc_user($id_application){
        $this->db->select("*");
        $this->db->from("application_file");
        $this->db->join("document_config", "document_config.id_document_config=application_file.id_document_config");
        $this->db->where("id_application",$id_application);
        return $this->db->get();
    }

    public function next_step($data,$condition){
        $this->db->where($condition);
        $this->db->update(Tbappst,$data);
    }

    public function insert_app_status($data){
       $this->db->insert(Tbappst, $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function insert_app_sts_for_map($data){
        $this->db->insert('application_status_form_mapping', $data);
    }

    public function get_application_file($id_application_status){
            $this->db->select("*");
            $this->db->from("application_status");
            $this->db->join('applications','application_status.id_application = applications.id_application');
            $this->db->join('application_file','application_file.id_application=applications.id_application');
            $this->db->join(Tbdoccfg,'document_config.id_document_config=application_file.id_document_config');
            $this->db->where('id_application_status', $id_application_status);
            return $this->db->get();
    }

    public function insert_application_file($data){
        $this->db->insert('application_file', $data);
    }

    public function get_user_survey($user){
         $this->db->select("*");
            $this->db->from("applications")
            ->join
             (
                TbuseR,
                Tbuser.'.id_user = applications.id_user'
             )
             ->join
             (
                'survey_answer',
                'survey_answer.id_user='.tbuser.'.id_user'
             );
            $this->db->where('id_user', $user); 
            return $this->db->get();
    }

    public function get_has_iin($user)
    {
        $this->db->select("*");
            $this->db->from("applications")
            ->join
             (
                TbuseR,
                Tbuser.'.id_user = applications.id_user'
             )
             ->join
             (
                TbiiN,
                'iin.id_user='.tbuser.'.id_user'
             );
            $this->db->where('id_user', $user); 
            return $this->db->get();
    }    

    // public function insert_assesment_application($data){
    //      $this->db->insert('assesment_application', $data);
    // }

    public function get_assesment_application($id){
        $this->db->select('*');
        $this->db->from(Tbappst);
        $this->db->where('id_application', $id);
        $this->db->where('assessment_status', 'OPEN');
        return $this->db->get();
    }

    public function insert_assessment_registered($data){
         $this->db->insert('assessment_registered', $data);
    }

    public function insert_assessment_application($data){
        $this->db->insert('assessment_application', $data);
         $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function get_assessment_team($data){
        $this->db->select('id_assessment_team, name');
        $this->db->from(Tbasmtm);
        $this->db->like('name', $data);
        $this->db->where('status','active');
        return $this->db->get();
    }

    public function get_assessment_team_title(){
        return $this->db->get(Tbasmtt);
    }

    public function update_assessment_team_title($condition,$data){
        $this->db->where($condition);
        $this->db->update(Tbasmtt,$data);
    }

    public function get_document_config($param){
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->like('type', $param);
        return $this->db->get();
    }

    public function get_document_by_prm($id){
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->where('id_document_config',$id);
        return $this->db->get();
    }

    public function get_document_by_display($display){
        $this->db->select('id_document_config, display_name, keys');
        $this->db->from(Tbdoccfg);
        $this->db->like('display_name',$display);
        return $this->db->get();
    }

    public function update_documenet_config($condition,$data){
        $this->db->where($condition);
        $this->db->update(Tbdoccfg,$data);
    }

    public function insert_documenet_config($data){
        $this->db->insert('assessment_application', $data);
    }

    public function question_survey_question(){
       $this->db->select('*');
       $this->db->from('survey_question');
       $this->db->order_by('id_survey_question', 'desc');
       return $this->db->get();

    }

    public function get_iin(){
       return $this->db->get(TbiiN);
    }

    public function get_iin_by_prm($id){
        $this->db->select('*');
        $this->db->from(TbiiN);
        $this->db->where('id_iin',$id);
        return $this->db->get();
    }

    public function update_iin($condition,$data){
        $this->db->where($condition);
        $this->db->update(TbiiN,$data);
    }

    public function insert_iin($data){
        $this->db->insert(TbiiN, $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function get_cms_active(){
        $this->db->select('*');
        $this->db->from('cms');
        $this->db->where('status','Y');
        return $this->db->get();
    }

    public function get_cms(){
       return $this ->db-> get('cms');
    }

    public function insert_cms($data){
        $this->db->insert('cms', $data);
    }

    public function get_cms_by_prm($id){
        $this->db->select('*');
        $this->db->from('cms');
        $this->db->where('id_cms',$id);
        return $this->db->get();
    }

    public function get_list_cms(){
        $this->db->select('id_cms, url, title');
        $this->db->from('cms');
        $this->db->where('status','Y');
        return $this->db->get();
    }

    public function update_cms($condition,$data){
        $this->db->where($condition);
        $this->db->update('cms',$data);
    }

    public function insert_cms_file($data){
        $this->db->insert('cms_file', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function update_cms_file($condition,$data){
        $this->db->where($condition);
        $this->db->update('cms_file',$data);
    }

    public function get_complaint(){
        $this->db->select('us.username, co.*');
        $this->db->from('complaint co');
        $this->db->join(Tbuser.' us','co.id_user = us.id_user');
        return $this->db->get();
    }

    public function insert_document_config($data){
        $this->db->insert(Tbdoccfg, $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    //untuk laporan cetak laporan smentara lihat ini dulu
    public function get_application_data($data){
        $this->db->get('applications');
    }

    //untuk select email user
    public function get_user_application_data($data){
        
        $this->db->select('*');
        $this->db->from('applications');
        $this->db->where('id_application',$data);
        return $this->db->get();

    }

    //list document untuk user mengupload
    public function get_doc_for_user()
    {
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
            $con = 'type="DYNAMIC" and mandatory = "1"';
        $this->db->where($con);
        // $this->db->or('type','DYNAMIC');
        // $this->db->where('mandatory','1');

        return $this->db->get();
    }

    //masukkan ke tabel document2 yang harus diupload user
    public function insert_doc_for_user($prm)
    {
        
        $this->db->insert('application_file', $prm);
    }

    public function get_pay($prm)
    {
        $this->db->select('*');
        $this->db->from('application_file');
        $this->db->join(Tbdoccfg, 'document_config.id_document_config=application_file.id_document_config');
            // $con = 'application_file.id_application = "'.$prm.'" AND application_file.id_document_config = "24" ';
        // $this->db->where($con);    
        $this->db->where('application_file.id_application', $prm);
        $this->db->where('application_file.id_document_config', '21');
        $this->db->order_by('application_file.id_application_file', 'desc');
        return $this->db->get();
    }

    public function get_doc_bill_res()
    {
        $this->db->select('*');
        $this->db->from('document_config dc');
            $con = 'dc.keys = "KBS" 
            or dc.keys="SPNP" or dc.keys="SPPNBP"';
        $this->db->where('dc.keys', 'KBS')
        ->or_where('dc.keys', 'SPNP')
        ->or_where('dc.keys', 'SPPNBP');

        return $this->db->get();
    }
    //untuk mengambil document berita acara dan juga hasil asessment lapangan
    public function get_news_for_user()
    {
        $this->db->select('*');
        $this->db->from('document_config dc');
            $con = 'dc.keys = "BA" 
            or dc.keys="HAL" ';
        $this->db->where('dc.keys', 'BA')
        ->or_where('dc.keys', 'HAL');

        return $this->db->get();
    }

    //untuk mengambil document Usulan Tim Verivikasi Lapangan dan Surat Informasi Tim Verifikasi Lapangan IIN
    public function get_letter_of_assignment()
    {
        $this->db->select('id_document_config, keys, display_name, file_url');
        $this->db->from('document_config dc');
        $this->db->where('keys','UTVLI');
        $this->db->or_where('keys','SITVL');
        return $this->db->get();
    }

    //untuk mengambil document surat Surat Penugasan Tim Penugasan Asessment Lapangan
    public function get_letter_of_assignment_SPTAL()
    {
        $this->db->select('id_document_config, keys, display_name, file_url');
        $this->db->from('document_config dc');
        $this->db->where('keys','SPTAL');
        return $this->db->get();
    }

    public function get_assesment_application_byprm($prm)
    {
        $this->db->select('*');
        $this->db->from('assessment_application');
        $this->db->where('id_application',$prm);
        $this->db->where('assessment_status','OPEN');
        return $this->db->get();
    }

    public function get_data_for_mail($prm)
    {
        $this->db->select('*');
        $this->db->from('applications');
        $this->db->join(TbuseR, 'applications.id_user='.tbuser.'.id_user');
        $this->db->where('applications.id_application',$prm);
        return $this->db->get(); 
    }

  

    public function get_applications_by_prm($prm)
    {
        $this->db->select('*');
        $this->db->from('applications');
        $this->db->where('id_application',$prm);
        return $this->db->get(); 
    }

    public function update_applications($data,$prm)
    {
        $this->db->where($prm);
        $this->db->update('applications',$data);
    }

    public function application_file_get_transaction($id_app)
    {
        $this->db->select('*');
        $this->db->from('application_file');
        $this->db->where('id_application',$id_app);
        $this->db->where('status','ACTIVE');
        $this->db->where('id_document_config','24');
        return $this->db->get(); 
    }

    public function application_file_update($condition,$data)
    {
        $this->db->where($condition);
        $this->db->update('application_file',$data);
    }

    public function document_config_get_by_key()
    {
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->where('keys','BT PT');
        return $this->db->get(); 
    }

    public function document_config_get_by_prm_key($key)
    {
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->where('keys',$key);
        return $this->db->get(); 
    }

    public function application_file_get_by_idapp_iddc($idapp,$iddc)
    {
        $this->db->select('*');
        $this->db->from('application_file');
        $this->db->where('id_application',$idapp);
        $this->db->where('id_document_config',$iddc);
        $this->db->order_by('id_application_file', 'desc');
        return $this->db->get(); 
    }

    public function application_status_form_mapping_rev_by_idapp($idapp,$id_app_status)
    {
        $sub = $this->db->select('application_status_form_mapping.value as keys');
        $sub = $this->db->from(Tbappst)
        ->join('applications','application_status.id_application=applications.id_application')
        ->join('application_status_form_mapping','application_status.id_application_status=application_status_form_mapping.id_application_status');
        $sub = $this->db->where('applications.id_application',$idapp)
        ->where('application_status_form_mapping.id_application_status', $id_app_status)
        ->like('application_status_form_mapping.type','REVISION_FILE');
        $sub = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('applications.applicant, applications.id_application, document_config.keys, document_config.display_name, document_config.id_document_config,application_file.path_file');
        $this->db->from('applications');
        $this->db->join('application_file', 'applications.id_application=application_file.id_application');
        $this->db->join(Tbdoccfg, 'document_config.id_document_config=application_file.id_document_config');
        $this->db->join(Tbappst, 'applications.id_application=application_status.id_application');
        $this->db->where('application_file.id_application', $idapp);

        $this->db->where('application_file.status','ACTIVE');
        $this->db->where_in('document_config.keys',$sub, false);



        return $this->db->get(); 
    }


    public function get_doc_cra()
    {
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->where('keys','CRADOC');
        $this->db->where('mandatory','1');
        return $this->db->get();
    }

    public function get_doc_iin()
    {
        $this->db->select('*');
        $this->db->from(Tbdoccfg);
        $this->db->where('keys',TBIIN);
        
        return $this->db->get();
    }

    public function get_date_rev($id_app_status)
    {
        $this->db->select('id_application_status, type, value');
        $this->db->from('application_status_form_mapping');
        $this->db->where('id_application_status', $id_app_status);
        
        return $this->db->get();
    }

    public function get_doc_conf_by_name($name)
    {
        $this->db->select('id_document_config, keys, display_name');
        $this->db->from(Tbdoccfg);
        $this->db->where('display_name', $name);
        
        return $this->db->get();
    }

//ini dicoba dulu dengan menggunakan function 
    public function get_revised_resuld_assessment($idapp,$id_app_status)
    {
        $sub = $this->db->select('application_status_form_mapping.value as keys');
        $sub = $this->db->from(Tbappst)
        ->join('applications','application_status.id_application=applications.id_application')
        ->join('application_status_form_mapping','application_status.id_application_status=application_status_form_mapping.id_application_status');
        $sub = $this->db->where('applications.id_application',$idapp)
        ->where('application_status_form_mapping.id_application_status', $id_app_status)
        ->like('application_status_form_mapping.type','REVISION_ASSESSMENT_FILE');
        $sub = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('applications.applicant ,applications.id_application, document_config.keys, document_config.display_name, document_config.id_document_config, application_file.path_file');
        $this->db->from('applications');
        $this->db->join('application_file', 'applications.id_application=application_file.id_application');
        $this->db->join(Tbdoccfg, 'document_config.id_document_config=application_file.id_document_config');
        $this->db->join(Tbappst, 'applications.id_application=application_status.id_application');
        $this->db->where('application_file.id_application', $idapp);

        $this->db->where('application_file.status','ACTIVE');
        $this->db->where_in('document_config.id_document_config',$sub, false);

        return $this->db->get(); 
    }

    public function get_notif($notifikation_type){
        $this->db->select('*');
        $this->db->from('notification');
        $this->db->where('notification_type', $notifikation_type);
        // $this->db->where('Status !=', 'INACTIVE');
       
        $this->db->order_by('id_notification', 'desc');
         $this->db->limit(25,0);
        return $this->db->get();
    }

    public function update_notif($condition)
    {
        $data = array('Status' => 'INACTIVE' );
        $this->db->where('id_notification', $condition);
        $this->db->update('notification',$data);
    }

    public function insert_notif($data)
    {
        $this->db->insert('notification', $data);
    }


    public function get_instance_list($data){
        $this->db->select('instance_name');
        $this->db->from('applications');
        $this->db->like('instance_name',$data);
        return $this->db->get();
    }

    public function insert_user($dataUser)
    {
        $this->db->insert(TbuseR, $dataUser);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;        
    }

    public function insert_survey($dataSurvey)
    {
        $this->db->insert('survey_question',$dataSurvey);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    public function update_survey($condition,$data) 
    {   
        $this->db->where($condition);
        $this->db->update('survey_question',$data);
    }

    public function after_insert_or_update($idsurvey)
    {
        $tatus = array('question_status' => '0');
        $this->db->where('id_survey_question !=',$idsurvey);
        $this->db->update('survey_question', $tatus);
    }

    public function get_survey_by_prm($id_survey)
    {
        $idSurvey = array('id_survey_question' => $id_survey);

        $this->db->select('*');
        $this->db->from('survey_question');
        $this->db->where($idSurvey);

        return $this->db->get();
    }

    public function date_time_now() {
        /*
        SET TIMEZONE ASIA/JAKARTA
        */
        $datetime = new DateTime('Asia/Jakarta');
        return $datetime->format('Y\-m\-d\ h:i:s');
    }

    public function insert_banner($data)
    {
        $this->db->insert('banner',$data);
        $inserted_id = $this->db->insert_id();

        
        return $inserted_id;

    }

    public function update_banner($id_banner,$data)
    {
        $this->db->where('id_banner',$id_banner);
        $this->db->update('banner', $data);

    }

    public function get_banner($data = null)
    {
        $this->db->select('*');
        $this->db->from('banner');
        if($data!=null)
        {
            $this->db->where('id_banner',$data);
        }
        return $this->db->get();
    }
    
    // ALDY SOURCE CODE
     public function survey($type, $data)
     {
        switch ($type) {

            case 'vote':
                $this->db->select('*');
                $this->db->from('survey_question sq');
                $this->db->where('sq.question_status','1');
                return $this->db->get();
                break;
            case 'insert-answer':
                // TYPE CODE HERE FOR ANSWER FROM USER
                $this->db->insert('survey_answer',$data);
                $inserted_id = $this->db->insert_id();
                return $inserted_id;

                break;
            case 'get-survey-result':
                // QUERY BERDASARKAN survey_question yang aktif
                // 
                // CONTOH 
                // NILAI     JUMLAH ORANG YANG MENJAWAB
                // 1         * 0                           = 0
                // 2         * 0                           = 0
                // 3         * 3                           = 9
                // 4         * 3                           = 12
                // 5         * 9                           = 45
                //      total= 15                     total= 66
                // lalu 66/15 = 4.4
                // nilai rata-rata 4.4

                // CONTOH HASIL QUERY RESULT() JIKA DI UBAH KE JSON
                // {
                //   "id_survey_question": "1",
                //   "version"           : "1",
                //   "total_answer"      : "15",
                //   "survey_questions"  : [
                //     {
                //       "no"       : "1",
                //       "question" : "Question number 1",
                //       "average"  : "4.4",
                //       "answer": {
                //         "1": "0",
                //         "2": "0",
                //         "3": "3",
                //         "4": "3",
                //         "5": "9"
                //       }
                //     },
                //     {
                //       "no": "2",
                //       "question": "Question number 2",
                //       "average"  : "4.4",
                //       "answer": {
                //         "1": "0",
                //         "2": "0",
                //         "3": "3",
                //         "4": "3",
                //         "5": "9"
                //       }
                //     }
                //   ]
                // }

            $this->db->select('*');
            $this->db->from('survey_question sq');
            $this->db->join('survey_answer sa','sq.id_survey_question=sa.version');
            $this->db->where('sq.question_status','1');

            return $this->db->get();
                break;
        }
        
    }


    public function reupl_bil_rec_update($id_app,$id_doc_con)
    {
        $data = array('id_application' => $id_app,
            'id_document_config' => $id_doc_con,
            'status' => 'ACTIVE');
        $sts = array('status'=>'INACTIVE');
        $this->db->where($data);
        $this->db->update('application_file', $sts);

    }

    public function form_mapping_exp_date($idapps)
    {
        $this->db->select('*');
        $this->db->from('application_status_form_mapping');
        $this->db->where('id_application_status',$idapps);
        $this->db->where('type', 'BILLING_DATE');

        return $this->db->get();
    }

    public function exp_bill_simponi($id_application,$data)
    {
        $this->db->where($id_application);
        $this->db->update('application_file', $data);
    }

    public function get_applications_for_exp(){
        $this->db->select('*');
        $this->db->from('applications app');
        $this->db->join('application_status apps', 'app.id_application = apps.id_application');
        $this->db->where('apps.id_application_status_name','7');
        $this->db->where('apps.process_status','PENDING');
        $this->db->where('app.iin_status', 'OPEN');                        
        return $this->db->get();
    }

      public function get_id_before($id_application){
        $this->db->select('*');
        $this->db->from('application_status');
        $this->db->where('id_application',$id_application);
        $this->db->where('process_status', 'COMPLETED');     
        $this->db->order_by('id_application_status', 'DESC'); 
        $this->db->limit('1');                    
        return $this->db->get();
    }

}
?>
