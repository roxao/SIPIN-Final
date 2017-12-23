<section class="clearfix content-approval">
	<div class="section_list_file">
		<p>Berikut kelengkapan dokumen yang telah di unggah (upload) oleh Pemohon.</p>
		<div class="section_iin_file_list attach_user_file">

		</div>
		<p>Pastikan bahwa dokumen yang di unggah (upload) oleh Pemohon sudah lengkap dan benar.</p>
	</div>
	<?php echo form_open_multipart('admin_verifikasi_controller/VERIF_REV_ASSESS_RES_REQ_PROSES') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<br/><br/>
		<label class="input_dashed_file float_left" style="width: 100%">
			Berita Acara
			<input name="bill[]"  type="file" placeholder="Masukan Dokumen Kode Billing SIMPONI" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<label class="input_dashed_file float_left" style="width: 100%">
			Hasil Assessment Lapangan
			<input name="bill[]"  type="file" placeholder="Masukan Surat Persetujuan Proses" required />
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section> 





<section class="clearfix content-revision" style="display:none">
	<p>Masukan keterangan perbaikan dokumen yang harus di unggah oleh Pemohon</p>
	<?php echo form_open_multipart('admin_verifikasi_controller/VERIF_REV_ASSESS_RES_REQ_REVISI') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<div class="doc_rev">
			
		</div>
		<input type="submit" name="submit_revision" style="display:none"/>
	</form>
</section>








<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();

	value=respon.doc_user;
	app=respon.application;

	$("input[name=id_application_status]").val(app.id_application_status);
	$("input[name=id_application]").val(app.id_application);
	$("input[name=created_by]").val(app.created_by);

	for (var i = 0; i < value.length; i++) {
		$('.attach_user_file').append('<div class="clearfix"><div>'+ (i+1) +'. '+ value[i].display_name
		 			+'</div><a href="<?php echo base_url();?>submit_iin/download?var1='+ window.btoa(value[i].path_file) 
		 			+'" class="btn_download float_right">Download</a></div>');
	}

	for (var i = 0; i < value.length; i++) {
		$('.doc_rev').append('<div><label><input name="docRef[]" type="checkbox" id="id_admin" value="'+value[i].id_document_config+'" />'+value[i].display_name+'</label></div>');

	}

</script>

