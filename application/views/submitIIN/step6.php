<section section-id="6" class="section_iin float_right" style="display:none">
	<h1 class="title_iin">Konfirmasi Tim Verifikasi Lapangan</h1>
	<p>Bersama ini kami informasikan bahwa Badan Standardisasi Nasional (BSN) akan melaksanakan verifikasi lapangan dalam rangka penerbitan nomor Issuer Identification Number (IIN) sesuai dengan ISO/IEC 7812 pada:  </p>
	<br/>
	<p><?php echo $team_date; ?></p>
	<br>
	<table class="table_def table_assessment" style="width: 100%;">
	    <tr>
	      	<th class="sort" data-sort="id_no"><center>#</center></th>
	      	<th class="sort" data-sort="id_name">Nama Anggota</th>
	      	<th class="sort" data-sort="id_roles">Jabatan</th>
	    </tr>
	    <tbody class="list">
	        <?php foreach($step6_listTeam as $key=>$datas) { ?>
	        <tr>
	          	<td><?php echo ($key+1).".";?></td>
	          	<td><?php echo $datas->name;?></td>
				<td><?php echo $datas->title;?></td>
	        </tr>
	        <?php } ?> 
	    </tbody>
	</table>
	<br/>	
	<p >Konfirmasi atas persetujuan Saudara terhadap pelaksanaan dan tim verifikasi tersebut di atas, mohon dapat disampaikan kepada kami sebelum tanggal <?php echo $verif_date ?>. Demikian kami sampaikan, atas perhatian dan kerjasama yang diberikan, kami mengucapkan terima kasih.
	</p>
		<br/>
		

	<ul class="list_iin_download">
		 <?php $no=0; 
		 	foreach($team_doc as $data) { 
		 		?>
	 				<div class="item-download">
						<div><?php echo $data->display_name; ?></div>
						 <a href="<?php echo base_url();?>submit_iin/download?var1=<?php echo $data->path_file;?>" class="btn_download"  >Download</a>
	 				</div>	
		 <?php 			
				
	 		} ?> 
	</ul>

		<br/>
		<button style="background: orange" class="float_left show-rev-assess-modal step6_rev">Revisi Tanggal</button>
		<div class="modal-form-rev-assess hidden" >	
			<form action="<?php echo base_url()?>submit_iin/step_6_rev" method="post">
				<h1 class="modal-form-rev-assess-title">
					Revisi Tanggal
					<span class="btn-close-modal-rev-assess"></span>
				</h1>
				<div>
					<p>Masukkan tanggal permohonan Assesment Lapangan :</p>
					<input type="date" name="rev_assess_date" placeholder="dd/MM/yyyy" required><br/><br/>
					<button style="background: orange" class="float_right " name="step6_rev" value="step6_rev">Kirim</button>
				</div>
				
			</form>
		</div>

		
		<div class="clearfix">
			<!-- <button style="background: red" class="float_left">Kembali</button> -->
			<a href="<?php echo base_url()?>submit_iin/step_6">
				<button style="background: #01923f" class="float_right step6_next"  value="step6_next">Lanjutkan Proses</button>
			</a>
		</div>
</section>

<script type="text/javascript">
	var upload_status = "<?php echo $upload_status4?>";
	console.log(upload_status);


	if (upload_status == 'success') {
		$(".step6_next").hide();
		$(".step6_rev").hide();
	} else {
		$(".step6_next").show();
		$(".step6_rev").show();
	}

</script>