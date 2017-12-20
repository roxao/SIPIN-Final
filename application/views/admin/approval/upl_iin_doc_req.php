<section class="clearfix content-approval">
	<?php echo form_open_multipart('admin_verifikasi_controller/UPL_IIN_DOC_REQ_PROSES') ?>
		<input type="hidden" name="id_application_status">
		<input type="hidden" name="id_application">
		<input type="hidden" name="created_by">
		<input type="hidden" name="id_user_iin">
		<input type="hidden" name="application_type">
		
		<label class="input_dashed float_left" style="width: 100%">
			Nomor IIN
			<input id="iin_number" name="iin_number" type="text" placeholder="Masukan Nomor IIN" pattern="\d*" maxlength="6" required/>
		</label>
		<label class="input_dashed float_left" style="width: 100%">
			Tanggal Terbit
			<input id="iin_established_date" name="iin_established_date" type="date" placeholder="Masukan Tanggal Terbit IIN" required/>
		</label>
		</label>
		<label class="input_dashed float_left" style="width: 100%">
			Tanggal Kadaluarsa
			<input id="iin_expiry_date" name="iin_expiry_date" type="date" placeholder="Masukan Tanggal Kadaluarsa" required/>
		</label>
		<label class="input_dashed_file float_left" style="width: 100%">
			Dokumen IIN
			<input id="iin_doc" name="doc[]"  type="file" placeholder="Masukan Dokumen Dokumen IIN" required/>
			<span>Pilih</span><i class="float_right"></i>
		</label>
		<input type="submit" name="submit_approval" style="display:none"/>
	</form>
</section>


<script>
	$.set_value_data();
	$.base_config_approval();
	$.config_file_type();
	$('#btn-approval').html('Unggah Dokumen').css('margin',"5px auto");
   	$('#btn-approval').on('click', function(event) {$('[name=submit_approval]').click()});
	$('#btn-revision').remove();
	$('#section-revision').remove();
	$('[name=iin_number]').keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
</script>
