<section section-id="assessment_rev" class="section_iin float_right" style="display:none">
	<h1 class="title_iin">Assessment Lapangan</h1>
	<p class="step-process">Hasil dari Assessment Lapangan Anda telah diverifikasi, mohon unggah dokumen-dokumen berikut ini :</p>
	<br>
	<?php echo form_open_multipart('submit_iin/upload_files_assessment');?>
	<ul class="list_iin_download">
		<?php 
			$no=0;
			foreach($assessment_rev_doc as $data) { 

				?>

				<li class="item-upload"> 
					<input type="checkbox" <?php echo (($upload_status6 == "success") ? "checked ": "" );?> disabled/> 
					<?php  
						$files = "file".$no;
						$name = "{$data->display_name}";

						echo $name;
						$no++;
					?>
					
					<label class="upload_button">
						<span>Cari...</span>
						<input type="file"  id="<?php echo $no?>" class="fileChoser" name="<?php echo $files?>" 
						required/>
						<i id="<?php echo $files?>" ></i>
					</label>
					
				</li> 	
			<?php
			} 

			?> 
	</ul>
	<br>
	<div class="clearfix">
		<button style="background: #01923f" class="float_right uploadstep7" name="upload" value="uploadstep7" onclick="checkUploadedFile()">Proses</button>	
	</div>
	
</section>
<script>
	$("input[type=file]").change(function() {
	    var fileName = $(this).val().split('/').pop().split('\\').pop();
	    $(this).next().html(fileName);
		$(this).parent().prev().prop('checked',(fileName.length>1?true:false));
	});
</script>
