<section section-id="4" class="section_iin float_right" style="display:none">
	<h1 class="title_iin">Konfirmasi Surat Lulus Kelengkapan dan Kode Billing</h1>
	<p>Berdasarkan persetujuan pada proses verifikasi dan validasi terhadap status permohonan Anda, dengan ini terlampir dokumen-dokumen.</p>

	<p>Silakan unduh (download) untuk dokumen-dokumen terlampir.</p>


	<ul class="list_iin_download">
		 <?php $no=0; 
		 	foreach($bill_doc as $data) { 
		 		?>
	 				<div class="item-download">
						<div><?php echo $data->display_name; ?></div>
						 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo base64_encode($data->path_file);?>" class="btn_download"  >Download</a>
	 				</div>	
		 <?php 			
				
	 		} ?> 
	</ul>

	<p >Silakan klik tombol “Lanjutkan Proses Permohonan IIN Baru” untuk melanjutkan ke proses pembayaran penerbitan IIN baru.</p>
		<br/>
		<br/>

	<div class="clearfix">
		<!-- <button style="background: red" class="float_left">Kembali</button>	 -->
		<a href="<?php echo base_url()?>submit_iin/step_4">
			<button style="background: #01923f" class="float_right step4_next" >Lanjutkan Proses</button>
		</a>	
	</div>
</section>

<script type="text/javascript">
	var upload_status = "<?php echo $upload_status2?>";
	console.log(upload_status);


	if (upload_status == 'success') {
		$(".step4_next").hide();
	} else {
		$(".step4_next").show();
	}
</script>