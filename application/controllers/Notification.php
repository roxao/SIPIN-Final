<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Notification extends CI_Controller {

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



	public function getNotification(){
	// echo "iduser : ".$this->session->userdata('id_user');
	// echo "adminrol : ".$this->session->userdata('admin_role');

		if($this->session->userdata('admin_role') != NULL)
		{
			$notifikation_type = 'admin';
		}else
		{
			$notifikation_type = $this->session->userdata('id_user');
		}

		$data = $this->admin_model->get_notif($notifikation_type)->result_array();
		echo json_encode($data);

	}

	public function updateNotificationStatus(){
		$id = $this->input->get('notifId');
		$this->admin_model->update_notif($id);
	}

}
