<section class="clearfix content-approval">
	<p class="p-desc">Silakan unggah dokumen pendukung IIN:</p>
	<?php echo form_open_multipart('admin_verifikasi_controller/CRA_APPROVAL_REQ_PROSES') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<div class="content-upload clearfix">
		</div>
		<div onclick="add_upload()" class="btn-add-doc">Tambah Dokumen</div>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section>



<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();
	$.set_add_upload();
	$('#btn-revision').remove();
	$('#section-revision').remove();
</script>
