<div class="content-background"></div>

<!-- <div>
	<button class="show_popup">TAMPILKAN POPUP BOX</button>
</div>

 -->

<div class="page" style="margin-top: 150px">
	<div class="clearfix">
		<ul id="section_progress" style="width: 100%">
			<li stepId="<?php echo $state0?>" class="<?php echo $box_status_0?>"><button>Pengajuan Surat Permohonan ke BSN</button></li>
			<li stepId="1" class="<?php echo $box_status_1?>"><button>Hasil Verifikasi Status Permohonan</button></li>
			<li stepId="<?php echo $state2?>" class="<?php echo $box_status_2?>"><button>Submit Kelengkapan Dokumen</button></li>
			<li stepId="<?php echo $state3?>" class="<?php echo $box_status_3?>"><button>Proses Verifikasi dan Validasi</button></li>
			<li stepId="4" class="<?php echo $box_status_4?>"><button>Konfirmasi Surat Lulus Kelengkapan dan Kode Billing</button></li>
			<li stepId="<?php echo $state5?>" class="<?php echo $box_status_5?>"><button>Submit Bukti Transfer Pembayaran</button></li>
			<li stepId="<?php echo $state6?>" class="<?php echo $box_status_6?>"><button>Menerima Konfirmasi Tim Verifikasi Lapangan</button></li>
			<li stepId="<?php echo $state7?>" class="<?php echo $box_status_7?>"><button>Assessment Lapangan</button></li>
			<li stepId="<?php echo $state8?>" class="<?php echo $box_status_8?>"><button>Proses Permohonan ke CRA</button></li>
			<li stepId="9" class="<?php echo $box_status_9?>"><button><?php echo $buttonStep9?></button></li>
		</ul>

		<script type="text/javascript">
			$('document').ready(function(){
				$('#section_progress>li.PENDING').click();
			})

		</script>

		<?php

		if($this->session->userdata('status') != "login"){
			redirect(base_url("SipinHome"));
		}
		for ($x=0; $x <= (int)$page ; $x++) {
			$this->load->view('submitIIN/step'.$x);
		}

		$data['process_view'] = array(
			'title' => $title,
			'text'  => $text
		);
		$this->load->view('submitIIN/process', $data);

		$data['rejected_view'] = array(
			'title' => $title,
			'text'  => $text,
			'reject_msg'  => $reject_msg
		);
		$this->load->view('submitIIN/rejected', $data);

		$data['assessment_rev'] = array(
			'title' => $title,
			'text'  => $text,
		);

		if(isset($assessment_rev_doc))$this->load->view('submitIIN/assessment_rev', $data);


?>
	</div>
</div>
