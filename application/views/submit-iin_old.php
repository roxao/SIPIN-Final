<div class="content-background"></div>

<div>
	<button class="show_popup">TAMPILKAN POPUP BOX</button>
</div>


<div class="page" style="margin-top: 150px">
	<div class="clearfix">
		<ul id="section_progress" style="width: 100%">
			<li stepId="0" class="COMPLETED"><button>Pengajuan Surat Permohonan ke BSN</button></li>
			<li stepId="1" class="COMPLETED"><button>Hasil Verifikasi Status Permohonan</button></li>
			<li stepId="2" class="COMPLETED"><button>Submit Kelengkapan Dokumen</button></li>
			<li stepId="3" class="COMPLETED"><button>Proses Verifikasi dan Validasi</button></li>
			<li stepId="4" class="COMPLETED"><button>Konfirmasi Surat Lulus Kelengkapan dan Kode Billing</button></li>
			<li stepId="5" class="PENDING"><button>Submit Bukti Transfer Pembayaran</button></li>
			<li stepId="6" class=""><button>Menerima Konfirmasi Tim Verifikasi Lapangan</button></li>
			<li stepId="7" class=""><button>Assessment Lapangan</button></li>
			<li stepId="8" class=""><button>Proses Permohonan ke CRA</button></li>
			<li stepId="9" class=""><button>Menerima IIN Baru Berserta Kelengkapan Dokumen</button></li>
		</ul>


		<?php 
		$id_user = $this->session->userdata('id_user');
	
	if ($this->user_model->getdocument_aplication($id_user) ){

		$data['download_upload']    = $this->user_model->getdocument_aplication($id_user);

		$datas['aplication_asesment']    = $this->user_model->getAssesmentStatus($id_user);
	}


	// if ($this->user_model->getAplicationStatus($id_user) ){

		
	// }

		$this->load->view('submitIIN/step0');
		$this->load->view('submitIIN/step1',$data); 
		$this->load->view('submitIIN/step2',$data);
		$this->load->view('submitIIN/step3',$data);	
		$this->load->view('submitIIN/step4',$data);
		$this->load->view('submitIIN/step5',$data); 
		$this->load->view('submitIIN/step6',$datas, $data);
		$this->load->view('submitIIN/step7',$data);
		$this->load->view('submitIIN/step8',$data);
		$this->load->view('submitIIN/step9',$data);
?>
	</div>
</div>
