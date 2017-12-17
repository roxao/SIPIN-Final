<section class="clearfix content-approval">
	<p class="p-desc">Silakan unggah dokumen penugasan Tim Assessment:</p>
	<?php echo form_open_multipart('admin_verifikasi_controller/FIELD_ASSESS_REQ_SUCCEST') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<div class="content-upload clearfix"></div>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section>


<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();

	$.set_add_upload();

	$('#btn-approval').html('Unggah Dokumen').css('margin',"5px auto");
   	$('#btn-approval').on('click', function(event) {$('[name=submit_approval]').click()});
	$('#btn-revision').remove();
	$('#section-revision').remove();
	
</script>
<style>
	.item-upload-v2>label{padding-right: 10px !important}
</style>
