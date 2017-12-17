<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
    parent::__construct();
    $this->load->database();
        
    }

    //query untuk login berdasarkan username atau email dan pasword sha256
    public function cek_login($username,$password) {  
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $this->db->where('password', $password);
        return  $this->db->get('admin');   
    }

    public function insert_log($data){
         $this->db->insert('log', $data);
    }

    public function get_admin(){
       return $this->db->get('admin');
    }

    public function get_admin_byprm($prm){
       $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('id_admin', $prm);
        return $this->db->get();
    }

    public function update_admin($condition,$data){
        $this->db->where($condition);
        $this->db->update('admin',$data);
    }

    public function insert_admin ($data){
        $this->db->insert('admin', $data);
    }


    // ALL GET TABLE
    public function get_assessment(){
        return $this->db->get('assessment_team');
    }

    public function get_assessment_byid($data){
        $this->db->select('*');
        $this->db->from('assessment_team');
        $this->db->where('id_assessment_team', $data);
        return $this->db->get();
    }

    public function update_assessment($condition,$data){
        $this->db->where($condition);
        $this->db->update('assessment_team',$data);
    }

    public function insert_assesment($data){
        $this->db->insert('assessment_team', $data);
    }

    public function get_assessment_title(){
        return $this->db->get('assessment_team_title');
    }

    public function get_assessment_title_byprm($prm){
        $this->db->select('*');
        $this->db->from('assessment_team_title');
        $this->db->where('id_assessment_team_title', $prm);
        return $this->db->get();
    }

    public function insert_assesment_title($data){
        $this->db->insert('assessment_team_title', $data);
    }

    public function get_user(){
        return $this->db->get('user');
    }

    public function get_user_by_prm($id){
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('id_user',$id);
        return $this->db->get();
    }

    public function update_user($condition,$data){
        $this->db->where($condition);
        $this->db->update('user',$data);
    }
    //untuk menampilkan data pengajuan iin
    public function get_applications(){
        $this->db->select('*');
        $this->db->from('application_status');
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
        $this->db->from('application_status');
        $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        $where = ("applications.iin_status = "."'OPEN'"." and application_status.id_application_status in (select max(id_application_status) from application_status group by id_application)");
        $this->db->where($where);
        $this->db->where('application_type','extend');

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
        $this->db->update('application_status',$data);
    }

    public function insert_app_status($data){
       $this->db->insert('application_status', $data);
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
            $this->db->join('document_config','document_config.id_document_config=application_file.id_document_config');
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
                'user',
                'user.id_user = applications.id_user'
             )
             ->join
             (
                'survey_answer',
                'survey_answer.id_user=user.id_user'
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
                'user',
                'user.id_user = applications.id_user'
             )
             ->join
             (
                'iin',
                'iin.id_user=user.id_user'
             );
            $this->db->where('id_user', $user); 
            return $this->db->get();
    }    

    // public function insert_assesment_application($data){
    //      $this->db->insert('assesment_application', $data);
    // }

    public function get_assesment_application($id){
        $this->db->select('*');
        $this->db->from('application_status');
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
        $this->db->select('*');
        $this->db->from('assessment_team');
        $this->db->like('name', $data);
        $this->db->where('status','active');
        return $this->db->get();
    }

    public function get_assessment_team_title(){
        return $this->db->get('assessment_team_title');
    }

    public function update_assessment_team_title($condition,$data){
        $this->db->where($condition);
        $this->db->update('assessment_team_title',$data);
    }

    public function get_document(){
        return $this->db->get('document_config');
    }

    public function get_document_by_prm($id){
        $this->db->select('*');
        $this->db->from('document_config');
        $this->db->where('id_document_config',$id);
        return $this->db->get();
    }

    public function update_documenet_config($condition,$data){
        $this->db->where($condition);
        $this->db->update('document_config',$data);
    }

    public function insert_documenet_config($data){
        $this->db->insert('assessment_application', $data);
    }

    public function question_survey_question(){
       return $this->db->get('survey_question');
    }

    public function get_iin(){
       return $this->db->get('iin');
    }

    public function get_iin_by_prm($id){
        $this->db->select('*');
        $this->db->from('iin');
        $this->db->where('id_iin',$id);
        return $this->db->get();
    }

    public function update_iin($condition,$data){
        $this->db->where($condition);
        $this->db->update('iin',$data);
    }

    public function insert_iin($data){
        $this->db->insert('iin', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
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

    public function update_cms($condition,$data){
        $this->db->where($condition);
        $this->db->update('cms',$data);
    }

    public function get_conplain(){
        return $this->db->get('complaint');
    }

    public function insert_document_config($data){
        $this->db->insert('document_config', $data);
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
        $this->db->from('document_config');
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
        $this->db->join('document_config', 'document_config.id_document_config=application_file.id_document_config');
            // $con = 'application_file.id_application = "'.$prm.'" AND application_file.id_document_config = "24" ';
        // $this->db->where($con);    
        $this->db->where('application_file.id_application', $prm);
        $this->db->where('application_file.id_document_config', '24');
        return $this->db->get();
    }

    public function get_doc_bill_res()
    {
        $this->db->select('*');
        $this->db->from('document_config dc');
            $con = 'dc.key = "KBS" 
            or dc.key="SPNP" or dc.key="SPPNBP"';
        $this->db->where($con);

        return $this->db->get();
    }
    //untuk mengambil document berita acara dan juga hasil asessment lapangan
    public function get_news_for_user()
    {
        $this->db->select('*');
        $this->db->from('document_config dc');
            $con = 'dc.key = "BA" 
            or dc.key="HAL" ';
        $this->db->where($con);

        return $this->db->get();
    }

    //untuk mengambil document surat penugasan tim assesment
    public function get_letter_of_assignment()
    {
        $this->db->select('*');
        $this->db->from('document_config dc');
        $this->db->where('key','SPTAL');

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
        $this->db->join('user', 'applications.id_user=user.id_user');
        $this->db->where('applications.id_application',$prm);
        return $this->db->get(); 
    }

    public function all_dat()
    {
        $this->db->select('*');
        $this->db->from('complaint');

        
        $this->db->from('document_config');

        
        $this->db->from('applications');

        
        $this->db->from('user');

        
        $this->db->from('admin');

        
        $this->db->from('survey_question');

        
        $this->db->from('cms');

        
        $this->db->from('assessment_team_title');

        
        $this->db->from('assessment_team');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
        // return $this->db->get('');
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
        $this->db->from('document_config');
        $this->db->where('key','BT PT');
        return $this->db->get(); 
    }

    public function document_config_get_by_prm_key($key)
    {
        $this->db->select('*');
        $this->db->from('document_config');
        $this->db->where('key',$key);
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

    public function application_status_form_mapping_rev_by_idapp5($idapp,$id_app_status)
    {
        $sub = $this->db->select('application_status_form_mapping.value as key');
        $sub = $this->db->from('application_status')
        ->join('applications','application_status.id_application=applications.id_application')
        ->join('application_status_form_mapping','application_status.id_application_status=application_status_form_mapping.id_application_status');
        $sub = $this->db->where('applications.id_application',$idapp)
        ->where('application_status_form_mapping.id_application_status', $id_app_status)
        ->like('application_status_form_mapping.type','REVISED_DOC');
        $sub = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('applications.applicant, applications.id_application, document_config.key, document_config.display_name, document_config.id_document_config');
        $this->db->from('applications');
        $this->db->join('application_file', 'applications.id_application=application_file.id_application');
        $this->db->join('document_config', 'document_config.id_document_config=application_file.id_document_config');
        $this->db->join('application_status', 'applications.id_application=application_status.id_application');
        $this->db->where('application_file.id_application', $idapp);

        $this->db->where('application_file.status','ACTIVE');
        $this->db->where_in('document_config.key',$sub, false);



        return $this->db->get(); 
    }

    public function application_status_form_mapping_rev_by_idapp($idapp)
    {
        $sub = $this->db->select('application_status_form_mapping.value as key');
        $sub = $this->db->from('application_status')
        ->join('applications','application_status.id_application=applications.id_application')
        ->join('application_status_form_mapping','application_status.id_application_status=application_status_form_mapping.id_application_status');
        $sub = $this->db->where('applications.id_application',$idapp)
        ->like('application_status_form_mapping.type','REVISED_DOC');
        $sub = $this->db->get_compiled_select();

        $this->db->distinct();
        $this->db->select('applications.applicant, applications.id_application, document_config.key, document_config.display_name, document_config.id_document_config');
        $this->db->from('applications');
        $this->db->join('application_file', 'applications.id_application=application_file.id_application');
        $this->db->join('document_config', 'document_config.id_document_config=application_file.id_document_config');
        $this->db->join('application_status', 'applications.id_application=application_status.id_application');
        $this->db->where('application_file.id_application', $idapp);
        $this->db->where('application_status.id_application_status_name','5');
        $this->db->where('application_file.status','ACTIVE');
        $this->db->where_in('document_config.key',$sub, false);



        return $this->db->get(); 
    }

    public function get_doc_cra()
    {
        $this->db->select('*');
        $this->db->from('document_config');
        $this->db->where('key','CRADOC');
        $this->db->where('mandatory','1');
        return $this->db->get();
    }

    public function get_doc_iin()
    {
        $this->db->select('*');
        $this->db->from('document_config');
        $this->db->where('key','IIN');
        
        return $this->db->get();
    }
}
?>
