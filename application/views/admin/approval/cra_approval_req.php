<section class="clearfix content-approval">
	<p class="p-desc">Silakan unggah dokumen pendukung IIN:</p>
	<?php echo form_open_multipart('admin_verifikasi_controller/CRA_APPROVAL_REQ_PROSES') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<div class="content-upload clearfix">
		</div>
		<input type="submit" name="submit_approval" hidden/>
	</form>
</section>



<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();
	$.set_upload_cra(".content-upload");
	$('#btn-revision').remove();
	$('#section-revision').remove();
</script>
