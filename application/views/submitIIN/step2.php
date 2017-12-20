<section section-id="2" class="section_iin float_right" style="display:none">
	<h1 class="title_iin"><?php echo $title_iin;?></h1>
	<p><?php echo $text_iin;?></p>
	<?php echo form_open_multipart('submit_iin/upload_files');?>
	<ul class="list_iin_download">
		<?php

			foreach($step2_upload as $data) {

				?>

				<li class="item-upload">
					<input type="checkbox" <?php echo (($upload_status == "success") ? "checked ": "" );?> disabled/>
					<span>
						<?php
							$mandatory = ($data->mandatory === '1') ? '*': '' ;
							$name = "{$data->keys}. {$data->display_name} {$mandatory}";
							echo $name;
						?>
					</span>

					<label class="upload_button">
						<span>Cari...</span>
						<input type="file"  id="<?php echo $data->keys?>" class="fileChoser" name="<?='file'.$data->keys?>" <?=(($data->mandatory === "1") ? "required": "" )?>/>
						<i data-id="<?='file'.$data->keys?>"></i>
					</label>
				</li>
			<?php
			}

			?>
	</ul>


	<input type="hidden" id="no_count" name="no_count" >


	<p >*Dokumen yang wajib disertakan</p>
		<br/>
		<br/>

	<div class="clearfix">
		<button style="background: #01923f" class="float_right uploadstep3" name="upload" value="uploadstep3" onclick="checkUploadedFile()">Proses</button>

	</div>
	</form>

</section>




<!-- DEFAULT ALDY -->
<script>
	$("input[type=file]").change(function() {
	    var fileName = $(this).val().split('/').pop().split('\\').pop();
	    swal(fileName);
	    $(this).next().html(fileName);
		$(this).parent().prev().prop('checked',(fileName.length>1?true:false));
	});
</script>
<script type="text/javascript">

	var upload_status = "<?php echo $upload_status ?>";

	if (upload_status == 'success') {
		$(".uploadstep3").hide();
		$(".upload_button").hide();
	} else {
		$(".uploadstep3").show();
		$(".upload_button").show();
	}

	function checkUploadedFile(){
		var temp = "";
		$(".fileChoser").each(function(){
			var value = $(this).val();
			var attr = $(this).attr('required');
			// alert(value);
			if(value != null && value != ""){

				if(temp == "" || temp == null){
					temp = $(this).attr("id");
				} else {
					temp = temp +","+$(this).attr("id");
				}
			} else {
				// For some browsers, `attr` is undefined; for others,
				// `attr` is false.  Check for both.
				if (typeof attr !== typeof undefined && attr !== false) {
					swal('','Mohon unggah semua dokumen wajib','warning');
				}	
			}

				
		});

		$("#no_count").val(temp);
	}
</script>





<!-- KALAU GAGAL UPLOAD -->
