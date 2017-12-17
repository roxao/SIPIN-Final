<section section-id="7" class="section_iin float_right" style="display:none">
	<h1 class="title_iin">Assessment Lapangan</h1>
	<p>Permohonan IIN baru yang anda ajukan sudah memasuki tahapan Assessment Lapangan oleh tim yang ditunjuk Sekretariat Layanan dan berikut terlampir surat penugasan untuk kegiatan terkait. Silakan diunduh jika diperlukan sebagai dokumen pendukung.</p>

	<ul class="list_iin_download">
		 <?php 
		 	foreach($assess_lap as $data) { 
		 		?>
		 		<div class="item-download">
					<div><?php  echo $data->display_name; ?></div>
					 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $data->path_file;?>" class="btn_download"  >Download</a>
 				</div>	
		 	 	
		 <?php 			
				
	 		} ?>  
	</ul>


	<p >Hasil dari kegiatan Assement Lapangan ini akan dilakukan verifikasi. Jika instansi Anda telah memenuhi persyaratan permohonan IIN, maka Silakan anda menunggu dalam waktu maksimal 9 hari kerja untuk menerima informasi penerbitan IIN. Namun, Jika persyaratan permohonan IIN Anda belum terpenuhi, maka Anda harus melakukan perbaikan hasil Assessment yang akan diinformasikan setelah rapat pembahsan hasil verifikasi lapangan oleh Sekretariat Layanan melalui aplikasi SIPIN ini..</p>
	<br>
	</br>
	<p class="step7_p">
	Silakan klik tombol “Selanjutnya” jika anda sudah memahami alur proses di tahap ini dan siap untuk melanjutkan ke tahapan proses penerbitan IIN selanjutnya.
	</p>
		<br/>
		<br/>

	<div class="clearfix">
		<!-- <button style="background: red" class="float_left">Kembali</button>	 -->
		<button style="background: #01923f" class="float_right step7_next">Lanjutkan Proses</button>	
	</div>
</section>
<script type="text/javascript">
	var upload_status = "<?php echo $upload_status5?>";
	console.log(upload_status);


	if (upload_status == 'success') {
		$(".step7_next").show();
		$(".step7_p").show();
	} else {
		$(".step7_next").hide();
		$(".step7_p").hide();
	}
</script>