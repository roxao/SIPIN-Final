<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class SurveyController extends CI_Controller {

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

	public function set_template($view_name, $data = array()){
        $this->load->view('header', $data);
        $this->load->view($view_name, $data);
        $this->load->view('footer', $data);
        return;
    }


	public function survey( $x = null){
		switch ($x) {
			case 'vote':
				$survey = $this->usr_model->survey('vote',null)->result_array();
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

				// Masukan $survey_answers ke database
				// Hapus echo dibawah
				// function model sudah dibuat 
				break;
			case 'result-survey';
				// KALAU SUDAH MEMBUAT QUERY YANG JIKA DI json_encode seperti dibawah
				// HAPUS CODE DIBAWAH INI
				$data['survey'] = json_decode('{"id_survey_question":"1","version":"1","total_answer":"15","survey_questions":[{"no":"1","question":"Question number 1","average":"4","answer":{"1":"0","2":"0","3":"3","4":"3","5":"9"}},{"no":"2","question":"Question number 2","average":"4","answer":{"1":"0","2":"0","3":"3","4":"3","5":"9"}}]}',true);
				// SAMPAI CODE INI

				// LALU HAPUS COMMENT CODE DIBAWAH INI
				// $data['survey'] = $this->user_model->get-survey-result()->result_array();

				$result['survey']=$this->admin_model->survey('get-survey-result',null)->result();

				foreach ($result as $index => $valIndex) {
					foreach ($valIndex as $key => $value) {
						foreach ($value as $keyval => $val) {
							# code...
							if($keyval == 'id_survey_question')
							{
								echo $val;
							}
						// print_r($val);
							if ($keyval == 'answer') {
								// echo "string";
								echo $val;
						// echo json_encode($val);
							}

						}
					}
				}

				// echo json_encode($result);

				return false;
				$this->set_template('survey-result',$data);
				break;
			default:
				redirect(base_url());
				break;
		}

	}


	

}