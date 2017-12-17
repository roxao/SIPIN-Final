<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {
    
    /*construct function*/
    public function __construct() {
        parent::__construct();
        $this->load->database();    
    }
    
    /*Registrasi*/
    public function register_user($email, $username, $password, $name) {
        $data = array(
            'email'      => $email,
            'username'   => $username,
            'password'   => $password,
            'name'   => $name,
            'created_date'   => date('Y-m-j H:i:s'),
            'created_by'   => $name,
            'modified_date' => "",
            'modified_by'   => "",
            'status_user'   => "0",
            'survey_status'   => "0",
            );
        return $this->db->insert('user', $data);        
    }



// public function cek_login($username,$password){ 
//     $this->db->select('*');
//     $this->db->from('user'); 
//     $this->db->join('iin', 'user.id_user=applications.id_user');
//     $this->db->where("user.email = '$username' or user.username = '$username' or iin.number = '$username' ");
//     $this->db->where('user.password', $password);         
//     $query = $this->db->get(); 
 
//         return  $query;   
//     }

    public function cek_login2($username,$password){  
        $this->db->where("email = '$username' or username = '$username'");  
        $this->db->where('password', $password); 
        return  $this->db->get('user');   
    }

    /*
    This Query to Validate :
    - User login 
    - Already have iin? 
    - Any Open Application?
    - Application Type?
    */
    public function cek_login($username,$password){  
        $this->db->select('MAX(ap.id_application) AS id_application,us.id_user,us.username, us.email, us.status_user, ii.iin_number, MAX(ap.iin_status) AS iin_status, ap.application_type, us.survey_status');
        $this->db->from('user us'); 
        $this->db->join('applications ap', 'us.id_user = ap.id_user','left');
        $this->db->join('iin ii', 'us.id_user = ii.id_user','left');
        $this->db->where("us.email = '$username' or us.username = '$username' or ii.iin_number = '$username' ");
        $this->db->where('us.password', $password);
        $query = $this->db->get(); 
 
        return  $query;    
    }

    /*
    Validate if there is more than 1 OPEN(iin_status) Application
    */
    public function step_0_validation_1($id_user){  
        $this->db->select('COUNT(*) AS totals');
        $this->db->from('applications'); 
        $this->db->where('id_user', $id_user);
        $this->db->where('iin_status', 'OPEN');
        $query = $this->db->get(); 
 
        return  $query;    
    }

    /*
    Get all fields for box_status_0
    */
    public function step_0_get_application($id_user){  
        // SELECT * FROM applications WHERE applicant LIKE '%novalen%' AND iin_status ='OPEN' ORDER BY id_application DESC;
        // echo $id_user;
        $this->db->select('*');
        $this->db->from('applications'); 
        $this->db->where('id_user', $id_user);
        $this->db->where('iin_status', 'OPEN');
        // $this->db->order_by('id_application', 'DESC');
        $query = $this->db->get(); 
 
        return  $query;    
    }



    /**
    GET id application STATUS  ^^^ 
    */
    public function get_form_id_app_status($id_application, $id_application_status_name){  
        $this->db->select('*');
        $this->db->from('application_status a'); 
        $this->db->join('application_status_form_mapping b', 'a.id_application_status = b.id_application_status');
        $this->db->join('document_config c', 'c.key = b.value','left');
        $this->db->join('application_file d', 'd.id_document_config = c.id_document_config AND d.id_application = a.id_application','left');
        $this->db->where('a.id_application_status_name', $id_application_status_name);
        $this->db->where('a.id_application', $id_application);

        return  $this->db->get();   
    }
    /**
    GET id application STATUS ^^^
    */
    public function get_form_mapping_by_id_aps($id_application_status){  
        $this->db->select('*');
        $this->db->from('application_status_form_mapping'); 
        $this->db->where('id_application_status', $id_application_status);
 
        return  $this->db->get();   
    }












    /*Check any open application*/
    // public function validate_active_application($username) {  
    //     $this->db->select('ap.iin_status');
    //     $this->db->from('user us'); 
    //     $this->db->join('applications ap', 'us.id_user = ap.id_user');
    //     $this->db->join('iin ii', 'us.id_user = ii.id_user');
    //     $this->db->where("email = '$username' or username = '$username'");
    //     return  $this->db->get('applications'); 
    // }

    public function get_all_notifikasi($id_user){  
        $this->db->where('notification_owner ','$id_user');  
        return  $this->db->get('notification');   
    }

    public function get_count_notifikasi($id_user){  
    $this->db->where('notification_owner ','$id_user');
     $this->db->where('Status ','FALSE');   
        return  $this->db->get('notification');   
    }

    /*Forgot Password*/
    public function forgot_password($username){ 
        $this->db->select('*');
        $this->db->from('user'); 
        $this->db->join('applications', 'user.id_user=applications.id_user');
        $this->db->where("user.email = '$username' or user.username = '$username' or instance_name = '$username'");        
        $query = $this->db->get(); 
 
        return  $query;   
    }

    /*Kondisi dimana user ingin mengganti password*/
    public function Update_password($id_user, $modified_by, $password)
    {
        $data = array('password' => $password,
            'modified_by' => $modified_by,
            'modified_date' => date('Y-m-j H:i:s'));
        $this->db->where('id_user', $id_user);


        return $this->db->update('user', $data);
    }

    /*Melkukan pengecekan file untuk didownload di step2*/
    public function getdocument_aplication($id_user){ 
    $this->db->select('*');
    $this->db->from('applications'); 
    $this->db->join('application_file', 'applications.id_application=application_file.id_application');
    $this->db->join('document_config', 'application_file.id_document_config=document_config.id_document_config');
    $this->db->where('applications.id_user',$id_user);
    $this->db->where('applications.iin_status',"OPEN");
    // $this->db->where('document_config.type',"DYNAMIC");
    $this->db->order_by('document_config.id_document_config', 'ASC');

    $query = $this->db->get(); 
    $results = $query->result();
 
        return  $results ;   
    }

    //menampilkan data documen yang harus di download user typenya yang static
    public function get_doc_statis()
    {
        $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url', 'dc.mandatory');
        $this->db->from('document_config dc');
        $this->db->where('dc.type','DYNAMIC');
        $this->db->or_where('dc.type','STATIC');
        $this->db->or_where('dc.type','TRANSACTIONAL');
        return $this->db->get()->result();
    }


    /*
    
    */
    // public function get_doc_iin()
    // {
    //     $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url', 'dc.mandatory');
    //     $this->db->from('document_config dc');
    //     $this->db->where('dc.type','DYNAMIC');
    //     $this->db->or_where('dc.type','STATIC');
    //     return $this->db->get()->result();
    // }


    /*
    Get Doc User
    */
    public function get_doc_user_upload($key,$id_application,$id_keys)
    {
        // $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url ,dc.mandatory');
        $this->db->from('document_config dc');
        

        /*
        get only list of file that provided by users
        @ using limit
        */
        if ( $key != '' ) {
            if ($key[0] != 'BT PT') {

            $this->db->where('dc.type','DYNAMIC');
            }
            // $this->db->limit($limit);
            $this->db->where_in('dc.key', $key);
        }

        if ( $id_application != '' ) {

            $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url ,dc.mandatory,af.path_id');
            $this->db->join('application_file af', 'dc.id_document_config = af.id_document_config');
            $this->db->where('af.id_application', $id_application);

            if ($id_keys != '') {
                $this->db->where('dc.type', 'TRANSACTIONAL');
                $this->db->where('af.status','ACTIVE');

                $this->db->where_in('dc.key', $id_keys);


                $this->db->limit(count($id_keys));

            } else {    
                $this->db->where('dc.type', 'DYNAMIC');
                $this->db->where('af.status','ACTIVE');
            }


        } 
        else {
            $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url ,dc.mandatory');
            if($key == ''){
            $this->db->where('dc.type','DYNAMIC');
            }
        }


        // $this->db->where('af.status','ACTIVE');

        $this->db->order_by('dc.id_document_config', 'ASC');
            
        return $this->db->get()->result();
    }

    /*
    GET id_application_status step
    */

    public function id_application_status_step_n($id_application, $prev_id_app_status_name)
    {
        $this->db->select('aps.id_application_status');
        $this->db->from('applications ap');
        $this->db->join('application_status aps', 'ap.id_application = aps.id_application');
        $this->db->where('ap.id_application', $id_application);
        $this->db->where('aps.id_application_status_name', $prev_id_app_status_name);
        $this->db->where('aps.process_status', 'COMPLETED');
        $this->db->order_by('aps.id_application_status', 'DESC');
        $this->db->limit('1');
            
        return $this->db->get()->result();
    }


    /*
    GET Index(KEY) of revision documents
    @Table : application_status_form_mapping
    */
    public function get_index_rev_doc($id_application_status)
    {
        $this->db->select('value, id_application_status_form_mapping');
        $this->db->from('application_status_form_mapping'); 
        $this->db->where('id_application_status', $id_application_status);
        
        $this->db->like('type', 'REVISED_DOC');

        return $this->db->get()->result();
    }

    /*
    Table application_status_form_mapping
    @ $type = REVISED_DOC | List Index of Revision File {step2}
    @ $type = REVISED_PAY | Admin Message for Revision Payment {step5}
    @ $type = BILLING_CODE | get billing code {step4}
    @ $type = BILLING_DATE | get billing date {step4}
    @ $type = BILLING_DOC | get list of billing doc value (document_config_id) {step4}
    @ $type = ASSESSMENT_DOC | get list of assesment team doc value (document_config_id) {step4}
    @ $type = REV_DOC_ASM | get list of assesment revision file list  (document_config_id) {step7}
    */
    public function get_form_mapping_by_type($id_application_status, $type)
    {
        $this->db->select('*');
        $this->db->from('application_status_form_mapping'); 
        $this->db->where('id_application_status', $id_application_status);
        
        // $this->db->like('type', 'REVISED_DOC');
        if ($type != "") {
            $this->db->like('type', $type);
        }

        return $this->db->get()->result();
    }

    /*
    GET list of revision documents
    */
    public function get_rev_doc_user_upload($key)
    {
        $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url ,dc.mandatory');
        $this->db->from('document_config dc');
        $this->db->where('dc.type','DYNAMIC');
        $this->db->where_in('dc.key', $key);
        $this->db->order_by('dc.id_document_config', 'ASC');
            
        return $this->db->get()->result();
    }
    
    /*
    SET application_form_mapping
    Call this function when :
    @ Revision File (step2)
    */
    public function set_app_form($data)
    {
        $this->db->insert('application_status_form_mapping', $data);
    }

    /*
    UPDATE application_form_mapping
    Call this function when :
    @ Revision File (step2)
    */
    public function update_app_form($data,  $id_application_status_form_mapping)
    {
        $this->db->where('id_application_status_form_mapping', $id_application_status_form_mapping);
        return $this->db->update('application_status_form_mapping', $data);
    }

    /*
    Validate application_file table based on id_application
    @ IF empty, goes to Normal Upload
    @ IF not empty, goes to Revision Upload
    */
    public function check_app_file($data)
    {
        $this->db->insert('application_file', $data);
    }

    public function insert_app_file($data)
    {
        $this->db->insert('application_file', $data);
    }

    //menampilkan data documen yang harus di download user typenya yang static
    public function get_doc_dynamic()
    {
        $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url');
        $this->db->from('document_config dc');
        $this->db->where('mandatory','1');
        $this->db->where('type','DYNAMIC');
        return $this->db->get();
    }

    //menampilkan data documen yang harus di download user typenya yang static
    public function get_doc_kbs()
    {
        $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url');
        $this->db->from('document_config dc');
        
        return $this->db->get()->result();
    }

    /*Function ini di buat untuk mengambil id dari dokument untuk insert path documentdi document config di buat untuk global*/
    public function getdocument_aplication_forUpload($id_user, $type, $type1,  $status){ 
        $this->db->select('*');
        $this->db->from('applications'); 
        $this->db->join('application_file', 'applications.id_application=application_file.id_application');
        $this->db->join('document_config', 'application_file.id_document_config=document_config.id_document_config');
        $this->db->where('applications.id_user',$id_user);
        $this->db->where('applications.iin_status',"OPEN");
        $this->db->where($type,$type1);
        $this->db->where('application_file.status',$status);
        $this->db->order_by('document_config.id_document_config', 'ASC');

        $query = $this->db->get(); 
        $results = $query->result();
 
        return  $results ;   
    }



    





    /*
    Get id_assessment_application
    @ value of this query is the parameter for Get assesment_team
    */
    public function get_id_assessment_application($id_user){ 
        $this->db->select('assa.id_assessment_application');
        $this->db->from('applications app'); 
        $this->db->join('assessment_application assa','app.id_application=assa.id_application');
        $this->db->where('app.id_user',$id_user);
        $this->db->where('app.iin_status',"OPEN");
        $this->db->order_by('assa.id_assessment_application', 'DESC');
        $this->db->limit('1');


        return $this->db->get()->result();
    }

    /*
    Get assesment_team
    @ value of this query is,  list of assessment team (step6)
    */
    public function get_assesment_team($id_assessment_application){ 
        $this->db->select('*');
        $this->db->from('assessment_registered asr'); 
        $this->db->join('assessment_team ate','ate.id_assessment_team=asr.id_assessment_team');
        $this->db->join('assessment_team_title att','att.id_assessment_team_title=asr.id_assessment_team_title');
        $this->db->where('asr.id_assessment_application',$id_assessment_application);
         
        return $this->db->get()->result();
    }



    /*
    Get assessment team doc
    @ get list of document assessment (step 6)
    */
    public function get_assessment_team_docxx($id_application_status,$id_application_status_name, $key){ 
        $this->db->select('*');
        $this->db->from('application_status aps'); 
        $this->db->join('application_file af','af.id_application = aps.id_application');
        $this->db->join('document_config dc','af.id_document_config = dc.id_document_config');
        $this->db->where('aps.id_application_status',$id_application_status);
        $this->db->where('dc.type','TRANSACTIONAL');
        $this->db->where('aps.id_application_status_name', $id_application_status_name);
        $this->db->where_in('dc.key', $key);
        $this->db->order_by('af.id_application_file', 'DESC');
        $this->db->limit(count($key));


        return $this->db->get()->result();
    }

    public function get_assessment_team_doc($id_application_status, $key){ 
        $this->db->select('*');
        $this->db->from('application_status aps'); 
        $this->db->join('application_file af','af.id_application = aps.id_application');
        $this->db->join('document_config dc','af.id_document_config = dc.id_document_config');
        $this->db->where('aps.id_application_status',$id_application_status);
        $this->db->where('dc.type',"TRANSACTIONAL");
        $this->db->where_in('dc.key', $key);
        $this->db->order_by('af.id_application_file', 'DESC');
        $this->db->limit(count($key));

        // $sql = $this->db->get_compiled_select('application_status aps');
        // echo $sql;


        return $this->db->get()->result();
    }

    // public function get_assessment_doc($id_application_status, $key){ 
    //     $this->db->select('*');
    //     $this->db->from('application_status aps'); 
    //     $this->db->join('application_file af','af.id_application = aps.id_application');
    //     $this->db->join('document_config dc','af.id_document_config = dc.id_document_config');
    //     $this->db->where('aps.id_application_status',$id_application_status);
    //     $this->db->where('dc.type',"TRANSACTIONAL");
    //     $this->db->where_in('dc.key', $key);
    //     $this->db->order_by('af.id_application_file', 'DESC');


    //     return $this->db->get()->result();
    // }

    /*
    Get assessment rev file list ()
    @ get list of assessment verification file list (step 6)
    */
    public function get_assessment_rev_list($id_document_config){ 

        $this->db->select('dc.id_document_config, dc.type, dc.key, dc.display_name, dc.file_url ,dc.mandatory');
        $this->db->from('document_config dc');
        $this->db->where_in('dc.id_document_config', $id_document_config);


        return $this->db->get()->result();
    }

     

    // /*Melakukan Assesment Status*/
    // public function getAssesmentStatus($id_user){ 
    //     $this->db->select('*');
    //     $this->db->from('applications'); 
    //     $this->db->join('assessment_application','applications.id_application=assessment_application.id_application');
    //     $this->db->join('assessment_registered','assessment_application.id_assessment_application=assessment_registered.id_assessment_application');
    //     $this->db->join('assessment_team','assessment_registered.id_assessment_team=assessment_team.id_assessment_team');
    //     $this->db->join('assessment_team_title','assessment_registered.id_assessment_team_title=assessment_team_title.id_assessment_team_title');
    //     $this->db->where('applications.id_user',$id_user);
    //     $this->db->where('applications.iin_status',"OPEN");
    //     // $this->db->where('document_config.type','STATIC'); 
    //     // $this->db->where('document_config.key',"IPPSA"); 
    //      $query = $this->db->get(); 
    //     $results = $query->result(); 
    //     return  $results ; 
    // }

        // public function get_applications_Status($id_user){
        //     $this->db->select('*');
        //     $this->db->from('application_status');
        //     $this->db->join ('applications', 'application_status.id_application = applications.id_application');
        //     $this->db->join('application_status_name','application_status_name.id_application_status_name=application_status.id_application_status_name');
        //     $this->db->where('applications.id_user',$id_user);
        //     $this->db->where('applications.iin_status',"OPEN");

        //     return $this->db->get();
        // }

    /*
    Get Application Status
    */
    public function get_applications_Status($id_user) {

        $this->db->select('aps.id_application_status, ap.id_application, ap.application_type, apsn.id_application_status_name, aps.process_status, ap.iin_status, ap.created_by');
        $this->db->from('application_status aps');
        $this->db->join ('applications ap', 'aps.id_application = ap.id_application');
        $this->db->join('application_status_name apsn','apsn.id_application_status_name = aps.id_application_status_name');
        $this->db->where('ap.id_user',$id_user);
        $this->db->order_by('aps.id_application_status', 'DESC');
        $this->db->limit('1');


        // $this->db->select('MAX(apsn.id_application_status_name) AS id_application_status_name, aps.process_status, ap.iin_status');
        // $this->db->from('application_status aps');
        // $this->db->join ('applications ap', 'aps.id_application = ap.id_application');
        // $this->db->join('application_status_name apsn','apsn.id_application_status_name = aps.id_application_status_name');
        // $this->db->where('ap.id_user',$id_user);
        // $this->db->where('ap.iin_status','OPEN');

        return $this->db->get();
    }

    public function get_aplication($id_user){ 
        // $this->db->select('*');
        // $this->db->from('applications'); 
        $this->db->where('id_user',$id_user);
        $this->db->where('iin_status','OPEN');
        return  $this->db->get('applications');
    }

    /*Cek Status User*/
    public function cek_status_user($username){  
        $this->db->where("email = '$username' or username = '$username'"); 
        return  $this->db->get('user');   
    }

    /*
    Insert applications Table (step0)
    */
    public function insert_pengajuan ($data){
        $this->db->insert('applications', $data);
        $inserted_id = $this->db->insert_id();
        return $inserted_id;
    }

    /*
    Insert application_status Table
    */
    public function insert_app_status($data)
    {
       $this->db->insert('application_status', $data);
       $inserted_id = $this->db->insert_id();
       return $inserted_id;
    }



    /*
    step1
    
    
    */
   

    public function get_id_application($id_user)
    {
        $this->db->select('id_application, created_by');
        $this->db->from('applications');
        $this->db->where('id_user',$id_user);
        $this->db->where('iin_status','OPEN');

        return  $this->db->get();
    }

    public function get_id_application_status_name($id_application, $name)
    {
        $this->db->select('id_application_status_name');
        $this->db->from('application_status');
        $this->db->where('id_application',$id_application);
        $this->db->where('id_application_status_name',$name);
        $this->db->order_by('id_application_status', 'ASC');
        $this->db->limit('1');

        return  $this->db->get();
    }

    /*Proses send email*/
    // public function sendMail($email,$username, $Desc) {
    //     $encrypted_id = md5($email) ;
    //     $from_email = 'andaru140789@gmail.com'; // ganti dengan email kalian
    //     $subject = 'Verify Your Email Address';


    //     $config['protocol'] = 'smtp';
    //     $config['smtp_host'] = 'ssl://smtp.gmail.com'; // sesuaikan dengan host email
    //     $config['smtp_timeout'] = '7';
    //     $config['smtp_port'] = '465'; // sesuaikan
    //     $config['smtp_user'] = $from_email;
    //     $config['smtp_pass'] = '14071989'; // ganti dengan password email
    //     $config['mailtype'] = 'html';
    //     $config['charset'] = 'iso-8859-1';
    //     $config['wordwrap'] = TRUE;
    //     $config['newline'] = "\r\n";
    //     $config['crlf'] = "\r\n";
    //     $this->email->initialize($config);
    //     $this->email->from($from_email, 'Bsn');
    //     $this->email->to($email);
    //     $this->email->subject($subject);
    //     $this->email->message($Desc."<br><br>".base_url("SipinHome/verify/$encrypted_id"));
    //     // gunakan return untuk mengembalikan nilai yang akan selanjutnya diproses ke verifikasi email
    //     return $this->email->send();
    // }

    public function sendMail($email,$username, $Desc) {
        $encrypted_id = md5($email) ;
        // echo "|encrypted_id : {$encrypted_id} |";
        $from_email = 'andaru140789@gmail.com'; // ganti dengan email kalian
        $subject = 'Verify Your Email Address';


        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com'; // sesuaikan dengan host email
        $config['smtp_timeout'] = '7';
        $config['smtp_port'] = '465'; // sesuaikan
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = '14071989'; // ganti dengan password email
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $this->email->initialize($config);
        $this->email->from($from_email, 'Bsn');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($Desc."<br><br>".base_url("SipinHome/verify/$encrypted_id"));
        // gunakan return untuk mengembalikan nilai yang akan selanjutnya diproses ke verifikasi email
        return $this->email->send();
    }


    // UpdateStatus

    /*
    UPDATE field iin_status (OPEN, CLOSED)
    @ Table applications
    */
    public function update_applications($iin_status, $id_application, $modified_by)
    {
        $data = array('iin_status' => $iin_status,
                // 'created_date' => date('Y-m-j'),
                'modified_by' => $modified_by,
                'modified_date' => date('Y-m-j H:i:s'));
        $this->db->where('id_application', $id_application);
        return $this->db->update('applications', $data);
    }

    /*
    UPDATE field process_status (PENDING, COMPLETED, REJECTED)
    @ Table application_status
    */
    public function update_aplication_status($process_status, $id_application, $id_application_status_name, $modified_by)
    {
        $data = array('process_status' => $process_status,
                // 'created_date' => date('Y-m-j'),
                'modified_by' => $modified_by,
                'modified_date' => date('Y-m-j H:i:s'));
        $this->db->where('id_application', $id_application);
        $this->db->where('id_application_status_name', $id_application_status_name);
        return $this->db->update('application_status', $data);
    }


    public function update_document($id_application, $id_application_file, $id_document_config, $path_id, $modified_by)
    {
        $data = array('path_id' => $path_id,
                // 'created_date' => date('Y-m-j'),
                'modified_by' => $modified_by,
                'modified_date' => date('Y-m-j H:i:s'));
        $this->db->where('id_application', $id_application);
        $this->db->where('id_application_file', $id_application_file);
        $this->db->where('id_document_config', $id_document_config);
    

        return $this->db->update('application_file', $data);
    }


    public function insert_log($data)
    {
         $this->db->insert('log', $data);
    }
    
    /*Email Activation and status_user update*/
    public function verifyEmail($key) {
        // nilai dari status yang berawal dari Tidak Aktif akan diubah menjadi Aktif disini
        echo " key : {$key}|";
        $this->db->from('user');
        $this->db->where('md5(email)', $key);
        $query = $this->db->get()->row();
        echo "SIZE : ".sizeof($query)."|";
        
        if ($query) {
            $username = $query->username;
            $email = $query->email;
            $status = $query->status_user;
            echo "username : ".$username."|"."email : ".$email."|"."status : ".$status."|";
            if ($status == 1) {
                /*Set Registration Message on Current Session*/
                $this->session->set_flashdata('regis_msg', "Email Anda Telah Aktif!");
            } else {
                
                /*Updating User Table, Set status_user='1'*/
                $this->db->update('user', array('status_user' => '1'));

                /*Set Registration Message on Current Session*/
                $this->session->set_flashdata('regis_msg', "Aktivasi Email Berhasil!");

            }
        } else {
            /*Set Registration Message on Current Session*/
            $this->session->set_flashdata('regis_msg', "Aktivasi Email Gagal!!!");
        }

    }



    /*
    GET Survey answer
    */
    public function get_survey_answer()
    {
        $this->db->select('*');
        $this->db->from('survey_answer');
        $this->db->where('id_user', 'ISO7812');
        return  $this->db->get();
    }

    /*
    GET Survey status
    */
    public function get_survey_status()
    {
        $this->db->select('*');
        $this->db->from('survey_answer');
        $this->db->where('id_user', 'ISO7812');
        return  $this->db->get();
    }













    //ANDARU DEFAULT
    /*Simpan File*/
    public function simpan($data){
        $this->db->insert('upload', $data);
     }

     public function get_file_iso()
     {
        $this->db->select('*');
        $this->db->from('document_config');
        $this->db->where('key', 'ISO7812');
        return  $this->db->get();  
     }

     public function get_user_by_prm($email,$name)
     {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('email', $email);
        $this->db->where('name', $name);
        return $this->db->get(); 
     }

     public function insert_complain($data)
     {
        $this->db->insert('complaint', $data);
     }

     public function get_iin()
     {
        
        $this->db->select('iin.id_iin, iin.iin_number, iin.iin_established_date, iin.iin_expiry_date, applications.instance_name, applications.instance_email, applications.instance_phone, applications.mailing_location');
        $this->db->from('user');
        $this->db->join('iin', 'user.id_user=iin.id_user');
        $this->db->join('applications','applications.id_user=user.id_user');
        return $this->db->get(); 
     }

     public function get_app_by_prm($id_usr)
     {
        $this->db->select('id_application');
        $this->db->from('applications');
        $this->db->where('id_user', $id_usr);
        return $this->db->get(); 
     }











     // ALDY SOURCE CODE
     public function survey($type, $data){
        switch ($type) {
            case 'vote':
                $this->db->select('*');
                $this->db->from('survey_question sq');
                $this->db->where('sq.question_status','1');
                return $this->db->get();
                break;
            case 'insert-answer':
                // TYPE CODE HERE FOR ANSWER FROM USER
                return $this->db->get();
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
                break;
        }
        
    }
    
}
