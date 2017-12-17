<section section-id="1" class="section_iin float_right" style="display:none">
	<h1 class="title_iin">Hasil Verifikasi Status Permohonan</h1>
	<p>Status Permohonan IIN Baru Anda telah di Verifikasi dan telah disetujui. Berikut ini merupakan surat yang diterbitkan oleh Sekretariat Layanan terkait permohonan Anda. Silakan diunduh (download) sebagai bukti untuk bisa melanjutkan permohonan ke tahap selanjutnya.</p>
	
	<ul class="list_iin_download">


		<?php 
			$no=0; 
		 	// foreach($download_upload as $data) { 
		 	foreach($step1_download as $data) { 
		 	 	switch ($data->keys) {
		 	 		case 'IPPSA':?>
		 				<div class="item-download">
							<div><?php $no++; echo "$no.  "; echo $data->display_name; ?></div>
							 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $data->file_url;?>" class="btn_download"  >Download</a>
		 				</div>	
		<?php 	
					break;
				} 
	 		} 
 		?>


		
			
	</ul>

	<p>Silakan unduh (download) beberapa dokumen berikut dan diunggah (upload) kembali setelah dilengkapi.</p>

	<ul class="list_iin_download">

		 <?php 
		 	// foreach($download_upload as $data) { 
		 	foreach($step1_download as $data) { 
		 	 	switch ($data->keys) {
		 	 		case 'ISO':?>
		 				<div class="item-download">
							<div><?php $no++; echo "$no.  "; echo $data->display_name; ?></div>
							 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $data->file_url;?>" class="btn_download"  >Download</a>
		 				</div>	
		 <?php 			break;
		 			case 'T&C':?>
		 				<div class="item-download">
							<div><?php $no++; echo "$no.  "; echo $data->display_name; ?></div>
							 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $data->file_url;?>" class="btn_download"  >Download</a>
		 				</div>	
		 <?php 			break;
				} 
	 		} ?> 
	</ul>

	<p >Setelah mengunduh dan melengkapi isi dari masing-masing dokumen, silakan klik button link di bawah ini untuk melanjutkan proses permohonan dengan melengkapi dokumen-dokumen yang dibutuhkan untuk diproses oleh Sekretariat Layanan.</p>
	<br/>
	<br/>
	<div class="clearfix">
		<!-- <button style="background: red" class="btn_back float_left">Kembali</button>	 -->
		<a href="<?php echo base_url()?>submit_iin/step_1">
			<button style="background: #01923f" class="float_right step1_next" name="step1_next" value="step1_next">  Lanjutkan Proses</button>
		</a>	
	</div>
</section>

<script type="text/javascript">
		var step1_next = ("<?php echo $step1_next ?>" != "") ? "<?php echo $step1_next ?>" : '';


		if (step1_next == 'success') {
			$(".step1_next").hide();
		} else {
			$(".step1_next").show();
		}
</script>